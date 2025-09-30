<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DataGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Imports\GuruImport;
use App\Exports\GuruExport;
use Maatwebsite\Excel\Facades\Excel;

class DataGuruController extends Controller
{
    public function index()
    {
        $guru = User::with('guru') // relasi ke data_guru
            ->where('role', 'guru')
            ->get();

        return view('admin.guru', [
            'guru' => $guru,
            'pageTitle' => 'Kelola Data Guru'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'   => 'required|unique:data_guru,kode',
            'nama'   => 'required',
            'email'  => 'required|email|unique:users,email',
        ]);

        // buat user login
        $user = User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password ?? env('DEFAULT_GURU_PASSWORD', 'password')),
            'role'     => 'guru',
        ]);

        // simpan data guru
        DataGuru::create([
            'kode'    => $request->kode,
            'user_id' => $user->id
        ]);

        return back()->with('alert', [
            'message' => 'Data guru berhasil ditambahkan!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    public function edit($id)
    {
        $guru = DataGuru::with('user')->findOrFail($id);
        return view('admin.guru_edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = DataGuru::with('user')->findOrFail($id);

        $request->validate([
            'kode'  => 'required|unique:data_guru,kode,' . $id,
            'nama'  => 'required',
            'email' => 'required|email|unique:users,email,' . $guru->user_id,
        ]);

        // update user
        $guru->user->update([
            'name'  => $request->nama,
            'email' => $request->email,
        ]);

        // update data guru
        $guru->update([
            'kode' => $request->kode,
        ]);

        return back()->with('alert', [
            'message' => 'Data guru berhasil diperbarui!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    public function destroy($id)
    {
        $guru = DataGuru::with('user')->findOrFail($id);

        // hapus user sekaligus
        $guru->user->delete();
        $guru->delete();

        return back()->with('alert', [
            'message' => 'Data guru berhasil dihapus!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);

    //     // hapus relasi guru
    //     $user->guru()->delete();

    //     // hapus user
    //     $user->delete();

    //     return back()->with('alert', [
    //         'message' => 'Data guru berhasil dihapus!',
    //         'type'    => 'success',
    //         'title'   => 'Berhasil',
    //     ]);
    // }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new GuruImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failedRows = $e->failures();

            $table = '<div style="max-height:300px; overflow-y:auto;">';
            $table .= '<table class="w-full border border-collapse border-gray-300 table-auto">';
            $table .= '<thead><tr><th class="px-2 py-1 border">Kode Guru</th><th class="px-2 py-1 border">Nama Guru</th></tr></thead>';
            $table .= '<tbody>';
            foreach ($failedRows as $failure) {
                $row = $failure->values();
                $table .= "<tr>
                            <td class='px-2 py-1 border'>{$row['kode']}</td>
                            <td class='px-2 py-1 border'>{$row['nama']}</td>
                        </tr>";
            }
            $table .= '</tbody></table></div>';

            return back()->with('alert', [
                'type'    => 'error',
                'title'   => 'Import Gagal Sebagian',
                'message' => $table,
                'html'    => true
            ]);
        }

        return back()->with('alert', [
            'type'    => 'success',
            'title'   => 'Import Berhasil',
            'message' => 'Data guru berhasil diimport.'
        ]);
    }

    public function template()
    {
        return Excel::download(new GuruExport, 'template_data_guru.xlsx');
    }

    public function destroyAll()
    {
        // hapus semua user guru + data_guru
        $guruList = DataGuru::with('user')->get();
        foreach ($guruList as $guru) {
            $guru->user->delete();
            $guru->delete();
        }

        return back()->with('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Semua data guru berhasil dihapus.'
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'kode' => 'required',
            'name' => 'required',
            'email'=> 'required|email|unique:users,email,'.$user->id,
        ]);

        // Update user
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Update atau buat data_guru
        DataGuru::updateOrCreate(
            ['user_id' => $user->id],
            ['kode' => $request->kode]
        );

        return back()->with('alert', [
            'type'=>'success',
            'title'=>'Berhasil',
            'message'=>'Data guru berhasil diperbarui'
        ]);
    }
}
