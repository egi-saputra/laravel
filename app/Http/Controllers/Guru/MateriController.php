<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\DataKelas;
use App\Models\DataMapel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MateriController extends Controller
{
    /**
     * Tampilkan daftar materi milik guru yang login.
     */
    public function index()
    {
        $materis = Materi::with(['user', 'kelas', 'mapel'])
            ->where('user_id', auth()->id()) // hanya materi milik guru ini
            ->latest()
            ->paginate(10);

        $kelas = DataKelas::all();
        $mapel = DataMapel::all();

        return view('guru.materi', compact('materis', 'kelas', 'mapel'));
    }

    /**
     * Simpan materi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:data_kelas,id',
            'mapel_id' => 'required|exists:data_mapel,id',
            'judul'    => 'required|string|max:255',
            'materi'   => 'nullable|string',
            'file'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,webp,png|max:10240',
        ]);

        $data = [
            'user_id'  => auth()->id(),
            'kelas_id' => $validated['kelas_id'],
            'mapel_id' => $validated['mapel_id'],
            'judul'    => $validated['judul'],
            'materi'   => $validated['materi'] ?? null,
        ];

        // Upload file jika ada
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $slugName = Str::slug($validated['judul'], '_');
            $fileName = $slugName . '_' . time() . '.' . $extension; // Tambahkan timestamp

            $filePath = $file->storeAs('materi', $fileName, 'public');

            $data['file_name'] = $fileName;
            $data['file_path'] = 'storage/' . $filePath;
        }

        Materi::create($data);

        return redirect()->route('guru.materi.index')
            ->with('alert', [
                'message' => 'Materi berhasil dibuat!',
                'type'    => 'success',
                'title'   => 'Berhasil',
            ]);
    }

    /**
     * Update materi.
     */
    public function update(Request $request, $id)
    {
        // Ambil materi milik guru ini, jika bukan miliknya maka 404
        $materi = Materi::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        $validated = $request->validate([
            'kelas_id' => 'required|exists:data_kelas,id',
            'mapel_id' => 'required|exists:data_mapel,id',
            'judul'    => 'required|string|max:255',
            'materi'   => 'nullable|string',
            'file'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,webp,png|max:10240',
        ]);

        $data = [
            'kelas_id' => $validated['kelas_id'],
            'mapel_id' => $validated['mapel_id'],
            'judul'    => $validated['judul'],
            'materi'   => $validated['materi'] ?? null,
        ];

        // Upload file baru jika ada
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($materi->file_path && Storage::disk('public')->exists(str_replace('storage/', '', $materi->file_path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $materi->file_path));
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $slugName = Str::slug($validated['judul'], '_');
            $fileName = $slugName . '_' . time() . '.' . $extension; // Tambahkan timestamp

            $filePath = $file->storeAs('materi', $fileName, 'public');

            $data['file_name'] = $fileName;
            $data['file_path'] = 'storage/' . $filePath;
        }

        $materi->update($data);

        return redirect()->route('guru.materi.index')
            ->with('alert', [
                'message' => 'Materi berhasil diperbarui!',
                'type'    => 'success',
                'title'   => 'Berhasil',
            ]);
    }

    /**
     * Hapus materi.
     */
    public function destroy($id)
    {
        $materi = Materi::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        if ($materi->file_path && Storage::disk('public')->exists(str_replace('storage/', '', $materi->file_path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $materi->file_path));
        }

        $materi->delete();

        return redirect()->route('guru.materi.index')
            ->with('alert', [
                'message' => 'Materi berhasil dihapus!',
                'type'    => 'success',
                'title'   => 'Berhasil',
            ]);
    }

    /**
     * Hapus semua materi milik guru yang login.
     */
    public function destroyAll()
    {
        $materiList = Materi::where('user_id', auth()->id())->get();

        foreach ($materiList as $materi) {
            if ($materi->file_path && Storage::disk('public')->exists(str_replace('storage/', '', $materi->file_path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $materi->file_path));
            }
            $materi->delete();
        }

        return back()->with('alert', [
            'message' => 'Semua materi berhasil dihapus!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    /**
     * Tampilkan file materi.
     */
    public function view_file_materi($id)
    {
        $materi = Materi::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        return view('guru.view_file_materi', compact('materi'));
    }
}
