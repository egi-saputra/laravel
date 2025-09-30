<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DataSiswa;
use App\Models\DataGuru;
use Illuminate\Support\Facades\Hash;
use App\Exports\UserExport;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all(); // semua user
        // ubah role menjadi lowercase agar konsisten
        $users->transform(function($user) {
            $user->role = strtolower($user->role);
            return $user;
        });

        return view('admin.users.index', [
            'users' => $users,
            'pageTitle' => 'Manajemen User'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:user,siswa,guru,staff,admin'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Jika role siswa, buat data_siswa otomatis
        if ($user->role === 'siswa') {
            DataSiswa::create([
                'user_id'      => $user->id,
                'nama_lengkap' => $request->name, // atau ambil dari input nama
                'nisn'         => $request->nisn ?? null, // bisa opsional
                'kelas_id'     => $request->kelas_id ?? null,
                'kejuruan_id'  => $request->kejuruan_id ?? null,
                'jenis_kelamin'=> $request->jenis_kelamin ?? null,
                'agama'        => $request->agama ?? null,
                'status'       => 'Aktif',
            ]);
        }

        // Jika role guru, buat DataGuru
        if ($user->role === 'guru') {
            \App\Models\DataGuru::create([
                'user_id' => $user->id,
                'nama'    => $user->name,
                'kode'    => 'G' . str_pad($user->id, 3, '0', STR_PAD_LEFT), // kode otomatis
            ]);
        }

        return back()->with('alert', [
            'message' => 'User baru berhasil ditambahkan!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:user,siswa,guru,staff,admin'],
            'new_password' => ['nullable', 'string', 'min:6']
        ]);

        $oldRole = $user->role;
        $user->role = $request->role;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
            $message = 'Role dan password pengguna berhasil diperbarui!';
        } else {
            $message = 'Role pengguna berhasil diperbarui!';
        }

        $user->save();

        // Jika role diubah menjadi siswa dan belum ada DataSiswa
        if ($user->role === 'siswa' && !DataSiswa::where('user_id', $user->id)->exists()) {
            DataSiswa::create([
                'user_id' => $user->id,
                'nama_lengkap' => $user->name,
                'status' => 'Aktif',
            ]);
        }

        // Jika role diubah menjadi guru dan belum ada DataGuru
        if ($user->role === 'guru' && !\App\Models\DataGuru::where('user_id', $user->id)->exists()) {
            \App\Models\DataGuru::create([
                'user_id' => $user->id,
                'nama'    => $user->name,
                'kode'    => 'G' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
            ]);
        }

        return back()->with('alert', [
            'message' => $message,
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Optional: jangan izinkan hapus super admin
        if ($user->role === 'admin' && $user->id == 1) {
                return redirect()->back()->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Super Admin tidak boleh dihapus!'
            ]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
        ->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'User berhasil dihapus!'
        ]);
    }

    public function export()
    {
        return Excel::download(new UserExport, 'template_tambah_user.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new UserImport, $request->file('file'));

            $siswaUsers = User::where('role', 'siswa')->get();

            foreach ($siswaUsers as $siswa) {
                if (!DataSiswa::where('user_id', $siswa->id)->exists()) {
                    DataSiswa::create([
                        'user_id'     => $siswa->id,
                        'nama_lengkap' => $siswa->name,
                        'status'       => 'Aktif',
                    ]);
                }
            }

            return back()->with('alert', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Data user berhasil diimport.'
            ]);
        } catch (\Exception $e) {
            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage()
            ]);
        }
    }

}
