<?php

namespace App\Imports;

use App\Models\DataKelas;
use App\Models\DataGuru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class KelasImport implements ToModel, WithHeadingRow
{
    use Importable;

    public $failedRows = [];

    public function model(array $row)
    {
        // Jika kolom kode / kelas kosong → gagal
        if (empty($row['kode']) || empty($row['kelas'])) {
            $this->failedRows[] = [
                'kode'   => $row['kode'] ?? null,
                'kelas'  => $row['kelas'] ?? null,
                'walas'  => $row['walas'] ?? null,
                'reason' => 'Kolom kode atau kelas kosong',
            ];
            return null;
        }

        $walasId = null;

        // Cari wali kelas (guru) jika ada
        if (!empty($row['walas'])) {
            $guru = DataGuru::with('user')
                ->where('kode', trim($row['walas']))
                ->orWhereHas('user', function($q) use ($row) {
                    $q->where('name', trim($row['walas']));
                })
                ->first();

            if ($guru) {
                $walasId = $guru->id;
            } else {
                // Walas tidak ketemu → gagal
                $this->failedRows[] = [
                    'kode'   => $row['kode'],
                    'kelas'  => $row['kelas'],
                    'walas'  => $row['walas'],
                    'reason' => 'Walas tidak ditemukan',
                ];
                return null;
            }
        }

        // Simpan atau update kelas
        return DataKelas::updateOrCreate(
            ['kode' => $row['kode']],
            [
                'kelas'    => $row['kelas'],
                'walas_id' => $walasId
            ]
        );
    }
}
