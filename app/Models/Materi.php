<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'mapel_id',
        'judul',
        'materi',
        'file_name',
        'file_path',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke DataKelas
    public function kelas()
    {
        return $this->belongsTo(DataKelas::class, 'kelas_id');
    }

    // ✅ Relasi ke Mapel
    public function mapel()
    {
        return $this->belongsTo(DataMapel::class, 'mapel_id');
    }
}
