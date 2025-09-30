<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ProfileController, FotoProfilController};

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/delete', fn() => view('profile.delete'))->name('profile.delete');
    Route::get('/profile/change-password', [ProfileController::class, 'editPassword'])->name('profile.password');

    Route::post('/upload-foto', [FotoProfilController::class, 'upload'])->name('foto.upload');
    Route::delete('/foto/remove', [FotoProfilController::class, 'remove'])->name('foto.remove');
});
