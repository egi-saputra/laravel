<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DataSiswa;
use App\Models\DataKelas;
use App\Models\DataKejuruan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;
use App\Exports\DataSiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;

class DataSiswaController extends Controller
{
    public function index(Request $request)
    {
        $role = auth()->user()->role;

        // Ambil parameter filter/search dari query string
        $search      = $request->query('search', '');
        $filterKelas = $request->query('kelas_id', '');
        $filterKejur = $request->query('kejuruan_id', '');

        // Query siswa dengan relasi
        $query = DataSiswa::with(['kelas','kejuruan','user'])
            ->when($filterKelas, fn($q) => $q->where('kelas_id', $filterKelas))
            ->when($filterKejur, fn($q) => $q->where('kejuruan_id', $filterKejur))
            ->when($search, function($q) use ($search) {
                $q->where(function($q2) use ($search) {
                    $q2->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('nis', 'like', "%{$search}%")
                       ->orWhere('nisn', 'like', "%{$search}%")
                       ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$search}%"));
                });
            })
            // ->orderBy('nama_lengkap', 'asc');
            ->orderByRaw("CAST(SUBSTRING_INDEX(nama_lengkap, ' ', -1) AS UNSIGNED) ASC")
            ->orderByRaw('TRIM(LOWER(nama_lengkap)) asc');

        // Pagination
        $perPage = 10;
        $siswa = $query->paginate($perPage)->withQueryString();

        // Data filter list
        $kelasList    = DataKelas::all();
        $kejuruanList = DataKejuruan::all();

        // Data sekolah default
        $sekolah = DB::table('profil_sekolah')->first() ?? (object)[
            'nama_sekolah' => 'SMA Negeri 1 Contoh',
            'alamat'       => 'Jl. Pendidikan No. 123, Kota Contoh',
            'telepon'      => '(021) 123456',
            'email'        => 'info@smanc1contoh.sch.id'
        ];

        // Mapping role ke view
        $views = [
            'admin' => 'admin.siswa',
            'guru'  => 'guru.walas',
        ];
        if (!array_key_exists($role, $views)) abort(403, 'Akses ditolak.');

        return view($views[$role], compact(
            'siswa','kelasList','kejuruanList','sekolah'
        ));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama_lengkap' => 'required|string|max:255',
    //         'email'        => 'required|email|unique:users,email',
    //         'nisn'         => 'required|string|max:10|unique:data_siswa,nisn',
    //         'kelas_id'     => 'required|exists:data_kelas,id',
    //         'kejuruan_id' => 'required|exists:program_kejuruan,id',
    //     ]);

    //     // Buat user login
    //     $user = User::create([
    //         'name'     => $request->nama_lengkap,
    //         'email'    => $request->email,
    //         'password' => Hash::make(env('DEFAULT_SISWA_PASSWORD', 'password')),
    //         'role'     => 'siswa',
    //     ]);

    //     // Buat data siswa
    //     DataSiswa::create([
    //         'user_id'       => $user->id,
    //         'nama_lengkap'  => $request->nama_lengkap,
    //         'nisn'          => $request->nisn,
    //         'kelas_id'      => $request->kelas_id,
    //         'kejuruan_id'   => $request->kejuruan_id,
    //         'jenis_kelamin' => $request->jenis_kelamin ?? 'Laki-laki',
    //         'agama'         => $request->agama ?? 'Islam',
    //         'status'        => 'Aktif',
    //     ]);

