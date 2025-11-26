<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\SellerCategoryController;
use App\Http\Controllers\AdminCategoryController;

Route::get('/', function () {
    return view('Page.Store.Landing');
});

// File upload route (for Dropzone)
Route::post('/upload/temp', [FileUploadController::class, 'uploadTemp'])->name('upload.temp');

Route::prefix('register')->name('register.')->group(function () {
    Route::get('/', [AuthController::class, 'registerIndex'])->name('registerIndex');
    Route::post('/submit', [AuthController::class, 'register'])->name('submit');
});

Route::prefix('login')->name('login.')->group(function () {
    Route::get('/', [AuthController::class, 'loginIndex'])->name('loginIndex');
    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('admin')->prefix('admin')->name('dashboard-admin.')->group(function () {
    Route::view('/dashboard', 'Page.DashboardAdmin.Dashboard')->name('dashboard');
    Route::get('/kategori', [AdminCategoryController::class, 'index'])->name('kategori');
    Route::post('/kategori/create', [AdminCategoryController::class, 'store'])->name('kategori.create');
    Route::get('/tambah-kategori', [AdminCategoryController::class, 'createView'])->name('tambah-kategori');
    Route::get('/produk', [AdminController::class, 'products'])->name('produk');
    Route::view('/laporan', 'Page.DashboardAdmin.Laporan')->name('laporan');
    Route::view('/profile', 'Page.Profile.Index')->name('profile');
    Route::get('/pending-sellers', [AdminController::class, 'pendingSellers'])->name('pending-sellers');
    Route::post('/sellers/{sellerId}/approve', [AdminController::class, 'approveSeller'])->name('sellers.approve');
    Route::post('/sellers/{sellerId}/reject', [AdminController::class, 'rejectSeller'])->name('sellers.reject');
});

Route::prefix('seller')->name('dashboard-seller.')->group(function () {
    Route::view('/dashboard', 'Page.DashboardSeller.Dashboard')->name('dashboard');
    Route::get('/kategori', [SellerProductController::class, 'categories'])->name('kategori');
    Route::get('/produk', [SellerProductController::class, 'index'])->name('produk');
    Route::view('/laporan', 'Page.DashboardSeller.Laporan')->name('laporan');
    Route::get('/tambah-produk', [SellerProductController::class, 'createView'])->name('tambah-produk');
    Route::post('/produk/create', [SellerProductController::class, 'store'])->name('produk.create');
    Route::view('/profile', 'Page.Profile.Index')->name('profile');
});

// API endpoints for wilayah (used by registration dropdowns)
Route::prefix('api')->group(function () {
    Route::get('provinces', [WilayahController::class, 'provinces']);
    Route::get('regencies/{province}', [WilayahController::class, 'regencies']);
    Route::get('districts/{regency}', [WilayahController::class, 'districts']);
    Route::get('villages/{district}', [WilayahController::class, 'villages']);
});


Route::prefix('store')->name('store.')->group(function () {
    Route::view('/', 'Page.Store.Landing')->name('landing');
    Route::view('/detail/{id}', 'Page.Store.Detail')->name('detail');
});
