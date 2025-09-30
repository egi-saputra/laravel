<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RekapHonorStaffController extends Controller
{
    public function index()
    {
        $pageTitle = "Rekap Honor Staff";
        $profil = \App\Models\ProfilSekolah::first();

        $rekap = collect();
        $periodeBulan = null;
        $isGenerated = false;
        $uangJam = 0;
        $uangApel = 0;
        $uangUpacara = 0;

        return view('staff.rekap_honor_staff', compact(
            'pageTitle', 'profil', 'rekap', 'periodeBulan', 'isGenerated',
            'uangJam', 'uangApel', 'uangUpacara' // <-- tambahkan ini
        ));
    }

    public function generate(Request $request)
    {
        $periodeBulan = $request->periode_bulan;
        $startDate = Carbon::createFromFormat('Y-m', $periodeBulan)->startOfMonth();
        $endDate   = Carbon::createFromFormat('Y-m', $periodeBulan)->endOfMonth();

        // Bersihkan titik supaya jadi angka murni
        $uangJam     = (int) str_replace('.', '', $request->uang_jam);
        $uangApel    = (int) str_replace('.', '', $request->uang_apel);
        $uangUpacara = (int) str_replace('.', '', $request->uang_upacara);

        // Ambil rekap presensi staff
        $rekap = DB::table('presensi_staff')
            ->join('users', 'presensi_staff.staff_id', '=', 'users.id')
            ->select(
                'users.name',
                DB::raw("COUNT(DISTINCT CASE WHEN presensi_staff.keterangan = 'Hadir' THEN DATE(presensi_staff.created_at) END) as jumlah_hadir"),
                DB::raw("COUNT(DISTINCT CASE WHEN presensi_staff.apel = 'Apel' THEN DATE(presensi_staff.created_at) END) as jumlah_apel"),
                DB::raw("COUNT(DISTINCT CASE WHEN presensi_staff.upacara = 'Upacara' THEN DATE(presensi_staff.created_at) END) as jumlah_upacara")
            )
            ->whereBetween('presensi_staff.created_at', [
                $startDate->format('Y-m-d 00:00:00'),
                $endDate->format('Y-m-d 23:59:59')
            ])
            ->groupBy('users.name')
            ->get();

        // Hitung total honor
        $rekap = $rekap->map(function ($row) use ($uangJam, $uangApel, $uangUpacara) {
            $row->total = ($row->jumlah_hadir * $uangJam)
                        + ($row->jumlah_apel * $uangApel)
                        + ($row->jumlah_upacara * $uangUpacara);
            return $row;
        });

        $pageTitle = "Rekap Honor Staff";
        $profil = \App\Models\ProfilSekolah::first();
        $isGenerated = true;

        return view('staff.rekap_honor_staff', compact(
            'pageTitle', 'profil', 'rekap', 'periodeBulan', 'isGenerated',
            'uangJam', 'uangApel', 'uangUpacara'
        ));
    }

}
