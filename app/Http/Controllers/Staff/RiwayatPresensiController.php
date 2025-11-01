<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatPresensiController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle   = "Rekap Presensi";
        $profil      = \App\Models\ProfilSekolah::first();
        $rekap       = collect();
        $isGenerated = false;

        // cek filter dari session
        $filters = session('riwayat_filters');

        if ($filters) {
            $jenis = $filters['jenis_presensi'];
            $mulai = Carbon::parse($filters['periode_mulai'])->startOfDay();
            $akhir = Carbon::parse($filters['periode_akhir'])->endOfDay();

            if ($jenis === 'guru') {
                $rekap = DB::table('presensi_guru as p')
                    ->leftJoin('users as petugas', 'p.user_id', '=', 'petugas.id')
                    ->leftJoin('data_guru as g', 'p.guru_id', '=', 'g.id')
                    ->leftJoin('users as guru', 'g.user_id', '=', 'guru.id')
                    ->whereBetween('p.created_at', [$mulai, $akhir])
                    ->select(
                        'p.id',
                        'p.created_at',
                        'p.sesi',
                        'p.keterangan',
                        'p.apel',
                        'p.upacara',
                        'petugas.name as nama_petugas',
                        'guru.name as nama_guru'
                    )
                    ->orderBy('p.created_at', 'asc')
                    ->paginate(30);
            } else {
                $rekap = DB::table('presensi_staff as p')
                    ->leftJoin('users as petugas', 'p.user_id', '=', 'petugas.id')
                    ->leftJoin('users as staff', 'p.staff_id', '=', 'staff.id')
                    ->whereBetween('p.created_at', [$mulai, $akhir])
                    ->select(
                        'p.id',
                        'p.created_at',
                        'p.keterangan',
                        'p.keterangan',
                        'p.apel',
                        'p.upacara',
                        'petugas.name as nama_petugas',
                        'staff.name as nama_staff'
                    )
                    ->orderBy('p.created_at', 'asc')
                    ->paginate(30);
            }

            $pageTitle   = "Rekap Presensi " . ucfirst($jenis);
            $isGenerated = true;
        }

        return view('staff.riwayat_presensi', compact(
            'pageTitle', 'profil', 'rekap', 'isGenerated'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'jenis_presensi' => 'required|in:guru,staff',
            'periode_mulai'  => 'required|date',
            'periode_akhir'  => 'required|date|after_or_equal:periode_mulai',
        ]);

        // simpan filter ke session
        session([
            'riwayat_filters' => $request->only('jenis_presensi', 'periode_mulai', 'periode_akhir')
        ]);

        return redirect()->route('staff.riwayat_presensi.index');
    }

    public function backup()
    {
        $guru   = DB::table('presensi_guru')->get();
        $staff  = DB::table('presensi_staff')->get();

        $data = [
            'guru'  => $guru,
            'staff' => $staff,
        ];

        $filename = 'backup_presensi_' . now()->format('Ymd_His') . '.json';

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $filename);
    }

    public function clear(Request $request)
    {
        $request->validate([
            'jenis_presensi' => 'required|in:guru,staff',
            'periode_mulai'  => 'required|date',
            'periode_akhir'  => 'required|date|after_or_equal:periode_mulai',
        ]);

        $jenis = $request->jenis_presensi;
        $mulai = Carbon::parse($request->periode_mulai)->startOfDay();
        $akhir = Carbon::parse($request->periode_akhir)->endOfDay();

        if ($jenis === 'guru') {
            DB::table('presensi_guru')
                ->whereBetween('created_at', [$mulai, $akhir])
                ->delete();
        } else {
            DB::table('presensi_staff')
                ->whereBetween('created_at', [$mulai, $akhir])
                ->delete();
        }

        return redirect()->route('staff.riwayat_presensi.index')
            ->with('success', "Data presensi {$jenis} dari {$mulai->format('d/m/Y')} sampai {$akhir->format('d/m/Y')} berhasil dihapus!");
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json,txt'
        ]);

        $path = $request->file('backup_file')->getRealPath();
        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (!empty($data['guru'])) {
            $guruData = array_map(function ($row) {
                unset($row['id']);
                return $row;
            }, $data['guru']);
            DB::table('presensi_guru')->insert($guruData);
        }

        if (!empty($data['staff'])) {
            $staffData = array_map(function ($row) {
                unset($row['id']);
                return $row;
            }, $data['staff']);
            DB::table('presensi_staff')->insert($staffData);
        }

        return redirect()->route('staff.riwayat_presensi.index')
            ->with('alert', [
                'message' => 'Data presensi berhasil direstore dari backup!',
                'type'    => 'success',
                'title'   => 'Berhasil',
            ]);
    }
}
