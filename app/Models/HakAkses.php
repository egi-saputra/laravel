<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HakAkses extends Model
{
    use HasFactory;

    protected $table = 'hak_akses';

    protected $fillable = [
        'guru_id',
        'status',
    ];

    // Default attributes
    protected $attributes = [
        'status' => 'Deactivated',
    ];

    public function guru()
    {
        return $this->belongsTo(DataGuru::class, 'guru_id');
    }
}
