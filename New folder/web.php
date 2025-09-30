<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    FotoProfilController,
    AdminDashboardController,
    AdminUserController,
    UserDashboardController,
    UserKegiatanController,
    GoogleController,
    AdminController,
    UserController,
    GuruController,
    StaffController
};

// Homepage
Route::get('/', fn() => view('auth.login'))->name('home');

// Verifikasi Email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

// Google Login
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    // Halaman edit profil (nama, email)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Update data profil
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Hapus akun
    Route::get('/profile/delete', function () {
        return view('profile.delete');
    })->middleware('auth')->name('profile.delete');


    // Halaman terpisah untuk ganti password
    Route::get('/profile/change-password', [ProfileController::class, 'editPassword'])->name('profile.password');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // -----------------
    // Admin Routes
    // -----------------
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

        // Resource admin users
        Route::resource('users', AdminUserController::class)->only(['index', 'update', 'destroy'])->names([
            'index' => 'users.index',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);
    });

    // -----------------
    // Guru Routes
    // -----------------
    Route::prefix('guru')->name('guru.')->middleware('role:guru')->group(function () {
        Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
        Route::get('/scores', [GuruController::class, 'scores'])->name('scores');
    });

    // -----------------
    // Staff Routes
    // -----------------
    Route::prefix('staff')->name('staff.')->middleware('role:staff')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
        Route::get('/absensi', [StaffController::class, 'absensi'])->name('attendance');
    });

    // -----------------
    // User Routes
    // -----------------
    Route::prefix('user')->name('user.')->middleware('role:user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/activities', [UserController::class, 'activities'])->name('activities');
        Route::get('/kegiatan', [UserController::class, 'kegiatan'])->name('kegiatan');
    });

    // -----------------
    // Profile Routes
    // -----------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // -----------------
    // Upload Foto Profil
    // -----------------
    // Route::post('/upload-foto', [FotoProfilController::class, 'upload'])->name('foto.upload');
    Route::post('/upload-foto', [FotoProfilController::class, 'upload'])->name('foto.upload');
    Route::delete('/foto/remove', [FotoProfilController::class, 'remove'])->name('foto.remove');
});

// Default dashboard route
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('guru')) {
        return redirect()->route('guru.dashboard');
    } elseif ($user->hasRole('staff')) {
        return redirect()->route('staff.dashboard');
    } else {
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');


// Auth default route
require __DIR__.'/auth.php';
