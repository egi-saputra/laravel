<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FotoProfil;
use Illuminate\Support\Facades\Storage;

class FotoProfilController extends Controller
{
    public function upload(Request $request)
    {
        // Validasi: minimal 5MB (5120 KB), maksimal 10MB (opsional)
        $request->validate([
            // 'foto' => 'required|image|min:5120|max:10240', // min:5MB, max:10MB
            'foto' => 'required|image|max:10240',
        ], [
            'foto.required' => 'Silakan pilih file foto terlebih dahulu.',
            'foto.image' => 'File harus berupa gambar (jpg, png, jpeg, dll).',
            // 'foto.min' => 'Ukuran foto minimal 5MB.',
            'foto.max' => 'Ukuran foto maksimal 10MB.'
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        $oldFoto = FotoProfil::where('user_id', $user->id)->first();
        if ($oldFoto && Storage::exists($oldFoto->file_path)) {
            Storage::delete($oldFoto->file_path);
        }

        // Upload foto baru
        $file = $request->file('foto');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Simpan ke disk "public"
        $filePath = $file->storeAs('foto_profil', $fileName, 'public');

        // Simpan ke database (hanya relative path)
        FotoProfil::updateOrCreate(
            ['user_id' => $user->id],
            [
                'file_name' => $fileName,
                'file_path' => $filePath, // contoh: "foto_profil/nama_file.jpg"
            ]
        );

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Foto berhasil disimpan!'
        ]);

    }

    public function remove(Request $request)
    {
        $user = auth()->user();

        if ($user->foto_profil) {
            // Hapus file dari storage
            Storage::delete($user->foto_profil->file_path);

            // Hapus record dari database
            $user->foto_profil()->delete();
        }

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Foto profil berhasil dihapus.'
        ]);

    }
}
