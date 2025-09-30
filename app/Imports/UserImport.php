<?php

namespace App\Imports;

use App\Models\User;
use App\Models\DataSiswa;
use App\Models\DataGuru;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Buat user baru
        $user = new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'password' => Hash::make($row['password']),
            'role'     => $row['role'] === 'admin' ? 'user' : strtolower($row['role']),
        ]);

        $user->save();

        // Jika role siswa, otomatis tambahkan ke data_siswa
        if ($user->role === 'siswa') {
            DataSiswa::create([
                'users_id'     => $user->id,
                'nama_lengkap' => $user->name,
                'status'       => 'Aktif',
            ]);
        }

        // Jika role guru, otomatis tambahkan ke data_guru
        if ($user->role === 'guru') {
            DataGuru::create([
                'user_id' => $user->id,
                'nama'    => $user->name,
                'kode'    => 'G' . str_pad($user->id, 3, '0', STR_PAD_LEFT), // kode guru otomatis
            ]);
        }

        return $user;
    }

    public function rules(): array
    {
        return [
            '*.name'     => ['required', 'string', 'max:255'],
            '*.email'    => ['required', 'email', 'unique:users,email'],
            '*.password' => ['required', 'string', 'min:6'],
            '*.role'     => ['required', 'in:user,siswa,guru,staff'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.role.in' => 'Role tidak valid. Hanya diperbolehkan: user, siswa, guru, staff.',
        ];
    }
}
