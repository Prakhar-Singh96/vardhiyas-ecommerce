<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FilterTypeController;
use App\Http\Controllers\Admin\FilterValueController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\MoodSectionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CollectionController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('frontend.pages.home');
});

Route::prefix('admin')->name('admin.')->group(function () {

    // Public routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Protected routes
    Route::middleware('admin.auth')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Categories
        Route::resource('categories', CategoryController::class);
        Route::patch('categories/{category}/toggle', [CategoryController::class, 'toggle'])
            ->name('categories.toggle');

        // Filter Types
        Route::resource('filter-types', FilterTypeController::class)
            ->except(['show']);

        // Filter Values — shallow nested
        Route::post(
            'filter-types/{filterType}/values',
            [FilterValueController::class, 'store']
        )
            ->name('filter-values.store');

        Route::put(
            'filter-values/{filterValue}',
            [FilterValueController::class, 'update']
        )
            ->name('filter-values.update');

        Route::delete(
            'filter-values/{filterValue}',
            [FilterValueController::class, 'destroy']
        )
            ->name('filter-values.destroy');

        Route::patch(
            'filter-values/{filterValue}/toggle',
            [FilterValueController::class, 'toggle']
        )
            ->name('filter-values.toggle');

        // Products
        Route::resource('products', ProductController::class);
        Route::patch('products/{product}/toggle', [ProductController::class, 'toggle'])
            ->name('products.toggle');
        Route::delete(
            'product-images/{image}',
            [ProductController::class, 'deleteImage']
        )->name('product-images.destroy');
        Route::patch(
            'product-images/{image}/primary',
            [ProductController::class, 'setPrimaryImage']
        )->name('product-images.primary');

        Route::resource('banners', BannerController::class)->except(['show']);
        Route::patch(
            'banners/{banner}/toggle',
            [BannerController::class, 'toggle']
        )->name('banners.toggle');
        Route::resource('mood-sections', MoodSectionController::class)->except(['show']);
        Route::patch(
            'mood-sections/{moodSection}/toggle',
            [MoodSectionController::class, 'toggle']
        )->name('mood-sections.toggle');
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings/payment', [SettingController::class, 'updatePayment'])->name('settings.payment');
        Route::put('settings/whatsapp', [SettingController::class, 'updateWhatsapp'])->name('settings.whatsapp');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    });
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collections/{slug}', [CollectionController::class, 'show'])
    ->name('collection.show');
Route::get('/favourites', [CollectionController::class, 'favourites'])
     ->name('favourites');
Route::get('/products/{slug}', [FrontendProductController::class, 'show'])
     ->name('product.show');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/create-order', [CheckoutController::class, 'createOrder'])->name('checkout.create');
Route::post('/checkout/verify', [CheckoutController::class, 'verifyPayment'])->name('checkout.verify');