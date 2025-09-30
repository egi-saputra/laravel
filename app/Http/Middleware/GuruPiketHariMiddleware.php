<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataGuru;
use App\Models\JadwalPiket; // tabel jadwal piket
use Carbon\Carbon;

class GuruPiketHariMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // hanya guru yang boleh akses
        if ($user->role !== 'guru') {
            abort(403, 'Hanya guru yang bisa mengakses halaman ini.');
        }

        // ambil data guru berdasarkan user login
        $guru = DataGuru::where('user_id', $user->id)->first();
        if (!$guru) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        // nama hari ini (Senin, Selasa, dst)
        $hariIni = Carbon::now('Asia/Jakarta')->locale('id')->dayName;

        // cek apakah guru ini ada di jadwal piket hari ini
        $isPetugasHariIni = JadwalPiket::where('hari', $hariIni)
            ->where('petugas', $guru->id)
            ->exists();

        if (!$isPetugasHariIni) {
            abort(403, "Anda tidak bertugas piket hari {$hariIni}.");
        }

        return $next($request);
    }
}
