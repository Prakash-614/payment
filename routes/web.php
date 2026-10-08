<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\CheckoutController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/websites', [WebsiteController::class, 'index'])->name('websites.index');
    Route::get('/websites/create', [WebsiteController::class, 'create'])->name('websites.create');
    Route::post('/websites', [WebsiteController::class, 'store'])->name('websites.store');
    Route::get('/websites/{website}/edit', [WebsiteController::class, 'edit'])->name('websites.edit');
    Route::post('/websites/{website}', [WebsiteController::class, 'update'])->name('websites.update');
});

// Public Hosted Checkout Routes
Route::post('/api/v1/checkout/session', [CheckoutController::class, 'createSession']);
Route::get('/checkout/{token}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::get('/checkout/{token}/process/{gateway}', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/callback/{gateway}/{action}/{token}', [CheckoutController::class, 'callback'])->name('checkout.callback');


// The Soaltee Kathmandu Client Demo Routes
Route::get('/demo', function () {
    return view('demo');
})->name('demo');

Route::get('/demo/success', function (Illuminate\Http\Request $request) {
    return "<h1 style='color:green;font-family:sans-serif;'>Booking Confirmed!</h1><p>Order ID: " . e($request->query('order_id')) . "</p><p>Status: " . e($request->query('status')) . "</p><a href='/demo'>Try again</a>";
});

Route::get('/demo/cancel', function (Illuminate\Http\Request $request) {
    return "<h1 style='color:red;font-family:sans-serif;'>Booking Cancelled</h1><p>Status: " . e($request->query('status')) . "</p><a href='/demo'>Try again</a>";
});
