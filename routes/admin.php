<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DataSekolahController,
    AdminController,
    AdminUserController,
    DataGuruController,
    DataMapelController,
    DataKelasController,
    DataEkskulController,
    DataStrukturalController,
    JadwalPiketController,
    JadwalGuruController,
    DataSiswaController,
    DataKejuruanController,
    HakAksesController
};

Route::middleware(['auth', 'verified', 'log.visitor'])->group(function () {

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // ======================
        // Profil Sekolah
        // ======================
        // Route::get('/profil_sekolah', [DataSekolahController::class, 'index'])->name('profil_sekolah');
        Route::post('/profil-sekolah', [DataSekolahController::class, 'storeOrUpdate'])->name('profil_sekolah.storeOrUpdate');

        // Admin Users
        Route::resource('users', AdminUserController::class)
            ->except(['show']);
        // Import & Export Users
        Route::get('/users/export', [AdminUserController::class, 'export'])->name('users.export');
        Route::post('/users/import', [AdminUserController::class, 'import'])->name('users.import');

        // Data Guru (CRUD)
        // Route DestroyAll (Harus berada sebelum route resource agar terbaca)
        Route::delete('/guru/destroyAll', [DataGuruController::class, 'destroyAll'])->name('guru.destroyAll');
        // Route untuk import & download template
        Route::post('/guru/import', [DataGuruController::class, 'import'])
            ->name('guru.import');
        Route::get('/guru/template', [DataGuruController::class, 'template'])
            ->name('guru.template');
        Route::put('/guru/updateUser/{user}', [DataGuruController::class, 'updateUser'])
            ->name('guru.updateUser');
        Route::resource('guru', DataGuruController::class)
            ->except(['show']);

        // Data Siswa (CRUD)
        Route::delete('/siswa/destroyAll', [DataSiswaController::class, 'destroyAll'])->name('siswa.destroyAll');
        Route::post('/siswa/import', [DataSiswaController::class, 'import'])
            ->name('siswa.import');
        Route::get('/siswa/template', [DataSiswaController::class, 'template'])
            ->name('siswa.template');
        Route::get('/siswa/export', [DataSiswaController::class, 'export'])
            ->name('daftar_siswa.export');
        Route::resource('siswa', DataSiswaController::class)
            ->except(['show']);

        // Data Mapel (CRUD)
        Route::delete('/mapel/destroyAll', [DataMapelController::class, 'destroyAll'])->name('mapel.destroyAll');
        Route::post('/mapel/import', [DataMapelController::class, 'import'])
            ->name('mapel.import');
        Route::get('/mapel/template', [DataMapelController::class, 'template'])
            ->name('mapel.template');
        Route::resource('mapel', DataMapelController::class)
            ->except(['show']);

        // Data Kelas (CRUD)
        Route::delete('/kelas/destroyAll', [DataKelasController::class, 'destroyAll'])->name('kelas.destroyAll');
        Route::post('/kelas/import', [DataKelasController::class, 'import'])
            ->name('kelas.import');
        Route::get('/kelas/template', [DataKelasController::class, 'template'])
            ->name('kelas.template');
        Route::resource('kelas', DataKelasController::class)
            ->except(['show']);

        // Data Kejuruan (CRUD)
        Route::resource('kejuruan', DataKejuruanController::class)
            ->except(['show']);

        // Data Ekskul (CRUD)
        Route::delete('/ekskul/destroyAll', [DataEkskulController::class, 'destroyAll'])->name('ekskul.destroyAll');
        Route::post('/ekskul/import', [DataEkskulController::class, 'import'])
        ->name('ekskul.import');
        Route::get('/ekskul/export', [DataEkskulController::class, 'export'])
        ->name('ekskul.export');
        Route::resource('ekskul', DataEkskulController::class)
            ->except(['show']);

        // Jadwal Guru (CRUD)
        Route::delete('/jadwal_guru/destroyAll', [JadwalGuruController::class, 'destroyAll'])->name('jadwal_guru.destroyAll');
        Route::resource('jadwal_guru', JadwalGuruController::class)
            ->except(['show']);
        Route::post('/jadwal_guru/import', [JadwalGuruController::class, 'import'])
            ->name('jadwal_guru.import');
        Route::get('/jadwal_guru/export', [JadwalGuruController::class, 'export'])
            ->name('jadwal_guru.export');

        // Jadwal Piket (CRUD)
        Route::delete('/jadwal/destroyAll', [JadwalPiketController::class, 'destroyAll'])->name('jadwal.destroyAll');
        Route::resource('jadwal', JadwalPiketController::class)->except(['show']);

        // Menu lainnya
        // Route::get('/siswa', [AdminController::class, 'siswa'])->name('siswa');
        Route::get('/profil_sekolah', [AdminController::class, 'profil_sekolah'])->name('profil_sekolah');
        Route::resource('struktural', DataStrukturalController::class)
            ->except(['show']);

        Route::resource('akses', HakAksesController::class)
            ->except(['show']);
        Route::post('/akses/toggle', [HakAksesController::class, 'toggleStatus'])
            ->name('akses.toggle');


    });
});
