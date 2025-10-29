<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\JadwalGuru;
use Illuminate\Pagination\LengthAwarePaginator;

class CardJadwalGuruController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $daysOrder = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

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
            return $item->hari.'-'.$item->guru_id.'-'.$item->kelas_id.'-'.$item->mapel_id;
        })->map(function($items){
            $items = $items->sortBy('jam_mulai')->values();

            $result = [];
            $current = null;

            foreach ($items as $item) {
                $guruName  = $item->guru->user->name ?? $item->guru->nama ?? '-';
                $kelasName = $item->kelas->kelas ?? '-';
                $mapelName = $item->mapel->mapel ?? '-';

                if (!$current) {
                    $current = [
                        'hari'  => $item->hari,
                        'guru'  => $guruName,
                        'kelas' => $kelasName,
                        'mapel' => $mapelName,
                        'jam_mulai'   => $item->jam_mulai,
                        'jam_selesai' => $item->jam_selesai,
                    ];
                } else {
                    // jika jam nyambung → extend
                    if ($current['jam_selesai'] === $item->jam_mulai) {
                        $current['jam_selesai'] = $item->jam_selesai;
                    } else {
                        $result[] = $current;
                        $current = [
                            'hari'  => $item->hari,
                            'guru'  => $guruName,
                            'kelas' => $kelasName,
                            'mapel' => $mapelName,
                            'jam_mulai'   => $item->jam_mulai,
                            'jam_selesai' => $item->jam_selesai,
                        ];
                    }
                }
            }

            if ($current) {
                $result[] = $current;
            }

            return collect($result)->map(function($r){
                return [
                    'hari'  => $r['hari'],
                    'guru'  => $r['guru'],
                    'kelas' => $r['kelas'],
                    'mapel' => $r['mapel'],
                    'jam'   => date('H:i', strtotime($r['jam_mulai'])) .
                            ' - ' .
                            date('H:i', strtotime($r['jam_selesai'])),
                ];
            });
        })->flatten(1)->values();

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
            'nama_sekolah'=>'SMA Negeri 1 Contoh',
            'alamat'=>'Jl. Pendidikan No.123',
            'telepon'=>'(021)123456',
            'email'=>'info@smanc1contoh.sch.id'
        ];

        $logoBase64 = '';
        $logoMime   = 'png';

        // Ambil logo dari database file_path
        if(!empty($sekolah->file_path) && file_exists(storage_path('app/public/'.$sekolah->file_path))) {
            $logoPath = storage_path('app/public/'.$sekolah->file_path);
            $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $logoData = file_get_contents($logoPath);
            $logoBase64 = base64_encode($logoData);

            // Tentukan MIME untuk jsPDF
            if(in_array($ext,['jpg','jpeg'])) $logoMime = 'jpeg';
            elseif($ext === 'webp') $logoMime = 'png'; // paksa PNG untuk jsPDF
            elseif($ext === 'png') $logoMime = 'png';
            else $logoMime = 'png'; // fallback
        } else {
            // fallback default logo
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
            'guru'=>'public.card_jadwal_guru',
            'staff'=>'public.card_jadwal_guru',
        ];

        if(!array_key_exists($role,$views)) abort(403,'Akses ditolak');

        return view($views[$role], [
            'paginatedJadwal'=>$paginatedJadwal,
            'sekolah'=>$sekolah,
            'pageTitle'=>'Data Jadwal Guru',
            'logoBase64'=>$logoBase64,
            'logoMime'=>$logoMime
        ]);
    }
}
