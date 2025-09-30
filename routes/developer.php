<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dev\{
    DevController,
    UserDataController
};

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('dev')->name('dev.')->middleware('role:developer')->group(function () {

        Route::get('/dashboard', [DevController::class, 'dashboard'])->name('dashboard');

        // Admin Users
        Route::resource('users', UserDataController::class)
            ->except(['show']);
        // Import & Export Users
        Route::get('/users/export', [UserDataController::class, 'export'])->name('users.export');
        Route::post('/users/import', [UserDataController::class, 'import'])->name('users.import');

    });
});
