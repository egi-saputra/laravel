<?php

namespace App\Imports;

use App\Models\DataMapel;
use App\Models\DataGuru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class MapelImport implements ToModel, WithHeadingRow
{
    use Importable;

    public $failedRows = [];

    public function model(array $row)
    {
        // Validasi kolom mapel
        if (empty($row['mapel'])) {
            $this->failedRows[] = [
                'mapel'   => $row['mapel'] ?? null,
                'pengampu' => $row['pengampu'] ?? null,
                'reason'  => 'Kolom mapel kosong',
            ];
            return null;
        }

        $pengampuId = null;

        // Cari guru pengampu jika ada
        if (!empty($row['pengampu'])) {
            $pengampuInput = trim($row['pengampu']);

            // Cari berdasarkan kode di data_guru
            $guru = DataGuru::where('kode', $pengampuInput)->first();

            // Kalau tidak ketemu di kode, cari nama di relasi user
            if (!$guru) {
                $guru = DataGuru::whereHas('user', function($query) use ($pengampuInput) {
                    $query->where('name', $pengampuInput);
                })->first();
            }

            if (!$guru) {
                // Simpan ke failedRows kalau tidak ditemukan
                $this->failedRows[] = [
                    'mapel'   => $row['mapel'],
                    'pengampu' => $row['pengampu'],
                    'reason'  => 'Guru pengampu tidak ditemukan',
                ];
                return null;
            }

            $pengampuId = $guru->id;
        }

        // Simpan atau update mapel
        return DataMapel::updateOrCreate(
            ['mapel' => $row['mapel']],
            ['guru_id' => $pengampuId]
        );
    }
}
