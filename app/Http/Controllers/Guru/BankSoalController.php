<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\BankSoal;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use App\Imports\BankSoalImport;
use App\Exports\BankSoalExport;

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
            'link_lampiran' => 'nullable|string', // ⬅ sekarang URL
            'opsi_a' => 'nullable|string',
            'opsi_b' => 'nullable|string',
            'opsi_c' => 'nullable|string',
            'opsi_d' => 'nullable|string',
            'opsi_e' => 'nullable|string',
        ]);

        BankSoal::create([
            'soal_id' => $request->soal_id,
            'soal' => $request->soal,
            'tipe_soal' => $request->tipe_soal,
            'jenis_lampiran' => $request->jenis_lampiran,
            'link_lampiran' => $request->link_lampiran ?? null, // simpan URL
            'jawaban_benar' => $request->jawaban_benar,
            'opsi_a' => $request->opsi_a,
            'opsi_b' => $request->opsi_b,
            'opsi_c' => $request->opsi_c,
            'opsi_d' => $request->opsi_d,
            'opsi_e' => $request->opsi_e,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('guru.soal.index', $request->soal_id)
            ->with('success', 'Bank Soal berhasil ditambahkan!');
    }

    public function edit(BankSoal $bankSoal)
    {
        return Inertia::render('BankSoal/Edit', [
            'bankSoal' => $bankSoal,
            'soal' => $bankSoal,
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
            'link_lampiran' => 'nullable|string', // ⬅ sekarang URL
            'opsi_a' => 'nullable|string',
            'opsi_b' => 'nullable|string',
            'opsi_c' => 'nullable|string',
            'opsi_d' => 'nullable|string',
            'opsi_e' => 'nullable|string',
        ]);

        $bankSoal->update([
            'soal' => $request->soal,
            'tipe_soal' => $request->tipe_soal,
            'jenis_lampiran' => $request->jenis_lampiran,
            'link_lampiran' => $request->link_lampiran ?? $bankSoal->link_lampiran, // tetap simpan URL lama jika kosong
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

    public function import(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls',
            'soal_id' => 'required|exists:soal,id',
        ]);

        $file = $request->file('excel');

        Excel::import(new \App\Imports\BankSoalImport($request->soal_id), $file);

        return redirect()->route('guru.soal.index', $request->soal_id)
            ->with('success', 'Bank Soal berhasil diimport dari Excel!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new BankSoalExport, 'template_bank_soal.xlsx');
    }

    public function destroy(BankSoal $bankSoal)
    {
        $soal_id = $bankSoal->soal_id;
        $bankSoal->delete();

        return redirect()->route('guru.soal.index', $soal_id)
            ->with('success', 'Bank Soal berhasil dihapus!');
    }
}
