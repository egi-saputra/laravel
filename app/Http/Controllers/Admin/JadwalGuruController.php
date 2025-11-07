<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\JadwalGuru;
use App\Models\DataGuru;
use App\Models\DataKelas;
use App\Models\DataMapel;
use App\Models\JamMengajar;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalGuruImport;
use App\Exports\JadwalGuruExport;
use Illuminate\Pagination\LengthAwarePaginator;

class JadwalGuruController extends Controller
{
    /**
     * Daftar jadwal guru
     */
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $daysOrder = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        // Ambil parameter filter/search dari query string
        $search      = $request->query('search', '');
        $filterHari  = $request->query('hari', '');
        $filterSesi  = $request->query('sesi', '');
        $filterGuru  = $request->query('guru', '');
        $filterKelas = $request->query('kelas', '');
        $filterMapel = $request->query('mapel', '');

        // Query jadwal dengan filter
        $jadwalQuery = JadwalGuru::with(['guru.user','kelas','mapel'])
            ->when($filterHari, fn($q) => $q->where('hari', $filterHari))
            ->when($filterSesi, fn($q) => $q->where('sesi','like',"%$filterSesi%"))
            ->when($filterGuru, fn($q) => $q->whereHas('guru.user', fn($q2) =>
                $q2->where('name','like',"%$filterGuru%")))
            ->when($filterKelas, fn($q) => $q->whereHas('kelas', fn($q2) =>
                $q2->where('kelas','like',"%$filterKelas%")))
            ->when($filterMapel, fn($q) => $q->whereHas('mapel', fn($q2) =>
                $q2->where('mapel','like',"%$filterMapel%"))) // ✅ FIXED
            ->when($search, fn($q) => $q->where(function($q2) use ($search){
                $q2->where('sesi','like',"%$search%")
                    ->orWhere('hari','like',"%$search%")
                    ->orWhereHas('guru.user', fn($q3) => $q3->where('name','like',"%$search%"))
                    ->orWhereHas('kelas', fn($q3) => $q3->where('kelas','like',"%$search%"))
                    ->orWhereHas('mapel', fn($q3) => $q3->where('mapel','like',"%$search%")); // ✅ FIXED
            }))
            ->orderByRaw("FIELD(hari,'".implode("','",$daysOrder)."')")
            ->orderBy('jam_mulai');

        // Pagination
        $perPage = 12;
        $jadwal = $jadwalQuery->paginate($perPage)->withQueryString();

        // Ambil data referensi
        $guru   = DataGuru::with('user')->get();
        $kelas  = DataKelas::all();
        $mapel  = DataMapel::all();

        // Data sekolah default
        $sekolah = DB::table('profil_sekolah')->first() ?? (object)[
            'nama_sekolah' => 'SMA Negeri 1 Contoh',
            'alamat'       => 'Jl. Pendidikan No. 123, Kota Contoh',
            'telepon'      => '(021) 123456',
            'email'        => 'info@smanc1contoh.sch.id'
        ];

        $views = [
            'admin' => 'admin.jadwal_guru',
        ];
        if (!array_key_exists($role, $views)) abort(403, 'Akses ditolak.');

