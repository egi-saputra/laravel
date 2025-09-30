<?php

namespace App\Imports;

use App\Models\DataSiswa;
use App\Models\User;
use App\Models\DataKelas;
use App\Models\DataKejuruan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class SiswaImport implements ToModel, WithHeadingRow
{
    use Importable;

    public $failedRows = [];

    public function model(array $row)
    {
        // Skip jika kolom wajib kosong
        if (empty($row['nama_lengkap']) || empty($row['email']) || empty($row['kelas']) || empty($row['kejuruan'])) {
            $this->failedRows[] = [
                'nama_lengkap' => $row['nama_lengkap'] ?? null,
                'email'        => $row['email'] ?? null,
                'nis'          => $row['nis'] ?? null,
                'nisn'         => $row['nisn'] ?? null,
                'kelas'        => $row['kelas'] ?? null,
                'kejuruan'     => $row['kejuruan'] ?? null,
                'reason'       => 'Kolom wajib kosong',
            ];
            return null;
        }

        // Cek duplikat siswa berdasarkan email, nis, atau nisn
        if (
            User::where('email', $row['email'])->exists() ||
            (!empty($row['nis']) && DataSiswa::where('nis', $row['nis'])->exists()) ||
            (!empty($row['nisn']) && DataSiswa::where('nisn', $row['nisn'])->exists())
        ) {
            $this->failedRows[] = [
                'nama_lengkap' => $row['nama_lengkap'],
                'email'        => $row['email'],
                'nis'          => $row['nis'] ?? null,
                'nisn'         => $row['nisn'] ?? null,
                'kelas'        => $row['kelas'],
                'kejuruan'     => $row['kejuruan'],
                'reason'       => 'Email, NIS, atau NISN sudah ada',
            ];
            return null;
        }

        // Cari kelas
        $kelas = DataKelas::where('kelas', $row['kelas'])
            ->orWhere('kode', $row['kelas'])
            ->first();
        if (!$kelas) {
            $this->failedRows[] = [
                'nama_lengkap' => $row['nama_lengkap'],
                'email'        => $row['email'],
                'nis'          => $row['nis'] ?? null,
                'nisn'         => $row['nisn'] ?? null,
                'kelas'        => $row['kelas'],
                'kejuruan'     => $row['kejuruan'],
                'reason'       => 'Kelas tidak ditemukan',
            ];
            return null;
        }

        // Cari kejuruan
        $kejuruan = DataKejuruan::where('nama_kejuruan', $row['kejuruan'])
            ->orWhere('kode', $row['kejuruan'])
            ->first();
        if (!$kejuruan) {
            $this->failedRows[] = [
                'nama_lengkap' => $row['nama_lengkap'],
                'email'        => $row['email'],
                'nis'          => $row['nis'] ?? null,
                'nisn'         => $row['nisn'] ?? null,
                'kelas'        => $row['kelas'],
                'kejuruan'     => $row['kejuruan'],
                'reason'       => 'Kejuruan tidak ditemukan',
            ];
            return null;
        }

        // Buat user siswa
        $user = User::create([
            'name'     => $row['nama_lengkap'],
            'email'    => $row['email'],
            'password' => Hash::make(env('DEFAULT_SISWA_PASSWORD', 'password')),
            'role'     => 'siswa',
        ]);

        return new DataSiswa([
            'user_id'       => $user->id,
            'nama_lengkap'  => $row['nama_lengkap'],
            'nis'           => $row['nis'] ?? null,
            'nisn'          => $row['nisn'] ?? null,
            'kelas_id'      => $kelas->id,
            'kejuruan_id'   => $kejuruan->id,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? 'Laki-laki',
            'agama'         => $row['agama'] ?? 'Islam',
            'status'        => 'Aktif',
        ]);
    }
}
