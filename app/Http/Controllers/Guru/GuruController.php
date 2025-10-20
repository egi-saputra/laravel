<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DataKelas;
use App\Models\HakAkses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class GuruController extends Controller
{
    public function dashboard() {
        $user = Auth::user();
        $role = $user->role;

        // Hak akses walas (Tanpa Cache)
        // $isWalas = DataKelas::whereHas('waliKelas', fn($q)=>$q->where('user_id', $user->id))->exists();

        // Hak akses walas (cache 5 menit)
        $isWalas = Cache::remember("dashboard.isWalas.{$user->id}", 5, function() use ($user) {
            return DataKelas::whereHas('waliKelas', fn($q)=>$q->where('user_id', $user->id))->exists();
        });
        $hakAkses = HakAkses::whereHas('guru', fn($q)=>$q->where('user_id', $user->id))->first();
        $isActivated = $hakAkses && $hakAkses->status === 'Activated';

        // Statistik user (cache 60 menit)
        $stats = Cache::remember('dashboard.stats', 60, function() {
            return [
                'guru' => User::where('role','guru')->count(),
                'staff'=> User::where('role','staff')->count(),
                'siswa'=> User::where('role','siswa')->count(),
                'user' => User::where('role','user')->count(),
            ];
        });

        // Ambil online users dari last_activity
        $users = User::all();
        $onlineUsers = $users->filter(fn($u) => $u->last_activity && Carbon::parse($u->last_activity)->gt(now()->subMinutes(1)));

        // Statistik visitor
        $limit = request()->get('limit', 10);
        $query = User::withCount('visitors as total_visits')
            ->orderByDesc('total_visits');

        if ($limit !== 'all') {
            $query->take((int) $limit);
        }

        $visitsByUser = $query->get();

        return view('guru.dashboard', compact(
            'user','role','isWalas','isActivated','stats','onlineUsers','visitsByUser'
        ));
    }
}
