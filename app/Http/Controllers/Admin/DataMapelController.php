<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataMapel;
use App\Models\DataGuru;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MapelImport;
use App\Exports\MapelExport;

class DataMapelController extends Controller
{
    public function index()
    {
        // Ambil semua mapel + relasi guru (berdasarkan user_id)
        $mapel = DataMapel::with('guru')->get();
        return view('admin.mapel', [
            'mapel' => $mapel,
            'pageTitle' => 'Data Mapel'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mapel'    => 'required|string|max:255',
            'guru_id'  => 'required|exists:data_guru,id', // FK ke data_guru.id
        ]);

        DataMapel::create($request->only('mapel', 'guru_id'));

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Data mapel berhasil ditambahkan!'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mapel' => 'required|string|max:255',
            'guru_id' => 'required|exists:data_guru,id', // ✔ arahkan ke id
        ]);

        $mapel = DataMapel::findOrFail($id);
        $mapel->update($request->only('mapel', 'guru_id'));

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Data mapel berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $mapel = DataMapel::findOrFail($id);
        $mapel->delete();

        return back()->with('alert', [
            'message' => 'Data mapel berhasil dihapus!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new MapelImport;
        Excel::import($import, $request->file('file'));

        $failedRows = $import->failedRows;

        if (count($failedRows) > 0) {
            $table = '<div style="max-height:300px; overflow-y:auto;">';
            $table .= '<table class="w-full border border-collapse border-gray-300 table-auto">';
            $table .= '<thead><tr><th class="px-2 py-1 border">Mapel</th><th class="px-2 py-1 border">Pengampu</th><th class="px-2 py-1 border">Keterangan</th></tr></thead>';
            $table .= '<tbody>';
            foreach ($failedRows as $row) {
                $table .= "<tr>
                            <td class='px-2 py-1 border'>{$row['mapel']}</td>
                            <td class='px-2 py-1 border'>{$row['pengampu']}</td>
                            <td class='px-2 py-1 border'>" . ($row['reason'] ?? '') . "</td>
                        </tr>";
            }
            $table .= '</tbody></table></div>';

            return back()->with('alert', [
                'type' => 'error',
                'title' => 'Import Gagal Sebagian',
                'message' => $table,
                'html' => true
            ]);
        }

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Import Berhasil',
            'message' => 'Data mapel berhasil diimport.'
        ]);
    }

    public function template()
    {
        return Excel::download(new MapelExport, 'template_mapel.xlsx');
    }

    public function destroyAll()
    {
        DataMapel::query()->delete();

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Semua data mapel berhasil dihapus.'
        ]);
    }
}
