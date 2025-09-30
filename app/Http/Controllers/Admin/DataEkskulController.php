<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataEkskul;
use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\EkskulImport;
use App\Exports\EkskulExport;
use Maatwebsite\Excel\Facades\Excel;

class DataEkskulController extends Controller
{
    public function index()
    {
        // Ambil semua data ekskul + relasi pembina (user dengan role guru)
        $ekskul = DataEkskul::with('userPembina')->get();

        // Ambil daftar user guru untuk dropdown di form tambah/edit
        $guruUsers = User::where('role', 'guru')->get();

        return view('admin.ekskul', [
            'ekskul'     => $ekskul,
            'guruUsers'  => $guruUsers,
            'pageTitle'  => 'Data Ekskul'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ekskul' => 'required|string|max:255',
            'ekskul_id'   => 'required|exists:data_guru,id', // validasi ke data_guru.id
        ]);

        DataEkskul::create($request->only('nama_ekskul', 'ekskul_id'));

        return back()->with('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Data ekskul berhasil ditambahkan!'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ekskul' => 'required|string|max:255',
            'ekskul_id'   => 'required|exists:data_guru,id', // validasi ke tabel data_guru
        ]);

        $ekskul = DataEkskul::findOrFail($id);
        $ekskul->update($request->only('nama_ekskul', 'ekskul_id'));

        return back()->with('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Data ekskul berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $ekskul = DataEkskul::findOrFail($id);
        $ekskul->delete();

        return back()->with('alert', [
            'message' => 'Data ekskul berhasil dihapus!',
            'type'    => 'success',
            'title'   => 'Berhasil',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new EkskulImport();
        Excel::import($import, $request->file('file'));

        $failedRows = $import->failedRows;

        if (count($failedRows) > 0) {
            $table = '<div style="max-height:300px; overflow-y:auto;">';
            $table .= '<table class="w-full border border-collapse border-gray-300 table-auto">';
            $table .= '<thead><tr>
                          <th class="px-2 py-1 border">Nama Ekskul</th>
                          <th class="px-2 py-1 border">Pembina</th>
                          <th class="px-2 py-1 border">Keterangan</th>
                       </tr></thead>';
            $table .= '<tbody>';
            foreach ($failedRows as $row) {
                $table .= "<tr>
                            <td class='px-2 py-1 border'>{$row['nama_ekskul']}</td>
                            <td class='px-2 py-1 border'>{$row['pembina']}</td>
                            <td class='px-2 py-1 border'>" . ($row['alasan'] ?? '-') . "</td>
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
            'message' => 'Data ekskul berhasil diimport.'
        ]);
    }

    public function export()
    {
        return Excel::download(new EkskulExport, 'template_ekskul.xlsx');
    }

    public function destroyAll()
    {
        DataEkskul::query()->delete(); // hapus semua data di tabel

        return back()->with('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Semua data ekskul berhasil dihapus.'
        ]);
    }
}
