<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    public static function getRate(string $from, string $to): float
    {
        // dd($from, $to);
        if ($from === $to) {
            return 1;
        }

        return Cache::remember(
            "currency_rate_{$from}_{$to}",
            now()->addDays(30),
            function () use ($from, $to) {

                $response = Http::get('https://anyapi.io/api/v1/exchange/convert', [
                    'base'   => $from,
                    'to'     => $to,
                    'amount' => 1,
                    'apiKey' => env('ANYAPI_CURRENCY_KEY'),
                ]);

                if (! $response->successful()) {
                    Log::error('Currency API HTTP Error', [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                    return 1;
                }

                $data = $response->json();

                return (float) (
                    $data['converted']
                    ?? $data['rate']
                    ?? 1
                );
            }
        );
    }
}
