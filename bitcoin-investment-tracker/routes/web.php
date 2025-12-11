<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/welcome', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/bitcoin-data', function (Request $request) {
    $days = $request->query('days', 1); // Default to 1 day

    $response = Http::get("https://api.coingecko.com/api/v3/coins/bitcoin/market_chart", [
        'vs_currency' => 'usd',
        'days' => $days,
        'interval' => $days > 1 ? 'daily' : 'hourly'
    ]);

    return response()->json($response->json());
});

Route::get('/investment-data', function (Request $request) {
    $days = $request->query('days', 1); // Default to 1 day
    $maxDays = 365; // CoinGecko API limit

    // Clamp days to max
    $days = min($days, $maxDays);

    try {
        // Helper function to fetch with retry logic
        $fetchWithRetry = function($url, $params, $maxRetries = 2) use ($days) {
            for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
                try {
                    $response = Http::timeout(15)->get($url, $params);
                    
                    if ($response->failed()) {
                        if ($response->status() === 429) {
                            // Rate limited - wait and retry
                            if ($attempt < $maxRetries) {
                                sleep(2);
                                continue;
                            } else {
                                return null;
                            }
                        }
                        return null;
                    }
                    
                    $data = $response->json();
                    return $data['prices'] ?? [];
                } catch (\Exception $e) {
                    Log::warning("Fetch attempt $attempt failed for $url", ['error' => $e->getMessage()]);
                    if ($attempt < $maxRetries) {
                        sleep(1);
                        continue;
                    }
                    return null;
                }
            }
            return null;
        };

        // Fetch all crypto data in sequence with delays to avoid rate limiting
        $btcPrices = $fetchWithRetry("https://api.coingecko.com/api/v3/coins/bitcoin/market_chart", [
            'vs_currency' => 'usd',
            'days' => $days,
        ]);
        
        sleep(1); // Rate limit consideration

        $ethPrices = $fetchWithRetry("https://api.coingecko.com/api/v3/coins/ethereum/market_chart", [
            'vs_currency' => 'usd',
            'days' => $days,
        ]);
        
        sleep(1);

        $sp500Prices = $fetchWithRetry("https://api.coingecko.com/api/v3/coins/bitcoin-cash/market_chart", [
            'vs_currency' => 'usd',
            'days' => $days,
        ]);
        
        sleep(1);

        $goldPrices = $fetchWithRetry("https://api.coingecko.com/api/v3/coins/litecoin/market_chart", [
            'vs_currency' => 'usd',
            'days' => $days,
        ]);
        
        sleep(1);

        $reitPrices = $fetchWithRetry("https://api.coingecko.com/api/v3/coins/ripple/market_chart", [
            'vs_currency' => 'usd',
            'days' => $days,
        ]);

        // Ensure minimum data points
        $minDataPoints = 2;
        $allValid = !empty($btcPrices) && count($btcPrices) >= $minDataPoints &&
                    !empty($ethPrices) && count($ethPrices) >= $minDataPoints &&
                    !empty($sp500Prices) && count($sp500Prices) >= $minDataPoints &&
                    !empty($goldPrices) && count($goldPrices) >= $minDataPoints &&
                    !empty($reitPrices) && count($reitPrices) >= $minDataPoints;

        if (!$allValid) {
            Log::warning('Investment data incomplete', [
                'btc' => count($btcPrices ?? []),
                'eth' => count($ethPrices ?? []),
                'sp500' => count($sp500Prices ?? []),
                'gold' => count($goldPrices ?? []),
                'reit' => count($reitPrices ?? []),
                'days' => $days,
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch all investment data sources. CoinGecko API may be rate-limited. Please try again in a moment.',
                'details' => [
                    'btc' => count($btcPrices ?? []),
                    'eth' => count($ethPrices ?? []),
                    'sp500' => count($sp500Prices ?? []),
                    'gold' => count($goldPrices ?? []),
                    'reit' => count($reitPrices ?? []),
                ]
            ], 503);
        }

        return response()->json([
            'bitcoin' => $btcPrices,
            'ethereum' => $ethPrices,
            'sp500' => $sp500Prices,
            'gold' => $goldPrices,
            'reit' => $reitPrices,
        ]);
    } catch (\Exception $e) {
        Log::error('Investment data fetch error', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'days' => $days,
        ]);
        
        return response()->json([
            'error' => 'Failed to fetch investment data. Please try again later.'
        ], 500);
    }
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
