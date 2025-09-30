<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit');
        $currentRole = Auth::user()->role ?? null;

        // Statistik jumlah user per role
        $adminCount     = User::where('role', 'admin')->count();
        $developerCount = User::where('role', 'developer')->count();
        $guruCount      = User::where('role', 'guru')->count();
        $staffCount     = User::where('role', 'staff')->count();
        $siswaCount     = User::where('role', 'siswa')->count();
        $userCount      = User::where('role', 'user')->count();

        $totalUsers = $guruCount + $staffCount + $siswaCount + $userCount;

        // Tentukan user_ids untuk statistik global
        if ($currentRole === 'admin') {
            $userIds = User::pluck('id');
        } else {
            $userIds = User::whereNotIn('role', ['admin', 'developer'])->pluck('id');
        }

        // Statistik visitor global
        $totalVisitors = Visitor::whereIn('user_id', $userIds)->count();
        $uniqueVisitors = Visitor::whereIn('user_id', $userIds)
            ->distinct('user_id')
            ->count('user_id');

        // Query visits per user
        $query = User::leftJoin('visitors', 'visitors.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.role', DB::raw('COUNT(visitors.id) AS total_visits'))
            ->groupBy('users.id', 'users.name', 'users.role')
            ->orderByDesc('total_visits');

        if ($currentRole !== 'admin') {
            $query->whereNotIn('users.role', ['admin', 'developer']);
        }

        if (!empty($limit) && is_numeric($limit)) {
            $query->limit((int)$limit);   // pakai limit, bukan take
        }

        $visitsByUser = $query->get();

        // Mapping role → view
        $views = [
            'admin'     => 'admin.dashboard',
            'guru'      => 'guru.dashboard',
            'siswa'     => 'siswa.dashboard',
            'staff'     => 'staff.dashboard',
            'user'      => 'user.dashboard',
            'developer' => 'developer.dashboard',
        ];

        // Default fallback kalau role tidak ada di mapping
        $view = $views[$currentRole] ?? 'dashboard';

        return view($view, compact(
            'adminCount','developerCount','guruCount','staffCount','siswaCount','userCount',
            'totalUsers','visitsByUser','uniqueVisitors','totalVisitors'
        ));
    }

    public function truncateData()
    {
        DB::table('visitors')->truncate();
        return redirect()->back()->with('success', 'Semua data visitor berhasil dihapus!');
    }
}
