<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// use App\Traits\Cachable;

class User extends Authenticatable
{
    // use HasApiTokens, HasFactory, Notifiable, Cachable;
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'last_activity',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_activity' => 'datetime',
    ];

    // ✅ Flexible role check
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function foto_profil()
    {
        return $this->hasOne(\App\Models\FotoProfil::class);
    }

    // Relasi Visitors
    public function visitors()
    {
        return $this->hasMany(Visitor::class, 'user_id', 'id');
    }

    public function getIsOnlineAttribute()
    {
        return $this->last_activity && $this->last_activity->gt(now()->subMinutes(5));
    }

    public function guru()
    {
        return $this->hasOne(\App\Models\DataGuru::class, 'user_id', 'id');
    }

    // User.php
    public function dataSiswa()
    {
        return $this->hasOne(\App\Models\DataSiswa::class, 'user_id', 'id');
    }

}
