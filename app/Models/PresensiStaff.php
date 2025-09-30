<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiStaff extends Model
{
    use HasFactory;

    protected $table = 'presensi_staff';

    protected $fillable = [
        'user_id',
        'staff_id',
        'tanggal',
        'bulan',
        'tahun',
        'keterangan',
        'apel',
        'upacara',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
