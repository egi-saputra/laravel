<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\BankSoal; // ⬅️ WAJIB ditambahkan
use Inertia\Inertia;

class BankSoalController extends Controller
{
    public function create(Request $request)
    {
        return Inertia::render('BankSoal/Create', [
            'soal_id' => $request->soal_id
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'soal_id' => 'required|exists:soal,id',
            'soal' => 'required|string',
            'tipe_soal' => 'required|in:PG,Essay',
            'jawaban_benar' => 'nullable|string',
            'nilai' => 'required|numeric',
            'jenis_lampiran' => 'nullable|string',
            'link_lampiran' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mp3,pdf|max:4096',
            'opsi_a' => 'nullable|string',
            'opsi_b' => 'nullable|string',
            'opsi_c' => 'nullable|string',
            'opsi_d' => 'nullable|string',
            'opsi_e' => 'nullable|string',
        ]);

        // Default null jika tidak ada upload
        $lampiranPath = null;

        // Upload file jika ada
        if ($request->hasFile('link_lampiran')) {
            $file = $request->file('link_lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/lampiran'), $filename);
            $lampiranPath = 'uploads/lampiran/' . $filename;
        }

        // Simpan ke database
        BankSoal::create([
            'soal_id' => $request->soal_id,
            'soal' => $request->soal, // Teks Pertanyaan
            'tipe_soal' => $request->tipe_soal,
            'jenis_lampiran' => $request->jenis_lampiran,
            'link_lampiran' => $lampiranPath,
            'jawaban_benar' => $request->jawaban_benar,
            'opsi_a' => $request->opsi_a,
            'opsi_b' => $request->opsi_b,
            'opsi_c' => $request->opsi_c,
            'opsi_d' => $request->opsi_d,
            'opsi_e' => $request->opsi_e,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('guru.soal.show', $request->soal_id)
            ->with('success', 'Bank Soal berhasil ditambahkan!');
    }

    public function edit(BankSoal $bankSoal)
{
    return Inertia::render('BankSoal/Edit', [
        'bankSoal' => $bankSoal,
        'soal' => $bankSoal->soal, // optional, kalau mau tampilkan info soal parent
    ]);
}

public function update(Request $request, BankSoal $bankSoal)
{
    $request->validate([
        'soal' => 'required|string',
        'tipe_soal' => 'required|in:PG,Essay',
        'jawaban_benar' => 'nullable|string',
        'nilai' => 'required|numeric',
        'jenis_lampiran' => 'nullable|string',
        'link_lampiran' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mp3,pdf|max:4096',
        'opsi_a' => 'nullable|string',
        'opsi_b' => 'nullable|string',
        'opsi_c' => 'nullable|string',
        'opsi_d' => 'nullable|string',
        'opsi_e' => 'nullable|string',
    ]);

    // Upload file jika ada
    if ($request->hasFile('link_lampiran')) {
        $file = $request->file('link_lampiran');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/lampiran'), $filename);
        $bankSoal->link_lampiran = 'uploads/lampiran/' . $filename;
    }

    $bankSoal->update([
        'soal' => $request->soal,
        'tipe_soal' => $request->tipe_soal,
        'jenis_lampiran' => $request->jenis_lampiran,
        'jawaban_benar' => $request->jawaban_benar,
        'opsi_a' => $request->opsi_a,
        'opsi_b' => $request->opsi_b,
        'opsi_c' => $request->opsi_c,
        'opsi_d' => $request->opsi_d,
        'opsi_e' => $request->opsi_e,
        'nilai' => $request->nilai,
    ]);

    return redirect()->route('guru.soal.show', $bankSoal->soal_id)
        ->with('success', 'Bank Soal berhasil diperbarui!');
}

public function destroy(BankSoal $bankSoal)
{
    $soal_id = $bankSoal->soal_id;
    $bankSoal->delete();

    return redirect()->route('guru.soal.show', $soal_id)
        ->with('success', 'Bank Soal berhasil dihapus!');
}

}
