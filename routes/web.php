<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\EnquiryController as AdminEnquiryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{category}', [ProductController::class, 'byCategory'])->name('products.category');
Route::get('/products/{category}/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/industries', [PageController::class, 'industries'])->name('industries');
Route::get('/infrastructure', [PageController::class, 'infrastructure'])->name('infrastructure');
Route::get('/insights', [BlogController::class, 'index'])->name('insights.index');
Route::get('/insights/{slug}', [BlogController::class, 'show'])->name('insights.show');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::post('/enquiry', [EnquiryController::class, 'submit'])->name('enquiry.submit');

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'form'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'submit'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    
    Route::resource('enquiries', AdminEnquiryController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::patch('enquiries/{id}/status', [AdminEnquiryController::class, 'status'])->name('enquiries.status');
    
    Route::resource('posts', AdminPostController::class);
    
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
