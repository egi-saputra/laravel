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
        // Validasi kolom kode & mapel
        if (empty($row['kode'])) {
            $this->failedRows[] = [
                'kode'     => $row['kode'] ?? null,
                'mapel'    => $row['mapel'] ?? null,
                'pengampu' => $row['pengampu'] ?? null,
                'reason'   => 'Kolom kode kosong',
            ];
            return null;
        }

        if (empty($row['mapel'])) {
            $this->failedRows[] = [
                'kode'     => $row['kode'] ?? null,
                'mapel'    => $row['mapel'] ?? null,
                'pengampu' => $row['pengampu'] ?? null,
                'reason'   => 'Kolom mapel kosong',
            ];
            return null;
        }

        $pengampuId = null;

        // Cari guru pengampu jika ada
        if (!empty($row['pengampu'])) {
            $pengampuInput = trim($row['pengampu']);

            // Cari berdasarkan kode guru lebih dulu
            $guru = DataGuru::where('kode', $pengampuInput)->first();

            // Jika tidak ketemu, cari berdasarkan nama user
            if (!$guru) {
                $guru = DataGuru::whereHas('user', function($query) use ($pengampuInput) {
                    $query->where('name', $pengampuInput);
                })->first();
            }

            // Jika tetap tidak ketemu, tandai gagal import
            if (!$guru) {
                $this->failedRows[] = [
                    'kode'     => $row['kode'],
                    'mapel'    => $row['mapel'],
                    'pengampu' => $row['pengampu'],
                    'reason'   => 'Guru pengampu tidak ditemukan',
                ];
                return null;
            }

            $pengampuId = $guru->id;
        }

        // Simpan atau update berdasarkan KODE mapel (bukan nama)
        return DataMapel::updateOrCreate(
            ['kode' => $row['kode']], // primary key unik
            [
                'mapel'   => $row['mapel'],
                'guru_id' => $pengampuId,
            ]
        );
    }
}
