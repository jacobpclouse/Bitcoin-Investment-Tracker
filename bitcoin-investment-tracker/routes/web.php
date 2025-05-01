<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// // fetch bitcoin data 
// Route::get('/bitcoin-data', function () {
//     $response = Http::get('https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd');
//     return response()->json($response->json());
// });

Route::get('/bitcoin-data', function (Request $request) {
    $days = $request->query('days', 1); // Default to 1 day

    $response = Http::get("https://api.coingecko.com/api/v3/coins/bitcoin/market_chart", [
        'vs_currency' => 'usd',
        'days' => $days,
        'interval' => $days > 1 ? 'daily' : 'hourly'
    ]);

    return response()->json($response->json());
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
