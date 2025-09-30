<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoProfil extends Model
{
    protected $table = 'foto_profil';
    protected $fillable = ['user_id', 'file_name', 'file_path'];
}
