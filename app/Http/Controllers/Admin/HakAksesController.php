<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HakAkses;
use App\Models\DataGuru;

class HakAksesController extends Controller
{
    // Tampilkan halaman daftar guru
    public function index()
    {
        $guru = DataGuru::with(['user', 'hakAkses'])->get();
        return view('admin.akses', compact('guru'));
    }

    // Simpan data hak akses manual
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:data_guru,id',
            'status'  => 'required|in:Activated,Deactivated',
        ]);

        HakAkses::updateOrCreate(
            ['guru_id' => $request->guru_id],
            ['status'  => $request->status]
        );

        return redirect()->back()->with('success', 'Hak akses berhasil ditambahkan.');
    }

    public function toggleStatus(Request $request)
{
    try {
        $data = $request->validate([
            'guru_id' => 'required|exists:data_guru,id',
            'status'  => 'required|in:Activated,Deactivated',
        ]);

        $hakAkses = HakAkses::updateOrCreate(
            ['guru_id' => $data['guru_id']],
            ['status'  => $data['status']]
        );

        return response()->json([
            'success' => true,
            'status'  => $hakAkses->status,
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // kirim detail validasi
        return response()->json([
            'success' => false,
            'errors'  => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        \Log::error('HakAkses::toggleStatus - ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage(),
        ], 500);
    }
}

}
