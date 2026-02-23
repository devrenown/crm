<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Enums\TenantStatus;
use App\Enums\UserType;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenantOnboardMail;

class TenantService 
{
	/**
	* Create a new tenant and initialize related records.
	*
	* @param array $data
	* @return Tenant
	*/

	public static function createTenant (array $data): Tenant
	{
		return DB::transaction(function () use ($data) {

			$subDomain 	= static::generateSubDomain($data['organization_name']);
			$plan 		= $data['plan'];

			$tenant = Tenant::create([
				'name' 		=> $data['organization_name'],
                'domain' 	=> $subDomain,
                'size'      => $data['organization_size'],
                'status' 	=> TenantStatus::INACTIVE,
			]);

			app()->instance('creating_tenant', true);

			// Create Default Admin User for Tenant
            $user = User::withoutGlobalScopes()->create([
                'tenant_id' 				=> $tenant->id,
                'firstname' 				=> $data['f_name'],
                'lastname' 					=> $data['l_name'],
                'email' 					=> $data['email'],
                'phone'   					=> $data['phone'],
                'password' 					=> Hash::make($data['password']),
                'type' 						=> UserType::ADMIN,
                'is_onboarding_complete' 	=> 1,
                'is_active' 				=> 1,
            ]);

            $user->assignRole('admin');

            // Create Default Subscription (optional)
            Subscription::withoutGlobalScopes()->create([
                'tenant_id' 	=> $tenant->id,
                'plan_id' 		=> $plan->id ?? 1,
                'start_date' 	=> now(),
                'end_date' 		=> now()->addDays((int)$plan->duration),
                'status' 		=> 1, // Active
            ]);

            app()->forgetInstance('creating_tenant');

            // Send Welcome Email (optional)
            Mail::to($user->email)->send(new TenantOnboardMail($tenant));

            return $tenant;

		});
	}

	/**
	* Update existing tenant.
	*
	* @param array $data
	* @return Tenant
	*/

	public static function updateTenant (array $data): Tenant
	{
		return DB::transaction (function () use ($data) {
			$tenant 	= Tenant::findOrFail($data['tenant_id']);
			$adminUser 	= User::withoutGlobalScopes()->findOrFail($data['user_id']);

			$tenant->update([
				'name' 		=> $data['organization_name'],
                'size'      => $data['organization_size'],
                'status' 	=> $data['status'],
			]);

			$adminUser->update([
				'firstname' 	=> $data['f_name'],
                'lastname'      => $data['l_name'],
                'email' 		=> $data['email'],
                'phone' 		=> $data['phone'],
			]);

			return $tenant;

		});
	}

	/**
     * Generate unique subdomain for the tenant.
     *
     * @param string $companyName
     * @return string
     */

	private static function generateSubDomain(string $companyName): string
	{
	    // Take the first word only
	    $firstWord = explode(' ', trim($companyName))[0];

	    // Convert to a clean slug
	    $slug = Str::slug($firstWord);

	    // Base domain
	    $domain = "{$slug}.renownsystem.com";

	    // Check uniqueness
	    $count = Tenant::where('domain', $domain)->count();

	    if ($count > 0) {
	        // append incremental number instead of random
	        $suffix = Tenant::where('domain', 'LIKE', "{$slug}%.renownsystem.com")->count();
	        $domain = "{$slug}-" . ($suffix + 1) . ".renownsystem.com";
	    }

	    return $domain;
	}
	
	/**
     * Get tenant timezone
     *
     * @param int $tenantId
     * @return string
     */
	public static function timezone(int $tenantId): string
	{
		$tz = DB::table('settings')
			->where('tenant_id', $tenantId)
			->where('group', 'localization')
			->where('name', 'timezone')
			->value('payload');
	
		if (!$tz) {
			return config('app.timezone');
		}
	
		// Remove quotes and fix escaped slashes
		return str_replace('\/', '/', trim($tz, "\"'"));
	}
	

 
}