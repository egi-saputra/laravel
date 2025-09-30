<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiSiswa extends Model
{
    use HasFactory;

    protected $table = 'presensi_siswa';

    protected $fillable = [
        'user_id',
        'siswa_id',
        'keterangan',
        'is_selesai', // tambahkan ini
    ];

    protected $casts = [
        'is_selesai' => 'boolean', // agar 0/1 otomatis jadi false/true
    ];

    // Relasi ke user (guru/walas yang melakukan presensi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(DataSiswa::class, 'siswa_id');
    }
}
