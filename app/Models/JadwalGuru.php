<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalGuru extends Model
{
    use HasFactory;

    protected $table = 'jadwal_guru';

    protected $fillable = [
        'hari',
        'sesi',
        'jam_mulai',
        'jam_selesai',
        'guru_id',
        'kelas_id',
        'mapel_id',
        'jumlah_jam',
    ];

    protected $casts = [
        'jam_mulai'   => 'string',
        'jam_selesai' => 'string',
    ];

    /**
     * Mutator untuk selalu set jumlah_jam = 1
     */
    public function setJumlahJamAttribute($value)
    {
        $this->attributes['jumlah_jam'] = 1;
    }

    /** ==========================
     *  RELASI MODEL
     * ========================== */

    // Relasi ke DataGuru
    public function guru()
    {
        return $this->belongsTo(DataGuru::class, 'guru_id', 'id');
    }

    // Relasi ke DataKelas
    public function kelas()
    {
        return $this->belongsTo(DataKelas::class, 'kelas_id', 'id');
    }

    // Relasi ke DataMapel
    public function mapel()
    {
        return $this->belongsTo(DataMapel::class, 'mapel_id', 'id');
    }
}
