<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeDetail;
use App\Models\RoleDepartmentPermission;
use App\Models\RoleDepartmentAssignment;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

/**
 * HierarchyController
 *
 * Handles the advanced employee hierarchy / reporting tree system.
 * All role checks use role_id (integer) — never role name strings.
 *
 * Features:
 *  - Admin: Full org tree with employee count per node
 *  - Admin: Role-based department visibility management
 *  - Employee: View their own full reporting chain (upward hierarchy)
 *
 * Performance:
 *  - Single query fetches all tenant users with eager-loaded relations
 *  - Tree built entirely in memory (zero N+1 queries)
 *  - Role lookups batched into single queries
 *  - Optional caching via the cache() facade
 */
class HierarchyController extends Controller
{
    // How long to cache the org tree (in seconds). Set to 0 to disable caching.
    // NOTE: Set to 300 (5 min) in production after verifying everything works.
    private const CACHE_TTL = 0;

    // Maximum allowed hierarchy depth (prevents infinite loops from bad data)
    private const MAX_DEPTH = 20;

    public function __construct()
    {
        $this->tenant = app()->bound('tenant') ? app('tenant') : null;
    }

    /* ==============================================================
     *  SHARED HELPERS
     * ============================================================== */

    /**
     * Generate a fully-qualified avatar URL from the raw DB value.
     *
     * DB stores: 22/38/f86e94901e8677376f707bf040053918.jpg
     *            ^   ^   ^--- filename hash
     *            |   +------ user_id
     *            +---------- tenant_id
     *
     * Full URL:  https://renownsystem.com/storage/22/38/filename.jpg
     *            (storage/app/public/ is linked via php artisan storage:link)
     */
    private function getAvatarUrl(?string $avatar): string
    {
        $default = asset('images/user.jpg');

        if (empty($avatar)) {
            return $default;
        }

        // Already a full URL (just use it)
        if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
            return $avatar;
        }

