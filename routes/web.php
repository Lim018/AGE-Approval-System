<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to login if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    
    // IMPORTANT: Create route must come BEFORE the show route with wildcard parameter
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])
        ->middleware('role:pegawai')
        ->name('pengajuan.create');
    
    // Common Pengajuan Routes
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])
        ->middleware('role:pegawai')
        ->name('pengajuan.store');
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    
    // Approval routes
    Route::post('/pengajuan/{pengajuan}/approve', [PengajuanController::class, 'approve'])
        ->middleware('role:atasan,kepalaDepartemen,hrd')
        ->name('pengajuan.approve');
    Route::post('/pengajuan/{pengajuan}/reject', [PengajuanController::class, 'reject'])
        ->middleware('role:atasan,kepalaDepartemen,hrd')
        ->name('pengajuan.reject');
});