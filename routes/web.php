<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuoteEnquiryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VideoTourController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\QuoteEnquiryController as AdminQuoteEnquiryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\BannerController;
use App\Models\Page;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\UserController;

/* ============================================================
   1. PUBLIC HOMEPAGE + QUOTE FORM
   ============================================================ */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/quote', [QuoteEnquiryController::class, 'store'])->name('quote.store');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


/* ============================================================
   2. ADMIN AUTH (guest only) — must come before the catch-all
   ============================================================ */
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login',  [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.attempt');

        // Password reset
        Route::get('forgot-password',       [PasswordResetController::class, 'request'])->name('password.request');
        Route::post('forgot-password',      [PasswordResetController::class, 'email'])->name('password.email');
        Route::get('reset-password/{token}',[PasswordResetController::class, 'reset'])->name('password.reset');
        Route::post('reset-password',       [PasswordResetController::class, 'update'])->name('password.update');
    });

    Route::post('logout', [LoginController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');
});

/* ============================================================
   3. ADMIN (authenticated)
   ============================================================ */
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Site Settings
        Route::get('settings',  [SiteSettingController::class, 'index'])->name('settings.index');
        Route::put('settings',  [SiteSettingController::class, 'update'])->name('settings.update');

        // Newsletter
        Route::get('newsletter',            [NewsletterSubscriberController::class, 'index'])->name('newsletter.index');
        Route::get('newsletter/export',     [NewsletterSubscriberController::class, 'export'])->name('newsletter.export');
        Route::post('newsletter/bulk',      [NewsletterSubscriberController::class, 'bulk'])->name('newsletter.bulk');
        Route::patch('newsletter/{subscriber}/toggle', [NewsletterSubscriberController::class, 'toggle'])->name('newsletter.toggle');
        Route::delete('newsletter/{subscriber}',       [NewsletterSubscriberController::class, 'destroy'])->name('newsletter.destroy');

        // Countries
        Route::get('countries',                       [CountryController::class, 'index'])->name('countries.index');
        Route::get('countries/create',                [CountryController::class, 'create'])->name('countries.create');
        Route::post('countries',                      [CountryController::class, 'store'])->name('countries.store');
        Route::get('countries/{country}/edit',        [CountryController::class, 'edit'])->name('countries.edit');
        Route::put('countries/{country}',             [CountryController::class, 'update'])->name('countries.update');
        Route::patch('countries/{country}/toggle',    [CountryController::class, 'toggle'])->name('countries.toggle');
        Route::delete('countries/{country}',          [CountryController::class, 'destroy'])->name('countries.destroy');

        // Product Categories
        Route::get('categories',                          [ProductCategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create',                   [ProductCategoryController::class, 'create'])->name('categories.create');
        Route::post('categories',                         [ProductCategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit',          [ProductCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}',               [ProductCategoryController::class, 'update'])->name('categories.update');
        Route::patch('categories/{category}/toggle',      [ProductCategoryController::class, 'toggle'])->name('categories.toggle');
        Route::delete('categories/{category}',            [ProductCategoryController::class, 'destroy'])->name('categories.destroy');

        // Products
        Route::get('products',                       [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create',                [ProductController::class, 'create'])->name('products.create');
        Route::post('products',                      [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit',        [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}',             [ProductController::class, 'update'])->name('products.update');
        Route::patch('products/{product}/toggle',    [ProductController::class, 'toggle'])->name('products.toggle');
        Route::delete('products/{product}',          [ProductController::class, 'destroy'])->name('products.destroy');

        // Video Tours
        Route::get('video-tours',                       [VideoTourController::class, 'index'])->name('video-tours.index');
        Route::get('video-tours/create',                [VideoTourController::class, 'create'])->name('video-tours.create');
        Route::post('video-tours',                      [VideoTourController::class, 'store'])->name('video-tours.store');
        Route::get('video-tours/{videoTour}/edit',      [VideoTourController::class, 'edit'])->name('video-tours.edit');
        Route::put('video-tours/{videoTour}',           [VideoTourController::class, 'update'])->name('video-tours.update');
        Route::patch('video-tours/{videoTour}/toggle',  [VideoTourController::class, 'toggle'])->name('video-tours.toggle');
        Route::delete('video-tours/{videoTour}',        [VideoTourController::class, 'destroy'])->name('video-tours.destroy');

        // Certifications
        Route::get('certifications',                          [CertificationController::class, 'index'])->name('certifications.index');
        Route::get('certifications/create',                   [CertificationController::class, 'create'])->name('certifications.create');
        Route::post('certifications',                         [CertificationController::class, 'store'])->name('certifications.store');
        Route::get('certifications/{certification}/edit',     [CertificationController::class, 'edit'])->name('certifications.edit');
        Route::put('certifications/{certification}',          [CertificationController::class, 'update'])->name('certifications.update');
        Route::patch('certifications/{certification}/toggle', [CertificationController::class, 'toggle'])->name('certifications.toggle');
        Route::delete('certifications/{certification}',       [CertificationController::class, 'destroy'])->name('certifications.destroy');

        // Quote Enquiries  ← destroy now uses the ADMIN controller (was wrong before)
        Route::get('enquiries',                       [AdminQuoteEnquiryController::class, 'index'])->name('enquiries.index');
        Route::get('enquiries/export',                [AdminQuoteEnquiryController::class, 'export'])->name('enquiries.export');
        Route::post('enquiries/bulk',                 [AdminQuoteEnquiryController::class, 'bulk'])->name('enquiries.bulk');
        Route::get('enquiries/{enquiry}',             [AdminQuoteEnquiryController::class, 'show'])->name('enquiries.show');
        Route::put('enquiries/{enquiry}',             [AdminQuoteEnquiryController::class, 'update'])->name('enquiries.update');
        Route::patch('enquiries/{enquiry}/status',    [AdminQuoteEnquiryController::class, 'updateStatus'])->name('enquiries.status');
        Route::delete('enquiries/{enquiry}',          [AdminQuoteEnquiryController::class, 'destroy'])->name('enquiries.destroy');

        // Pages
        Route::get('pages',                    [PageController::class, 'index'])->name('pages.index');
        Route::get('pages/create',             [PageController::class, 'create'])->name('pages.create');
        Route::post('pages',                   [PageController::class, 'store'])->name('pages.store');
        Route::get('pages/{page}/edit',        [PageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{page}',             [PageController::class, 'update'])->name('pages.update');
        Route::patch('pages/{page}/toggle',    [PageController::class, 'toggle'])->name('pages.toggle');
        Route::delete('pages/{page}',          [PageController::class, 'destroy'])->name('pages.destroy');

        // Testimonials
        Route::get('testimonials',                          [TestimonialController::class, 'index'])->name('testimonials.index');
        Route::get('testimonials/create',                   [TestimonialController::class, 'create'])->name('testimonials.create');
        Route::post('testimonials',                         [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::get('testimonials/{testimonial}/edit',       [TestimonialController::class, 'edit'])->name('testimonials.edit');
        Route::put('testimonials/{testimonial}',            [TestimonialController::class, 'update'])->name('testimonials.update');
        Route::patch('testimonials/{testimonial}/toggle',   [TestimonialController::class, 'toggle'])->name('testimonials.toggle');
        Route::delete('testimonials/{testimonial}',         [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

        // Banners
        Route::get('banners',                     [BannerController::class, 'index'])->name('banners.index');
        Route::get('banners/create',              [BannerController::class, 'create'])->name('banners.create');
        Route::post('banners',                    [BannerController::class, 'store'])->name('banners.store');
        Route::get('banners/{banner}/edit',       [BannerController::class, 'edit'])->name('banners.edit');
        Route::put('banners/{banner}',            [BannerController::class, 'update'])->name('banners.update');
        Route::patch('banners/{banner}/toggle',   [BannerController::class, 'toggle'])->name('banners.toggle');
        Route::delete('banners/{banner}',         [BannerController::class, 'destroy'])->name('banners.destroy');

        // Contact Messages
        Route::get('messages',                      [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/export',               [ContactMessageController::class, 'export'])->name('messages.export');
        Route::post('messages/bulk',                [ContactMessageController::class, 'bulk'])->name('messages.bulk');
        Route::get('messages/{message}',            [ContactMessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}/toggle',   [ContactMessageController::class, 'toggle'])->name('messages.toggle');
        Route::delete('messages/{message}',         [ContactMessageController::class, 'destroy'])->name('messages.destroy');

        // Manage Users
        Route::get('users',                    [UserController::class, 'index'])->name('users.index');
        Route::get('users/create',             [UserController::class, 'create'])->name('users.create');
        Route::post('users',                   [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit',        [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}',             [UserController::class, 'update'])->name('users.update');
        Route::patch('users/{user}/toggle',    [UserController::class, 'toggle'])->name('users.toggle');
        Route::delete('users/{user}',          [UserController::class, 'destroy'])->name('users.destroy');

        // Account settings
        Route::get('account',           [AccountController::class, 'index'])->name('account.index');
        Route::put('account/profile',   [AccountController::class, 'updateProfile'])->name('account.profile');
        Route::put('account/password',  [AccountController::class, 'updatePassword'])->name('account.password');
    });

/* ============================================================
   4. PUBLIC PAGES CATCH-ALL — MUST BE THE VERY LAST ROUTE
   ============================================================ */
Route::get('{slug}', function ($slug) {
    $page = Page::published()
        ->where('slug', $slug)
        ->firstOrFail();

    return view('page', compact('page'));
})->where('slug', '[a-z0-9\-]+')->name('page.show');