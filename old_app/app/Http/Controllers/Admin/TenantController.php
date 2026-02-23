<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use App\Services\TenantService;
use App\Enums\UserType;

class TenantController extends Controller
{
    public $view = 'pages.tenant.';

    public function index ()
    {
        $title      = 'Organization List'; 
        $tenants    = Tenant::all();
        $settings   = []; 

        foreach ($tenants as $tenant) {
            $settings[$tenant->id] = [
                'theme' => DB::table('settings')
                    ->where('group', 'theme')
                    ->where('tenant_id', $tenant->id)
                    ->get(),

                'company' => DB::table('settings')
                    ->where('group', 'company')
                    ->where('tenant_id', $tenant->id)
                    ->get(),
            ];
        }

        return view(
            $this->view . 'index', 
            [
                'tenants' => $tenants, 
                'settings' => $settings, 
                'pageTitle' => $title
            ]
        );
    }

    public function show (Request $request)
    {
        $pageTitle = 'Tenant Details';
        $tenantId = decrypt($request->id);

        if (!$tenantId) {
            $notification = notify('Organization Not Found !');
            return back()->with($notification);
        }

        $tenant         = Tenant::withoutGlobalScopes()->findOrFail($tenantId);
        $subscription   = $tenant->currentSubscriptionWithoutGlobalScope;

        // Company & Theme Settings
        $theme   = DB::table('settings')->where('tenant_id', $tenant->id)->where('group', 'theme')->get();
        $company = DB::table('settings')->where('tenant_id', $tenant->id)->where('group', 'company')->get();

        // Logo
        $logo = $theme->firstWhere('name', 'logo_dark') ?? $theme->firstWhere('name', 'logo_light');
        $file = trim($logo->payload, '"');

        $clientCount        = User::withoutGlobalScopes()->where(['tenant_id' => $tenant->id, 'type' => UserType::CLIENT])->count();
        $activeUserCount    = User::withoutGlobalScopes()->where(['tenant_id' => $tenant->id, 'is_active' => 1])->count();
        $invoiceCount       = DB::table('invoices')->where('tenant_id', $tenant->id)->count();
        $projectCount       = DB::table('projects')->where('tenant_id', $tenant->id)->count();

        return view($this->view . 'show', compact(
            'pageTitle', 'tenant', 'theme', 'company', 'logo', 'file',
            'clientCount', 'invoiceCount', 'projectCount', 'activeUserCount', 'subscription'
        ));
    }

    // Insert function is located to OrganizationController

    public function create (Request $request)
    {
        return view($this->view . 'create');
    }

    public function edit (Request $request)
    {
        $tenantId = $request->id;

        if (!$tenantId) {
            $notification = notify('Organization Not Found !');
            return back()->with($notification);
        }

        $tenant = Tenant::Find($tenantId);

        return view($this->view . 'edit', ['tenant' => $tenant]);
    }

    public function update (Request $request)
    {
        $tenantId = $request->tenant_id;
        $userId   = $request->user_id;

        if (!$tenantId || !$userId) {
            $notification = notify('User or Tenant Not Found!');
            return back()->with($notification);
        }

        $data = [
            'tenant_id'         => $request->tenant_id,
            'user_id'           => $request->user_id,
            'organization_name' => $request->organization_name,
            'organization_size' => $request->organization_size,
            'status'            => $request->status,
            'f_name'            => $request->f_name,
            'l_name'            => $request->l_name,
            'email'             => $request->email,
            'phone'             => $request->phone
        ];

        $update = TenantService::updateTenant($data);

        if (!$update) {
            $notification = notify('Something went wrong!');
            return back()->with($notification);
        }

        $notification = notify('Organization Update Successfully');
        return back()->with($notification);
    }

}
