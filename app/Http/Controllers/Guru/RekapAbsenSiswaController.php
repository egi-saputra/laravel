<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSiswa;
use App\Models\PresensiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class RekapAbsenSiswaController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle   = "Rekap Presensi Siswa";
        $profil      = \App\Models\ProfilSekolah::first();
        $rekap       = collect();
        $isGenerated = false;
        $hasPresensi = false;

        $guruUserId = auth()->user()->id;

        $dataGuruId = DB::table('data_guru')
            ->where('user_id', $guruUserId)
            ->value('id');

        $kelas_id = DB::table('data_kelas')
            ->where('walas_id', $dataGuruId)
            ->value('id');

        if (!$kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini (bukan wali kelas).');
        }

        $periode_mulai = $request->query('periode_mulai');
        $periode_akhir = $request->query('periode_akhir');
        $q = $request->query('q'); // ✅ search nama siswa

        if ($periode_mulai && $periode_akhir) {
            $periode_mulai_carbon = Carbon::parse($periode_mulai)->startOfDay();
            $periode_akhir_carbon = Carbon::parse($periode_akhir)->endOfDay();

            $rekap = DataSiswa::withCount([
                'presensi as hadir_count' => fn($q) =>
                    $q->whereBetween('created_at', [$periode_mulai_carbon, $periode_akhir_carbon])
                    ->where('keterangan', 'Hadir'),
                'presensi as sakit_count' => fn($q) =>
                    $q->whereBetween('created_at', [$periode_mulai_carbon, $periode_akhir_carbon])
                    ->where('keterangan', 'Sakit'),
                'presensi as izin_count'  => fn($q) =>
                    $q->whereBetween('created_at', [$periode_mulai_carbon, $periode_akhir_carbon])
                    ->where('keterangan', 'Izin'),
                'presensi as alpa_count'  => fn($q) =>
                    $q->whereBetween('created_at', [$periode_mulai_carbon, $periode_akhir_carbon])
                    ->where('keterangan', 'Alpa'),
            ])
            ->where('kelas_id', $kelas_id);

            // ✅ filter nama siswa kalau ada input pencarian
            if (!empty($q)) {
                $rekap->where('nama_lengkap', 'like', "%{$q}%");
            }

            $rekap = $rekap->paginate(40)
                ->appends($request->only(['periode_mulai','periode_akhir','q']));

            $isGenerated = true;
            $hasPresensi = $rekap->sum(fn($s) =>
                $s->hadir_count + $s->sakit_count + $s->izin_count + $s->alpa_count
            ) > 0;
        }

        return view('guru.absensi_kelas', compact(
            'pageTitle', 'profil', 'rekap', 'isGenerated',
            'kelas_id', 'periode_mulai', 'periode_akhir', 'hasPresensi', 'q'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_mulai',
        ]);

        return redirect()->route('guru.absensi_kelas.index', [
            'periode_mulai' => $request->periode_mulai,
            'periode_akhir' => $request->periode_akhir,
        ]);
    }

    public function backup(Request $request)
    {
        $request->validate([
            'kelas_id'      => 'required|exists:data_kelas,id',
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_mulai',
        ]);

        $mulai = Carbon::parse($request->periode_mulai)->startOfDay();
        $akhir = Carbon::parse($request->periode_akhir)->endOfDay();

        $siswaIds = DataSiswa::where('kelas_id', $request->kelas_id)->pluck('id');

        $data = PresensiSiswa::whereIn('siswa_id', $siswaIds)
            ->whereBetween('created_at', [$mulai, $akhir])
            ->get();

        $filename = 'backup_presensi_siswa_' . now()->format('Ymd_His') . '.json';

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $filename);
    }

    public function clear(Request $request)
    {
        $request->validate([
            'kelas_id'      => 'required|exists:data_kelas,id',
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_mulai',
        ]);

        $mulai = Carbon::parse($request->periode_mulai)->startOfDay();
        $akhir = Carbon::parse($request->periode_akhir)->endOfDay();

        $siswaIds = DataSiswa::where('kelas_id', $request->kelas_id)->pluck('id');

        PresensiSiswa::whereIn('siswa_id', $siswaIds)
            ->whereBetween('created_at', [$mulai, $akhir])
            ->delete();

        return redirect()->route('guru.absensi_kelas.index')
            ->with('success', "Data presensi siswa dari {$mulai->format('d/m/Y')} sampai {$akhir->format('d/m/Y')} berhasil dihapus!");
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json,txt'
        ]);

        $path = $request->file('backup_file')->getRealPath();
        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (!empty($data)) {
            $insertData = array_map(function ($row) {
                unset($row['id']);

                // ✅ Convert datetime agar cocok dengan MySQL
                if (isset($row['created_at'])) {
                    $row['created_at'] = \Carbon\Carbon::parse($row['created_at'])->format('Y-m-d H:i:s');
                }
                if (isset($row['updated_at'])) {
                    $row['updated_at'] = \Carbon\Carbon::parse($row['updated_at'])->format('Y-m-d H:i:s');
                }

                return $row;
            }, $data);

            PresensiSiswa::insert($insertData);
        }

        return redirect()->route('guru.absensi_kelas.index')
            ->with('alert', [
                'message' => 'Data presensi siswa berhasil direstore dari backup!',
                'type'    => 'success',
                'title'   => 'Berhasil',
            ]);
    }

    // RekapAbsenSiswaController.php
    public function exportExcel(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:data_kelas,id',
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_mulai',
        ]);

        $mulai = Carbon::parse($request->periode_mulai)->startOfDay();
        $akhir = Carbon::parse($request->periode_akhir)->endOfDay();

        $siswaIds = DataSiswa::where('kelas_id', $request->kelas_id)->pluck('id');

        $data = PresensiSiswa::with('siswa')
            ->whereIn('siswa_id', $siswaIds)
            ->whereBetween('created_at', [$mulai, $akhir])
            ->get();

        $filename = 'rekap_presensi_'.$request->periode_mulai.'_sd_'.$request->periode_akhir.'.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PresensiSiswaExport($data), $filename);
    }

}
