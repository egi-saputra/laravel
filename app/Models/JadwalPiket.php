<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataGuru;
use App\Models\User;

class JadwalPiket extends Model
{
    use HasFactory;

    protected $table = 'jadwal_piket';
    protected $fillable = ['hari', 'petugas'];

    /**
     * Relasi ke DataGuru (guru yang bertugas)
     */
    public function guru()
    {
        return $this->belongsTo(DataGuru::class, 'petugas', 'id');
    }

    /**
     * Relasi ke User melalui DataGuru
     * Bisa akses langsung: $jadwal->user->name
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,       // model tujuan
            DataGuru::class,   // model perantara
            'id',              // foreign key DataGuru di JadwalPiket -> 'petugas'
            'id',              // foreign key User -> 'id'
            'petugas',         // local key di JadwalPiket -> 'petugas'
            'user_id'          // local key di DataGuru -> 'user_id'
        );
    }
}
