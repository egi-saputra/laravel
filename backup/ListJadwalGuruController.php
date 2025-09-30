<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\JadwalGuru;
use Illuminate\Pagination\LengthAwarePaginator;

class ListJadwalGuruController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $daysOrder = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

        // Ambil filter dari request
        $search = $request->get('search');
        $filterHari = $request->get('hari');
        $filterSesi = $request->get('sesi');
        $filterGuru = $request->get('guru');
        $filterKelas = $request->get('kelas');

        // Ambil semua jadwal dengan relasi
        $jadwal = JadwalGuru::with(['guru.user','kelas','mapel'])
            ->orderByRaw("FIELD(hari, '".implode("','", $daysOrder)."')")
            ->orderBy('jam_mulai')
            ->get();

        // ===========================
        // FILTERING MANUAL SEBELUM GROUPING
        // ===========================
        if ($search || $filterHari || $filterSesi || $filterGuru || $filterKelas) {
            $jadwal = $jadwal->filter(function($item) use ($search, $filterHari, $filterSesi, $filterGuru, $filterKelas) {

                $guruName = $item->guru->user->name ?? $item->guru->nama ?? '-';
                $kelasName = $item->kelas->kelas ?? '-';

                $text = strtolower($item->hari.' '.$item->sesi.' '.$guruName.' '.$kelasName);

                return
                    (!$search || str_contains($text, strtolower($search))) &&
                    (!$filterHari || strtolower($item->hari) === strtolower($filterHari)) &&
                    (!$filterSesi || str_contains(strtolower($item->sesi), strtolower($filterSesi))) &&
                    (!$filterGuru || str_contains(strtolower($guruName), strtolower($filterGuru))) &&
                    (!$filterKelas || str_contains(strtolower($kelasName), strtolower($filterKelas)));
            })->values();
        }

        // ===========================
        // GROUPING
        // ===========================
        $groupedJadwal = $jadwal->groupBy(function($item){
            return $item->hari.'-'.$item->sesi.'-'.$item->guru_id.'-'.$item->kelas_id;
        })->map(function($items){
            return [
                'hari'  => $items->first()->hari,
                'sesi'  => $items->first()->sesi,
                'guru'  => $items->first()->guru->user->name ?? $items->first()->guru->nama ?? '-',
                'kelas' => $items->first()->kelas->kelas ?? '-',
                'jam'   => $items->map(fn($j) => $j->jam_mulai.'-'.$j->jam_selesai)->toArray(),
            ];
        })->values();

        // ===========================
        // PAGINATION MANUAL
        // ===========================
        $page = $request->get('page', 1);
        $perPage = 48;
        $offset = ($page * $perPage) - $perPage;
        $paginatedJadwal = new LengthAwarePaginator(
            $groupedJadwal->slice($offset, $perPage)->values(),
            $groupedJadwal->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ===========================
        // Sekolah default
        // ===========================
        $sekolah = DB::table('profil_sekolah')->first() ?? (object)[
            'nama_sekolah' => 'SMA Negeri 1 Contoh',
            'alamat'       => 'Jl. Pendidikan No. 123, Kota Contoh',
            'telepon'      => '(021) 123456',
            'email'        => 'info@smanc1contoh.sch.id',
        ];

        // Mapping role ke view
        $views = [
            'admin'  => 'admin.jadwal_guru',
            'guru'   => 'public.jadwal_guru',
            'staff'  => 'public.jadwal_guru',
            'siswa'  => 'public.jadwal_guru',
        ];

        if (!array_key_exists($role, $views)) {
            abort(403, 'Akses ditolak.');
        }

        return view($views[$role], [
            'groupedJadwal' => $paginatedJadwal,
            'sekolah' => $sekolah,
            'pageTitle' => 'Data Jadwal Guru',
        ]);
    }
}
