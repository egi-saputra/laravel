<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiSiswa;
use App\Models\DataKelas;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PresensiHariIniExport;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $kelasId = $request->kelas;
        $kelasList = DataKelas::orderBy('kelas')->get();

        $presensi = collect(); // default kosong agar view tidak error

        if ($kelasId) {
            // $presensi = PresensiSiswa::with(['user:id,name', 'dataSiswa.kelas:id,kelas'])
            //     ->whereDate('created_at', $today)
            //     ->whereHas('dataSiswa', fn($q) => $q->where('kelas_id', $kelasId))
            //     ->orderBy('created_at')
            //     ->get()
            //     ->sortBy(fn($item) => strtolower($item->dataSiswa->nama_lengkap ?? ''));

            $presensi = PresensiSiswa::with(['user:id,name', 'dataSiswa.kelas:id,kelas'])
                ->whereDate('created_at', $today)
                ->when($kelasId, fn($q) => $q->whereHas('dataSiswa', fn($q2) => $q2->where('kelas_id', $kelasId)))
                ->orderBy('created_at')
                ->get()
                ->sortBy(fn($item) => strtolower($item->dataSiswa->nama_lengkap ?? ''));
        }

        return view('guru.absensi_hari_ini', compact('presensi', 'today', 'kelasList', 'kelasId'));
    }

    public function export(Request $request)
    {
        $today = Carbon::today();
        $kelasId = $request->kelas;

        if (!$kelasId) {
            return redirect()->back()->with('error', 'Silakan pilih kelas terlebih dahulu sebelum export.');
        }

        return Excel::download(new PresensiHariIniExport($today, $kelasId), 'presensi_hari_ini.xlsx');
    }
}
