<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TenantService;
use App\Enums\TenantStatus;
use App\Models\Plan;
use Illuminate\Support\Facades\Http;

class OrganizationController extends Controller
{
    public function login () 
    {
        $tenant = app('tenant');

        if (!$tenant) {
            return response("<h2>Invalid organization</h2>", 404);
        }

        if (auth()->check()) {
            return redirect('/dashboard');
        }

        return view('auth.organization-login', ['tenant' => $tenant]);
    }

    public function signup (Request $request)
    {
        $planName = decrypt($request->plan);
        $plan = Plan::where('name', $planName)->first();

        if (!$plan) {
            return back()->with('error', 'Plan not found!');
        }

        if ($request->getHost() === env('PRIMARY_HOST')) {
            $this->data['tenant']       = app('tenant');
            $this->data['plan']         = $plan;
            return view('auth.organization-signup', $this->data);
        }

        return redirect()->route('tenant.login');
        
    }

    public function store (Request $request)
    {

        // dd($request->all());

        $planName = decrypt($request->plan);
        $plan = Plan::where('name', $planName)->first();

        $validated = $request->validate([
            'organization_name'     => 'required|string|max:255',
            'organization_size'     => 'required|string',
            'f_name'                => 'required|string|min:3|max:255',
            'l_name'                => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required',
            'password'              => 'required|min:6|confirmed',
            'cf-turnstile-response' => 'required'
        ]);
        
        $turnstile = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret'   => env('TURNSTILE_SECRET_KEY'),
                'response' => $request->input('cf-turnstile-response'),
                'remoteip' => $request->ip(),
            ]
        );
        
        $result = $turnstile->json();

        if (!($result['success'] ?? false)) {
    
            return back()
                ->withErrors([
                    'captcha' => 'Captcha verification failed.'
                ])
                ->withInput();
        }

        $validated['plan'] = $plan;

        try {
            $createTenant = TenantService::createTenant($validated);

            return back()->with('tenant_status', 'success');
        }catch (\Exception $e) {
            return back()->with('tenant_status', 'error');
        }
        
    }

}
