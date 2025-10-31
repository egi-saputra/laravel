<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DataKelas;
use App\Models\User;
use App\Models\DataSiswa;
use App\Imports\WalasSiswaImport;
use App\Exports\WalasSiswaExport;
use Maatwebsite\Excel\Facades\Excel;

class WalasController extends Controller
{
    // public function index()
    // {
    //     $user = Auth::user();
    //     $guru = $user->guru;
    //     $kelas = DataKelas::where('walas_id', $guru->id)->first();

    //     $siswa = $kelas
    //         ? DataSiswa::where('kelas_id', $kelas->id)
    //             ->with(['user','kelas'])
    //             ->get()
    //         : collect();

    //     return view('guru.walas', compact('kelas', 'siswa'));
    // }

    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $kelas = DataKelas::where('walas_id', $guru->id)->first();

        // Jika bukan wali kelas, tolak akses
        if (!$kelas) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini (bukan wali kelas).');
        }

        $siswa = DataSiswa::where('kelas_id', $kelas->id)
            ->with(['user', 'kelas'])
            ->get();

        return view('guru.walas', compact('kelas', 'siswa'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;
        $kelas = DataKelas::where('walas_id', $guru->id)->first();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'nullable|string|max:10|unique:data_siswa,nis',
            'nisn' => 'nullable|string|max:10|unique:data_siswa,nisn',
            'email' => 'required|email|unique:users,email',
            'kelas_id' => 'required|exists:data_kelas,id',
        ]);

        if (!$kelas || $request->kelas_id != $kelas->id) {
            return redirect()->back()->with('alert', [
                'message' => 'Anda tidak berhak menambahkan siswa ke kelas ini!',
                'type' => 'error',
                'title' => 'Error',
            ]);
        }

        $defaultPassword = $request->password ?? env('DEFAULT_SISWA_PASSWORD', 'password123');

        DB::beginTransaction();
        try {
            $newUser = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($defaultPassword),
                'role' => 'siswa',
            ]);

            DataSiswa::create([
                'user_id' => $newUser->id,
                'nama_lengkap' => $request->nama_lengkap,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'kelas_id' => $kelas->id,
            ]);

            DB::commit();
            return redirect()->back()->with('alert', [
                'message' => 'Siswa berhasil ditambahkan!',
                'type' => 'success',
                'title' => 'Berhasil',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'message' => $e->getMessage(),
                'type' => 'error',
                'title' => 'Terjadi kesalahan',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $siswa = DataSiswa::with('user')->findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'nullable|string|max:10|unique:data_siswa,nis,' . $siswa->id,
            'nisn' => 'nullable|string|max:10|unique:data_siswa,nisn,' . $siswa->id,
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
            'password' => 'nullable|string|min:6',
            'jabatan_siswa' => 'required|in:Tidak Ada,Sekretaris,Bendahara',
        ]);

        DB::beginTransaction();
        try {
            $siswa->user->update([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => $request->filled('password')
                    ? Hash::make($request->password)
                    : $siswa->user->password,
            ]);

            $siswa->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'jabatan_siswa' => $request->jabatan_siswa,
            ]);

            DB::commit();
            return redirect()->back()->with('alert', [
                'message' => 'Data siswa berhasil diperbarui!',
                'type' => 'success',
                'title' => 'Berhasil',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'message' => $e->getMessage(),
                'type' => 'error',
                'title' => 'Terjadi kesalahan',
            ]);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $user = Auth::user();
        $guru = $user->guru;
        $kelas = DataKelas::where('walas_id', $guru->id)->first();

        if (!$kelas) {
            return back()->with('alert', [
                'type'    => 'error',
                'title'   => 'Import Gagal',
                'message' => 'Anda belum terdaftar sebagai wali kelas.'
            ]);
        }

        $import = new WalasSiswaImport($kelas->id);
        Excel::import($import, $request->file('file'));

        if (!empty($import->failedRows)) {
            $table = '<div style="max-height:300px; overflow-y:auto;">';
            $table .= '<table class="w-full border border-collapse border-gray-300 table-auto">';
            $table .= '<thead>
                        <tr>
                            <th class="px-2 py-1 border">Nama Lengkap</th>
                            <th class="px-2 py-1 border">Email</th>
                            <th class="px-2 py-1 border">NIS</th>
                            <th class="px-2 py-1 border">NISN</th>
                            <th class="px-2 py-1 border">Kelas</th>
                            <th class="px-2 py-1 border">Keterangan</th>
                        </tr>
                    </thead><tbody>';

            foreach ($import->failedRows as $row) {
                $table .= "<tr>
                            <td class='px-2 py-1 border'>".($row['nama_lengkap'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['email'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['nis'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['nisn'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['kelas'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['reason'] ?? '')."</td>
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
            'message' => 'Data siswa berhasil diimport.'
        ]);
    }

    public function template()
    {
        return Excel::download(new WalasSiswaExport, 'template_walas_data_siswa.xlsx');
    }

    public function destroy($id)
    {
        $auth = Auth::user();
        $guru = $auth->guru;
        $kelas = DataKelas::where('walas_id', $guru->id)->firstOrFail();

        $siswa = DataSiswa::with('user')
            ->where('kelas_id', $kelas->id)
            ->where('id', $id)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $user = $siswa->user;

            $siswa->delete();

            if ($user && $user->role === 'siswa') {
                $user->delete();
            }

            DB::commit();
            return redirect()->back()->with('alert', [
                'message' => 'Data siswa berhasil dihapus!',
                'type' => 'success',
                'title' => 'Berhasil',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'message' => $e->getMessage(),
                'type' => 'error',
                'title' => 'Gagal menghapus',
            ]);
        }
    }

    public function destroyAll()
    {
        $auth = Auth::user();
        $guru = $auth->guru;
        $kelas = DataKelas::where('walas_id', $guru->id)->firstOrFail();

        DB::beginTransaction();
        try {
            $siswas = DataSiswa::with('user')->where('kelas_id', $kelas->id)->get();
            $userIds = $siswas->pluck('user_id')->filter()->all();

            DataSiswa::whereIn('id', $siswas->pluck('id'))->delete();

            if (!empty($userIds)) {
                User::whereIn('id', $userIds)->where('role', 'siswa')->delete();
            }

            DB::commit();
            return redirect()->back()->with('alert', [
                'message' => 'Semua siswa di kelas ini berhasil dihapus!',
                'type' => 'success',
                'title' => 'Berhasil',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'message' => $e->getMessage(),
                'type' => 'error',
                'title' => 'Gagal menghapus semua',
            ]);
        }
    }
}
