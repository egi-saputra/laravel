<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Visitor;
use App\Models\ProfilSekolah;
use Carbon\Carbon;
use App\Observers\UserObserver;
use App\Observers\DataSiswaObserver;
use App\Models\DataSiswa;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 🔹 Root view untuk Inertia
        // Inertia::setRootView('inertia');

        // 🔹 Global share untuk Inertia
        // Inertia::share([
        //     'auth' => fn () => [
        //         'user' => auth()->user(),
        //     ],
        //     'app' => fn () => [
        //         'profil' => \App\Models\ProfilSekolah::first(),
        //     ],
        // ]);

        // 🔹 Observers
        User::observe(UserObserver::class);
        DataSiswa::observe(DataSiswaObserver::class);

        // 🔹 View composer tetap jalan untuk Blade
        View::composer('*', function ($view) {
            $profil = ProfilSekolah::first();
            $currentUser = auth()->user();

            /**
             * Role Visibility Mapping
             */
            $roleVisibility = [
                'developer' => ['developer','admin','guru','staff','siswa','user'],
                'admin'     => ['admin','guru','staff','siswa','user'],
                'guru'      => ['guru','staff','siswa','user'],
                'staff'     => ['guru','staff','siswa','user'],
                'siswa'     => ['guru','siswa','user'],
                'user'      => ['guru','staff','siswa','user'],
            ];

            $allowedRoles = $currentUser
                ? ($roleVisibility[$currentUser->role] ?? [$currentUser->role])
                : [];

            /**
             * Users & Online Status
             */
            $users = User::whereIn('role', $allowedRoles)->get();

            $onlineUsers = $users->map(function ($user) {
                $user->is_online = $user->last_activity
                    && Carbon::parse($user->last_activity)->gt(now()->subMinutes(1));
                return $user;
            })->filter(fn($u) => $u->is_online);

            $onlineGuru  = $onlineUsers->where('role','guru');
            $onlineStaff = $onlineUsers->where('role','staff');
            $onlineSiswa = $onlineUsers->where('role','siswa');
            $onlineUser  = $onlineUsers->where('role','user');

            // Admin hanya lihat max 5 online per kategori
            if ($currentUser && $currentUser->role === 'admin') {
                $onlineGuru  = $onlineGuru->take(5);
                $onlineStaff = $onlineStaff->take(5);
                $onlineSiswa = $onlineSiswa->take(5);
                $onlineUser  = $onlineUser->take(5);
            }

            /**
             * Statistik User
             */
            $adminCount = in_array('admin',$allowedRoles) ? User::where('role','admin')->count() : 0;
            $guruCount  = in_array('guru',$allowedRoles) ? User::where('role','guru')->count() : 0;
            $staffCount = in_array('staff',$allowedRoles) ? User::where('role','staff')->count() : 0;
            $siswaCount = in_array('siswa',$allowedRoles) ? User::where('role','siswa')->count() : 0;
            $userCount  = in_array('user',$allowedRoles) ? User::where('role','user')->count() : 0;

            $totalUsers = $users->count();

            /**
             * Visitors
             */
            $userIds = User::whereIn('role',$allowedRoles)->pluck('id');

            $totalVisitors  = Visitor::whereIn('user_id', $userIds)->count();
            $uniqueVisitors = Visitor::whereIn('user_id', $userIds)
                ->distinct('user_id')
                ->count('user_id');

            // ambil limit dari request, default 10
            // $limit = request()->get('limit', 10);
            // $visitsByUser = User::withCount('visitors as total_visits')
            //     ->whereIn('role', $allowedRoles)
            //     ->orderByDesc('total_visits')
            //     ->take($limit)
            //     ->get();

            // ambil limit dari request, default 10
            $limit = request()->get('limit', 10);
            $query = User::withCount('visitors as total_visits')
                ->whereIn('role', $allowedRoles)
                ->orderByDesc('total_visits');

            if ($limit !== 'all') {
                $query->take((int) $limit);
            }

            $visitsByUser = $query->get();

            /**
             * Routes (menu + title)
             */
            $routes = [
                'admin' => [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard Admin'],
                    ['route' => 'admin.user.index', 'label' => 'Kelola Data Pengguna'],
                    ['route' => 'admin.profil_sekolah', 'label' => 'Profil Sekolah'],
                    ['route' => 'admin.struktural.index', 'label' => 'Struktural Sekolah'],
                    ['route' => 'admin.kejuruan', 'label' => 'Program Kejuruan'],
                    ['route' => 'admin.guru.index', 'label' => 'Kelola Data Guru'],
                    ['route' => 'admin.mapel.index', 'label' => 'Kelola Data Mapel'],
                    ['route' => 'admin.kelas.index', 'label' => 'Kelola Data Kelas'],
                    ['route' => 'admin.ekskul.index', 'label' => 'Kelola Data Ekskul'],
                    ['route' => 'admin.siswa.index', 'label' => 'Kelola Data Siswa'],
                ],
                'guru' => [
                    ['route' => 'guru.dashboard', 'label' => 'Dashboard Guru'],
                    ['route' => 'guru.jadwal_piket.index', 'label' => 'Jadwal Guru Piket'],
                    ['route' => 'public.informasi_sekolah.index', 'label' => 'Informasi Umum Sekolah'],
                    ['route' => 'public.jadwal_mapel.index', 'label' => 'Daftar Jadwal Pelajaran'],
                ],
                'staff' => [
                    ['route' => 'staff.dashboard', 'label' => 'Dashboard Staff'],
                    ['route' => 'staff.agenda', 'label' => 'Agenda Staff'],
                ],
                'siswa' => [
                    ['route' => 'siswa.dashboard', 'label' => 'Dashboard Siswa'],
                    ['route' => 'siswa.agenda', 'label' => 'Agenda Siswa'],
                ],
                'user' => [
                    ['route' => 'user.dashboard', 'label' => 'Dashboard User'],
                    ['route' => 'user.activities', 'label' => 'Kegiatan User'],
                ],
            ];

            $currentRouteName = request()->route()?->getName();
            $pageTitle = null;
            foreach ($routes as $role => $menus) {
                foreach ($menus as $menu) {
                    if ($menu['route'] === $currentRouteName) {
                        $pageTitle = $menu['label'];
                        break 2;
                    }
                }
            }

            /**
             * Pass data ke semua view
             */
            $view->with([
                'users'          => $users,
                'onlineUsers'    => $onlineUsers,
                'onlineGuru'     => $onlineGuru,
                'onlineStaff'    => $onlineStaff,
                'onlineSiswa'    => $onlineSiswa,
                'onlineUser'     => $onlineUser,
                'adminCount'     => $adminCount,
                'guruCount'      => $guruCount,
                'staffCount'     => $staffCount,
                'siswaCount'     => $siswaCount,
                'userCount'      => $userCount,
                'totalUsers'     => $totalUsers,
                'visitsByUser'   => $visitsByUser,
                'totalVisitors'  => $totalVisitors,
                'uniqueVisitors' => $uniqueVisitors,
                'routes'         => $routes,
                'pageTitle'      => $pageTitle,
                'profil'         => $profil,
            ]);
        });
    }
}
