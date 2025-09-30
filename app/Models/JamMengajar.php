<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamMengajar extends Model
{
    use HasFactory;

    protected $table = 'jam_mengajar'; // jika tabelnya beda dari nama plural default

    protected $fillable = [
        'jadwal_id',
        'guru_id',
        'jumlah_jam',
        // tambahkan kolom lain jika ada
    ];
}
