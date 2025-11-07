<?php

namespace App\Imports;

use App\Models\JadwalGuru;
use App\Models\DataGuru;
use App\Models\DataKelas;
use App\Models\DataMapel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JadwalGuruImport implements ToCollection, WithHeadingRow
{
    public $failedRows = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $hari        = trim($row['hari'] ?? '');
            $sesi        = trim($row['sesi'] ?? '');
            $kelasInput  = trim((string)($row['kelas'] ?? ''));
            $mapelInput  = trim((string)($row['mapel'] ?? ''));
            $jam_mulai   = trim($row['jam_mulai'] ?? '');
            $jam_selesai = trim($row['jam_selesai'] ?? '');

            // Hilangkan titik desimal dari Excel
            $mapelInput = preg_replace('/\.0$/', '', $mapelInput);
            $kelasInput = preg_replace('/\.0$/', '', $kelasInput);

            // 🔸 Validasi wajib isi
            if (!$hari || !$sesi || !$kelasInput || !$mapelInput) {
                $this->failedRows[] = [
                    'hari'   => $hari,
                    'sesi'   => $sesi,
                    'kelas'  => $kelasInput,
                    'mapel'  => $mapelInput,
                    'reason' => 'Kolom wajib tidak boleh kosong (hari/sesi/kelas/mapel)',
                ];
                continue;
            }

            /** 🔹 CARI KELAS */
            $kelasData = DataKelas::where('kode', $kelasInput)
                ->orWhere('kelas', $kelasInput)
                ->first();

            if (!$kelasData) {
                $this->failedRows[] = [
                    'hari'   => $hari,
                    'sesi'   => $sesi,
                    'kelas'  => $kelasInput,
                    'mapel'  => $mapelInput,
                    'reason' => 'Kelas tidak ditemukan',
                ];
                continue;
            }

            /** 🔹 CARI MAPEL DAN GURU OTOMATIS */
            $mapelData = DataMapel::where('id', $mapelInput)
                ->orWhere('kode', $mapelInput)
                ->orWhere('mapel', $mapelInput)
                ->first();

            if (!$mapelData) {
                $this->failedRows[] = [
                    'hari'   => $hari,
                    'sesi'   => $sesi,
                    'kelas'  => $kelasInput,
                    'mapel'  => $mapelInput,
                    'reason' => 'Mapel tidak ditemukan',
                ];
                continue;
            }

            // Ambil guru dari mapel (relasi guru_id di DataMapel)
            $guruData = $mapelData->guru; // pastikan DataMapel punya relasi guru()
            if (!$guruData) {
                $this->failedRows[] = [
                    'hari'   => $hari,
                    'sesi'   => $sesi,
                    'kelas'  => $kelasInput,
                    'mapel'  => $mapelInput,
                    'reason' => 'Guru untuk mapel tidak ditemukan',
                ];
                continue;
            }

            /** 🔹 CEK DUPLIKAT */
            $exists = JadwalGuru::where('hari', $hari)
                ->where('sesi', $sesi)
                ->where('guru_id', $guruData->id)
                ->where('kelas_id', $kelasData->id)
                ->where('mapel_id', $mapelData->id)
                ->where('jam_mulai', $jam_mulai)
                ->where('jam_selesai', $jam_selesai)
                ->exists();

            if ($exists) {
                $this->failedRows[] = [
                    'hari'   => $hari,
                    'sesi'   => $sesi,
                    'kelas'  => $kelasInput,
                    'mapel'  => $mapelInput,
                    'reason' => 'Jadwal duplikat',
                ];
                continue;
            }

            /** 🔹 SIMPAN KE DATABASE */
            JadwalGuru::create([
                'hari'        => $hari,
                'sesi'        => $sesi,
                'jam_mulai'   => $jam_mulai ?: '07:00',
                'jam_selesai' => $jam_selesai ?: '08:00',
                'guru_id'     => $guruData->id,
                'kelas_id'    => $kelasData->id,
                'mapel_id'    => $mapelData->id,
                'jumlah_jam'  => 1,
            ]);
        }
    }
}
