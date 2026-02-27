<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $timeout = config('session.lifetime') * 60;

        // Session expired
        if (
            session()->has('last_activity') &&
            time() - session('last_activity') > $timeout
        ) {
            $user->update([
                'is_online' => false,
            ]);

            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('login')
                ->with('message', 'Your session has expired. Please login again.');
        }

        // Update session activity
        session(['last_activity' => time()]);

        // Update DB only if last activity is older than 1 minute
        if (
            !$user->last_activity_at ||
            $user->last_activity_at->diffInSeconds(now()) > 60
        ) {
            $user->update([
                'last_activity_at' => now(),
                'is_online' => true,
            ]);
        }

        return $next($request);
    }
}