<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'username',      // ✅ TAMBAHKAN
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke WilayahTugas
    public function wilayahTugas()
    {
        return $this->hasMany(WilayahTugas::class, 'id_user', 'id');
    }

    // Method helper untuk cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function getWilayahTugasCount()
    {
        return $this->wilayahTugas()->count();
    }

    public function scopeHasWilayahTugas($query)
    {
        return $query->whereHas('wilayahTugas');
    }

    public function scopeWithoutWilayahTugas($query)
    {
        return $query->whereDoesntHave('wilayahTugas');
    }
}
