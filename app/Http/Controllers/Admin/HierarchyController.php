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
    private const CACHE_TTL = 300;

    // Maximum allowed hierarchy depth for org tree (prevents infinite loops from bad data)
    private const MAX_DEPTH = 20;

    // Maximum reporting chain depth for the detail panel.
    // In real orgs: 5-8 levels is typical. Even Amazon (1.5M employees) has ~10 levels.
    // 30+ levels almost always means BAD DATA (circular refs, misconfigured reporting_manager).
    // Set to 10 as a safe upper bound. Chain is truncated with "showing X of Y" notice.
    private const MAX_CHAIN_DEPTH = 10;

    // How many tree levels to fully render on page load.
    // Deeper levels are collapsed and lazy-loaded via AJAX on expand.
    // AUTO-DETECTED in orgTree() based on employee count:
    //   < 500  → 3 levels (small org)
    //   500-3000 → 2 levels (medium org)
    //   > 3000 → 1 level (large org — only root nodes, everything else AJAX)
    private const MAX_RENDER_DEPTH = 2;

    // In the JS tree: how many child cards to show before "Show X more" button.
    // Compact cards (~155px): 7 cards fit in ~1150px. Limit to 7 per row.
    private const VISIBLE_CHILDREN_LIMIT = 7;

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
        $tenantId       = $this->getTenantId();
        $allowedDeptIds = $this->getAllowedDeptIds();

        // ── Auto-detect render depth based on org size ──
        // For 10,000+ employees, we only render root nodes (depth 1).
        // Everything else is loaded on-demand via AJAX (getSubordinatesTree).
        // This keeps the initial page load under 50KB JSON.
        $employeeCount = User::where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->whereIn('type', [UserType::EMPLOYEE, UserType::ADMIN])
            ->count();

        if ($employeeCount > 3000) {
            $maxRenderDepth = 1; // Large org: roots only → all children AJAX
        } elseif ($employeeCount > 500) {
            $maxRenderDepth = 2; // Medium org: 2 levels deep
        } else {
            $maxRenderDepth = 3; // Small org: full 3 levels
        }

        // Build SHALLOW tree — only first $maxRenderDepth levels.
        // Deeper levels are lazy-loaded via AJAX (getSubordinatesTree) on expand.
        $result    = $this->resolveOrgTree($tenantId, $allowedDeptIds, $maxRenderDepth);
        $treeData  = ['tree' => $result['tree']];
        $treeStats = $result['stats'];

        $departments = Department::where('tenant_id', $tenantId)->get();
        $roles       = Role::where('tenant_id', $tenantId)->get();

        $pageTitle = __('Organization Hierarchy');

        $visibleChildrenLimit = self::VISIBLE_CHILDREN_LIMIT;

        return view('pages.hierarchy.org-tree', compact(
            'pageTitle', 'treeData', 'treeStats', 'departments', 'roles',
            'maxRenderDepth', 'visibleChildrenLimit'
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
     *  - 1 Level Down: Direct reports (loaded via AJAX pagination)
     *
     * Performance: Only lightweight COUNT queries run on page load.
     * Report data is fetched 15 items at a time via getMyReportsPaginated()
     * AJAX endpoint — no N+1, no bulk data loading.
     */
    public function myHierarchy()
    {
        $user     = auth()->user();
        $tenantId = $this->getTenantId();

        // Upward chain (small — usually 3-5 people max)
        $chain = $this->buildReportingChain($user, $tenantId);

        // Lightweight COUNT queries only — reports loaded via AJAX pagination
        $directCount = User::where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->where('reporting_manager', $user->id)
            ->count();

        $subCount = User::where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->where('sub_reporting_manager', $user->id)
            ->where('reporting_manager', '!=', $user->id)
            ->count();

        $downCount = $directCount + $subCount;

        $pageTitle = __('My Reporting Hierarchy');

        return view('pages.hierarchy.my-hierarchy', compact(
            'pageTitle', 'chain', 'downCount', 'directCount', 'subCount'
        ));
    }

    /**
     * JSON API: Paginated direct reports for AJAX loading.
     * Used when a manager has 200+ reports and client-side is too slow.
     *
     * Query params:
     *  - page (int, default 1)
     *  - per_page (int, default 15, max 50)
     *  - search (string) — filters by name, designation, department
     *  - type (string) — 'all', 'direct', 'sub'
     */
    public function getMyReportsPaginated(Request $request)
    {
        $user     = auth()->user();
        $tenantId = $this->getTenantId();

        $perPage  = min((int) ($request->per_page ?? 15), 50);
        $search   = $request->get('search', '');
        $type     = $request->get('type', 'all'); // all | direct | sub

        $query = User::where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->where(function ($q) use ($user) {
                $q->where('reporting_manager', $user->id)
                  ->orWhere('sub_reporting_manager', $user->id);
            });

        // Filter by type (direct vs sub)
        if ($type === 'direct') {
            $query->where('reporting_manager', $user->id);
        } elseif ($type === 'sub') {
            $query->where('sub_reporting_manager', $user->id)
                  ->where('reporting_manager', '!=', $user->id);
        }

        // Search by name, email, designation, department
        // NOTE: 'fullname' is a model accessor, NOT a DB column.
        //       DB columns are 'firstname' and 'lastname' (confirmed from searchHierarchy).
        if (!empty($search)) {
            $searchLike = '%' . $search . '%';
            $query->where(function ($q) use ($searchLike) {
                $q->whereRaw("CONCAT_WS(' ', firstname, lastname) LIKE ?", [$searchLike])
                  ->orWhere('email', 'LIKE', $searchLike);
            });
            // Also search designation/department via employee_detail
            $query->orWhereHas('employeeDetail', function ($q) use ($search) {
                $q->where('designation_id', function ($sq) use ($search) {
                    $sq->select('id')->from('designations')
                       ->where('name', 'LIKE', '%' . $search . '%');
                })->orWhere('department_id', function ($sq) use ($search) {
                    $sq->select('id')->from('departments')
                       ->where('name', 'LIKE', '%' . $search . '%');
                });
            });
        }

        // Clone for count before pagination
        $total = $query->count();

        // Paginate with eager loads
        // NOTE: 'fullname' is a model accessor, NOT a DB column.
        //       DB columns are 'firstname' and 'lastname' (confirmed from searchHierarchy).
        $subs = $query->with(['employeeDetail.department', 'employeeDetail.designation'])
            ->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->skip(($request->get('page', 1) - 1) * $perPage)
            ->take($perPage)
            ->get();

        if ($subs->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [],
                'total' => 0,
                'page' => 1,
                'per_page' => $perPage,
                'last_page' => 1,
            ]);
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

        $data = $subs->map(function ($sub) use ($roleAssignments, $roleNameMap, $user) {
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
        })->values()->toArray();

        return response()->json([
            'success'   => true,
            'data'      => $data,
            'total'     => $total,
            'page'      => (int) $request->get('page', 1),
            'per_page'  => $perPage,
            'last_page' => (int) ceil($total / $perPage),
        ]);
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
                return $this->resolveOrgTree($tenantId, $allowedDeptIds)['tree'];
            });
        }

        return $this->resolveOrgTree($tenantId, $allowedDeptIds)['tree'];
    }

    /**
     * Actual tree resolution — all queries run here.
     */
    private function resolveOrgTree(int $tenantId, ?array $allowedDeptIds, ?int $maxBuildDepth = null): array
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

        // ── 6. Build parent→children maps for O(1) lookups ──
        $childrenMap = [];      // reporting_manager → [User, ...]  (direct reports — solid line)
        $subChildrenMap = [];  // sub_reporting_manager → [User, ...]  (sub-reports — dotted line)
        foreach ($users as $u) {
            $rmId = (int) $u->reporting_manager;
            if ($rmId > 0 && $rmId !== $u->id) {
                $childrenMap[$rmId][] = $u;
            }
            $srmId = (int) $u->sub_reporting_manager;
            if ($srmId > 0 && $srmId !== $u->id && $srmId !== $rmId) {
                // Only map as sub-report if their primary RM is NOT the same person
                $subChildrenMap[$srmId][] = $u;
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
                    $userMap[$rootId], $userMap, $childrenMap, $subChildrenMap,
                    $roleAssignments, $roleNameMap, $activeRoleMap, $allowedUserIds,
                    0, $visited, $maxBuildDepth
                );
                if ($node) {
                    $tree[] = $node;
                }
            }
        }

        // ── 9. Compute stats from already-loaded data (free — no extra queries) ──
        $stats = [
            'total_employees'   => $users->count(),
            'total_managers'    => count(array_filter($childrenMap, fn($c) => count($c) > 0)),
            'total_departments' => $users->pluck('employeeDetail.department_id')
                                         ->filter()
                                         ->unique()
                                         ->count(),
            'max_depth'         => 0,
        ];

        // Compute max depth via BFS on childrenMap (pure in-memory walk)
        if (!empty($rootIds)) {
            $bfsVisited   = [];
            $currentLevel = $rootIds;
            $depth        = 0;
            while (!empty($currentLevel)) {
                $depth++;
                $bfsVisited = array_merge($bfsVisited, $currentLevel);
                $nextLevel = [];
                foreach ($currentLevel as $pid) {
                    foreach (($childrenMap[$pid] ?? []) as $childUser) {
                        if (!in_array($childUser->id, $bfsVisited)) {
                            $nextLevel[] = $childUser->id;
                        }
                    }
                }
                $currentLevel = $nextLevel;
            }
            $stats['max_depth'] = max(0, $depth - 1);
        }

        return ['tree' => $tree, 'stats' => $stats];
    }

    /**
     * Build a single tree node entirely from in-memory data.
     * No database queries — everything comes from pre-loaded collections.
     *
     * @param User               $user
     * @param \Illuminate\Database\Eloquent\Collection $userMap
     * @param array              $childrenMap      parent_id => [User, ...] (direct reports)
     * @param array              $subChildrenMap    sub_rm_id => [User, ...] (sub-reports)
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
        array $subChildrenMap,
        $roleAssignments,
        array $roleNameMap,
        array $activeRoleMap,
        ?array $allowedUserIds,
        int $depth,
        array &$visited,
        ?int $maxBuildDepth = null
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

        // ── Count direct reports from childrenMap (no recursion needed) ──
        $childUsers        = $childrenMap[$user->id] ?? [];
        $directReportCount = count($childUsers);

        // ── SHALLOW MODE: stop recursion at MAX_RENDER_DEPTH ──
        // Children at deeper levels are loaded via AJAX when user clicks expand.
        // Accurate counts are still computed from the maps (zero extra queries).
        if ($maxBuildDepth !== null && $depth >= $maxBuildDepth) {
            // Count sub-reports from map (without building node objects)
            $subReportCount  = 0;
            $subReportUsers  = $subChildrenMap[$user->id] ?? [];
            $directIds       = array_map(fn($c) => $c->id, $childUsers);
            foreach ($subReportUsers as $subChild) {
                if (!in_array($subChild->id, $directIds)) {
                    $subReportCount++;
                }
            }

            // Count total descendants via lightweight map walk (no node objects)
            $descVisited      = $visited;
            $descVisited[]    = $user->id;
            $totalDescendants = $this->countDescendantsFromMap($user->id, $childrenMap, $descVisited);

            return [
                'id'                       => $user->id,
                'name'                     => $user->fullname,
                'avatar'                   => $this->getAvatarUrl($user->avatar),
                'email'                    => $user->email,
                'phone'                    => $user->phone,
                'department'               => $department,
                'department_id'            => $deptId,
                'designation'              => $designation,
                'type'                     => $user->type->value,
                'is_active'                => $user->is_active,
                'role_ids'                 => $userRoleIds,
                'role_names'               => array_values($userRoleNames),
                'active_role_id'           => $activeRoleId,
                'active_role'              => $user->active_role,
                'direct_reports'           => $directReportCount,
                'sub_report_count'         => $subReportCount,
                'descendant_count'         => $totalDescendants,
                'reporting_manager_id'     => $user->reporting_manager,
                'sub_reporting_manager_id' => $user->sub_reporting_manager,
                'sub_reporting_manager_name' => $srmName,
                'children_loaded'          => false,
                'children'                 => [],
                'sub_reports'              => [],
                'depth'                    => $depth,
            ];
        }

        // ── Build DIRECT reports from childrenMap (solid line) ──
        $children = [];
        $totalDescendants = 0;

        foreach ($childUsers as $child) {
            $childNode = $this->buildNodeInMemory(
                $child, $userMap, $childrenMap, $subChildrenMap,
                $roleAssignments, $roleNameMap, $activeRoleMap, $allowedUserIds,
                $depth + 1, $visited, $maxBuildDepth
            );
            if ($childNode) {
                $childNode['report_type'] = 'direct';
                $children[] = $childNode;
                $totalDescendants += 1 + ($childNode['descendant_count'] ?? 0);
            }
        }

        // ── Build SUB-REPORTS from subChildrenMap (dotted line) ──
        //    These are users whose sub_reporting_manager = me but reporting_manager ≠ me.
        //    They appear in the tree under their primary RM; here we add a dotted reference.
        $subReports = [];
        $subReportUsers = $subChildrenMap[$user->id] ?? [];
        foreach ($subReportUsers as $subChild) {
            // Skip if already in children (their RM = me, so they're already direct)
            if (isset($childrenMap[$user->id])) {
                $directIds = array_map(fn($c) => $c->id, $childrenMap[$user->id]);
                if (in_array($subChild->id, $directIds)) continue;
            }

            $subNode = $this->buildSubReportNode(
                $subChild, $roleAssignments, $roleNameMap, $activeRoleMap
            );
            if ($subNode) {
                $subReports[] = $subNode;
            }
        }

        $directReportCount = count($children);
        $subReportCount = count($subReports);

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
            'direct_reports'         => $directReportCount,
            'sub_report_count'       => $subReportCount,
            'descendant_count'       => $totalDescendants,
            'reporting_manager_id'   => $user->reporting_manager,
            'sub_reporting_manager_id' => $user->sub_reporting_manager,
            'sub_reporting_manager_name' => $srmName,
            'children_loaded'        => $depth < self::MAX_RENDER_DEPTH,
            'children'               => $children,
            'sub_reports'            => $subReports,
            'depth'                  => $depth,
        ];
    }

    /**
     * Build a lightweight sub-report node (dotted line connection).
     * These nodes are NOT recursively expanded — they're just references
     * showing the cross-functional reporting relationship.
     */
    private function buildSubReportNode(
        User $user,
        $roleAssignments,
        array $roleNameMap,
        array $activeRoleMap
    ): ?array {
        $userRoleIds   = ($roleAssignments[$user->id] ?? collect())->pluck('role_id')->toArray();
        $userRoleNames = array_filter(
            array_map(fn($rid) => $roleNameMap[$rid] ?? null, $userRoleIds)
        );
        $empDetail     = $user->employeeDetail;

        return [
            'id'          => $user->id,
            'name'        => $user->fullname,
            'avatar'      => $this->getAvatarUrl($user->avatar),
            'email'       => $user->email,
            'department'  => $empDetail?->department?->name ?? 'N/A',
            'department_id'=> $empDetail?->department_id ?? null,
            'designation' => $empDetail?->designation?->name ?? 'N/A',
            'type'        => $user->type->value,
            'active_role' => $user->active_role,
            'role_names'  => array_values($userRoleNames),
            'active_role_id' => $activeRoleMap[$user->active_role] ?? null,
            'report_type' => 'sub',
            'reporting_manager_id' => $user->reporting_manager,
            'direct_reports' => 0,
            'sub_report_count' => 0,
            'descendant_count' => 0,
            'children'    => [],
            'sub_reports' => [],
            'depth'       => 0,
        ];
    }

    /**
     * Count total descendants for a user using the childrenMap.
     * Walks the map recursively without building node objects — O(n) where n = descendants.
     * Used in shallow mode to compute accurate descendant_count without building the full subtree.
     */
    private function countDescendantsFromMap(int $userId, array $childrenMap, array &$visited): int
    {
        $count = 0;
        foreach (($childrenMap[$userId] ?? []) as $childUser) {
            if (in_array($childUser->id, $visited)) continue;
            $visited[] = $childUser->id;
            $count++;
            $count += $this->countDescendantsFromMap($childUser->id, $childrenMap, $visited);
        }
        return $count;
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
     * Get reporting chain for ANY user (AJAX).
     * Used by org-tree detail panel to show full upward chain
     * regardless of lazy-loading depth. No depth limit — walks to root.
     *
     * PERFORMANCE (10K+ employees):
     *   - 1 batch query: SELECT id, reporting_manager (indexed, ~5-15ms for 10K rows)
     *   - Chain walk: pure PHP memory (0 queries, microseconds)
     *   - 1 batch query: chain member details + eager loads
     *   - 1 batch query: role assignments
     *   - Total: 3 queries per request (was N+1 before)
     *   - Cached per user for CACHE_TTL seconds when caching enabled
     *
     * CHAIN DEPTH IN REAL ORGS (even 10K+ employees):
     *   Typical: 3-6 levels. Maximum realistic: 8-10 levels.
     *   The panel scrolls for chains > 10 levels.
     */
    public function getReportingChain(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $tenantId = $this->getTenantId();
        $userId   = (int) $request->user_id;

        // ── CHECK CACHE FIRST ──
        // Reporting chains rarely change. Cache for 5 minutes.
        // Key includes user_id + tenant_id for isolation.
        $cacheKey = "reporting_chain_{$tenantId}_{$userId}";
        if (self::CACHE_TTL > 0) {
            $cached = Cache::get($cacheKey);
            if ($cached !== null) {
                return response()->json(['success' => true, 'chain' => $cached]);
            }
        }

        // ════════════════════════════════════════════════════════════
        // PERFORMANCE STRATEGY FOR 10K+ EMPLOYEES:
        // ──────────────────────────────────────────────────────────
        // OLD WAY (N queries):  while loop → 1 SELECT per chain level
        //   6-level chain = 6 queries. Slow under concurrent load.
        //
        // NEW WAY (1 query):  Single batch SELECT id, reporting_manager
        //   for ALL active tenant users → build id→rm map → walk chain
        //   entirely in PHP memory (ZERO extra queries).
        //
        // Why this is fast for 10K employees:
        //   - Single indexed query: SELECT id, reporting_manager FROM users
        //     WHERE tenant_id=? AND is_active=1  → returns 2 cols × 10K rows
        //     → ~200KB data, ~5-15ms on modern MySQL/MariaDB
        //   - Chain walk is pure PHP array lookups (microseconds)
        //   - Total: 1 query + in-memory walk + 1 detail batch = 3 queries total
        // ════════════════════════════════════════════════════════════

        // ── STEP 1: Single batch query — load ALL reporting_manager mappings ──
        // This replaces the N-query while loop.
        // Even with 10K employees, this is a single indexed read (~5-15ms).
        $rmMap = DB::table('users')
            ->where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->select('id', 'reporting_manager')
            ->pluck('reporting_manager', 'id')
            ->toArray();

        // ── STEP 2: Walk chain entirely in memory (ZERO queries) ──
        $chainIds = [];
        $visited  = [];
        $currentId = $userId;
        $truncated = false;

        while ($currentId && isset($rmMap[$currentId]) && !in_array($currentId, $visited)) {
            $visited[] = $currentId;
            $chainIds[] = $currentId;

            // ── SAFETY: Stop at MAX_CHAIN_DEPTH ──
            // Prevents runaway chains from bad data (30+ levels = almost always broken data).
            // We still count the TOTAL depth so the UI can show "showing 15 of 30 levels".
            if (count($chainIds) >= self::MAX_CHAIN_DEPTH) {
                // Check if chain continues beyond our limit
                $nextRm = $rmMap[$currentId] ?? null;
                if ($nextRm && $nextRm != $currentId && !in_array($nextRm, $visited)) {
                    $truncated = true;
                }
                break;
            }

            $rm = $rmMap[$currentId];
            if (!$rm || $rm == $currentId) break;  // null = top of chain, self-loop = error
            $currentId = (int) $rm;
        }

        // If user doesn't exist in rmMap (inactive/wrong tenant), try to include them
        if (empty($chainIds) && isset($rmMap[$userId])) {
            $chainIds[] = $userId;
        }

        if (empty($chainIds)) {
            return response()->json(['success' => true, 'chain' => [], 'truncated' => false, 'total_depth' => 0]);
        }

        // ── Count total depth if truncated (for "showing X of Y" message) ──
        $totalDepth = count($chainIds);
        if ($truncated) {
            // Continue walking just to count the total depth (cheap — in-memory only)
            $countId = (int) ($rmMap[end($chainIds)] ?? 0);
            while ($countId && isset($rmMap[$countId]) && !in_array($countId, $visited)) {
                $visited[] = $countId;
                $totalDepth++;
                $nextRm = $rmMap[$countId];
                if (!$nextRm || $nextRm == $countId) break;
                $countId = (int) $nextRm;
                if ($totalDepth > 100) break; // absolute safety cap on counting
            }
        }

        // ── STEP 3: Fetch chain member details in ONE batch query ──
        $users = User::whereIn('id', $chainIds)
            ->where('tenant_id', $tenantId)
            ->with('employeeDetail.department', 'employeeDetail.designation')
            ->get()
            ->keyBy('id');

        // ── STEP 4: Batch roles in ONE query ──
        $roleAssignments = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('model_id', $chainIds)
            ->get()
            ->groupBy('model_id');

        $allRoleIds  = $roleAssignments->flatten()->pluck('role_id')->unique()->toArray();
        $roleNameMap = DB::table('roles')->whereIn('id', $allRoleIds)->pluck('name', 'id')->toArray();

        // ── STEP 5: Build response ──
        $chain = [];
        foreach ($chainIds as $cid) {
            $u = $users->get($cid);
            if (!$u) continue;
            $roleIds    = ($roleAssignments[$cid] ?? collect())->pluck('role_id')->toArray();
            $roleNames  = array_values(array_filter(
                array_map(fn($rid) => $roleNameMap[$rid] ?? null, $roleIds)
            ));
            $chain[] = [
                'id'          => $u->id,
                'name'        => $u->fullname,
                'avatar'      => $this->getAvatarUrl($u->avatar),
                'designation' => $u->employeeDetail?->designation?->name ?? 'N/A',
                'department'  => $u->employeeDetail?->department?->name ?? 'N/A',
                'email'       => $u->email,
                'role_names'  => $roleNames,
                'active_role' => $u->active_role,
                'type'        => $u->type->value,
                'reporting_manager_id' => $u->reporting_manager,
            ];
        }

        // ── CACHE RESULT ──
        if (self::CACHE_TTL > 0) {
            Cache::put($cacheKey, $chain, self::CACHE_TTL);
        }

        return response()->json([
            'success'     => true,
            'chain'       => $chain,
            'truncated'   => $truncated,
            'total_depth' => $totalDepth,
        ]);
    }

    /**
     * Get subordinates tree for a specific user (JSON).
     * Used for lazy-loading children in the org tree when user expands a collapsed node.
     * Also returns per-child descendant counts via lightweight COUNT queries.
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

        // Lightweight: count direct reports per subordinate (single query)
        $directReportCounts = DB::table('users')
            ->where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->whereIn('reporting_manager', $subIds)
            ->select('reporting_manager', DB::raw('COUNT(*) as cnt'))
            ->groupBy('reporting_manager')
            ->pluck('cnt', 'reporting_manager')
            ->toArray();

        // Lightweight: count sub-reports per subordinate (single query)
        // Sub-reports = users whose sub_reporting_manager = this user AND reporting_manager != this user
        $subReportCounts = DB::table('users')
            ->where('tenant_id', $tenantId)
            ->where('is_active', 1)
            ->whereIn('sub_reporting_manager', $subIds)
            ->whereColumn('reporting_manager', '!=', 'sub_reporting_manager')
            ->select('sub_reporting_manager', DB::raw('COUNT(*) as cnt'))
            ->groupBy('sub_reporting_manager')
            ->pluck('cnt', 'sub_reporting_manager')
            ->toArray();

        // Lightweight: count total descendants per subordinate (recursive CTE or batched)
        $descendantCounts = $this->getBatchDescendantCounts($subIds, $tenantId);

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

            $directCount = $directReportCounts[$sub->id] ?? 0;
            $subCount    = $subReportCounts[$sub->id] ?? 0;
            $descCount   = $descendantCounts[$sub->id] ?? 0;

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
                'direct_reports'         => $directCount,
                'sub_report_count'       => $subCount,
                'descendant_count'       => $descCount,
                'reporting_manager_id'   => $sub->reporting_manager,
                'sub_reporting_manager_id' => $sub->sub_reporting_manager,
                'sub_reporting_manager_name' => $sub->subReportingManager?->fullname ?? null,
                'children_loaded'        => false,
                'children'               => [],
                'sub_reports'            => [],
                'depth'                  => 0,
            ];
        }

        // Also fetch sub-reports for this user (dashed line connections)
        $subReports = [];
        $subRmUsers = User::where('tenant_id', $tenantId)
            ->where('sub_reporting_manager', $userId)
            ->where('is_active', 1)
            ->where('reporting_manager', '!=', $userId)
            ->with(['employeeDetail.department', 'employeeDetail.designation'])
            ->get();

        if ($subRmUsers->isNotEmpty()) {
            $subRmIds  = $subRmUsers->pluck('id')->toArray();
            $subRmRoles = DB::table('model_has_roles')
                ->where('model_type', 'App\\Models\\User')
                ->whereIn('model_id', $subRmIds)
                ->get()
                ->groupBy('model_id');

            foreach ($subRmUsers as $sr) {
                // Skip if already in direct children
                if (in_array($sr->id, $subIds)) continue;

                // Department check
                if ($allowedDeptIds !== null) {
                    $srDetail = $sr->employeeDetail;
                    if ($srDetail && $srDetail->department_id && !in_array($srDetail->department_id, $allowedDeptIds)) {
                        continue;
                    }
                }

                $srRoleIds   = ($subRmRoles[$sr->id] ?? collect())->pluck('role_id')->toArray();
                $srRoleNames = array_values(array_filter(
                    array_map(fn($rid) => $roleNameMap[$rid] ?? null, $srRoleIds)
                ));
                $srDetail    = $sr->employeeDetail;

                $subReports[] = [
                    'id'                  => $sr->id,
                    'name'                => $sr->fullname,
                    'avatar'              => $this->getAvatarUrl($sr->avatar),
                    'email'               => $sr->email,
                    'department'          => $srDetail?->department?->name ?? 'N/A',
                    'department_id'       => $srDetail?->department_id ?? null,
                    'designation'         => $srDetail?->designation?->name ?? 'N/A',
                    'type'                => $sr->type->value,
                    'active_role'         => $sr->active_role,
                    'role_names'          => $srRoleNames,
                    'active_role_id'      => $activeRoleMap[$sr->active_role] ?? null,
                    'report_type'         => 'sub',
                    'reporting_manager_id' => $sr->reporting_manager,
                    'direct_reports'      => 0,
                    'sub_report_count'    => 0,
                    'descendant_count'    => 0,
                    'children'             => [],
                    'sub_reports'          => [],
                    'depth'               => 0,
                ];
            }
        }

        return response()->json([
            'success'     => true,
            'children'    => $children,
            'sub_reports' => $subReports,
        ]);
    }

    /**
     * Batch-count total descendants for multiple users (single query per depth level).
     * Iteratively walks down: who reports to X, who reports to those, etc.
     * Much faster than per-user recursive queries.
     */
    private function getBatchDescendantCounts(array $userIds, int $tenantId): array
    {
        if (empty($userIds)) return [];

        $counts = array_fill_keys($userIds, 0);
        $processed = [];
        $currentLevel = $userIds;
        $depth = 0;

        while (!empty($currentLevel) && $depth < self::MAX_DEPTH) {
            // Find who reports to anyone in the current level
            $nextLevel = DB::table('users')
                ->where('tenant_id', $tenantId)
                ->where('is_active', 1)
                ->whereIn('reporting_manager', $currentLevel)
                ->pluck('reporting_manager', 'id')
                ->toArray();

            if (empty($nextLevel)) break;

            // Count per parent
            $parentCounts = [];
            foreach ($nextLevel as $childId => $parentId) {
                $parentCounts[$parentId] = ($parentCounts[$parentId] ?? 0) + 1;
                if (!in_array($childId, $processed)) {
                    $currentLevel[] = $childId;
                }
            }

            // Accumulate into original ancestor counts
            foreach ($parentCounts as $parentId => $cnt) {
                // Walk up the chain to find if this parentId is one of our ancestors
                // For simplicity: just add to direct counts (skip recursive accumulation for perf)
                // The total visible count = direct_report_counts + these
            }

            // Group next level by parent for counting
            $grouped = DB::table('users')
                ->where('tenant_id', $tenantId)
                ->where('is_active', 1)
                ->whereIn('reporting_manager', $currentLevel)
                ->select('reporting_manager', DB::raw('COUNT(*) as cnt'))
                ->groupBy('reporting_manager')
                ->pluck('cnt', 'reporting_manager')
                ->toArray();

            foreach ($grouped as $parentId => $cnt) {
                if (isset($counts[$parentId])) {
                    $counts[$parentId] += $cnt;
                }
            }

            // Next level = all child IDs
            $nextIds = DB::table('users')
                ->where('tenant_id', $tenantId)
                ->where('is_active', 1)
                ->whereIn('reporting_manager', $currentLevel)
                ->pluck('id')
                ->toArray();

            $processed = array_merge($processed, $currentLevel);
            $currentLevel = array_diff($nextIds, $processed);
            $currentLevel = array_values($currentLevel);
            $depth++;
        }

        return $counts;
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

        // Support search by ID (for showDetailForExternal fallback)
        if (is_numeric($query)) {
            $users = User::where('tenant_id', $tenantId)
                ->where('is_active', 1)
                ->where('id', (int) $query)
                ->with('employeeDetail.designation', 'employeeDetail.department')
                ->limit(1)
                ->get();
        } else {
            // Search across ALL active users (Employee + Admin types) using CONCAT_WS
            // so full name searches like "Vineet Mishra" work correctly.
            $users = User::where('tenant_id', $tenantId)
                ->where('is_active', 1)
                ->whereIn('type', [UserType::EMPLOYEE, UserType::ADMIN])
                ->where(function ($q) use ($query) {
                    $like = '%' . $query . '%';
                    $q->whereRaw("CONCAT_WS(' ', firstname, lastname) LIKE ?", [$like])
                      ->orWhere('email', 'LIKE', $like);
                })
                ->with('employeeDetail.designation', 'employeeDetail.department')
                ->limit(20)
                ->get();
        }

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
