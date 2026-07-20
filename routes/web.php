<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\MejaController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\MenuController as CustomerMenuController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\ReservasiController as CustomerReservasiController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\LaporanController;
use Illuminate\Support\Facades\Route;

// ===================== PUBLIC / HOME =====================
Route::get('/', [CustomerDashboardController::class, 'index'])->name('home');

// ===================== AUTHENTICATION =====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ===================== CUSTOMER ROUTES =====================
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [CustomerMenuController::class, 'index'])->name('menu');
    Route::get('/reservasi/dinein', [CustomerReservasiController::class, 'createDineIn'])->name('reservasi.dinein');
    Route::get('/reservasi/pickup', [CustomerReservasiController::class, 'createPickUp'])->name('pickup');
    Route::post('/reservasi', [CustomerReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/riwayat', [CustomerReservasiController::class, 'riwayat'])->name('riwayat');

    // Payment routes (routes spesifik harus SEBELUM route wildcard {reservasi})
    Route::get('/payment/finish', [CustomerPaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/unfinish', [CustomerPaymentController::class, 'unfinish'])->name('payment.unfinish');
    Route::get('/payment/error', [CustomerPaymentController::class, 'error'])->name('payment.error');
    Route::get('/payment/{reservasi}', [CustomerPaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{reservasi}/process', [CustomerPaymentController::class, 'process'])->name('payment.process');
});

// Webhook Midtrans (tanpa middleware CSRF)
Route::post('/payment/notification', [CustomerPaymentController::class, 'notification'])->name('payment.notification')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ===================== ADMIN ROUTES =====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('meja', MejaController::class)->except(['show']);
    Route::get('/meja/{meja}/toggle', [MejaController::class, 'toggleStatus'])->name('meja.toggle');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::resource('menu', AdminMenuController::class)->except(['show']);
    Route::get('/menu/{menu}/toggle', [AdminMenuController::class, 'toggleStok'])->name('menu.toggle');
    Route::get('/reservasi', [AdminReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/{reservasi}', [AdminReservasiController::class, 'show'])->name('reservasi.show');
    Route::post('/reservasi/{reservasi}/terima', [AdminReservasiController::class, 'terima'])->name('reservasi.terima');
    Route::post('/reservasi/{reservasi}/tolak', [AdminReservasiController::class, 'tolak'])->name('reservasi.tolak');
    Route::post('/reservasi/{reservasi}/selesai', [AdminReservasiController::class, 'selesai'])->name('reservasi.selesai');
    Route::get('/kasir', [KasirController::class, 'create'])->name('kasir.create');
    Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
});

// ===================== OWNER ROUTES =====================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/export/csv', [LaporanController::class, 'exportCsv'])->name('laporan.export.csv');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
});
