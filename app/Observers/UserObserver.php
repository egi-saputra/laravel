<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        // Jika user punya relasi siswa
        if ($user->role === 'siswa' && $user->dataSiswa) {
            $user->dataSiswa()->update([
                'nama_lengkap' => $user->name,
            ]);
        }

        // Jika user punya relasi guru
        if ($user->role === 'guru' && $user->dataGuru) {
            $user->dataGuru()->update([
                'nama_lengkap' => $user->name,
            ]);
        }
    }

    public function updated(User $user)
    {
        if ($user->wasChanged('name')) {
            // Sinkron ke siswa
            if ($user->role === 'siswa' && $user->dataSiswa && $user->dataSiswa->nama_lengkap !== $user->name) {
                $user->dataSiswa()->update([
                    'nama_lengkap' => $user->name,
                ]);
            }

            // Sinkron ke guru
            if ($user->role === 'guru' && $user->dataGuru && $user->dataGuru->nama_lengkap !== $user->name) {
                $user->dataGuru()->update([
                    'nama_lengkap' => $user->name,
                ]);
            }
        }
    }
}
