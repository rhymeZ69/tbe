<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuoteEnquiryController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/quote', [QuoteEnquiryController::class, 'store'])->name('quote.store');
/* ---------- Admin ---------- */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Placeholder routes — build these later:
    // Route::resource('products', ProductController::class);
    // Route::resource('enquiries', QuoteEnquiryController::class);
    // Route::resource('messages', ContactMessageController::class);
    // ...
});
