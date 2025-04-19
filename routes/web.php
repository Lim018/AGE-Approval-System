<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Pengajuan Routes
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    
    // Approval Routes
    Route::post('/pengajuan/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
    Route::post('/pengajuan/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('pengajuan.reject');
    
    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

Route::middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
});

// Rute khusus untuk atasan
Route::middleware(['auth', 'role:atasan'])->group(function () {
    // Route untuk approval oleh atasan
});

// Rute khusus untuk kepala departemen
Route::middleware(['auth', 'role:kepalaDepartemen'])->group(function () {
    // Route untuk approval oleh kepala departemen  
});

// Rute khusus untuk HRD
Route::middleware(['auth', 'role:hrd'])->group(function () {
    // Route untuk approval oleh HRD
});