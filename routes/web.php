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
use App\Http\Controllers\Admin\PageEditorController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SectionContentController;
use App\Http\Controllers\Admin\SectionStyleController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'show'])->defaults('slug', 'about')->name('about');
Route::get('/industries', [PageController::class, 'show'])->defaults('slug', 'industries')->name('industries');
Route::get('/infrastructure', [PageController::class, 'show'])->defaults('slug', 'infrastructure')->name('infrastructure');
Route::get('/contact', [PageController::class, 'show'])->defaults('slug', 'contact')->name('contact');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{category}', [ProductController::class, 'byCategory'])->name('products.category');
Route::get('/products/{category}/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/insights', [BlogController::class, 'index'])->name('insights.index');
Route::get('/insights/{slug}', [BlogController::class, 'show'])->name('insights.show');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit')->middleware('throttle:5,1');
Route::post('/enquiry', [EnquiryController::class, 'submit'])->name('enquiry.submit');

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'form'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'submit'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('industries', \App\Http\Controllers\Admin\IndustryController::class);
    
    Route::resource('enquiries', AdminEnquiryController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::patch('enquiries/{id}/status', [AdminEnquiryController::class, 'status'])->name('enquiries.status');
    
    Route::resource('posts', AdminPostController::class);
    
    Route::resource('hero-slides', \App\Http\Controllers\Admin\HeroSlideController::class)
         ->only(['index','store','update','destroy']);
    Route::post('hero-slides/reorder', [\App\Http\Controllers\Admin\HeroSlideController::class,'reorder'])
         ->name('hero-slides.reorder');
    Route::post('hero-slides/upload', [\App\Http\Controllers\Admin\HeroSlideController::class,'upload'])
         ->name('hero-slides.upload');
    
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // CMS Visual Editor (PHASE 5) - UI and Generic Actions
    Route::name('editor.')->prefix('editor')->group(function () {
        Route::get('/',           [PageEditorController::class, 'index'])->name('index');
        Route::get('/{slug}',     [PageEditorController::class, 'page'])->name('page');
        Route::get('/{slug}/preview', [PageEditorController::class, 'preview'])->name('preview');
        Route::post('/{slug}/save',    [PageEditorController::class, 'save'])->name('save');
        Route::post('/publish/{slug}', [PageEditorController::class, 'publish'])->name('publish');
        Route::post('/upload-image',   [PageEditorController::class, 'uploadImage'])->name('upload-image');
    });

    // CMS Page Management (PHASE 5)
    Route::resource('pages', AdminPageController::class);
});
