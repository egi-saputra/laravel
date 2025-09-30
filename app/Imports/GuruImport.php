<?php

namespace App\Imports;

use App\Models\DataGuru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Jika kode kosong, skip
        if (empty($row['kode'])) {
            return null;
        }

        // Cek apakah kode sudah ada di tabel data_guru
        if (DataGuru::where('kode', $row['kode'])->exists()) {
            return null;
        }

        // Cek apakah email ada
        if (empty($row['email'])) {
            return null; // email wajib
        }

        // Jika email sudah ada, skip
        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        // Buat user baru
        $user = User::create([
            'name'     => $row['nama'] ?? '-', // default "-" kalau kosong
            'email'    => $row['email'],
            'password' => Hash::make(env('DEFAULT_GURU_PASSWORD', 'password')),
            'role'     => 'guru',
        ]);

        // Simpan ke tabel data_guru
        return new DataGuru([
            'kode'    => $row['kode'],
            'user_id' => $user->id,
        ]);
    }
}
