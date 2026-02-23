<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        // Skip guests
        if (!Auth::check()) {
            return $next($request);
        }

        $timeout = config('session.lifetime') * 60;

        if (
            session()->has('last_activity') &&
            time() - session('last_activity') > $timeout
        ) {
            // Mark user offline
            Auth::user()->update([
                'is_online' => false,
            ]);

            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('login')
                ->with('message', 'Your session has expired. Please login again.');
        }

        session(['last_activity' => time()]);

        Auth::user()->update([
            'last_activity_at' => now(),
            'is_online' => true,
        ]);

        return $next($request);
    }
}
