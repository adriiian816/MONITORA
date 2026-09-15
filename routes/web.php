<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OpdDashboardController;
use App\Http\Controllers\OpdSubmissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Mengarahkan halaman utama (/) langsung ke halaman Login jika belum autentikasi
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Redirect otomatis sesuai Role setelah Login
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('opd.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group Route khusus Admin / PIC Bappeda
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // PERBAIKAN: Dihapus prefix /admin di depannya karena sudah otomatis dari group prefix('admin')
    Route::patch('/submissions/{id}/status', [AdminDashboardController::class, 'updateStatus'])->name('submissions.update-status');

    // Update Password OPD oleh Admin
    Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])
        ->name('users.update-password');

    // Riwayat Login OPD (Pindah ke Grup Admin)
    Route::get('/login-histories', [UserController::class, 'loginHistories'])
        ->name('login-histories');

    // CRUD & Monitoring Tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Ekspor Rekapitulasi Tasks
    Route::get('/tasks/{task}/export-excel', [TaskController::class, 'exportExcel'])->name('tasks.export.excel');
    Route::get('/tasks/{task}/export-csv', [TaskController::class, 'exportCsv'])->name('tasks.export.csv');

    // Manajemen Akun OPD oleh Admin (CRUD)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Verifikasi Status Laporan OPD (ACC / Revisi)
    Route::post('/submissions/{submission}/verify', [TaskController::class, 'verifySubmission'])->name('submissions.verify');
});

// Group Route khusus OPD
Route::middleware(['auth', 'role:opd'])->prefix('opd')->name('opd.')->group(function () {
    Route::get('/dashboard', [OpdDashboardController::class, 'index'])->name('dashboard');

    Route::get('/tasks', [OpdSubmissionController::class, 'index'])->name('tasks.index');

    // Submissions
    Route::get('/submissions', [OpdSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}/edit', [OpdSubmissionController::class, 'edit'])->name('submissions.edit');
    Route::put('/submissions/{submission}', [OpdSubmissionController::class, 'update'])->name('submissions.update');

    // Submit/Revisi Berkas Laporan OPD
    Route::post('/tasks/{task}/submit', [OpdSubmissionController::class, 'store'])->name('submissions.store');
});

// Route Profile User & Utility (Dapat diakses Admin & OPD)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Stream Preview PDF In-App
    Route::get('/submissions/{submission}/preview', [TaskController::class, 'previewPdf'])->name('submissions.preview');
});

require __DIR__ . '/auth.php';