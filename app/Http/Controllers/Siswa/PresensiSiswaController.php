<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSiswa;
use App\Models\DataKelas;
use App\Models\PresensiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PresensiSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🚫 Batasi hanya sekretaris yang boleh akses
        if ($user->jabatan !== 'Sekretaris') {
            abort(403, 'Hanya sekretaris kelas yang dapat mengakses halaman presensi.');
        }

        $hariIni  = Carbon::now('Asia/Jakarta')->locale('id')->dayName;
        $tanggal  = Carbon::now('Asia/Jakarta')->day;
        $bulan    = Carbon::now('Asia/Jakarta')->month;
        $tahun    = Carbon::now('Asia/Jakarta')->year;

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
            : collect();

        // Ambil presensi siswa hari ini dan keyBy siswa_id
        $presensiHariIni = PresensiSiswa::whereIn('siswa_id', $siswaKelas->pluck('id'))
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->keyBy('siswa_id');

        // Cek apakah presensi sudah ditandai selesai oleh sekretaris hari ini
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
        $user = Auth::user();

        // 🚫 Batasi hanya sekretaris
        if ($user->jabatan !== 'Sekretaris') {
            abort(403, 'Anda tidak memiliki izin untuk menyimpan presensi.');
        }

        $keterangan = $request->input('keterangan', []);
        $userId = Auth::id();
        $today = Carbon::today();

        foreach ($keterangan as $siswaId => $value) {
            $presensi = PresensiSiswa::where('siswa_id', $siswaId)
                ->where('user_id', $userId)
                ->whereDate('created_at', $today)
                ->first();

            if ($presensi) {
                $presensi->update([
                    'keterangan' => $value,
                    'is_selesai' => false,
                ]);
            } else {
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

        // 🚫 Batasi hanya sekretaris
        if ($user->jabatan !== 'Sekretaris') {
            abort(403, 'Anda tidak memiliki izin untuk menandai presensi selesai.');
        }

        $today = Carbon::today();

        PresensiSiswa::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->update(['is_selesai' => 1]);

        return redirect()->back()->with('alert', [
            'message' => 'Presensi hari ini telah ditandai selesai!',
            'type' => 'success',
            'title' => 'Berhasil'
        ]);
    }
}
