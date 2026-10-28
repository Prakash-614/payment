<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\TransactionController;

use NepalPay\Gateway\Http\Controllers\AdminController as GatewayAdminController;
use NepalPay\Gateway\Http\Middleware\EnsureAdminAuthenticated;

// Protected Admin Portal (uses the package's EnsureAdminAuthenticated session guard)
use App\Http\Controllers\Admin\AuthController;

// Admin Portal Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Login Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes (Requires authentication)
    Route::middleware('auth')->group(function () {
        Route::get('/websites', [WebsiteController::class, 'index'])->name('websites.index');
        Route::get('/websites/create', [WebsiteController::class, 'create'])->name('websites.create');
        Route::post('/websites', [WebsiteController::class, 'store'])->name('websites.store');
        Route::get('/websites/{website}/edit', [WebsiteController::class, 'edit'])->name('websites.edit');
        Route::post('/websites/{website}', [WebsiteController::class, 'update'])->name('websites.update');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });
});

// Public Hosted Checkout Routes (Protected with 60 requests/minute throttle)
Route::match(['post', 'options'], '/api/v1/checkout/session', [CheckoutController::class, 'createSession'])
    ->middleware('throttle:100,1');
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

// Demo endpoint simulating Hotel Inn's server receiving the webhook
Route::post('/demo/webhook', function (Illuminate\Http\Request $request) {
    $signature = $request->header('X-GTech-Signature');
    $rawPayload = $request->getContent();

    $hotelInn = \App\Models\Website::where('domain', 'hotelinn.com')->first();
    $expectedSignature = hash_hmac('sha256', $rawPayload, $hotelInn->secret_key ?? '');

    $isValid = hash_equals($expectedSignature, $signature ?? '');

    \Illuminate\Support\Facades\Log::info("Hotel Inn Webhook received", [
        'valid_signature' => $isValid,
        'payload' => $request->all(),
    ]);

    return response()->json([
        'received' => true,
        'signature_verified' => $isValid,
    ]);
});