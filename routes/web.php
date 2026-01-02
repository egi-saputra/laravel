<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Http\Controllers\{
    HomeController,
    ProfileController,
    FotoProfilController,
    StaffController,
    // SiswaController,
    UserController,
    GoogleController,
    VisitorController,
    InformasiSekolahController,
    // StrukturalController,
    PiketController,
    JadwalMapelController,
    ProfilSekolahController,
    ListSiswaController,
    ListJadwalGuruController,
    ListPiketController,
    PengumumanController,
    ArtikelController,
    SuratController,
    JumlahJamController,
    NavBotController,
    CardJadwalGuruController,
    AnalitycsController
};

// ======================
// Homepage & Google Login
// ======================
Route::get('/', fn() => view('auth.login'))
// Route::get('/', fn() => view('errors.402'))
    ->name('home')
    ->middleware('log.visitor');
// Route::get('/', [HomeController::class, 'home'])->name('home');
// Route::get('/about', [HomeController::class, 'about'])->name('about');
// Route::get('/contact', [HomeController::class, 'contact'])->name('contact');


// Verifikasi Email
// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware(['auth'])->name('verification.notice');


Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/logo-sekolah', function () {
    $profil = \App\Models\ProfilSekolah::first();

    $filePath = $profil && $profil->file_path
        ? storage_path('app/public/' . ltrim($profil->file_path, '/'))
        : storage_path('app/public/logo_sekolah/default-logo.png'); // <- ubah path sini

    if (!File::exists($filePath)) {
        abort(404, 'Logo not found');
    }

    $lastModified = File::lastModified($filePath);
    $etag = md5_file($filePath);

    $response = Response::file($filePath, [
        'Cache-Control' => 'public, max-age=2592000, immutable',
    ]);

    $response->setLastModified(\Carbon\Carbon::createFromTimestamp($lastModified));
    $response->setEtag($etag);

    if ($response->isNotModified(request())) {
        return $response;
    }

    return $response;
});

Route::middleware(['auth', 'no.cache', 'log.visitor'])->group(function () {
    Route::get('/dev/dashboard', [DevController::class, 'index'])->name('dev.dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/guru/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});

// ======================
// Profile & Foto
// ======================
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/delete', fn() => view('profile.delete'))->name('profile.delete');
    Route::get('/profile/change-password', [ProfileController::class, 'editPassword'])->name('profile.password');

    // Foto profil
    Route::post('/upload-foto', [FotoProfilController::class, 'upload'])->name('foto.upload');
    Route::delete('/foto/remove', [FotoProfilController::class, 'remove'])->name('foto.remove');

    Route::prefix('public')->name('public.')->group(function () {

        // Profil Sekolah
        Route::resource('profil_sekolah', ProfilSekolahController::class)
        ->except(['show']);

        // Struktural Sekolah
        // Route::resource('struktural', StrukturalController::class)
        //     ->except(['show']);

        // Daftar GTK
        // Route::resource('guru', ListGuruController::class)
        //     ->except(['show']);

        // Daftar Kelas
        // Route::resource('kelas', ListKelasController::class)
        //     ->except(['show']);

        // Daftar Mapel
        Route::resource('jadwal_mapel', JadwalMapelController::class)
            ->except(['show']);

        // Daftar Ekskul
        // Route::resource('ekskul', ListEkskulController::class)
        //     ->except(['show']);

        // Daftar Program Kejuruan
        // Route::resource('kejuruan', ListKejuruanController::class)
        //     ->except(['show']);

        // Daftar Informasi Sekolah
        Route::resource('informasi_sekolah', InformasiSekolahController::class)
            ->except(['show']);

        // Daftar Informasi Sekolah
        Route::resource('jumlah_jam', JumlahJamController::class)
            ->except(['show']);

        Route::resource('daftar_siswa', ListSiswaController::class)
            ->except(['show']);
        Route::get('/daftar_siswa/search', [ListSiswaController::class, 'search'])->name('daftar_siswa.search');

        Route::resource('jadwal_guru', ListJadwalGuruController::class)
                ->except(['show']);
        // Card Jadwal Guru
        Route::resource('card_jadwal_guru', CardJadwalGuruController::class)
                ->except(['show']);

        // Jadwal Piket
        Route::resource('jadwal_piket', ListPiketController::class)
                ->except(['show']);

        // Pengumuman / Informasi
        Route::resource('pengumuman', PengumumanController::class)
                ->except(['show']);

        // Artikel
        Route::resource('artikel', ArtikelController::class)
                ->except(['show']);

        // Surat
        Route::resource('surat', SuratController::class)
                ->except(['show']);

        // Analitycs
        Route::resource('analitycs', AnalitycsController::class)
                ->except(['show']);

    });

});

// ======================
// Routes dengan Auth + Verified
// ======================
Route::middleware(['auth', 'verified'])->group(function () {
    // ----------------------
    // User
    // ----------------------
    Route::prefix('user')->name('user.')->middleware(['role:user', 'log.visitor'])->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/activities', [UserController::class, 'activities'])->name('activities');
        Route::get('/kegiatan', [UserController::class, 'kegiatan'])->name('kegiatan');
    });
});

// ======================
// Default Dashboard Redirect
// ======================
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user || empty($user->role)) {
        abort(403, 'Unauthorized');
    }

    return match($user->role) {
        'developer' => redirect()->route('dev.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        'guru'  => redirect()->route('guru.dashboard'),
        'staff' => redirect()->route('staff.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
        default => redirect()->route('user.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ======================
// Visitor (Universal)
// ======================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/visitor/{limit?}', [VisitorController::class, 'index'])->name('visitor.index');
    Route::post('/visitor/truncate', [VisitorController::class, 'truncateData'])->name('visitor.truncate');
    Route::get('/nav-bot', function () {
        return view('components.nav-bot', ['role' => auth()->user()->role]);
    })->name('nav-bot');
});

// ======================
// Auth Default
// ======================
require __DIR__.'/auth.php';

// import route terpisah
require __DIR__.'/developer.php';
require __DIR__.'/admin.php';
require __DIR__.'/guru.php';
require __DIR__.'/staff.php';
require __DIR__.'/siswa.php';
