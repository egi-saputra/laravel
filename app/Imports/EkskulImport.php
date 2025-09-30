<?php

namespace App\Imports;

use App\Models\DataEkskul;
use App\Models\DataGuru;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EkskulImport implements ToModel, WithHeadingRow
{
    public $failedRows = [];

    public function model(array $row)
    {
        $namaEkskul = trim($row['nama_ekskul'] ?? '');
        $pembina    = trim($row['pembina'] ?? '');

        if (empty($namaEkskul)) {
            return null; // skip kalau kosong
        }

        $guruId = null;

        if (!empty($pembina)) {
            // cari guru berdasarkan kode atau nama user terkait
            $guru = DataGuru::where('kode', $pembina)
                ->orWhereHas('user', function ($q) use ($pembina) {
                    $q->where('name', $pembina)->where('role', 'guru');
                })
                ->first();

            if ($guru) {
                $user = $guru->user; // relasi ke user
                if ($user && $user->role === 'guru') {
                    $guruId = $guru->id; // **penting**: simpan id dari data_guru
                } else {
                    $this->failedRows[] = [
                        'nama_ekskul' => $namaEkskul,
                        'pembina'     => $pembina,
                        'alasan'      => 'User tidak ditemukan atau bukan guru'
                    ];
                    return null;
                }
            } else {
                $this->failedRows[] = [
                    'nama_ekskul' => $namaEkskul,
                    'pembina'     => $pembina,
                    'alasan'      => 'Guru tidak ditemukan'
                ];
                return null;
            }
        }

        return DataEkskul::updateOrCreate(
            ['nama_ekskul' => $namaEkskul],
            ['ekskul_id'   => $guruId]
        );
    }
}
