<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\DataMapel;
use App\Models\DataKelas;
use Inertia\Inertia;

class SoalController extends Controller
{
    // Tampilkan daftar soal
    public function index()
    {
        $soal = Soal::with(['mapel', 'kelas'])->paginate(10);

        return Inertia::render('Soal/Index', [
            'soal' => $soal,
            'dashboardUrl' => route('guru.dashboard'),
            'success' => session('success'),
        ]);
    }

    // Halaman create
    public function create()
    {
        return Inertia::render('Soal/Create', [
            'mapel' => DataMapel::select('id', 'mapel')->get(),
            'kelas' => DataKelas::select('id', 'kelas')->get(),
        ]);
    }

    // Simpan data ke database
    public function store(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:data_mapel,id',
            'kelas_id' => 'required|exists:data_kelas,id',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'tipe_soal' => 'required|in:Berurutan,Acak',
            'waktu' => 'required|integer|min:1',
        ]);

        Soal::create($request->all());

        // Load ulang data untuk halaman index
        $soal = Soal::with(['mapel', 'kelas'])->paginate(10);

        return Inertia::render('Soal/Index', [
            'soal' => $soal,
            'dashboardUrl' => route('guru.dashboard'),
            'success' => 'Soal berhasil dibuat!'
        ]);

    }

    // Edit
    public function edit(Soal $soal)
    {
        return Inertia::render('Soal/Edit', [
            'soal' => $soal,
            'mapel' => DataMapel::select('id', 'mapel')->get(),
            'kelas' => DataKelas::select('id', 'kelas')->get(),
        ]);
    }

    // Update
    public function update(Request $request, Soal $soal)
    {
        $request->validate([
            'mapel_id' => 'required|exists:data_mapel,id',
            'kelas_id' => 'required|exists:data_kelas,id',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'tipe_soal' => 'required|in:Berurutan,Acak',
            'waktu' => 'required|integer|min:1',
        ]);

        $soal->update($request->all());

        $soalList = Soal::with(['mapel', 'kelas'])->paginate(10);

        return Inertia::render('Soal/Index', [
            'soal' => $soalList,
            'dashboardUrl' => route('guru.dashboard'),
            'success' => 'Soal berhasil diperbarui!'
        ]);
    }

    // Hapus data
    public function destroy(Soal $soal)
    {
        $soal->delete();

        return back()->with('success', 'Soal berhasil dihapus!');
    }

public function show(Soal $soal)
{
    $soal->load('mapel', 'kelas', 'bankSoal'); // pastikan memuat relasi bank_soal
    return Inertia::render('Soal/Show', [
        'soal' => $soal,
    ]);
}
}
