<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiGuru;
use App\Models\PresensiStaff;
use App\Models\JadwalGuru;
use App\Models\User;
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * Menampilkan form presensi guru dan staff sekaligus.
     */
    public function index(Request $request)
    {
        $hariIni = Carbon::now('Asia/Jakarta')->locale('id')->dayName;
        $tanggal = Carbon::now('Asia/Jakarta')->day;
        $bulan   = Carbon::now('Asia/Jakarta')->month;
        $tahun   = Carbon::now('Asia/Jakarta')->year;
        $perPage = 12;

        // Presensi Guru hari ini
        $guruHariIni = JadwalGuru::where('hari', $hariIni)
            ->with(['guru.user', 'kelas'])
            ->paginate($perPage, ['*'], 'guru_page')
            ->withQueryString();

        $presensiGuruHariIni = PresensiGuru::where('user_id', auth()->id())
            ->whereDate('created_at', Carbon::today())
            ->get();

        // Presensi Staff hari ini
        $staffHariIni = User::where('role', 'staff')
            ->paginate($perPage, ['*'], 'staff_page')
            ->withQueryString();

        $presensiStaffHariIni = PresensiStaff::whereDate('created_at', Carbon::today())->get();

        // Cek apakah presensi hari ini sudah selesai
        $presensiSelesai = PresensiGuru::where('tanggal', $tanggal)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->where('presensi_selesai', 1)
                            ->exists()
                        ||
                            PresensiStaff::where('tanggal', $tanggal)
                            ->where('bulan', $bulan)
                            ->where('tahun', $tahun)
                            ->where('presensi_selesai', 1)
                            ->exists();

        return view('guru.presensi', compact(
            'hariIni','tanggal','bulan','tahun',
            'guruHariIni','presensiGuruHariIni',
            'staffHariIni','presensiStaffHariIni',
            'presensiSelesai'
        ));
    }

    /**
     * Menyimpan presensi guru.
     */
   public function storeGuru(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::now('Asia/Jakarta')->day);
        $bulan   = $request->input('bulan', Carbon::now('Asia/Jakarta')->month);
        $tahun   = $request->input('tahun', Carbon::now('Asia/Jakarta')->year);

        $keterangan = $request->input('keterangan', []);
        $apel       = $request->input('apel', []);
        $upacara    = $request->input('upacara', []);

        // simpan status guru per hari
        $stateGuru = [];

        foreach ($keterangan as $jadwalId => $value) {
            $jadwal = JadwalGuru::find($jadwalId);
            if (!$jadwal) continue;

            $guruId = $jadwal->guru_id;

            // input baru
            $apelValue    = $apel[$jadwalId] ?? 'Tidak';
            $upacaraValue = $upacara[$jadwalId] ?? 'Tidak';

            // cek apakah guru ini sudah punya status di hari yg sama
            if (!isset($stateGuru[$guruId])) {
                $existing = PresensiGuru::where('guru_id', $guruId)
                    ->where('tanggal', $tanggal)
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->first();

                $stateGuru[$guruId] = [
                    'apel'    => $existing->apel    ?? 'Tidak',
                    'upacara' => $existing->upacara ?? 'Tidak',
                ];
            }

            $state = $stateGuru[$guruId];

            // ===== HIERARKI =====
            // Jika guru sudah Pembina Apel -> lock semua
            if ($state['apel'] === 'Pembina Apel') {
                $apelValue = 'Tidak';
                $upacaraValue = 'Tidak';
            }
            // Jika guru sudah Apel -> tidak boleh lagi Apel/Upacara
            elseif ($state['apel'] === 'Apel') {
                $apelValue = 'Tidak';
                $upacaraValue = 'Tidak';
            }
            // Jika guru sudah Pembina Upacara -> lock semua
            elseif ($state['upacara'] === 'Pembina Upacara') {
                $apelValue = 'Tidak';
                $upacaraValue = 'Tidak';
            }
            // Jika guru sudah Upacara -> tidak boleh Apel lagi
            elseif ($state['upacara'] === 'Upacara') {
                $apelValue = 'Tidak';
                $upacaraValue = 'Tidak';
            }

            // Kalau belum ada status, baru boleh isi
            if ($state['apel'] === 'Tidak' && $state['upacara'] === 'Tidak') {
                if ($apelValue === 'Pembina Apel') {
                    $stateGuru[$guruId] = ['apel' => 'Pembina Apel', 'upacara' => 'Tidak'];
                } elseif ($apelValue === 'Apel') {
                    $stateGuru[$guruId] = ['apel' => 'Apel', 'upacara' => 'Tidak'];
                } elseif ($upacaraValue === 'Pembina Upacara') {
                    $stateGuru[$guruId] = ['apel' => 'Tidak', 'upacara' => 'Pembina Upacara'];
                } elseif ($upacaraValue === 'Upacara') {
                    $stateGuru[$guruId] = ['apel' => 'Tidak', 'upacara' => 'Upacara'];
                }
            }

            PresensiGuru::updateOrCreate(
                [
                    'jadwal_id' => $jadwal->id,
                    'guru_id'   => $guruId,
                    'tanggal'   => $tanggal,
                    'bulan'     => $bulan,
                    'tahun'     => $tahun,
                ],
                [
                    'user_id'    => auth()->id(),
                    'keterangan' => $value,
                    'apel'       => $apelValue,
                    'upacara'    => $upacaraValue,
                ]
            );
        }

        return redirect()->back()->with('alert', [
            'message' => 'Presensi guru berhasil disimpan!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    /**
     * Menyimpan presensi staff.
     */
    public function storeStaff(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::now('Asia/Jakarta')->day);
        $bulan   = $request->input('bulan', Carbon::now('Asia/Jakarta')->month);
        $tahun   = $request->input('tahun', Carbon::now('Asia/Jakarta')->year);

        $keterangan = $request->input('keterangan', []);
        $apel       = $request->input('apel', []);
        $upacara    = $request->input('upacara', []);

        $apelValues = array_values($apel);
        $apelCounter = 0;
        $adaApel = in_array('Apel', $apelValues);

        $upacaraCounter = 0;

        foreach ($keterangan as $staffId => $value) {
            $apelValue = $apel[$staffId] ?? 'Tidak';
            if ($apelValue === 'Apel') {
                $apelCounter++;
                if ($apelCounter > 1) $apelValue = 'Tidak';
            }

            $upacaraValue = $upacara[$staffId] ?? 'Tidak';
            if ($adaApel || $apelValue === 'Apel') {
                $upacaraValue = 'Tidak';
            } else {
                if ($upacaraValue === 'Upacara') {
                    $upacaraCounter++;
                    if ($upacaraCounter > 1) $upacaraValue = 'Tidak';
                }
            }

            PresensiStaff::updateOrCreate(
                [
                    'staff_id' => $staffId,
                    'user_id'  => auth()->id(),
                    'tanggal'  => $tanggal,
                    'bulan'    => $bulan,
                    'tahun'    => $tahun,
                ],
                [
                    'keterangan' => $value,
                    'apel'       => $apelValue,
                    'upacara'    => $upacaraValue,
                ]
            );
        }

        return redirect()->back()->with('alert', [
            'message' => 'Presensi staff berhasil disimpan!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    /**
     * Tandai presensi hari ini selesai
     */
    public function selesaiPresensi()
    {
        $tanggal = Carbon::now('Asia/Jakarta')->day;
        $bulan   = Carbon::now('Asia/Jakarta')->month;
        $tahun   = Carbon::now('Asia/Jakarta')->year;

        PresensiGuru::where('user_id', auth()->id())
            ->where('tanggal', $tanggal)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->update(['presensi_selesai' => 1]);

        PresensiStaff::where('user_id', auth()->id())
            ->where('tanggal', $tanggal)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->update(['presensi_selesai' => 1]);

        return redirect()->back()->with('alert', [
            'message' => 'Presensi hari ini telah selesai dilakukan!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }
}
