<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DataStruktural extends Model
{
    use HasFactory;

    protected $table = 'data_struktural';

    protected $fillable = [
        'jabatan',
        'nama_gtk', // kolom ini akan berisi user_id
    ];

    /**
     * Relasi ke tabel users
     * kolom nama_gtk merujuk ke id di tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'nama_gtk', 'id');
    }
}
