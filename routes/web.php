<?php

use App\Http\Controllers\Atasan\ApprovalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backoffice\DokumenController;
use App\Http\Controllers\Marketing\PengajuanController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Redirect root berdasarkan role
    Route::get('/', function () {
        $user = auth()->user();

        return match ($user->role->value) {
            'marketing' => redirect()->route('marketing.pengajuan.index'),
            'atasan_marketing' => redirect()->route('atasan.approval.index'),
            'admin_backoffice' => redirect()->route('backoffice.dokumen.index'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('login'),
        };
    })->name('home');

    // === Marketing ===
    Route::prefix('marketing')->name('marketing.')->middleware('role:marketing')->group(function (): void {
        Route::resource('pengajuan', PengajuanController::class)->except(['destroy']);
        Route::post('pengajuan/{pengajuan}/submit', [PengajuanController::class, 'submit'])->name('pengajuan.submit');
    });

    // === Atasan Marketing ===
    Route::prefix('atasan')->name('atasan.')->middleware('role:atasan_marketing')->group(function (): void {
        Route::get('approval', [ApprovalController::class, 'index'])->name('approval.index');
        Route::get('approval/{pengajuan}', [ApprovalController::class, 'show'])->name('approval.show');
        Route::post('approval/{pengajuan}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('approval/{pengajuan}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
    });

    // === Admin Backoffice ===
    Route::prefix('backoffice')->name('backoffice.')->middleware('role:admin_backoffice')->group(function (): void {
        Route::get('dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
        Route::get('dokumen/{pengajuan}', [DokumenController::class, 'show'])->name('dokumen.show');
        Route::post('dokumen/{pengajuan}/generate', [DokumenController::class, 'generate'])->name('dokumen.generate');
    });

    // === Admin (opsional, monitoring) ===
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function (): void {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });

    // === Notifikasi (API, JSON) ===
    Route::prefix('api')->group(function (): void {
        Route::get('notifikasi', [NotifikasiController::class, 'index'])->name('api.notifikasi.index');
        Route::post('notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markAsRead'])->name('api.notifikasi.read');
        Route::post('notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('api.notifikasi.readAll');
    });
});
