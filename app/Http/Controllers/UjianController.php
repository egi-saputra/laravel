<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\BankSoal;
use App\Models\RiwayatUjian;
use App\Models\UjianSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UjianController extends Controller
{
    public function tokenPage()
    {
        return view('ujian.token');
    }

    // Validasi token input siswa (dari guru)
    public function validateToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $userId = Auth::id();
        $inputToken = $request->token;

        // Cek token di tabel ujian_siswa
        $ujianSiswa = UjianSiswa::where('user_id', $userId)
            ->where('token', $inputToken)
            ->first();

        if ($ujianSiswa) {
            return redirect()->route('ujian.preview', $ujianSiswa->soal_id);
        }

        // Cek token di tabel soal (token guru)
        $soal = Soal::where('token', $inputToken)->first();
        if (!$soal) {
            return back()->with('error', 'Token tidak valid.');
        }

        $soalId = $soal->id;

        // Cek apakah siswa sudah punya record
        $existing = UjianSiswa::where('user_id', $userId)
            ->where('soal_id', $soalId)
            ->first();

        if ($existing) {
            return back()->with('error', 'Token ujian sudah digunakan sebelumnya.');
        }

        // Buat record baru
        $ujianSiswa = UjianSiswa::create([
            'user_id' => $userId,
            'soal_id' => $soalId,
            'waktu_mulai' => now(),
            'status' => 'Belum Dikerjakan',
            'token' => Str::upper(Str::random(6)),
        ]);

        return redirect()->route('ujian.preview', $soalId);
    }

    public function preview($soal_id)
    {
        $soal = Soal::with(['mapel','kelas'])->findOrFail($soal_id);
        $jumlahSoal = BankSoal::where('soal_id', $soal_id)->count();

        return view('ujian.preview', compact('soal','jumlahSoal'));
    }

    /**
     * KERJAKAN — tampil 1 per 1 (multi page)
     */
    public function kerjakan(Request $request, $soal_id)
    {
        $userId = Auth::id();
        $soal = Soal::with(['mapel','kelas'])->findOrFail($soal_id);

        // Ambil atau buat ujian_siswa
        $ujianSiswa = UjianSiswa::firstOrCreate(
            ['user_id' => $userId, 'soal_id' => $soal_id],
            [
                'waktu_mulai' => now(),
                'status' => 'Sedang Dikerjakan',
                'token' => Str::upper(Str::random(6)),
            ]
        );

        // Update status jika belum dikerjakan
        if ($ujianSiswa->status === 'Belum Dikerjakan') {
            $ujianSiswa->update(['status' => 'Sedang Dikerjakan']);
        }

        // Cek jika sudah selesai
        if ($ujianSiswa->status === 'Selesai') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda sudah menyelesaikan ujian ini.');
        }

        // Ambil bank soal
        $questIds = BankSoal::where('soal_id', $soal_id)->pluck('id')->toArray();
        $nomorList = $questIds;

        // Generate riwayat soal jika belum ada
        foreach ($questIds as $qid) {
            RiwayatUjian::firstOrCreate(
                [
                    'user_id'  => $userId,
                    'soal_id'  => $soal_id,
                    'quest_id' => $qid,
                ],
                [
                    'jawaban' => null,
                    'benar'   => 0,
                    'nilai'   => 0,
                    'status'  => 'Belum Dikerjakan',
                ]
            );
        }

        // Urutan soal
        $sessionKey = "urutan_soal_{$soal_id}_{$userId}";
        if (!session()->has($sessionKey)) {
            $urutan = $questIds;
            if ($soal->tipe_soal === "Acak") shuffle($urutan);
            session([$sessionKey => $urutan]);
        }
        $urutan = session($sessionKey);
        $total  = count($urutan);

        $no = (int)($request->no ?? 1);
        $no = max(1, min($no, $total));

        $questId = $urutan[$no - 1];
        $quest   = BankSoal::findOrFail($questId);

        $riwayat = RiwayatUjian::where('user_id', $userId)
            ->where('soal_id', $soal_id)
            ->where('quest_id', $questId)
            ->first();

        // Timer
        $waktuMulai = $ujianSiswa->waktu_mulai;
        if (!($waktuMulai instanceof \Carbon\Carbon)) {
            $waktuMulai = \Carbon\Carbon::parse($waktuMulai);
        }
        $durasiMenit = $soal->waktu ?? 0;
        $waktuSelesai = $waktuMulai->copy()->addMinutes($durasiMenit);
        $sisaDetik = \Carbon\Carbon::now()->diffInSeconds($waktuSelesai, false);
        $sisaDetik = max(0, $sisaDetik);

        return view('ujian.kerjakan', [
            'soal'       => $soal,
            'totalSoal'  => $total,
            'no'         => $no,
            'quest'      => $quest,
            'riwayat'    => $riwayat,
            'ujianSiswa' => $ujianSiswa,
            'durasi'     => $soal->waktu,
            'nomorList'  => $nomorList,
            'sisaDetik'  => $sisaDetik,
        ]);
    }

    public function autosave(Request $request)
    {
        $request->validate([
            'soal_id'  => 'required',
            'quest_id' => 'required',
            'jawaban'  => 'nullable|string'
        ]);

        $userId = Auth::id();
        $quest  = BankSoal::find($request->quest_id);

        if ($quest) {
            $map = ['A'=>'opsi_a','B'=>'opsi_b','C'=>'opsi_c','D'=>'opsi_d','E'=>'opsi_e'];
            $jawab = $request->jawaban;
            $jawabanMapped = $map[$jawab] ?? null;
            $benar = ($jawabanMapped === $quest->jawaban_benar);
            $nilai = $benar ? $quest->nilai : 0;

            RiwayatUjian::where('user_id', $userId)
                ->where('soal_id', $request->soal_id)
                ->where('quest_id', $request->quest_id)
                ->update([
                    'jawaban' => $jawab,
                    'benar'   => $benar ? 1 : 0,
                    'nilai'   => $nilai,
                    'status'  => $jawab ? 'Sedang Dikerjakan' : 'Belum Dikerjakan'
                ]);
        }

        return response()->json(['success' => true]);
    }

    public function submitUjian(Request $request, $soal_id)
    {
        $userId = Auth::id();

        $ujian = UjianSiswa::where('user_id', $userId)
            ->where('soal_id', $soal_id)
            ->first();

        if ($ujian && $ujian->status === 'Selesai') {
            return redirect()->route('dashboard')->with('warning', 'Ujian sudah diselesaikan.');
        }

        $riwayat = RiwayatUjian::where('user_id',$userId)
            ->where('soal_id',$soal_id)
            ->get();

        $map = ['A'=>'opsi_a','B'=>'opsi_b','C'=>'opsi_c','D'=>'opsi_d','E'=>'opsi_e'];

        foreach ($riwayat as $item) {
            $quest = BankSoal::find($item->quest_id);
            if (!$quest) continue;

            $jawab = $item->jawaban;
            $jawabanMapped = $map[$jawab] ?? null;
            $benar = ($jawabanMapped === $quest->jawaban_benar);
            $nilai = $benar ? $quest->nilai : 0;

            $item->update([
                'benar'  => $benar ? 1 : 0,
                'nilai'  => $nilai,
                'status' => 'Selesai'
            ]);
        }

        $ujian->update([
            'status' => 'Selesai',
            'waktu_selesai' => now()
        ]);

        session()->forget("urutan_soal_{$soal_id}_{$userId}");

        return redirect()->route('dashboard')->with('success', 'Ujian berhasil disubmit!');
    }

    public function refreshToken(Request $request, $soal_id)
    {
        $userId = Auth::id();

        $ujian = UjianSiswa::where('user_id', $userId)
            ->where('soal_id', $soal_id)
            ->first();

        if ($ujian) {
            $ujian->update(['token' => Str::upper(Str::random(6))]);
        }

        return response()->json(['success' => true]);
    }
}
