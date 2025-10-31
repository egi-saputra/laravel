<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataStruktural;
use App\Models\User;
use Illuminate\Http\Request;

class DataStrukturalController extends Controller
{
    /**
     * Tampilkan daftar data struktural
     */
    public function index()
    {
        $struktural = DataStruktural::with('user')->get();
        $pageTitle = 'Data Struktural';

        // Ambil semua user dengan role guru, staff, atau admin
        $gurus = User::whereIn('role', ['guru', 'staff', 'admin'])
            ->orderBy('name')
            ->get();

        return view('admin.struktural', compact('struktural', 'gurus', 'pageTitle'));
    }

    /**
     * Simpan data struktural baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'jabatan'  => 'required|string|max:255',
            'nama_gtk' => 'required|exists:users,id',
        ]);

        DataStruktural::create([
            'jabatan'  => $request->jabatan,
            'nama_gtk' => $request->nama_gtk,
        ]);

        return back()->with('alert', [
            'message' => 'Data struktural berhasil ditambahkan!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    /**
     * Form edit data struktural
     */
    public function edit(DataStruktural $struktural)
    {
        $gurus = User::whereIn('role', ['guru', 'staff', 'admin'])
            ->orderBy('name')
            ->get();

        return view('admin.struktural.edit', compact('struktural', 'gurus'));
    }

    /**
     * Update data struktural
     */
    public function update(Request $request, DataStruktural $struktural)
    {
        $request->validate([
            'jabatan'  => 'required|string|max:255',
            'nama_gtk' => 'required|exists:users,id',
        ]);

        $struktural->update([
            'jabatan'  => $request->jabatan,
            'nama_gtk' => $request->nama_gtk,
        ]);

        return back()->with('alert', [
            'message' => 'Data struktural berhasil diperbarui!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    /**
     * Hapus data struktural
     */
    public function destroy(DataStruktural $struktural)
    {
        $struktural->delete();

        return back()->with('alert', [
            'message' => 'Data struktural berhasil dihapus!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }
}
