<?php

namespace App\Imports;

use App\Models\JadwalGuru;
use App\Models\DataGuru;
use App\Models\DataKelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class JadwalGuruImport implements ToModel, WithHeadingRow
{
    use Importable;

    public $failedRows = [];

    public function model(array $row)
    {
        if (empty($row['hari'])) {
            return null;
        }

        $guruId  = null;
        $kelasId = null;

        // --- Pastikan jam jadi string ---
        $jamMulai   = isset($row['jam_mulai']) ? trim((string) $row['jam_mulai']) : null;
        $jamSelesai = isset($row['jam_selesai']) ? trim((string) $row['jam_selesai']) : null;

        // --- Cari guru berdasarkan kode atau nama ---
        if (!empty($row['nama_guru'])) {
            $guru = DataGuru::where('kode', trim($row['nama_guru'])) // kalau isinya kode guru
                ->orWhereHas('user', function ($q) use ($row) {
                    $q->whereRaw('LOWER(name) = ?', [strtolower(trim($row['nama_guru']))]);
                })
                ->first();

            if (!$guru) {
                $this->failedRows[] = [
                    'hari'   => $row['hari'],
                    'sesi'   => $row['sesi'] ?? null,
                    'guru'   => $row['nama_guru'] ?? null,
                    'kelas'  => $row['kelas'] ?? null,
                    'reason' => 'Guru tidak ditemukan',
                ];
                return null;
            }

            $guruId = $guru->id;
        }

        // --- Cari kelas berdasarkan kode atau nama ---
        if (!empty($row['kelas'])) {
            $kelas = DataKelas::whereRaw('LOWER(kode) = ?', [strtolower(trim($row['kelas']))])
                ->orWhereRaw('LOWER(kelas) = ?', [strtolower(trim($row['kelas']))])
                ->first();

            if (!$kelas) {
                $this->failedRows[] = [
                    'hari'   => $row['hari'],
                    'sesi'   => $row['sesi'] ?? null,
                    'guru'   => $row['nama_guru'] ?? null,
                    'kelas'  => $row['kelas'] ?? null,
                    'reason' => 'Kelas tidak ditemukan',
                ];
                return null;
            }

            $kelasId = $kelas->id;
        }

        // --- Cek duplikat ---
        $exists = JadwalGuru::where('hari', $row['hari'])
            ->where('sesi', $row['sesi'] ?? null)
            ->where('jam_mulai', $jamMulai)
            ->where('jam_selesai', $jamSelesai)
            ->where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->exists();

        if ($exists) {
            $this->failedRows[] = [
                'hari'   => $row['hari'],
                'sesi'   => $row['sesi'] ?? null,
                'guru'   => $row['nama_guru'] ?? null,
                'kelas'  => $row['kelas'] ?? null,
                'reason' => 'Duplikat jadwal',
            ];
            return null;
        }

        // --- Simpan data ---
        return new JadwalGuru([
            'hari'        => $row['hari'],
            'sesi'        => $row['sesi'] ?? null,
            'jam_mulai'   => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'guru_id'     => $guruId,
            'kelas_id'    => $kelasId,
        ]);
    }
}
