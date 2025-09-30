<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSiswa;
use App\Models\DataKelas;
use App\Models\DataKejuruan;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function dashboard()
    {
        return view('siswa.dashboard');
    }

    // Tampilkan daftar atau form input
    public function index()
    {
        $siswa = DataSiswa::where('user_id', Auth::id())->first();

        $kelas = DataKelas::all();
        $kejuruan = DataKejuruan::all();

        return view('siswa.data_diri', compact('siswa','kelas','kejuruan'));
    }

    public function create()
    {
        $kelas = DataKelas::all();
        $kejuruan = DataKejuruan::all();
        return view('siswa.data_diri.create', compact('kelas','kejuruan'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:191',
            'tempat_tanggal_lahir' => 'required|string|max:255',
            'asal_sekolah' => 'nullable|string|max:255',
            'nis' => 'nullable|string|max:10|unique:data_siswa,nis',
            'nisn' => 'nullable|string|max:50|unique:data_siswa,nisn',
            'jenis_kelamin' => 'nullable|string|max:20',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'kecamatan' => 'nullable|string|max:255',
            'kota_kabupaten' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20',
            'kelas_id' => 'nullable|exists:data_kelas,id',
            'kejuruan_id' => 'nullable|exists:program_kejuruan,id',
        ]);

        $data['user_id'] = Auth::id();

        DataSiswa::create($data);

        return redirect()->route('siswa.data_diri')->with('alert', [
            'type' => 'success',
            'message' => 'Data siswa berhasil disimpan!'
        ]);
    }

    public function edit(DataSiswa $data_diri)
    {
        $kelas = DataKelas::all();
        $kejuruan = DataKejuruan::all();
        return view('siswa.data_diri.edit', compact('data_diri','kelas','kejuruan'));
    }

    public function update(Request $request, DataSiswa $data_diri)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:191',
            'tempat_tanggal_lahir' => 'required|string|max:255',
            'asal_sekolah' => 'nullable|string|max:255',
            'nis' => 'nullable|string|max:10|unique:data_siswa,nis,'.$data_diri->id,
            'nisn' => 'nullable|string|max:50|unique:data_siswa,nisn,'.$data_diri->id,
            'jenis_kelamin' => 'nullable|string|max:20',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'kecamatan' => 'nullable|string|max:255',
            'kota_kabupaten' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20',
            'kelas_id' => 'nullable|exists:data_kelas,id',
            'kejuruan_id' => 'nullable|exists:program_kejuruan,id',
        ]);

        $data_diri->update($data);

        return redirect()->route('siswa.data_diri')->with('alert', [
            'type' => 'success',
            'message' => 'Data siswa berhasil diperbarui!'
        ]);
    }

    public function destroy(DataSiswa $data_diri)
    {
        $data_diri->delete();
        return redirect()->route('siswa.data_diri')->with('alert', [
            'type' => 'success',
            'message' => 'Data siswa berhasil dihapus!'
        ]);
    }

    public function sync()
    {
        $siswa = DataSiswa::where('user_id', auth()->id())->first();

        if (!$siswa) {
            return response()->json([
                'error' => 'Data diri belum diisi',
            ], 400);
        }

        $siswa->update(['status' => 'Valid']);

        return response()->json([
            'status' => $siswa->status,
            'updated_at' => $siswa->updated_at->format('d M Y H:i'),
        ]);
    }

}