    //     return back()->with('alert', [
    //         'message' => 'Data siswa berhasil ditambahkan!',
    //         'type'    => 'success',
    //         'title'   => 'Berhasil',
    //     ]);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'nis'          => 'nullable|string|max:10|unique:data_siswa,nis',
            'nisn'         => 'required|string|max:10|unique:data_siswa,nisn',
            'kelas_id'     => 'required|exists:data_kelas,id',
            'kejuruan_id'  => 'required|exists:program_kejuruan,id',
        ]);

        // Buat user login
        $user = User::create([
            'name'     => $request->nama_lengkap,
            'email'    => $request->email,
            'password' => Hash::make(env('DEFAULT_SISWA_PASSWORD', 'password')),
            'role'     => 'siswa',
        ]);

        // Buat data siswa
        DataSiswa::create([
            'user_id'       => $user->id,
            'nama_lengkap'  => $request->nama_lengkap,
            'nis'           => $request->nis,
            'nisn'          => $request->nisn,
            'kelas_id'      => $request->kelas_id,
            'kejuruan_id'   => $request->kejuruan_id,
            'jenis_kelamin' => $request->jenis_kelamin ?? 'Laki-laki',
            'agama'         => $request->agama ?? 'Islam',
            'status'        => 'Aktif',
        ]);

        return back()->with('alert', [
            'message' => 'Data siswa berhasil ditambahkan!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    public function edit($id)
    {
        $siswa = DataSiswa::with('kelas', 'kejuruan', 'user')->findOrFail($id);
        return view('admin.siswa_edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $siswa = DataSiswa::with('user')->findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $siswa->user_id,
            'nisn'         => 'required|string|max:10|unique:data_siswa,nisn,' . $id,
            'nis' => 'nullable|string|max:20|unique:data_siswa,nis,' . $id,
            'kelas_id'     => 'required|exists:data_kelas,id',
            'kejuruan_id' => 'required|exists:program_kejuruan,id',
        ]);

        // Update user login
        $siswa->user->update([
            'name'  => $request->nama_lengkap,
            'email' => $request->email,
        ]);

        // Update data siswa
        $siswa->update([
            'nama_lengkap'  => $request->nama_lengkap,
            'nis'          => $request->nis,
            'nisn'          => $request->nisn,
            'kelas_id'      => $request->kelas_id,
            'kejuruan_id'   => $request->kejuruan_id,
            'jenis_kelamin' => $request->jenis_kelamin ?? $siswa->jenis_kelamin,
            'agama'         => $request->agama ?? $siswa->agama,
        ]);

        return back()->with('alert', [
            'message' => 'Data siswa berhasil diperbarui!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    public function destroy($id)
    {
        $siswa = DataSiswa::with('user')->findOrFail($id);

        // Hapus user login juga
        if ($siswa->user) {
            $siswa->user->delete();
        }

        $siswa->delete();

        return back()->with('alert', [
            'message' => 'Data siswa berhasil dihapus!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new SiswaImport();
        Excel::import($import, $request->file('file'));

        if (count($import->failedRows) > 0) {
            $table = '<div style="max-height:300px; overflow-y:auto;">';
            $table .= '<table class="w-full border border-collapse border-gray-300 table-auto">';
            $table .= '<thead>
                        <tr>
                            <th class="px-2 py-1 border">Nama Lengkap</th>
                            <th class="px-2 py-1 border">Email</th>
                            <th class="px-2 py-1 border">NIS</th>
                            <th class="px-2 py-1 border">NISN</th>
                            <th class="px-2 py-1 border">Kelas</th>
                            <th class="px-2 py-1 border">Kejuruan</th>
                            <th class="px-2 py-1 border">Keterangan</th>
                        </tr>
                    </thead>';
            $table .= '<tbody>';
            foreach ($import->failedRows as $row) {
                $table .= "<tr>
                            <td class='px-2 py-1 border'>".($row['nama_lengkap'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['email'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['nis'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['nisn'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['kelas'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['kejuruan'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>" . ($row['reason'] ?? '') . "</td>
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
        return Excel::download(new SiswaExport, 'template_data_siswa.xlsx');
    }

    public function destroyAll()
    {
        DataSiswa::truncate();
        User::where('role', 'siswa')->delete();

        return back()->with('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Semua data siswa berhasil dihapus.'
        ]);
    }

    public function export()
    {
        return Excel::download(new DataSiswaExport, 'daftar_siswa.xlsx');
    }
}
