<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\{
    StaffController,
    RekapHonorGuruController,
    RekapHonorStaffController,
    RekapKeuanganController,
    RiwayatPresensiController
};

Route::middleware(['auth', 'verified', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');

        // route tambahan
        // Backup → GET karena cuma ambil file
        Route::get('/riwayat_presensi/backup', [RiwayatPresensiController::class, 'backup'])
            ->name('riwayat_presensi.backup');
        Route::post('/riwayat_presensi/restore', [RiwayatPresensiController::class, 'restore'])
            ->name('riwayat_presensi.restore');

        // Clear → POST biar gampang dari form Blade
        Route::delete('/riwayat_presensi/clear', [RiwayatPresensiController::class, 'clear'])
            ->name('riwayat_presensi.clear');

        // Riwayat Presensi
        Route::resource('riwayat_presensi', RiwayatPresensiController::class)->except(['show']);
        // routes/web.php
        Route::match(['get','post'], '/riwayat_presensi/generate', [RiwayatPresensiController::class, 'generate'])
            ->name('riwayat_presensi.generate');

        // Rekap Honor Guru
        Route::resource('rekap_honor_guru', RekapHonorGuruController::class)->except(['show']);
        Route::post('/rekap-honor-guru', [RekapHonorGuruController::class, 'generate'])->name('rekap_honor_guru.generate');

        // Rekap Honor Staff / Karyawan
        Route::resource('rekap_honor_staff', RekapHonorStaffController::class)->except(['show']);
        Route::post('/rekap-honor-staff', [RekapHonorStaffController::class, 'generate'])->name('rekap_honor_staff.generate');

        // Rekap Keuangan
        Route::resource('rekap_keuangan', RekapKeuanganController::class)->except(['show']);

    });
