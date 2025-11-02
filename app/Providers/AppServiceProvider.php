<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Visitor;
use App\Models\ProfilSekolah;
use App\Models\DataSiswa;
use App\Observers\UserObserver;
use App\Observers\DataSiswaObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /**
         * 🔹 Daftarkan observer
         */
        User::observe(UserObserver::class);
        DataSiswa::observe(DataSiswaObserver::class);

        /**
         * 🔹 View Composer hanya untuk halaman tertentu
         * (hindari semua view dengan wildcard *)
         */
        View::composer([
            'dashboard.*',
            'admin.*',
            'guru.*',
            'staff.*',
            'siswa.*',
            'user.*',
        ], function ($view) {
            $profil = Cache::remember('profil_sekolah', now()->addMinutes(5), fn() => ProfilSekolah::first());
            $currentUser = auth()->user();

            if (! $currentUser) {
                // Jika belum login, kirim data minimal
                $view->with(['profil' => $profil]);
                return;
            }

            /**
             * Role visibility
             */
            $roleVisibility = [
                'developer' => ['developer','admin','guru','staff','siswa','user'],
                'admin'     => ['admin','guru','staff','siswa','user'],
                'guru'      => ['guru','staff','siswa','user'],
                'staff'     => ['guru','staff','siswa','user'],
                'siswa'     => ['guru','siswa','user'],
                'user'      => ['guru','staff','siswa','user'],
            ];

            $allowedRoles = $roleVisibility[$currentUser->role] ?? [$currentUser->role];

            /**
             * 🔹 Cache data berat agar tidak hit database tiap reload
             */
            $cacheKey = 'dashboard_data_' . $currentUser->id;
            $data = Cache::remember($cacheKey, now()->addSeconds(60), function () use ($allowedRoles, $currentUser) {
                $users = User::whereIn('role', $allowedRoles)->get();

                // Hitung online users
                $onlineUsers = $users->filter(function ($user) {
                    return $user->last_activity &&
                        Carbon::parse($user->last_activity)->gt(now()->subMinutes(1));
                });

                // Role-based limit (admin hanya lihat 5 per kategori)
                $limit = $currentUser->role === 'admin' ? 5 : null;

                $grouped = [
                    'onlineGuru'  => $onlineUsers->where('role', 'guru')->take($limit),
                    'onlineStaff' => $onlineUsers->where('role', 'staff')->take($limit),
                    'onlineSiswa' => $onlineUsers->where('role', 'siswa')->take($limit),
                    'onlineUser'  => $onlineUsers->where('role', 'user')->take($limit),
                ];

                // Statistik
                $counts = [
                    'adminCount' => in_array('admin', $allowedRoles) ? User::where('role','admin')->count() : 0,
                    'guruCount'  => in_array('guru', $allowedRoles) ? User::where('role','guru')->count() : 0,
                    'staffCount' => in_array('staff', $allowedRoles) ? User::where('role','staff')->count() : 0,
                    'siswaCount' => in_array('siswa', $allowedRoles) ? User::where('role','siswa')->count() : 0,
                    'userCount'  => in_array('user', $allowedRoles) ? User::where('role','user')->count() : 0,
                ];

                $userIds = User::whereIn('role',$allowedRoles)->pluck('id');
                $visitors = [
                    'totalVisitors'  => Visitor::whereIn('user_id', $userIds)->count(),
                    'uniqueVisitors' => Visitor::whereIn('user_id', $userIds)
                        ->distinct('user_id')
                        ->count('user_id'),
                ];

                $limitVisitors = request()->get('limit', 10);
                $visitsByUser = User::withCount('visitors as total_visits')
                    ->whereIn('role', $allowedRoles)
                    ->orderByDesc('total_visits')
                    ->when($limitVisitors !== 'all', fn($q) => $q->take((int) $limitVisitors))
                    ->get();

                return array_merge(
                    compact('users', 'onlineUsers', 'grouped', 'counts', 'visitors', 'visitsByUser'),
                    ['totalUsers' => $users->count()]
                );
            });

            /**
             * 🔹 Page title dari route name
             */
            $routes = [
                'admin' => [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard Admin'],
                    ['route' => 'admin.user.index', 'label' => 'Kelola Data Pengguna'],
                    ['route' => 'admin.profil_sekolah', 'label' => 'Profil Sekolah'],
                ],
                'guru' => [
                    ['route' => 'guru.dashboard', 'label' => 'Dashboard Guru'],
                    ['route' => 'guru.jadwal_piket.index', 'label' => 'Jadwal Guru Piket'],
                ],
                'siswa' => [
                    ['route' => 'siswa.dashboard', 'label' => 'Dashboard Siswa'],
                ],
            ];

            $currentRoute = request()->route()?->getName();
            $pageTitle = collect($routes)->flatten(1)
                ->firstWhere('route', $currentRoute)['label'] ?? null;

            /**
             * 🔹 Kirim ke view
             */
            $view->with([
                'profil'         => $profil,
                'users'          => $data['users'],
                'onlineUsers'    => $data['onlineUsers'],
                'onlineGuru'     => $data['grouped']['onlineGuru'],
                'onlineStaff'    => $data['grouped']['onlineStaff'],
                'onlineSiswa'    => $data['grouped']['onlineSiswa'],
                'onlineUser'     => $data['grouped']['onlineUser'],
                'adminCount'     => $data['counts']['adminCount'],
                'guruCount'      => $data['counts']['guruCount'],
                'staffCount'     => $data['counts']['staffCount'],
                'siswaCount'     => $data['counts']['siswaCount'],
                'userCount'      => $data['counts']['userCount'],
                'totalUsers'     => $data['totalUsers'],
                'visitsByUser'   => $data['visitsByUser'],
                'totalVisitors'  => $data['visitors']['totalVisitors'],
                'uniqueVisitors' => $data['visitors']['uniqueVisitors'],
                'routes'         => $routes,
                'pageTitle'      => $pageTitle,
            ]);
        });
    }
}
