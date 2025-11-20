<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Page.Store.Landing');
});

Route::prefix('register')->name('register.')->group(function () {
    Route::get('/', [AuthController::class, 'registerIndex'])->name('registerIndex');
    Route::post('/submit', [AuthController::class, 'register'])->name('submit');
});

Route::prefix('login')->name('login.')->group(function () {
    Route::get('/', [AuthController::class, 'loginIndex'])->name('loginIndex');
});

Route::prefix('dashboard-admin')->name('dashboard-admin.')->group(function () {
    Route::view('/dashboard', 'Page.DashboardAdmin.Dashboard')->name('dashboard');
    Route::view('/kategori', 'Page.DashboardAdmin.Kategori')->name('kategori');
    Route::view('/produk', 'Page.DashboardAdmin.Produk')->name('produk');
    Route::view('/laporan', 'Page.DashboardAdmin.Laporan')->name('laporan');
    Route::view('/profile', 'Page.Profile.Index')->name('profile');
});

Route::prefix('dashboard-seller')->name('dashboard-seller.')->group(function () {
    Route::view('/dashboard', 'Page.DashboardSeller.Dashboard')->name('dashboard');
    Route::view('/kategori', 'Page.DashboardSeller.Kategori')->name('kategori');
    Route::view('/produk', 'Page.DashboardSeller.Produk')->name('produk');
    Route::view('/laporan', 'Page.DashboardSeller.Laporan')->name('laporan');
    Route::view('/tambah-kategori', 'Page.DashboardSeller.TambahKategori')->name('tambah-kategori');
    Route::view('/tambah-produk', 'Page.DashboardSeller.TambahProduk')->name('tambah-produk');
    Route::view('/profile', 'Page.Profile.Index')->name('profile');
});

Route::prefix('store')->name('store.')->group(function () {
    Route::view('/', 'Page.Store.Landing')->name('landing');
    Route::view('/detail/{id}', 'Page.Store.Detail')->name('detail');
});