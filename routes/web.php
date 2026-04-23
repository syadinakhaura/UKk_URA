<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSiswaController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\YayasanAdminController;
use App\Http\Controllers\YayasanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard route (redirects based on role)
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->role === 'yayasan') {
            return redirect()->route('yayasan.dashboard');
        }

        return redirect()->route('siswa.dashboard');
    })->name('dashboard');

    // Siswa Routes
    Route::middleware(['can:isSiswa'])->group(function () {
        Route::get('/siswa/dashboard', [AspirasiController::class, 'index'])->name('siswa.dashboard');
        Route::post('/siswa/aspirasi', [AspirasiController::class, 'store'])->name('siswa.aspirasi.store');
        Route::get('/siswa/aspirasi/{aspirasi}', [AspirasiController::class, 'show'])->name('siswa.aspirasi.show');
    });

    // Admin Routes
    Route::middleware(['can:isAdmin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/aspirasi', [AdminController::class, 'aspirasiList'])->name('admin.aspirasi.index');
        Route::get('/admin/aspirasi/{aspirasi}', [AdminController::class, 'show'])->name('admin.aspirasi.show');
        Route::post('/admin/aspirasi/{aspirasi}/feedback', [AdminController::class, 'storeFeedback'])->name('admin.aspirasi.feedback');
        Route::patch('/admin/aspirasi/{aspirasi}/ajukan', [AdminController::class, 'submitToYayasan'])->name('admin.aspirasi.submit');
        Route::patch('/admin/aspirasi/{aspirasi}/status', [AdminController::class, 'updateStatus'])->name('admin.aspirasi.status');
        Route::get('/admin/siswa', [AdminSiswaController::class, 'index'])->name('admin.siswa.index');
        Route::post('/admin/siswa', [AdminSiswaController::class, 'store'])->name('admin.siswa.store');
    });

    // Yayasan Routes
    Route::middleware(['can:isYayasan'])->group(function () {
        Route::get('/yayasan/dashboard', [YayasanController::class, 'index'])->name('yayasan.dashboard');
        Route::get('/yayasan/aspirasi', [YayasanController::class, 'aspirasiIndex'])->name('yayasan.aspirasi.index');
        Route::get('/yayasan/aspirasi/{aspirasi}', [YayasanController::class, 'show'])->name('yayasan.aspirasi.show');
        Route::patch('/yayasan/aspirasi/{aspirasi}/validasi', [YayasanController::class, 'validateAspirasi'])->name('yayasan.aspirasi.validate');

        Route::get('/yayasan/admin', [YayasanAdminController::class, 'index'])->name('yayasan.admin.index');
        Route::post('/yayasan/admin', [YayasanAdminController::class, 'store'])->name('yayasan.admin.store');

        Route::get('/yayasan/laporan', [YayasanController::class, 'laporan'])->name('yayasan.laporan.index');
        Route::get('/yayasan/laporan/cetak', [YayasanController::class, 'cetakLaporan'])->name('yayasan.laporan.cetak');
    });
});
