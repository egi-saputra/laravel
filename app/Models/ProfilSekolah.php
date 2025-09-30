<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolah';
    protected $fillable = [
        'file_name',
        'file_path',
        'nama_sekolah',
        'akreditasi',
        'kepala_yayasan',
        'kepala_sekolah',
        'npsn',
        'no_izin',
        'nss',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'provinsi',
        'kecamatan',
        'kabupaten_kota',
        'kode_pos',
        'telepon',
        'email',
        'website',
        'visi',
        'misi',
    ];
}