        return view($views[$role], compact(
            'jadwal','guru','kelas','mapel','daysOrder','sekolah'
        ));
    }

    /**
     * Simpan data jadwal baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'sesi'        => 'required|string|max:50',
            'jam_mulai'   => ['required','string','regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            'jam_selesai' => ['required','string','regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            'guru_id'     => 'required|exists:data_guru,id',
            'kelas_id'    => 'required|exists:data_kelas,id',
            'mapel_id'    => 'required|exists:data_mapel,id',
        ], [
            'jam_mulai.regex'   => 'Format jam mulai harus HH:MM (contoh: 07:00)',
            'jam_selesai.regex' => 'Format jam selesai harus HH:MM (contoh: 08:30)',
        ]);

        // Cek duplikat jadwal
        $exists = JadwalGuru::where('hari', $request->hari)
            ->where('sesi', $request->sesi)
            ->where('jam_mulai', $request->jam_mulai)
            ->where('jam_selesai', $request->jam_selesai)
            ->where('guru_id', $request->guru_id)
            ->where('kelas_id', $request->kelas_id)
            ->where('mapel_id', $request->mapel_id)
            ->exists();

        if ($exists) {
            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Duplikat Ditemukan',
                'message' => 'Jadwal untuk guru, mapel, kelas, hari, dan sesi ini sudah ada.'
            ]);
        }

        JadwalGuru::create([
            'hari'        => $request->hari,
            'sesi'        => $request->sesi,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'guru_id'     => $request->guru_id,
            'kelas_id'    => $request->kelas_id,
            'mapel_id'    => $request->mapel_id,
            'jumlah_jam'  => 1,
        ]);

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Jadwal guru berhasil ditambahkan!'
        ]);
    }

    /**
     * Update data jadwal
     */
    // public function update(Request $request, $id)
    // {
    //     $jadwal = JadwalGuru::findOrFail($id);

    //     $request->validate([
    //         'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
    //         'sesi'        => 'required|string|max:50',
    //         'jam_mulai'   => ['required','string','regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
    //         'jam_selesai' => ['required','string','regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
    //         'guru_id'     => 'required|exists:data_guru,id',
    //         'kelas_id'    => 'required|exists:data_kelas,id',
    //         'mapel_id'    => 'required|exists:data_mapel,id',
    //     ]);

    //     // Validasi duplikat (kecuali dirinya sendiri)
    //     $exists = JadwalGuru::where('hari', $request->hari)
    //         ->where('sesi', $request->sesi)
    //         ->where('jam_mulai', $request->jam_mulai)
    //         ->where('jam_selesai', $request->jam_selesai)
    //         ->where('guru_id', $request->guru_id)
    //         ->where('kelas_id', $request->kelas_id)
    //         ->where('mapel_id', $request->mapel_id)
    //         ->where('id', '!=', $jadwal->id)
    //         ->exists();

    //     if ($exists) {
    //         return back()->with('alert', [
    //             'type' => 'error',
    //             'title' => 'Duplikat Ditemukan',
    //             'message' => 'Jadwal untuk guru, mapel, kelas, hari, dan sesi ini sudah ada.'
    //         ]);
    //     }

    //     $jadwal->update([
    //         'hari'        => $request->hari,
    //         'sesi'        => $request->sesi,
    //         'jam_mulai'   => $request->jam_mulai,
    //         'jam_selesai' => $request->jam_selesai,
    //         'guru_id'     => $request->guru_id,
    //         'kelas_id'    => $request->kelas_id,
    //         'mapel_id'    => $request->mapel_id,
    //     ]);

    //     return back()->with('alert', [
    //         'type' => 'success',
    //         'title' => 'Berhasil',
    //         'message' => 'Jadwal guru berhasil diperbarui!'
    //     ]);
    // }
    public function update(Request $request, $id)
    {
        $jadwal = JadwalGuru::findOrFail($id);

        $request->validate([
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'sesi'        => 'required|string|max:50',
            'jam_mulai'   => ['required','string','regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            'jam_selesai' => ['required','string','regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            'guru_id'     => 'required|exists:data_guru,id',
            'kelas_id'    => 'required|exists:data_kelas,id',
            'mapel_id'    => 'required|exists:data_mapel,id',
        ]);

        $exists = JadwalGuru::where('hari', $request->hari)
            ->where('sesi', $request->sesi)
            ->where('jam_mulai', $request->jam_mulai)
            ->where('jam_selesai', $request->jam_selesai)
            ->where('guru_id', $request->guru_id)
            ->where('kelas_id', $request->kelas_id)
            ->where('mapel_id', $request->mapel_id)
            ->where('id', '!=', $jadwal->id)
            ->exists();

        if ($exists) {
            if($request->ajax()){
                return response()->json([
                    'type' => 'error',
                    'title' => 'Duplikat Ditemukan',
                    'message' => 'Jadwal ini sudah ada.'
                ]);
            }

            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Duplikat Ditemukan',
                'message' => 'Jadwal ini sudah ada.'
            ]);
        }

        $jadwal->update($request->only('hari','sesi','jam_mulai','jam_selesai','guru_id','kelas_id','mapel_id'));

        if($request->ajax()){
            return response()->json([
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Jadwal guru berhasil diperbarui!'
            ]);
        }

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Jadwal guru berhasil diperbarui!'
        ]);
    }

    /**
     * Hapus data jadwal
     */
    public function destroy($id)
    {
        $jadwal = JadwalGuru::findOrFail($id);
        $jadwal->delete();

        return back()->with('alert', [
            'message' => 'Jadwal guru berhasil dihapus!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    /**
     * Import dari file excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $import = new \App\Imports\JadwalGuruImport();
        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

        $failedRows = $import->failedRows;

        if (!empty($failedRows)) {
            // 🧱 Bangun tabel hasil error
            $table = '<div style="max-height:300px; overflow-y:auto;">';
            $table .= '<table class="w-full border border-collapse border-gray-300 table-auto">';
            $table .= '
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-2 py-1 border">Hari</th>
                        <th class="px-2 py-1 border">Sesi</th>
                        <th class="px-2 py-1 border">Guru</th>
                        <th class="px-2 py-1 border">Kelas</th>
                        <th class="px-2 py-1 border">Mapel</th>
                        <th class="px-2 py-1 border">Alasan</th>
                    </tr>
                </thead>
                <tbody>
            ';

            foreach ($failedRows as $row) {
                // Ambil nama guru dari row jika tersedia (auto-generate di JadwalGuruImport)
                $guruNama = $row['guru'] ?? ($row['mapel_guru'] ?? ''); // fallback jika perlu

                $table .= '
                    <tr>
                        <td class="px-2 py-1 border">' . e($row['hari'] ?? '') . '</td>
                        <td class="px-2 py-1 border">' . e($row['sesi'] ?? '') . '</td>
                        <td class="px-2 py-1 border">' . e($guruNama) . '</td>
                        <td class="px-2 py-1 border">' . e($row['kelas'] ?? '') . '</td>
                        <td class="px-2 py-1 border">' . e($row['mapel'] ?? '') . '</td>
                        <td class="px-2 py-1 text-red-600 border">' . e($row['reason'] ?? '') . '</td>
                    </tr>';
            }

            $table .= '</tbody></table></div>';

            return back()->with('alert', [
                'type'    => 'error',
                'title'   => 'Beberapa data gagal diimport',
                'html'    => true,
                'message' => $table,
            ]);
        }

        // ✅ Jika semua sukses
        return back()->with('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Data jadwal guru berhasil diimport.',
        ]);
    }

    public function export()
    {
        return Excel::download(new JadwalGuruExport, 'template_jadwal_guru.xlsx');
    }

    public function destroyAll()
    {
        JadwalGuru::query()->delete();

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Semua data jadwal guru berhasil dihapus.'
        ]);
    }

    public function getMapelByGuru($id)
    {
        $mapel = \App\Models\DataMapel::where('guru_id', $id)
                    ->select('id', 'kode', 'mapel')
                    ->get();

        return response()->json($mapel);
    }

    public function tbody()
    {
        $jadwal = JadwalGuru::with(['guru.user','kelas','mapel'])->get();
        return view('admin.jadwal_guru._tbody', compact('jadwal'));
    }
}
