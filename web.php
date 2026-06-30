<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasKuliahController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\MahasiswaPortalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// Guest Routes (Only accessible if not logged in)
Route::middleware(['guest.all', 'disable.back'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout Route (Accessible to anyone logged in)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Shared Dosen & Admin Routes - Protected
Route::middleware(['auth.dosen', 'disable.back'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Toggle Absen Session
    Route::post('/kelas-kuliah/{id}/toggle-absen', [DashboardController::class, 'toggleAbsen'])->name('kelas.toggle-absen');

    // Presensi CRUD & Report Routes
    Route::get('presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('presensi', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('presensi/{tanggal}/edit', [PresensiController::class, 'edit'])->name('presensi.edit');
    Route::put('presensi/{tanggal}', [PresensiController::class, 'update'])->name('presensi.update');
    Route::delete('presensi/{tanggal}', [PresensiController::class, 'destroy'])->name('presensi.destroy');
    Route::get('rekap-presensi', [PresensiController::class, 'rekap'])->name('presensi.rekap');
    Route::get('print-presensi', [PresensiController::class, 'print'])->name('presensi.print');
});

// Mahasiswa Portal Routes - Protected (Registered first to avoid wildcard collision with admin/mahasiswa resource)
Route::middleware(['auth.mahasiswa', 'disable.back'])->group(function () {
    Route::get('/mahasiswa/portal', [MahasiswaPortalController::class, 'index'])->name('mahasiswa.portal');
    Route::post('/mahasiswa/absen', [MahasiswaPortalController::class, 'store'])->name('mahasiswa.absen');
});

// Exclusive Admin Routes - Protected
Route::middleware(['auth.admin', 'disable.back'])->group(function () {
    // GPS Geofence Configurations
    Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');

    // Pengguna (User) CRUD
    Route::resource('pengguna', UserController::class);

    // Dosen CRUD
    Route::resource('dosen', DosenController::class);

    // Mahasiswa CRUD
    Route::resource('mahasiswa', MahasiswaController::class);

    // Kelas Kuliah CRUD
    Route::resource('kelas-kuliah', KelasKuliahController::class);
});
