<?php

namespace App\Imports;

use App\Models\User;
use App\Models\DataSiswa;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WalasSiswaImport implements ToModel, WithHeadingRow
{
    protected $kelas_id;
    public $failedRows = [];

    public function __construct($kelas_id)
    {
        $this->kelas_id = $kelas_id;
    }

    public function model(array $row)
    {
        // Skip baris kosong total (nama_lengkap, email, nisn semuanya kosong)
        if (empty($row['nama_lengkap']) && empty($row['email']) && empty($row['nisn'])) {
            return null;
        }

        try {
            // Minimal nama_lengkap & email wajib
            if (empty($row['nama_lengkap']) || empty($row['email'])) {
                throw new \Exception('Kolom wajib kosong');
            }

            // cek unik email, nis, nisn jika ada
            if (User::where('email', $row['email'])->exists()) {
                throw new \Exception('Email sudah digunakan');
            }

            if (!empty($row['nis']) && DataSiswa::where('nis', $row['nis'])->exists()) {
                throw new \Exception('NIS sudah digunakan');
            }

            if (!empty($row['nisn']) && DataSiswa::where('nisn', $row['nisn'])->exists()) {
                throw new \Exception('NISN sudah digunakan');
            }

            // Ambil jabatan_siswa dari file jika ada, jika tidak default 'Tidak Ada'
            $jabatan = $row['jabatan_siswa'] ?? 'Tidak Ada';
            // Pastikan value valid sesuai ENUM
            if (!in_array($jabatan, ['Tidak Ada','Sekretaris','Bendahara'])) {
                $jabatan = 'Tidak Ada';
            }

            // buat user siswa
            $user = User::create([
                'name'     => $row['nama_lengkap'],
                'email'    => $row['email'],
                'password' => Hash::make('password'),
                'role'     => 'siswa',
            ]);

            // buat data siswa
            return new DataSiswa([
                'user_id'      => $user->id,
                'nama_lengkap' => $row['nama_lengkap'],
                'nis'          => $row['nis'] ?? null,
                'nisn'         => $row['nisn'] ?? null,
                'kelas_id'     => $this->kelas_id,
                'jabatan_siswa'=> $jabatan,
            ]);

        } catch (\Exception $e) {
            $this->failedRows[] = [
                'nama_lengkap' => $row['nama_lengkap'] ?? '',
                'email'        => $row['email'] ?? '',
                'nis'          => $row['nis'] ?? '',
                'nisn'         => $row['nisn'] ?? '',
                'kelas'        => '-',
                'reason'       => $e->getMessage()
            ];
            return null;
        }
    }
}
