<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSiswa;
use App\Models\DataKelas;
use App\Models\PresensiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresensiSiswaController extends Controller
{
    // public function index()
    // {
    //     $hariIni  = Carbon::now('Asia/Jakarta')->locale('id')->dayName;
    //     $tanggal  = Carbon::now('Asia/Jakarta')->day;
    //     $bulan    = Carbon::now('Asia/Jakarta')->month;
    //     $tahun    = Carbon::now('Asia/Jakarta')->year;

    //     $user = Auth::user();

    //     // Ambil kelas_id dari data_siswa milik user login
    //     $userKelasId = $user->dataSiswa->kelas_id ?? null;

    //     // Ambil semua siswa di kelas yang sama (hanya jika kelas_id tersedia)
    //     $siswaKelas = $userKelasId
    //         ? DataSiswa::with('user')
    //             ->where('kelas_id', $userKelasId)
    //             ->orderBy('nama_lengkap')
    //             ->get()
    //         : collect(); // kosong jika user belum punya data_siswa

    //     // Ambil presensi siswa hari ini dan keyBy siswa_id
    //     $presensiHariIni = PresensiSiswa::whereIn('siswa_id', $siswaKelas->pluck('id'))
    //         ->whereDate('created_at', Carbon::today())
    //         ->get()
    //         ->keyBy('siswa_id');

    //     // Cek apakah user login sudah menandai presensi selesai hari ini
    //     $presensiSelesai = PresensiSiswa::where('user_id', $user->id)
    //         ->whereDate('created_at', Carbon::today())
    //         ->where('is_selesai', true)
    //         ->exists();

    //     return view('siswa.presensi', compact(
    //         'hariIni', 'tanggal', 'bulan', 'tahun',
    //         'siswaKelas', 'presensiHariIni', 'presensiSelesai', 'user'
    //     ));
    // }

    public function index()
    {
        $hariIni  = Carbon::now('Asia/Jakarta')->locale('id')->dayName;
        $tanggal  = Carbon::now('Asia/Jakarta')->day;
        $bulan    = Carbon::now('Asia/Jakarta')->month;
        $tahun    = Carbon::now('Asia/Jakarta')->year;

        $user = Auth::user();

        // Ambil kelas_id dari data_siswa milik user login
        $userKelasId = $user->dataSiswa->kelas_id ?? null;

        // Ambil objek kelas dari database
        $kelas = $userKelasId
            ? DataKelas::find($userKelasId)
            : null;

        // Ambil semua siswa di kelas yang sama (hanya jika kelas_id tersedia)
        $siswaKelas = $userKelasId
            ? DataSiswa::with('user')
                ->where('kelas_id', $userKelasId)
                ->orderBy('nama_lengkap')
                ->get()
            : collect(); // kosong jika user belum punya data_siswa

        // Ambil presensi siswa hari ini dan keyBy siswa_id
        $presensiHariIni = PresensiSiswa::whereIn('siswa_id', $siswaKelas->pluck('id'))
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->keyBy('siswa_id');

        // Cek apakah user login sudah menandai presensi selesai hari ini
        $presensiSelesai = PresensiSiswa::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->where('is_selesai', true)
            ->exists();

        return view('siswa.presensi', compact(
            'hariIni', 'tanggal', 'bulan', 'tahun',
            'siswaKelas', 'presensiHariIni', 'presensiSelesai', 'user', 'kelas'
        ));
    }

    public function store(Request $request)
    {
        $keterangan = $request->input('keterangan', []);
        $userId = Auth::id();
        $today = Carbon::today();

        foreach ($keterangan as $siswaId => $value) {
            $presensi = PresensiSiswa::where('siswa_id', $siswaId)
                ->where('user_id', $userId)
                ->whereDate('created_at', $today)
                ->first();

            if ($presensi) {
                // update
                $presensi->update([
                    'keterangan' => $value,
                    'is_selesai' => false,
                ]);
            } else {
                // buat baru
                PresensiSiswa::create([
                    'siswa_id' => $siswaId,
                    'user_id'  => $userId,
                    'keterangan' => $value,
                    'is_selesai' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('alert', [
            'message' => 'Presensi siswa berhasil disimpan!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    public function selesai()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Hanya update is_selesai untuk semua presensi yang di-submit oleh user ini hari ini
        PresensiSiswa::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->update([
                'is_selesai' => 1
            ]);

        return redirect()->back()->with('alert', [
            'message' => 'Presensi hari ini telah ditandai selesai!',
            'type' => 'success',
            'title' => 'Berhasil'
        ]);
    }

}
