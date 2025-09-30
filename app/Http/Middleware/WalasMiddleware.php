<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataKelas;

class WalasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Ambil relasi guru
        $guru = $user->guru;
        if (!$guru) {
            abort(403, 'Hanya wali kelas yang bisa mengakses halaman ini.');
        }

        // Cek apakah guru ini wali kelas
        $isWalas = DataKelas::where('walas_id', $guru->id)->exists();
        if (!$isWalas) {
            abort(403, 'Hanya wali kelas yang bisa mengakses halaman ini.');
        }

        return $next($request);
    }
}
