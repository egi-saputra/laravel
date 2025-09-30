<?php

namespace App\Http\Controllers;

use App\Models\DataMapel;
use App\Models\DataGuru;
use App\Models\JadwalGuru;
use App\Models\DataKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class JadwalMapelController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        // Urutan hari
        $daysOrder = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        // 1. Ambil semua data jadwal
        $jadwal = JadwalGuru::with(['guru.user','kelas','mapel'])
            ->orderByRaw("FIELD(hari, '".implode("','", $daysOrder)."')")
            ->orderBy('jam_mulai')
            ->get();

        // 2. Hapus duplikat
        $jadwal = $jadwal->unique(function ($item) {
            return $item->hari.$item->sesi.$item->jam_mulai.$item->jam_selesai.$item->guru_id.$item->kelas_id;
        })->values();

        // 3. Mapping ke array sederhana untuk view
        $jadwalArray = $jadwal->map(function($item){
            return [
                'hari'  => $item->hari,
                'jam'   => Carbon::parse($item->jam_mulai)->format('H:i')
                          .' - '.Carbon::parse($item->jam_selesai)->format('H:i'),
                'mapel' => $item->mapel->mapel ?? '-',
                'kelas' => $item->kelas->kelas ?? '-',
            ];
        });

        // 4. Manual pagination
        $page = request()->get('page', 1);
        $perPage = 96; // misal 10 item per page
        $page = request()->get('page', 1);

        // Slice koleksi untuk page saat ini
        $itemsForCurrentPage = $jadwalArray->forPage($page, $perPage);

        // LengthAwarePaginator
        $paginatedJadwal = new LengthAwarePaginator(
            $itemsForCurrentPage->values(),    // item di page ini
            $jadwalArray->count(),             // total item
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // 5. Data tambahan untuk view
        $guru   = DataGuru::all();
        $kelas  = DataKelas::all();
        $hari   = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $pageTitle = 'Data Jadwal Guru';

        $sekolah = DB::table('profil_sekolah')->first() ?? (object)[
            'nama_sekolah' => 'SMA Negeri 1 Contoh',
            'npsn'         => '',
            'no_izin'      => '',
            'nss'          => '',
            'alamat'       => '',
            'rt'           => '',
            'rw'           => '',
            'kelurahan'    => '',
            'kecamatan'    => '',
            'provinsi'     => '',
            'kabupaten_kota' => '',
            'kode_pos'     => '',
            'telepon'      => '',
            'email'        => '',
            'website'      => '',
        ];

        // 6. Ambil logo sekolah
        $logoBase64 = '';
        $logoFolder = storage_path('app/public/logo_sekolah/');
        $logoFiles = glob($logoFolder . '*');
        if (!empty($logoFiles) && file_exists($logoFiles[0])) {
            $logoBase64 = base64_encode(file_get_contents($logoFiles[0]));
        } else {
            $defaultLogo = storage_path('app/public/logo_sekolah/default-logo.png');
            $logoBase64 = file_exists($defaultLogo) ? base64_encode(file_get_contents($defaultLogo)) : '';
        }

        // 7. Mapping role ke view
        $views = [
            'admin'  => 'admin.jadwal_guru',
            'guru'   => 'public.jadwal_mapel',
            'staff'  => 'public.jadwal_mapel',
            'siswa'  => 'public.jadwal_mapel',
            'user'   => 'public.jadwal_mapel',
        ];

        if (!array_key_exists($role, $views)) {
            abort(403, 'Akses ditolak.');
        }

        // 8. Kirim semua variabel ke view
        return view($views[$role], compact(
            'paginatedJadwal',
            'guru',
            'kelas',
            'hari',
            'sekolah',
            'pageTitle',
            'logoBase64'
        ));
    }
}
