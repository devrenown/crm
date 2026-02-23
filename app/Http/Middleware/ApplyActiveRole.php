<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyActiveRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

	    if ($user) {
	        $activeRole = session('active_role') ?? $user->active_role ?? $user->getRoleNames()->first();
	        session(['active_role' => $activeRole]);
	        app()->instance('active_role', $activeRole);
	    }

	    return $next($request);
    }
}
