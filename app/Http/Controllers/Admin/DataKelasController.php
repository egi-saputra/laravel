<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataKelas;
use App\Models\DataGuru;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KelasImport;
use App\Exports\KelasExport;

class DataKelasController extends Controller
{
    /**
     * Tampilkan daftar kelas
     */
    public function index()
    {
        $kelas = DataKelas::with('waliKelas.user')->get();

        // Ambil guru yang memiliki user dengan role 'guru'
        $guru = DataGuru::with('user')
            ->whereHas('user', function ($q) {
                $q->where('role', 'guru');
            })
            ->get();

        $pageTitle = 'Data Kelas';

        return view('admin.kelas', compact('kelas', 'guru', 'pageTitle'));
    }

    /**
     * Simpan data kelas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode'     => 'required|string|max:5|unique:data_kelas,kode',
            'kelas'    => 'required|string|max:255',
            'walas_id' => 'nullable|exists:data_guru,id',
        ]);

        DataKelas::create([
            'kode'     => $request->kode,
            'kelas'    => $request->kelas,
            'walas_id' => $request->walas_id,
        ]);

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Data kelas berhasil ditambahkan!'
        ]);
    }

    /**
     * Update data kelas
     */
    public function update(Request $request, $id)
    {
        $kelas = DataKelas::findOrFail($id);

        $request->validate([
            'kode'     => 'required|string|max:5|unique:data_kelas,kode,' . $kelas->id,
            'kelas'    => 'required|string|max:255',
            'walas_id' => 'nullable|exists:data_guru,id',
        ]);

        $kelas->update([
            'kode'     => $request->kode,
            'kelas'    => $request->kelas,
            'walas_id' => $request->walas_id,
        ]);

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Data kelas berhasil diperbarui!'
        ]);
    }

    /**
     * Hapus data kelas
     */
    public function destroy($id)
    {
        $ekskul = DataKelas::findOrFail($id);
        $ekskul->delete();

        return back()->with('alert', [
            'message' => 'Data kelas berhasil dihapus!',
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
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new KelasImport();
        Excel::import($import, $request->file('file'));
        // dd($import->failedRows);

        if (count($import->failedRows) > 0) {
            // bikin tabel row gagal
            $table = '<div style="max-height:300px; overflow-y:auto;">';
            $table .= '<table class="w-full border border-collapse border-gray-300 table-auto">';
            $table .= '<thead><tr>
                            <th class="px-2 py-1 border">Kode</th>
                            <th class="px-2 py-1 border">Kelas</th>
                            <th class="px-2 py-1 border">Wali Kelas</th>
                            <th class="px-2 py-1 border">Keterangan</th>
                        </tr></thead>';
            $table .= '<tbody>';
            foreach ($import->failedRows as $row) {
                $table .= "<tr>
                            <td class='px-2 py-1 border'>".($row['kode'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['kelas'] ?? '-')."</td>
                            <td class='px-2 py-1 border'>".($row['walas'] ?? '-')."</td>
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
            'message' => 'Data kelas berhasil diimport.'
        ]);
    }

    public function template()
    {
        return Excel::download(new KelasExport, 'template_kelas.xlsx');
    }

    public function destroyAll()
    {
        DataKelas::query()->delete(); // hapus semua data di tabel

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Semua data kelas berhasil dihapus.'
        ]);
    }

}
