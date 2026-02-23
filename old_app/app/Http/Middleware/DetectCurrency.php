<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Throwable;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class DetectCurrency
{
    public function handle(Request $request, Closure $next): Response
    {

        if (!session()->has('currency')) {
            try {
                $location = geoip($request->ip());
                $currency = $location->currency['code'] ?? 'INR';
            } catch (\Throwable $e) {
                $currency = 'INR';
            }

            session(['currency' => $currency]);
        }

        return $next($request);
    }
}
