<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Siswa\{
    SiswaController,
    TugasSiswaController,
    ListMateriController,
    PresensiSiswaController
};

Route::middleware(['auth', 'verified', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
        Route::resource('data_diri', SiswaController::class)
        ->except(['show'])->names([
            'index' => 'data_diri',
            'create' => 'data_diri.create',
            'store' => 'data_diri.store',
            'edit' => 'data_diri.edit',
            'update' => 'data_diri.update',
            'destroy' => 'data_diri.destroy',
        ]);

        Route::post('data_diri/sync', [SiswaController::class, 'sync'])
            ->name('data_diri.sync');

        // Materi
        Route::resource('materi', ListMateriController::class)->except(['show']);

        // Tugas Siswa
        Route::resource('tugas', TugasSiswaController::class)->except(['show']);
        Route::put('/tugas/{id}', [TugasSiswaController::class, 'update'])->name('tugas.update');
        Route::get('tugas/download/{id}', [TugasSiswaController::class, 'download'])->name('tugas.download');

        // Presensi Siswa
        Route::get('/presensi', [PresensiSiswaController::class, 'index'])
            ->name('presensi.index');

        Route::post('/presensi', [PresensiSiswaController::class, 'store'])
            ->name('presensi.store');

        // Tandai presensi hari ini selesai
        Route::post('/presensi/selesai', [PresensiSiswaController::class, 'selesai'])
            ->name('presensi.selesai');

    });
