<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class DataSekolahController extends Controller
{
    public function index()
    {
        $profil = ProfilSekolah::first();
        return view('admin.profil_sekolah', compact('profil'));
    }

    // public function storeOrUpdate(Request $request)
    // {
    //     $validated = $request->validate([
    //         'nama_sekolah'    => 'required|string|max:255',
    //         'kepala_yayasan'  => 'nullable|string|max:255',
    //         'kepala_sekolah'  => 'nullable|string|max:255',
    //         'akreditasi'      => 'nullable|string|max:50',
    //         'npsn'            => 'nullable|string|max:50',
    //         'no_izin'         => 'nullable|string|max:50',
    //         'nss'         => 'nullable|string|max:50',
    //         'alamat'          => 'nullable|string',
    //         'rt'              => 'nullable|string|max:5',
    //         'rw'              => 'nullable|string|max:5',
    //         'kelurahan'       => 'nullable|string|max:100',
    //         'kecamatan'       => 'nullable|string|max:100',
    //         'kabupaten_kota'  => 'nullable|string|max:100',
    //         'provinsi'        => 'nullable|string|max:100',
    //         'kode_pos'        => 'nullable|string|max:10',
    //         'telepon'         => 'nullable|string|max:20',
    //         'email'           => 'nullable|email',
    //         'website'         => 'nullable|string|max:255',
    //         'visi'            => 'nullable|string',
    //         'misi'            => 'nullable|string',
    //         'logo'            => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
    //     ]);

    //     // Ambil data lama
    //     $profil = ProfilSekolah::first();

    //     // Upload logo baru
    //     if ($request->hasFile('logo')) {
    //         $file = $request->file('logo');
    //         // simpan di 'public/logo_sekolah', tapi simpan path relatif tanpa 'public/'
    //         $path = $file->store('logo_sekolah', 'public'); // 'public' disk
    //         $validated['file_name'] = $file->getClientOriginalName();
    //         $validated['file_path'] = $path; // path relatif: logo_sekolah/nama_file.png

    //         // hapus logo lama kalau ada
    //         if ($profil && $profil->file_path && Storage::disk('public')->exists($profil->file_path)) {
    //             Storage::disk('public')->delete($profil->file_path);
    //         }
    //     }

    //     // Update kalau ada, kalau tidak insert
    //     if ($profil) {
    //         $profil->update($validated);
    //     } else {
    //         ProfilSekolah::create($validated);
    //     }

    //     return back()->with('alert', [
    //         'type' => 'success',
    //         'title' => 'Berhasil',
    //         'message' => 'Profil sekolah berhasil diperbarui!'
    //     ]);
    // }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'nama_sekolah'    => 'required|string|max:255',
            'kepala_yayasan'  => 'nullable|string|max:255',
            'kepala_sekolah'  => 'nullable|string|max:255',
            'akreditasi'      => 'nullable|string|max:50',
            'npsn'            => 'nullable|string|max:50',
            'no_izin'         => 'nullable|string|max:50',
            'nss'             => 'nullable|string|max:50',
            'alamat'          => 'nullable|string',
            'rt'              => 'nullable|string|max:5',
            'rw'              => 'nullable|string|max:5',
            'kelurahan'       => 'nullable|string|max:100',
            'kecamatan'       => 'nullable|string|max:100',
            'kabupaten_kota'  => 'nullable|string|max:100',
            'provinsi'        => 'nullable|string|max:100',
            'kode_pos'        => 'nullable|string|max:10',
            'telepon'         => 'nullable|string|max:20',
            'email'           => 'nullable|email',
            'website'         => 'nullable|string|max:255',
            'visi'            => 'nullable|string',
            'misi'            => 'nullable|string',
            'logo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $profil = ProfilSekolah::first();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = uniqid('logo_') . '.webp';
            $path = 'logo_sekolah/' . $filename;

            try {
                $manager = new ImageManager(new Driver());

                $image = $manager->read($file)
                    ->scale(width: 500) // otomatis jaga aspect ratio
                    ->toWebp(80);

                Storage::disk('public')->put($path, (string) $image);

                $validated['file_name'] = $file->getClientOriginalName();
                $validated['file_path'] = $path;

                if ($profil && $profil->file_path && Storage::disk('public')->exists($profil->file_path)) {
                    Storage::disk('public')->delete($profil->file_path);
                }
            } catch (\Exception $e) {
                return back()->with('alert', [
                    'type' => 'error',
                    'title' => 'Gagal Upload',
                    'message' => 'Logo tidak bisa diproses: ' . $e->getMessage()
                ]);
            }
        }

        $profil ? $profil->update($validated) : ProfilSekolah::create($validated);

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Profil sekolah berhasil diperbarui!'
        ]);
    }

}
