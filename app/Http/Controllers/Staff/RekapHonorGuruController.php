<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapHonorGuruController extends Controller
{
    public function index()
    {
        $pageTitle = "Rekap Honor Guru";
        $profil = \App\Models\ProfilSekolah::first();

        $rekap = collect();
        $periodeBulan = null;
        $jumlahMinggu = null;
        $isGenerated = false;

        return view('staff.rekap_honor_guru', compact('pageTitle', 'profil', 'rekap', 'periodeBulan', 'jumlahMinggu', 'isGenerated'));
    }

    // public function generate(Request $request)
    // {
    //     $periodeBulan = $request->periode_bulan;
    //     $startDate = Carbon::createFromFormat('Y-m', $periodeBulan)->startOfMonth();
    //     $endDate   = Carbon::createFromFormat('Y-m', $periodeBulan)->endOfMonth();
    //     $jumlahMinggu = $startDate->diffInWeeks($endDate) + 1;

    //     // Nominal
    //     $uangTransport       = (int) preg_replace('/\D/', '', $request->uang_transport ?? 0);
    //     $uangJamMati         = (int) preg_replace('/\D/', '', $request->uang_jam_mati ?? 0);
    //     $uangApel            = (int) preg_replace('/\D/', '', $request->uang_apel ?? 0);
    //     $uangUpacara         = (int) preg_replace('/\D/', '', $request->uang_upacara ?? 0);
    //     $uangPembinaApel     = (int) preg_replace('/\D/', '', $request->uang_pembina_apel ?? 0);
    //     $uangPembinaUpacara  = (int) preg_replace('/\D/', '', $request->uang_pembina_upacara ?? 0);

    //     // Daftar guru
    //     $guruData = \DB::table('data_guru as dg')
    //         ->join('users as u', 'dg.user_id', '=', 'u.id')
    //         ->leftJoin('jadwal_guru as j', 'dg.id', '=', 'j.guru_id')
    //         ->select(
    //             'dg.id as guru_id',
    //             'u.name',
    //             \DB::raw('COUNT(j.id) as jumlah_jam_guru') // <── jumlah row jadwal_guru
    //         )
    //         ->groupBy('dg.id', 'u.name')
    //         ->get();

    //     $rekap = $guruData->map(function ($row) use (
    //         $jumlahMinggu,
    //         $uangTransport, $uangJamMati,
    //         $uangApel, $uangUpacara, $uangPembinaApel, $uangPembinaUpacara,
    //         $startDate, $endDate
    //     ) {
    //         // Jam Mati
    //         $row->jam_mati = $row->jumlah_jam_guru * $jumlahMinggu;

    //         // Jam Kehadiran dari presensi (hitung row jadwal_guru yang match)
    //         $row->jumlah_jam_hadir = \DB::table('presensi_guru as p')
    //             ->join('jadwal_guru as j', 'p.jadwal_id', '=', 'j.id')
    //             ->where('p.guru_id', $row->guru_id)
    //             ->where('p.keterangan', 'Hadir') // hanya kehadiran
    //             ->whereBetween(
    //                 \DB::raw("STR_TO_DATE(CONCAT(p.tahun,'-',p.bulan,'-',p.tanggal), '%Y-%m-%d')"),
    //                 [$startDate, $endDate]
    //             )
    //             ->count(); // hitung row = jumlah jam hadir

    //         // Kehadiran Apel/Upacara
    //         $row->jumlah_apel = \DB::table('presensi_guru')
    //             ->where('guru_id', $row->guru_id)
    //             ->where('apel', 'Apel')
    //             ->whereBetween(
    //                 \DB::raw("STR_TO_DATE(CONCAT(tahun,'-',bulan,'-',tanggal), '%Y-%m-%d')"),
    //                 [$startDate, $endDate]
    //             )
    //             ->count();

    //         $row->jumlah_pembina_apel = \DB::table('presensi_guru')
    //             ->where('guru_id', $row->guru_id)
    //             ->where('apel', 'Pembina Apel')
    //             ->whereBetween(
    //                 \DB::raw("STR_TO_DATE(CONCAT(tahun,'-',bulan,'-',tanggal), '%Y-%m-%d')"),
    //                 [$startDate, $endDate]
    //             )
    //             ->count();

    //         $row->jumlah_upacara = \DB::table('presensi_guru')
    //             ->where('guru_id', $row->guru_id)
    //             ->where('upacara', 'Upacara')
    //             ->whereBetween(
    //                 \DB::raw("STR_TO_DATE(CONCAT(tahun,'-',bulan,'-',tanggal), '%Y-%m-%d')"),
    //                 [$startDate, $endDate]
    //             )
    //             ->count();

    //         $row->jumlah_pembina_upacara = \DB::table('presensi_guru')
    //             ->where('guru_id', $row->guru_id)
    //             ->where('upacara', 'Pembina Upacara')
    //             ->whereBetween(
    //                 \DB::raw("STR_TO_DATE(CONCAT(tahun,'-',bulan,'-',tanggal), '%Y-%m-%d')"),
    //                 [$startDate, $endDate]
    //             )
    //             ->count();

    //         // Hitung total
    //         $total = ($row->jumlah_jam_hadir * $uangTransport)   // transport dari kehadiran nyata
    //             + ($row->jam_mati * $uangJamMati)             // jam mati
    //             + ($row->jumlah_apel * $uangApel)
    //             + ($row->jumlah_upacara * $uangUpacara)
    //             + ($row->jumlah_pembina_apel * $uangPembinaApel)
    //             + ($row->jumlah_pembina_upacara * $uangPembinaUpacara);

    //         $row->total_rp = 'Rp. ' . number_format($total, 0, ',', '.');

    //         return $row;
    //     });

    //     $pageTitle = "Rekap Honor Guru";
    //     $profil = \App\Models\ProfilSekolah::first();
    //     $isGenerated = true;

    //     return view('staff.rekap_honor_guru', compact(
    //         'pageTitle', 'profil', 'rekap', 'periodeBulan', 'jumlahMinggu', 'isGenerated'
    //     ));
    // }

    public function generate(Request $request)
    {
        $periodeBulan = $request->periode_bulan;
        $startDate = Carbon::createFromFormat('Y-m', $periodeBulan)->startOfMonth();
        $endDate   = Carbon::createFromFormat('Y-m', $periodeBulan)->endOfMonth();
        $jumlahMinggu = $startDate->diffInWeeks($endDate) + 1;

        // Nominal (hapus format Rp dan titik)
        $uangTransport       = (int) preg_replace('/\D/', '', $request->uang_transport ?? 0);
        $uangJamMati         = (int) preg_replace('/\D/', '', $request->uang_jam_mati ?? 0);
        $uangApel            = (int) preg_replace('/\D/', '', $request->uang_apel ?? 0);
        $uangUpacara         = (int) preg_replace('/\D/', '', $request->uang_upacara ?? 0);
        $uangPembinaApel     = (int) preg_replace('/\D/', '', $request->uang_pembina_apel ?? 0);
        $uangPembinaUpacara  = (int) preg_replace('/\D/', '', $request->uang_pembina_upacara ?? 0);
        $uangSakit           = (int) preg_replace('/\D/', '', $request->uang_sakit ?? 0);
        $uangIzin            = (int) preg_replace('/\D/', '', $request->uang_izin ?? 0);

        // Daftar guru
        $guruData = \DB::table('data_guru as dg')
            ->join('users as u', 'dg.user_id', '=', 'u.id')
            ->leftJoin('jadwal_guru as j', 'dg.id', '=', 'j.guru_id')
            ->select(
                'dg.id as guru_id',
                'u.name',
                \DB::raw('COUNT(j.id) as jumlah_jam_guru')
            )
            ->groupBy('dg.id', 'u.name')
            ->get();

        $rekap = $guruData->map(function ($row) use (
            $jumlahMinggu,
            $uangTransport, $uangJamMati,
            $uangApel, $uangUpacara, $uangPembinaApel, $uangPembinaUpacara,
            $uangSakit, $uangIzin,
            $startDate, $endDate
        ) {
            // Hitung jam mati
            $row->jam_mati = $row->jumlah_jam_guru * $jumlahMinggu;

            // Jumlah kehadiran (Hadir)
            $row->jumlah_jam_hadir = \DB::table('presensi_guru as p')
                ->join('jadwal_guru as j', 'p.jadwal_id', '=', 'j.id')
                ->where('p.guru_id', $row->guru_id)
                ->where('p.keterangan', 'Hadir')
                ->whereBetween(
                    \DB::raw("STR_TO_DATE(CONCAT(p.tahun,'-',p.bulan,'-',p.tanggal), '%Y-%m-%d')"),
                    [$startDate, $endDate]
                )
                ->count();

            // Tambahan baru: Jumlah Sakit
            $row->jumlah_sakit = \DB::table('presensi_guru as p')
                ->where('p.guru_id', $row->guru_id)
                ->where('p.keterangan', 'Sakit')
                ->whereBetween(
                    \DB::raw("STR_TO_DATE(CONCAT(p.tahun,'-',p.bulan,'-',p.tanggal), '%Y-%m-%d')"),
                    [$startDate, $endDate]
                )
                ->count();

            // Tambahan baru: Jumlah Izin
            $row->jumlah_izin = \DB::table('presensi_guru as p')
                ->where('p.guru_id', $row->guru_id)
                ->where('p.keterangan', 'Izin')
                ->whereBetween(
                    \DB::raw("STR_TO_DATE(CONCAT(p.tahun,'-',p.bulan,'-',p.tanggal), '%Y-%m-%d')"),
                    [$startDate, $endDate]
                )
                ->count();

            // Kehadiran apel dan upacara
            $row->jumlah_apel = \DB::table('presensi_guru')
                ->where('guru_id', $row->guru_id)
                ->where('apel', 'Apel')
                ->whereBetween(
                    \DB::raw("STR_TO_DATE(CONCAT(tahun,'-',bulan,'-',tanggal), '%Y-%m-%d')"),
                    [$startDate, $endDate]
                )
                ->count();

            $row->jumlah_pembina_apel = \DB::table('presensi_guru')
                ->where('guru_id', $row->guru_id)
                ->where('apel', 'Pembina Apel')
                ->whereBetween(
                    \DB::raw("STR_TO_DATE(CONCAT(tahun,'-',bulan,'-',tanggal), '%Y-%m-%d')"),
                    [$startDate, $endDate]
                )
                ->count();

            $row->jumlah_upacara = \DB::table('presensi_guru')
                ->where('guru_id', $row->guru_id)
                ->where('upacara', 'Upacara')
                ->whereBetween(
                    \DB::raw("STR_TO_DATE(CONCAT(tahun,'-',bulan,'-',tanggal), '%Y-%m-%d')"),
                    [$startDate, $endDate]
                )
                ->count();

            $row->jumlah_pembina_upacara = \DB::table('presensi_guru')
                ->where('guru_id', $row->guru_id)
                ->where('upacara', 'Pembina Upacara')
                ->whereBetween(
                    \DB::raw("STR_TO_DATE(CONCAT(tahun,'-',bulan,'-',tanggal), '%Y-%m-%d')"),
                    [$startDate, $endDate]
                )
                ->count();

            // Hitung total honor
            $total =
                ($row->jumlah_jam_hadir * $uangTransport) +
                ($row->jam_mati * $uangJamMati) +
                ($row->jumlah_apel * $uangApel) +
                ($row->jumlah_upacara * $uangUpacara) +
                ($row->jumlah_pembina_apel * $uangPembinaApel) +
                ($row->jumlah_pembina_upacara * $uangPembinaUpacara) +
                ($row->jumlah_sakit * $uangSakit) +   // 💊 Tambahan
                ($row->jumlah_izin * $uangIzin);      // 📝 Tambahan

            $row->total_rp = 'Rp. ' . number_format($total, 0, ',', '.');

            return $row;
        });

        $pageTitle = "Rekap Honor Guru";
        $profil = \App\Models\ProfilSekolah::first();
        $isGenerated = true;

        return view('staff.rekap_honor_guru', compact(
            'pageTitle', 'profil', 'rekap', 'periodeBulan', 'jumlahMinggu', 'isGenerated'
        ));
    }
}
