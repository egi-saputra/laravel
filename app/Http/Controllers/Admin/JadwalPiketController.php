<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataGuru;
use App\Models\JadwalPiket;
use Illuminate\Http\Request;

class JadwalPiketController extends Controller
{
    public function index()
    {
        $guru = DataGuru::with('user')->get();
        $jadwalPiket = JadwalPiket::with('user')->get();

        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        return view('admin.jadwal_piket', [
            'guru' => $guru,
            'jadwalPiket' => $jadwalPiket,
            'pageTitle' => 'Jadwal Piket',
            'hariList' => $hariList
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (JadwalPiket::where('hari', $value)->exists()) {
                        $fail("Hari $value sudah dipakai.");
                    }
                }
            ],
            'petugas' => [
                'nullable',
                'exists:data_guru,id',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && JadwalPiket::where('petugas', $value)->exists()) {
                        $fail("Guru ini sudah memiliki jadwal piket.");
                    }
                }
            ],
        ]);

        JadwalPiket::create($request->only('hari', 'petugas'));

        return back()->with('alert', [
            'message' => 'Jadwal piket berhasil ditambahkan!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    public function edit($id)
    {
        $jadwal = JadwalPiket::findOrFail($id);
        $guruUsers = DataGuru::with('user')->get();
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        return view('admin.jadwal.edit', compact('jadwal', 'guruUsers', 'hariList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hari' => [
                'required',
                function ($attribute, $value, $fail) use ($id) {
                    if (JadwalPiket::where('hari', $value)->where('id', '!=', $id)->exists()) {
                        $fail("Hari $value sudah dipakai.");
                    }
                }
            ],
            'petugas' => [
                'nullable',
                'exists:data_guru,id',
                function ($attribute, $value, $fail) use ($id) {
                    if (!empty($value) && JadwalPiket::where('petugas', $value)->where('id', '!=', $id)->exists()) {
                        $fail("Guru ini sudah memiliki jadwal piket.");
                    }
                }
            ],
        ]);

        $jadwal = JadwalPiket::findOrFail($id);
        $jadwal->update($request->only('hari', 'petugas'));

        return back()->with('alert', [
            'message' => 'Jadwal piket berhasil diperbarui!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    public function destroy($id)
    {
        $jadwal = JadwalPiket::findOrFail($id);
        $jadwal->delete();

        return back()->with('alert', [
            'message' => 'Jadwal piket berhasil dihapus!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    public function destroyAll()
    {
        JadwalPiket::query()->delete();

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Semua jadwal piket berhasil dihapus.'
        ]);
    }
}
