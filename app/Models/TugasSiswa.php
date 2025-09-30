<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasSiswa extends Model
{
    use HasFactory;

    protected $table = 'tugas_siswa';

    protected $fillable = [
        'user_id',
        'judul',
        'nama',
        'kelompok',
        'kelas_id',
        'mapel_id',
        'file_name',
        'file_path',
    ];

    public function kelas()
    {
        return $this->belongsTo(DataKelas::class, 'kelas_id');
    }

    public function mapel()
    {
        return $this->belongsTo(DataMapel::class, 'mapel_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
