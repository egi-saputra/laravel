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
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $daysOrder = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        // ===========================
        // Ambil filter dari request
        // ===========================
        $search      = $request->get('search');
        $filterHari  = $request->get('hari');
        $filterMapel = $request->get('mapel');
        $filterKelas = $request->get('kelas');

        // ===========================
        // Ambil semua jadwal
        // ===========================
        $jadwal = JadwalGuru::with(['guru.user','kelas','mapel'])
            ->orderByRaw("FIELD(hari, '".implode("','", $daysOrder)."')")
            ->orderBy('jam_mulai')
            ->get();

        // ===========================
        // Filter manual sebelum grouping
        // ===========================
        if ($search || $filterHari || $filterMapel || $filterKelas) {
            $jadwal = $jadwal->filter(function($item) use ($search, $filterHari, $filterMapel, $filterKelas) {
                $mapelName = $item->mapel->mapel ?? '-';
                $kelasName = $item->kelas->kelas ?? '-';
                $text = strtolower($item->hari.' '.$mapelName.' '.$kelasName);

                return
                    (!$search || str_contains($text, strtolower($search))) &&
                    (!$filterHari || strtolower($item->hari) === strtolower($filterHari)) &&
                    (!$filterMapel || str_contains(strtolower($mapelName), strtolower($filterMapel))) &&
                    (!$filterKelas || str_contains(strtolower($kelasName), strtolower($filterKelas)));
            })->values();
        }

        // ===========================
        // Grouping Hari + Kelas
        // ===========================
        $groupedJadwal = $jadwal->groupBy(fn($item) => $item->hari.'-'.$item->kelas_id)
            ->map(function($items){
                $items = $items->sortBy('jam_mulai')->values();
                $result = [];
                $current = null;

                foreach ($items as $item) {
                    $mapelName = $item->mapel->mapel ?? '-';
                    $kelasName = $item->kelas->kelas ?? '-';

                    if (!$current) {
                        $current = [
                            'hari' => $item->hari,
                            'mapel' => $mapelName,
                            'kelas' => $kelasName,
                            'jam_mulai' => $item->jam_mulai,
                            'jam_selesai' => $item->jam_selesai,
                        ];
                    } else {
                        if ($current['mapel'] === $mapelName && $current['jam_selesai'] === $item->jam_mulai) {
                            $current['jam_selesai'] = $item->jam_selesai;
                        } else {
                            $result[] = $current;
                            $current = [
                                'hari' => $item->hari,
                                'mapel' => $mapelName,
                                'kelas' => $kelasName,
                                'jam_mulai' => $item->jam_mulai,
                                'jam_selesai' => $item->jam_selesai,
                            ];
                        }
                    }
                }

                if ($current) $result[] = $current;

                return collect($result)->map(fn($r) => [
                    'hari'  => $r['hari'],
                    'mapel' => $r['mapel'],
                    'kelas' => $r['kelas'],
                    'jam'   => Carbon::parse($r['jam_mulai'])->format('H:i').' - '.Carbon::parse($r['jam_selesai'])->format('H:i'),
                ]);
            })->flatten(1)->values();

        // ===========================
        // Pagination manual
        // ===========================
        $page = $request->get('page', 1);
        $perPage = 48;
        $itemsForCurrentPage = $groupedJadwal->forPage($page, $perPage);
        $paginatedJadwal = new LengthAwarePaginator(
            $itemsForCurrentPage->values(),
            $groupedJadwal->count(),
            $perPage,
            $page,
            ['path'=>$request->url(), 'query'=>$request->query()]
        );

        // ===========================
        // Data tambahan untuk view
        // ===========================
        $guru   = DataGuru::all();
        $kelas  = DataKelas::all();
        $hari   = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $pageTitle = 'Data Jadwal Mapel';
        $sekolah = DB::table('profil_sekolah')->first() ?? (object)[
            'nama_sekolah' => 'SMA Negeri 1 Contoh',
            'npsn'=>'','no_izin'=>'','nss'=>'','alamat'=>'','rt'=>'','rw'=>'',
            'kelurahan'=>'','kecamatan'=>'','provinsi'=>'','kabupaten_kota'=>'',
            'kode_pos'=>'','telepon'=>'','email'=>'','website'=>''
        ];

        // ===========================
        // Ambil logo sekolah
        // ===========================
        $logoBase64 = '';
        $logoMime   = 'png';

        if (!empty($sekolah->file_path) && file_exists(storage_path('app/public/'.$sekolah->file_path))) {
            $logoPath = storage_path('app/public/'.$sekolah->file_path);
            $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $logoData = file_get_contents($logoPath);
            $logoBase64 = base64_encode($logoData);
            if(in_array($ext,['jpg','jpeg'])) $logoMime='jpeg';
            else $logoMime='png';
        } else {
            $defaultLogo = storage_path('app/public/logo_sekolah/default-logo.png');
            if(file_exists($defaultLogo)){
                $logoBase64 = base64_encode(file_get_contents($defaultLogo));
                $logoMime   = 'png';
            }
        }

        // ===========================
        // Mapping role ke view
        // ===========================
        $views = [
            'admin' => 'admin.jadwal_guru',
            'guru'  => 'public.jadwal_mapel',
            'staff' => 'public.jadwal_mapel',
            'siswa' => 'public.jadwal_mapel',
            'user'  => 'public.jadwal_mapel',
        ];
        if(!array_key_exists($role,$views)) abort(403,'Akses ditolak.');

        // ===========================
        // Kirim ke view
        // ===========================
        return view($views[$role], compact(
            'paginatedJadwal','guru','kelas','hari','sekolah','pageTitle',
            'logoBase64','logoMime','search','filterHari','filterMapel','filterKelas'
        ));
    }
}
