<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->is_onboarding_complete == 0) {
            // return redirect()->route('onboard.start', ['user_id' => encrypt($user->id)]);

            if (session()->has('onboarding_redirect')) {
                return redirect(session('onboarding_redirect'));
            }

            Auth::logout();

            return response()->make('
                <div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
                    <h2 style="color: #f39c12;">⚠️ Onboarding Incomplete</h2>
                    <p style="color: #555; font-size: 16px;">
                        Your onboarding process is not completed yet. <br>
                        Please complete your onboarding process using the invitation link sent to your registered email by Renown CRM
                    </p>
                    <a href="'.url('/').'" 
                       style="display: inline-block; margin-top: 20px; 
                              padding: 10px 20px; background-color: #3490dc; 
                              color: #fff; text-decoration: none; border-radius: 5px;">
                        Go to Home
                    </a>
                </div>
            ');
            
        }
        
        return $next($request);
    }
}
