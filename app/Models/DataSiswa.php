<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSiswa extends Model
{
    use HasFactory;

    protected $table = 'data_siswa';

    protected $fillable = [
        'user_id',
        'status',
        'nama_lengkap',
        'asal_sekolah',
        'tempat_tanggal_lahir',
        'nis',
        'nisn',
        'jenis_kelamin',
        'agama',
        'alamat',
        'rt',
        'rw',
        'kecamatan',
        'kota_kabupaten',
        'kode_pos',
        'telepon',
        'kelas_id',
        'kejuruan_id',
        'jabatan_siswa',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(DataKelas::class, 'kelas_id');
    }

    // Relasi ke Kejuruan
    public function kejuruan()
    {
        return $this->belongsTo(DataKejuruan::class, 'kejuruan_id');
    }

    // Relasi ke PresensiSiswa
    public function presensi()
    {
        return $this->hasMany(PresensiSiswa::class, 'siswa_id');
    }
}
