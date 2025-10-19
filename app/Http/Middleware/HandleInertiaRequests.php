<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    /**
     * Nama root view (blade) yang digunakan oleh Inertia.
     */
    public function rootView(Request $request): ?string
    {
        return 'inertia'; // gunakan layout inertia.blade.php
    }

    /**
     * Data yang akan dibagikan ke semua halaman Inertia.
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],
        ]);
    }
}
