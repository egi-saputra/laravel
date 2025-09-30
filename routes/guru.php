<?php

use App\Http\Controllers\Guru\{
    GuruController,
    PresensiController,
    WalasController,
    PiketController,
    MateriController,
    TugasSiswaController,
    RekapAbsenSiswaController
};

// Semua route guru, pakai auth, verified dan role guru
Route::middleware(['auth', 'verified', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');

        // Walas (CRUD)
        Route::delete('/walas/destroyAll', [WalasController::class, 'destroyAll'])->name('walas.destroyAll');
        Route::post('/walas/import', [WalasController::class, 'import'])->name('walas.import');
        Route::get('/walas/template', [WalasController::class, 'template'])->name('walas.template');
        Route::resource('walas', WalasController::class)->except(['show']);

        // Halaman Piket
        Route::get('/halaman-piket', [PresensiController::class, 'index'])->middleware('guru.presensi.hari')->name('presensi.hari');

        // Halaman presensi (guru & staff sekaligus)
        Route::get('/presensi', [PresensiController::class, 'index'])
            ->name('presensi.index')
            ->middleware('auth', 'role:guru');

        Route::post('/presensi/guru', [PresensiController::class, 'storeGuru'])
            ->name('presensi.store')
            ->middleware('auth', 'role:guru');

        Route::post('/presensi/staff', [PresensiController::class, 'storeStaff'])
            ->name('presensi.staff.store')
            ->middleware('auth', 'role:guru');

        Route::post('/presensi/selesai', [PresensiController::class, 'selesaiPresensi'])->name('presensi.selesai');

        // Halaman Kelas
        Route::get('/ruang_walas', [WalasController::class, 'index'])
        ->middleware(['auth', 'walas'])
        ->name('walas.index');

        // Halaman utama rekap presensi siswa
        Route::get('absensi-kelas', [RekapAbsenSiswaController::class, 'index'])
            ->name('absensi_kelas.index');

        // Generate/filter berdasarkan kelas dan periode
        Route::post('absensi-kelas/generate', [RekapAbsenSiswaController::class, 'generate'])
            ->name('absensi_kelas.generate');

        // Backup data presensi siswa
        Route::post('absensi-kelas/backup', [RekapAbsenSiswaController::class, 'backup'])
            ->name('absensi_kelas.backup');

        // Hapus data presensi siswa sesuai filter
        Route::delete('absensi-kelas/clear', [RekapAbsenSiswaController::class, 'clear'])
            ->name('absensi_kelas.clear');

        // Restore data presensi siswa dari backup JSON
        Route::post('absensi-kelas/restore', [RekapAbsenSiswaController::class, 'restore'])
            ->name('absensi_kelas.restore');

        Route::post('/absensi/export-excel', [RekapAbsenSiswaController::class, 'exportExcel'])
            ->name('absensi_kelas.export_excel');

        // Jadwal Piket
        Route::resource('jadwal_piket', PiketController::class)
                ->except(['show']);

        Route::delete('materi/destroyAll', [MateriController::class, 'destroyAll'])->name('materi.destroyAll');
        Route::get('/{id}/view_file_materi', [MateriController::class, 'view_file_materi'])->name('view_file_materi');
        Route::resource('materi', MateriController::class)
            ->except(['show']);

        // Hapus semua tugas siswa
        Route::delete('tugas_siswa/destroyAll', [TugasSiswaController::class, 'destroyAll'])
            ->name('tugas_siswa.destroyAll');

        // Preview / lihat file tugas siswa
        Route::get('/{id}/view_file_tugas', [TugasSiswaController::class, 'view_file_tugas'])
            ->name('view_file_tugas');

        // Resource route untuk CRUD
        Route::resource('tugas_siswa', TugasSiswaController::class)
            ->except(['show']);

    });