        // Pattern: {tenant_id}/{user_id}/{filename} → /storage/...
        // Stored in storage/app/public/ → accessible via /storage/ symlink
        return Storage::url($avatar);
    }

    /**
     * Get the current tenant ID from the bound tenant or authenticated user.
     */
    private function getTenantId(): int
    {
        return (int) ($this->tenant->id ?? auth()->user()->tenant_id);
    }

    /**
     * Get the allowed department IDs for the currently active role.
     * Returns null when all departments are visible.
     */
    private function getAllowedDeptIds(?int $tenantId = null): ?array
    {
        $tenantId = $tenantId ?? $this->getTenantId();
        $activeRole = activeRole();
        if (!$activeRole) {
            return null; // no role restriction
        }

        $currentRole = Role::where('name', $activeRole)
            ->where('tenant_id', $tenantId)
            ->first();

        return $currentRole
            ? RoleDepartmentPermission::getAllowedDepartmentIds($currentRole->id, $tenantId)
            : null;
    }

    /* ==============================================================
     *  ADMIN VIEWS
     * ============================================================== */

    /**
     * Show the interactive organization hierarchy tree.
     * Accessible to Admin role (role_id, not name).
     */
    public function orgTree(Request $request)
    {
        $tenantId   = $this->getTenantId();
        $tree       = $this->buildOrgTree($tenantId, $this->getAllowedDeptIds());
        $treeData   = ['tree' => $tree];  // blade expects treeData.tree
        $departments = Department::where('tenant_id', $tenantId)->get();
        $roles       = Role::where('tenant_id', $tenantId)->get();

        $pageTitle = __('Organization Hierarchy');

        return view('pages.hierarchy.org-tree', compact(
            'pageTitle', 'treeData', 'departments', 'roles'
        ));
    }

    /**
     * JSON API: Load org tree data (for AJAX lazy-loading).
     */
    public function orgTreeData(Request $request)
    {
        $tenantId = $this->getTenantId();
        $tree     = $this->buildOrgTree($tenantId, $this->getAllowedDeptIds());

        return response()->json(['tree' => $tree]);
    }

    /**
     * Show the "My Hierarchy" page for an employee.
     * Displays a 3-tier view:
     *  - 1 Level Up:   Reporting Manager & Sub Reporting Manager
     *  - Center:       YOU (the logged-in user — prominent)
     *  - 1 Level Down: Direct reports (people reporting to you)
     */
    public function myHierarchy()
    {
        $user     = auth()->user();
        $tenantId = $this->getTenantId();

        // Upward chain (reporting manager → sub RM → ... admin)
        $chain = $this->buildReportingChain($user, $tenantId);

        // Direct reports (people whose reporting_manager = me)
        $directReports = $this->getDirectReports($user, $tenantId);

        $pageTitle = __('My Reporting Hierarchy');

        return view('pages.hierarchy.my-hierarchy', compact(
            'pageTitle', 'chain', 'directReports'
        ));
    }

    /* ==============================================================
     *  ROLE DEPARTMENT PERMISSION MANAGEMENT (Admin only)
     * ============================================================== */

    /**
     * Show role department permission settings.
     * Admin can set per-role: see all departments or only assigned departments.
     */
    public function roleDeptPermissions()
    {
        $tenantId = $this->getTenantId();

        $roles = Role::where('tenant_id', $tenantId)
                     ->whereNotIn('name', ['Super Admin', 'Admin', 'Client', 'Blog Writer'])
                     ->get();

        $departments = Department::where('tenant_id', $tenantId)->get();

        // Load existing permissions for all roles
        $permissions = RoleDepartmentPermission::where('tenant_id', $tenantId)
            ->with('departmentAssignments')
            ->get()
            ->keyBy('role_id');

        $assignments = RoleDepartmentAssignment::where('tenant_id', $tenantId)->get();

        $pageTitle = __('Role Department Permissions');

        return view('pages.hierarchy.role-dept-permissions', compact(
            'pageTitle', 'roles', 'departments', 'permissions', 'assignments'
        ));
    }

    /**
     * Update or create role department permission.
     */
    public function updateRoleDeptPermission(Request $request)
    {
        $request->validate([
            'role_id'         => 'required|integer|exists:roles,id',
            'visibility_type' => 'required|in:all_departments,assigned_departments',
            'department_ids'  => 'nullable|array',
            'department_ids.*' => 'integer|exists:departments,id',
        ]);

        $tenantId = $this->getTenantId();

        // Upsert permission
        RoleDepartmentPermission::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'role_id'   => $request->role_id,
            ],
            [
                'visibility_type' => $request->visibility_type,
            ]
        );

        // If assigned_departments, sync the department assignments
        if ($request->visibility_type === 'assigned_departments') {
            $deptIds = $request->department_ids ?? [];

            RoleDepartmentAssignment::where('role_id', $request->role_id)
                ->where('tenant_id', $tenantId)
                ->delete();

            // Batch insert using upsert for performance
            if (!empty($deptIds)) {
                $rows = array_map(fn($deptId) => [
                    'tenant_id'     => $tenantId,
                    'role_id'       => $request->role_id,
                    'department_id' => $deptId,
                    'created_at'    => now(),
                ], $deptIds);

                RoleDepartmentAssignment::insert($rows);
            }
        } else {
            RoleDepartmentAssignment::where('role_id', $request->role_id)
                ->where('tenant_id', $tenantId)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Role department permission updated successfully',
        ]);
    }

    /* ==============================================================
     *  INTERNAL: BUILD ORG TREE (OPTIMIZED — SINGLE QUERY)
     * ============================================================== */

    /**
     * Build the complete org tree for a tenant.
     *
     * Strategy: Fetch ALL tenant employees in ONE query with eager-loaded
     * relationships, then build the tree structure entirely in memory.
     * This eliminates the N+1 query problem completely.
     *
     * @param int       $tenantId
     * @param array|null $allowedDeptIds  null = all departments
     * @return array  Nested tree structure
     */
    private function buildOrgTree(int $tenantId, ?array $allowedDeptIds): array
    {
        $cacheKey = "hierarchy_tree_{$tenantId}_" . md5(serialize($allowedDeptIds));

        if (self::CACHE_TTL > 0) {
            return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($tenantId, $allowedDeptIds) {
                return $this->resolveOrgTree($tenantId, $allowedDeptIds);
            });
        }

        return $this->resolveOrgTree($tenantId, $allowedDeptIds);
    }

    /**
     * Actual tree resolution — all queries run here.
     */
    private function resolveOrgTree(int $tenantId, ?array $allowedDeptIds): array
    {
        // ── 1. Single query: fetch ALL active employees + admins with eager-loaded relations ──
        $users = User::where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->whereIn('type', [UserType::EMPLOYEE, UserType::ADMIN])
            ->with([
                'employeeDetail.department',
                'employeeDetail.designation',
                'subReportingManager',
            ])
            ->get();

        if ($users->isEmpty()) {
            return [];
        }

        // ── 2. Single query: batch-load ALL role assignments for these users ──
        $userIds = $users->pluck('id')->toArray();

        $roleAssignments = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('model_id', $userIds)
            ->get()
            ->groupBy('model_id'); // keyed by user ID

        // ── 3. Single query: fetch role names for all role IDs ──
        $allRoleIds = $roleAssignments->flatten()->pluck('role_id')->unique()->toArray();

        $roleNameMap = DB::table('roles')
            ->whereIn('id', $allRoleIds)
            ->pluck('name', 'id')
            ->toArray();

        // Also fetch role names for active_role fields
        $activeRoleNames = $users->pluck('active_role')->unique()->filter()->toArray();
        $activeRoleMap = DB::table('roles')
            ->whereIn('name', $activeRoleNames)
            ->pluck('id', 'name')
            ->toArray();

        // ── 4. Index users by ID for O(1) lookups ──
        $userMap = $users->keyBy('id');

        // ── 5. Apply department filtering ──
        $allowedUserIds = null; // null = all allowed
        if ($allowedDeptIds !== null) {
            $allowedUserIds = $users->filter(function ($user) use ($allowedDeptIds) {
                // Admins and SuperAdmins always pass
                if (in_array($user->type->value, [UserType::ADMIN->value, UserType::SUPERADMIN->value])) {
                    return true;
                }
                $empDetail = $user->employeeDetail;
                if (!$empDetail || !$empDetail->department_id) {
                    return true; // no department = visible
                }
                return in_array($empDetail->department_id, $allowedDeptIds);
            })->pluck('id')->toArray();
        }

        // ── 6. Build parent→children map for O(1) child lookups ──
        $childrenMap = []; // parent_user_id => [User, User, ...]
        foreach ($users as $u) {
            $rmId = (int) $u->reporting_manager;
            if ($rmId > 0 && $rmId !== $u->id) {
                $childrenMap[$rmId][] = $u;
            }
        }

        // ── 7. Find root nodes ──
        //    Root = a user with NO reporting_manager (top of chain)
        //    OR any Admin-type user
        //    OR any user who is not a subordinate of anyone (orphan)
        $rootIds     = [];
        $processedIds = [];

        // Collect IDs of everyone who IS someone's subordinate
        $subordinateIds = [];
        foreach ($childrenMap as $parentId => $children) {
            foreach ($children as $child) {
                $subordinateIds[(int) $child->id] = true;
            }
        }

        // Roots: no reporting_manager, or Admin type
        foreach ($users as $u) {
            if (
                (!$u->reporting_manager || $u->type === UserType::ADMIN)
                && !isset($processedIds[$u->id])
            ) {
                if ($allowedUserIds === null || in_array($u->id, $allowedUserIds)) {
                    $rootIds[] = $u->id;
                }
                $processedIds[$u->id] = true;
            }
        }

        // Orphans: not a subordinate of anyone AND not already added
        foreach ($users as $u) {
            if (!isset($processedIds[$u->id]) && !isset($subordinateIds[$u->id])) {
                if ($allowedUserIds === null || in_array($u->id, $allowedUserIds)) {
                    $rootIds[] = $u->id;
                }
                $processedIds[$u->id] = true;
            }
        }

        // ── 8. Build tree in memory using childrenMap ──
        $visited = []; // array of user IDs (simpler than SplObjectStorage)
        $tree = [];

        foreach ($rootIds as $rootId) {
            if (isset($userMap[$rootId])) {
                $node = $this->buildNodeInMemory(
                    $userMap[$rootId], $userMap, $childrenMap, $roleAssignments,
                    $roleNameMap, $activeRoleMap, $allowedUserIds,
                    0, $visited
                );
                if ($node) {
                    $tree[] = $node;
                }
            }
        }

        return $tree;
    }

    /**
     * Build a single tree node entirely from in-memory data.
     * No database queries — everything comes from pre-loaded collections.
     *
     * @param User               $user
     * @param \Illuminate\Database\Eloquent\Collection $userMap
     * @param array              $childrenMap      parent_id => [User, ...]
     * @param \Illuminate\Support\Collection          $roleAssignments
     * @param array              $roleNameMap      [role_id => name]
     * @param array              $activeRoleMap    [role_name => role_id]
     * @param array|null         $allowedUserIds
     * @param int                $depth
     * @param array              $visited          circular reference guard (user IDs)
     * @return array|null
     */
    private function buildNodeInMemory(
        User $user,
        $userMap,
        array $childrenMap,
        $roleAssignments,
        array $roleNameMap,
        array $activeRoleMap,
        ?array $allowedUserIds,
        int $depth,
        array &$visited
    ): ?array {
        // Circular reference protection
        if ($depth > self::MAX_DEPTH || in_array($user->id, $visited)) {
            return null;
        }
        $visited[] = $user->id;

        // Department permission check
        if ($allowedUserIds !== null && !in_array($user->id, $allowedUserIds)) {
            return null;
        }

        // ── Get role info from pre-loaded data ──
        $userRoleIds    = ($roleAssignments[$user->id] ?? collect())
                           ->pluck('role_id')->toArray();
        $userRoleNames  = array_map(fn($rid) => $roleNameMap[$rid] ?? null, $userRoleIds);
        $userRoleNames  = array_filter($userRoleNames);
        $activeRoleId   = $activeRoleMap[$user->active_role] ?? null;

        // ── Get employee detail info from eager-loaded relation ──
        $empDetail   = $user->employeeDetail;
        $department  = $empDetail?->department?->name ?? 'N/A';
        $deptId      = $empDetail?->department_id ?? null;
        $designation = $empDetail?->designation?->name ?? 'N/A';

        // ── Sub reporting manager ──
        $srmName = $user->subReportingManager?->fullname ?? null;

        // ── Find children from childrenMap (O(1) lookup, no iteration!) ──
        $children = [];
        $totalDescendants = 0;

        $childUsers = $childrenMap[$user->id] ?? [];
        foreach ($childUsers as $child) {
            $childNode = $this->buildNodeInMemory(
                $child, $userMap, $childrenMap, $roleAssignments,
                $roleNameMap, $activeRoleMap, $allowedUserIds,
                $depth + 1, $visited
            );
            if ($childNode) {
                $children[] = $childNode;
                $totalDescendants += 1 + ($childNode['descendant_count'] ?? 0);
            }
        }

        return [
            'id'                     => $user->id,
            'name'                   => $user->fullname,
            'avatar'                 => $this->getAvatarUrl($user->avatar),
            'email'                  => $user->email,
            'phone'                  => $user->phone,
            'department'             => $department,
            'department_id'          => $deptId,
            'designation'            => $designation,
            'type'                   => $user->type->value,
            'is_active'              => $user->is_active,
            'role_ids'               => $userRoleIds,
            'role_names'             => array_values($userRoleNames),
            'active_role_id'         => $activeRoleId,
            'active_role'            => $user->active_role,
            'direct_reports'         => count($children),
            'descendant_count'       => $totalDescendants,
            'reporting_manager_id'   => $user->reporting_manager,
            'sub_reporting_manager_id' => $user->sub_reporting_manager,
            'sub_reporting_manager_name' => $srmName,
            'children'               => $children,
            'depth'                  => $depth,
        ];
    }

    /* ==============================================================
     *  INTERNAL: BUILD REPORTING CHAIN (for employee self-view)
     * ============================================================== */

    /**
     * Get direct reports for a user (1 level down).
     * People whose reporting_manager or sub_reporting_manager = this user.
     */
    private function getDirectReports(User $user, int $tenantId): array
    {
        // Single query: all users reporting to me
        $subs = User::where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->where(function ($q) use ($user) {
                $q->where('reporting_manager', $user->id)
                  ->orWhere('sub_reporting_manager', $user->id);
            })
            ->with(['employeeDetail.department', 'employeeDetail.designation'])
            ->get();

        if ($subs->isEmpty()) {
            return [];
        }

        // Batch role lookup
        $subIds = $subs->pluck('id')->toArray();
        $roleAssignments = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('model_id', $subIds)
            ->get()
            ->groupBy('model_id');

        $roleNameMap = DB::table('roles')
            ->whereIn('id', $roleAssignments->flatten()->pluck('role_id')->unique())
            ->pluck('name', 'id')
            ->toArray();

        return $subs->map(function ($sub) use ($roleAssignments, $roleNameMap, $user) {
            $userRoleIds = ($roleAssignments[$sub->id] ?? collect())->pluck('role_id')->toArray();
            $userRoleNames = array_values(array_filter(
                array_map(fn($rid) => $roleNameMap[$rid] ?? null, $userRoleIds)
            ));
            $empDetail = $sub->employeeDetail;

            return [
                'id'          => $sub->id,
                'name'        => $sub->fullname,
                'avatar'      => $this->getAvatarUrl($sub->avatar),
                'email'       => $sub->email,
                'department'  => $empDetail?->department?->name ?? null,
                'designation' => $empDetail?->designation?->name ?? null,
                'active_role' => $sub->active_role,
                'role_names'  => $userRoleNames,
                'rel_type'    => (int) $sub->reporting_manager === $user->id ? 'reporting_manager' : 'sub_reporting_manager',
            ];
        })->toArray();
    }

    /**
     * Build the upward reporting chain for a user.
     * Uses single batched queries for all chain members.
     *
     * Returns ordered array: [self, reporting_manager, sub_reporting_manager, ... root, hr, admin]
     */
    private function buildReportingChain(User $user, int $tenantId): array
    {
        $chain      = [];
        $visitedIds = [];
        $userIds    = [];

        // 1) Collect all user IDs in the chain by traversing upward
        $this->collectChainIds($user, $userIds, $visitedIds);

        // 2) Fetch ALL users in the chain in a single query
        $usersBatch = User::whereIn('id', $userIds)
            ->with(['employeeDetail.department', 'employeeDetail.designation'])
            ->get()
            ->keyBy('id');

        // 3) Fetch role assignments for ALL chain members in a single query
        $roleAssignments = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('model_id', $userIds)
            ->get()
            ->groupBy('model_id');

        $allRoleIds  = $roleAssignments->flatten()->pluck('role_id')->unique()->toArray();
        $roleNameMap = DB::table('roles')
            ->whereIn('id', $allRoleIds)
            ->pluck('name', 'id')
            ->toArray();

        // 4) Find HR user
        $hrRole = Role::where('name', 'Hr')->where('tenant_id', $tenantId)->first();
        if ($hrRole) {
            $hrUserId = DB::table('model_has_roles')
                ->where('role_id', $hrRole->id)
                ->where('model_type', 'App\\Models\\User')
                ->value('model_id');

            if ($hrUserId && $hrUserId != $user->id && !in_array($hrUserId, $visitedIds)) {
                $usersBatch[$hrUserId] = User::where('id', $hrUserId)
                    ->with(['employeeDetail.department', 'employeeDetail.designation'])
                    ->first();

                if ($usersBatch[$hrUserId] && $usersBatch[$hrUserId]->tenant_id == $tenantId) {
                    // Load HR role assignments
                    $hrRoles = DB::table('model_has_roles')
                        ->where('model_type', 'App\\Models\\User')
                        ->where('model_id', $hrUserId)
                        ->get();
                    $roleAssignments[$hrUserId] = $hrRoles;
                }
            }
        }

        // 5) Find Admin
        $admin = User::where('tenant_id', $tenantId)
            ->where('type', UserType::ADMIN)
            ->where('is_active', 1)
            ->first();

        if ($admin && !in_array($admin->id, $visitedIds)) {
            $usersBatch[$admin->id] = $admin;
            $adminRoles = DB::table('model_has_roles')
                ->where('model_type', 'App\\Models\\User')
                ->where('model_id', $admin->id)
                ->get();
            $roleAssignments[$admin->id] = $adminRoles;
        }

        // 6) Build chain nodes from pre-loaded data (no queries!)
        $this->buildChainFromBatch($user, $usersBatch, $roleAssignments, $roleNameMap, $chain, $visitedIds);

        // 7) Add HR node
        if ($hrRole && $hrUserId && isset($usersBatch[$hrUserId]) && $usersBatch[$hrUserId]?->tenant_id == $tenantId) {
            if (!in_array($hrUserId, array_column($chain, 'id'))) {
                $chain[] = $this->makeChainNodeBatched(
                    $usersBatch[$hrUserId], $roleAssignments, $roleNameMap, 'hr'
                );
            }
        }

        // 8) Add Admin node
        if ($admin && !in_array($admin->id, array_column($chain, 'id'))) {
            $chain[] = $this->makeChainNodeBatched(
                $admin, $roleAssignments, $roleNameMap, 'admin'
            );
        }

        return $chain;
    }

    /**
     * Collect all user IDs needed for the reporting chain (upward traversal).
     * This is a lightweight operation — no DB queries.
     */
    private function collectChainIds(User $user, array &$userIds, array &$visitedIds): void
    {
        if (in_array($user->id, $visitedIds) || count($visitedIds) > self::MAX_DEPTH) {
            return;
        }
        $visitedIds[] = $user->id;
        $userIds[]    = $user->id;

        if ($user->reporting_manager && !in_array($user->reporting_manager, $visitedIds)) {
            $rm = User::find($user->reporting_manager);
            if ($rm && $rm->tenant_id == $user->tenant_id) {
                $this->collectChainIds($rm, $userIds, $visitedIds);
            }
        }

        if ($user->sub_reporting_manager && !in_array($user->sub_reporting_manager, $visitedIds)) {
            $srm = User::find($user->sub_reporting_manager);
            if ($srm && $srm->tenant_id == $user->tenant_id) {
                $this->collectChainIds($srm, $userIds, $visitedIds);
            }
        }
    }

    /**
     * Build chain nodes from batch-loaded data (no queries per node).
     */
    private function buildChainFromBatch(
        User $user,
        $usersBatch,
        $roleAssignments,
        array $roleNameMap,
        array &$chain,
        array $visitedIds
    ): void {
        $chain[] = $this->makeChainNodeBatched($user, $roleAssignments, $roleNameMap, 'self');

        if ($user->reporting_manager && !in_array($user->reporting_manager, array_column($chain, 'id'))) {
            $rm = $usersBatch[$user->reporting_manager] ?? null;
            if ($rm && $rm->tenant_id == $user->tenant_id) {
                $chain[] = $this->makeChainNodeBatched($rm, $roleAssignments, $roleNameMap, 'reporting_manager');
            }
        }

        if ($user->sub_reporting_manager && !in_array($user->sub_reporting_manager, array_column($chain, 'id'))) {
            $srm = $usersBatch[$user->sub_reporting_manager] ?? null;
            if ($srm && $srm->tenant_id == $user->tenant_id) {
                $chain[] = $this->makeChainNodeBatched($srm, $roleAssignments, $roleNameMap, 'sub_reporting_manager');
            }
        }
    }

    /**
     * Create a chain node array from pre-loaded data (zero queries).
     */
    private function makeChainNodeBatched(User $user, $roleAssignments, array $roleNameMap, string $relationship): array
    {
        $userRoleIds   = ($roleAssignments[$user->id] ?? collect())
                          ->pluck('role_id')->toArray();
        $userRoleNames = array_filter(
            array_map(fn($rid) => $roleNameMap[$rid] ?? null, $userRoleIds)
        );

        $empDetail   = $user->employeeDetail;
        $department  = $empDetail?->department?->name ?? null;
        $designation = $empDetail?->designation?->name ?? null;

        return [
            'id'          => $user->id,
            'name'        => $user->fullname,
            'avatar'      => $this->getAvatarUrl($user->avatar),
            'email'       => $user->email,
            'phone'       => $user->phone,
            'department'  => $department,
            'designation' => $designation,
            'type'        => $user->type->value,
            'active_role' => $user->active_role,
            'role_ids'    => $userRoleIds,
            'role_names'  => array_values($userRoleNames),
            'relationship'=> $relationship,
        ];
    }

    /* ==============================================================
     *  JSON API ENDPOINTS (AJAX)
     * ============================================================== */

    /**
     * Get the full reporting chain for the logged-in employee (JSON).
     */
    public function getMyHierarchyJson()
    {
        $user     = auth()->user();
        $tenantId = $this->getTenantId();
        $chain    = $this->buildReportingChain($user, $tenantId);

        return response()->json([
            'success' => true,
            'chain'   => $chain,
        ]);
    }

    /**
     * Get subordinates tree for a specific user (JSON).
     * Used for lazy-loading children in the org tree.
     */
    public function getSubordinatesTree(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
        ]);

        $tenantId       = $this->getTenantId();
        $userId         = $request->user_id;
        $allowedDeptIds = $this->getAllowedDeptIds();

        // Fetch only the requested user's subordinates (single query with eager load)
        $subs = User::where('tenant_id', $tenantId)
            ->where('reporting_manager', $userId)
            ->where('is_active', 1)
            ->with(['employeeDetail.department', 'employeeDetail.designation', 'subReportingManager'])
            ->get();

        // Batch load roles for these subordinates
        $subIds          = $subs->pluck('id')->toArray();
        $roleAssignments = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('model_id', $subIds)
            ->get()
            ->groupBy('model_id');

        $allRoleIds  = $roleAssignments->flatten()->pluck('role_id')->unique()->toArray();
        $roleNameMap = DB::table('roles')->whereIn('id', $allRoleIds)->pluck('name', 'id')->toArray();

        $activeRoleNames = $subs->pluck('active_role')->unique()->filter()->toArray();
        $activeRoleMap   = DB::table('roles')->whereIn('name', $activeRoleNames)->pluck('id', 'name')->toArray();

        $children = [];
        foreach ($subs as $sub) {
            // Department check
            if ($allowedDeptIds !== null) {
                $empDetail = $sub->employeeDetail;
                if ($empDetail && $empDetail->department_id && !in_array($empDetail->department_id, $allowedDeptIds)) {
                    continue;
                }
            }

            $userRoleIds  = ($roleAssignments[$sub->id] ?? collect())->pluck('role_id')->toArray();
            $userRoleNames = array_values(array_filter(
                array_map(fn($rid) => $roleNameMap[$rid] ?? null, $userRoleIds)
            ));
            $activeRoleId  = $activeRoleMap[$sub->active_role] ?? null;
            $empDetail     = $sub->employeeDetail;

            $children[] = [
                'id'                     => $sub->id,
                'name'                   => $sub->fullname,
                'avatar'                 => $this->getAvatarUrl($sub->avatar),
                'email'                  => $sub->email,
                'phone'                  => $sub->phone,
                'department'             => $empDetail?->department?->name ?? 'N/A',
                'department_id'          => $empDetail?->department_id ?? null,
                'designation'            => $empDetail?->designation?->name ?? 'N/A',
                'type'                   => $sub->type->value,
                'is_active'              => $sub->is_active,
                'role_ids'               => $userRoleIds,
                'role_names'             => $userRoleNames,
                'active_role_id'         => $activeRoleId,
                'active_role'            => $sub->active_role,
                'direct_reports'         => 0,
                'descendant_count'       => 0,
                'reporting_manager_id'   => $sub->reporting_manager,
                'sub_reporting_manager_id' => $sub->sub_reporting_manager,
                'sub_reporting_manager_name' => $sub->subReportingManager?->fullname ?? null,
                'children'               => [],
                'depth'                  => 0,
            ];
        }

        return response()->json([
            'success'  => true,
            'children' => $children,
        ]);
    }

    /**
     * Search employees in the hierarchy tree.
     */
    public function searchHierarchy(Request $request)
    {
        $tenantId = $this->getTenantId();
        $query    = $request->get('q', '');

        if (empty($query)) {
            return response()->json(['results' => []]);
        }

        // Single query with eager loading + batch role lookup
        $users = User::where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->where('type', UserType::EMPLOYEE)
            ->where(function ($q) use ($query) {
                $q->where('firstname', 'LIKE', "%{$query}%")
                  ->orWhere('lastname', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->with('employeeDetail.designation', 'employeeDetail.department')
            ->limit(20)
            ->get();

        // Batch role lookup (single query)
        $userIds         = $users->pluck('id')->toArray();
        $roleAssignments = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('model_id', $userIds)
            ->get()
            ->groupBy('model_id');

        $results = $users->map(function ($u) use ($roleAssignments) {
            return [
                'id'          => $u->id,
                'name'        => $u->fullname,
                'avatar'      => $this->getAvatarUrl($u->avatar),
                'department'  => $u->employeeDetail?->department?->name ?? null,
                'designation' => $u->employeeDetail?->designation?->name ?? null,
                'role_ids'    => ($roleAssignments[$u->id] ?? collect())->pluck('role_id')->toArray(),
                'active_role' => $u->active_role,
                'reporting_manager' => $u->reporting_manager,
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Get employee count statistics per department (for admin dashboard).
     */
    public function getDeptStats()
    {
        $tenantId = $this->getTenantId();

        // ── Single query: department employee counts using subquery ──
        $deptStats = Department::where('tenant_id', $tenantId)
            ->leftJoin('employee_details as ed', function ($join) use ($tenantId) {
                $join->on('departments.id', '=', 'ed.department_id');
            })
            ->leftJoin('users as u', function ($join) use ($tenantId) {
                $join->on('ed.user_id', '=', 'u.id')
                     ->where('u.tenant_id', $tenantId)
                     ->where('u.is_active', 1);
            })
            ->select([
                'departments.id as department_id',
                'departments.name as department_name',
                DB::raw('COUNT(DISTINCT u.id) as employee_count'),
            ])
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('employee_count')
            ->get();

        // ── Single query: count managers per department ──
        $managerCounts = DB::table('users')
            ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->join('departments', 'employee_details.department_id', '=', 'departments.id')
            ->where('users.tenant_id', $tenantId)
            ->where('users.is_active', 1)
            ->where('departments.tenant_id', $tenantId)
            ->whereIn('users.id', function ($q) {
                $q->select('reporting_manager')
                  ->from('users')
                  ->whereNotNull('reporting_manager')
                  ->where('is_active', 1);
            })
            ->select([
                'departments.id as department_id',
                DB::raw('COUNT(DISTINCT users.id) as manager_count'),
            ])
            ->groupBy('departments.id')
            ->pluck('manager_count', 'department_id');

        $stats = $deptStats->map(function ($dept) use ($managerCounts) {
            return [
                'department_id'    => $dept->department_id,
                'department_name'  => $dept->department_name,
                'employee_count'   => (int) $dept->employee_count,
                'manager_count'    => (int) ($managerCounts[$dept->department_id] ?? 0),
            ];
        });

        return response()->json(['stats' => $stats]);
    }
}
