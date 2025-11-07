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
        // Ambil semua mapel + relasi guru
        $mapel = DataMapel::with('guru')->get();

        return view('admin.mapel', [
            'mapel' => $mapel,
            'pageTitle' => 'Data Mapel'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'    => 'required|string|max:50|unique:data_mapel,kode', // tambahkan validasi kode unik
            'mapel'   => 'required|string|max:255',
            'guru_id' => 'required|exists:data_guru,id', // FK ke data_guru.id
        ]);

        DataMapel::create([
            'kode'    => $request->kode,
            'mapel'   => $request->mapel,
            'guru_id' => $request->guru_id,
        ]);

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Data mapel berhasil ditambahkan!'
        ]);
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'kode'    => 'required|string|max:50|unique:data_mapel,kode,' . $id, // kode tetap unik, tapi abaikan kode milik sendiri
    //         'mapel'   => 'required|string|max:255',
    //         'guru_id' => 'required|exists:data_guru,id',
    //     ]);

    //     $mapel = DataMapel::findOrFail($id);

    //     $mapel->update([
    //         'kode'    => $request->kode,
    //         'mapel'   => $request->mapel,
    //         'guru_id' => $request->guru_id,
    //     ]);

    //     return back()->with('alert', [
    //         'type' => 'success',
    //         'title' => 'Berhasil',
    //         'message' => 'Data mapel berhasil diperbarui!'
    //     ]);
    // }
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode'    => 'required|string|max:50|unique:data_mapel,kode,' . $id,
            'mapel'   => 'required|string|max:255',
            'guru_id' => 'required|exists:data_guru,id',
        ]);

        $mapel = DataMapel::findOrFail($id);

        // Cek apakah guru_id berubah
        $oldGuruId = $mapel->guru_id;

        // Update data mapel
        $mapel->update([
            'kode'    => $request->kode,
            'mapel'   => $request->mapel,
            'guru_id' => $request->guru_id,
        ]);

        // Jika guru pengampu berubah, update jadwal_guru yang terkait
        if ($oldGuruId != $request->guru_id) {
            \App\Models\JadwalGuru::where('mapel_id', $mapel->id)
                ->update(['guru_id' => $request->guru_id]);
        }

        return back()->with('alert', [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Data mapel & jadwal guru berhasil diperbarui!'
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
            $table .= '<thead>
                        <tr>
                            <th class="px-2 py-1 border">Kode</th>
                            <th class="px-2 py-1 border">Mapel</th>
                            <th class="px-2 py-1 border">Pengampu</th>
                            <th class="px-2 py-1 border">Keterangan</th>
                        </tr>
                    </thead>';
            $table .= '<tbody>';
            foreach ($failedRows as $row) {
                $table .= "<tr>
                            <td class='px-2 py-1 border'>{$row['kode']}</td>
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
