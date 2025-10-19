<?php

use App\Http\Controllers\Inertia\Guru\{
    InertiaMateriController
};

Route::middleware(['auth', 'verified', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

});
