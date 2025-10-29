<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ✅ Pastikan pakai tabel users (default Laravel Breeze)
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',     // ✅ Kolom yang akan ditampilkan sebagai nama pengawas
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

    // ✅ TAMBAHAN: Relasi ke WilayahTugas (One User -> Many WilayahTugas)
    public function wilayahTugas()
    {
        return $this->hasMany(WilayahTugas::class, 'id_user', 'id');
        // Parameter:
        // - WilayahTugas::class = Model tujuan
        // - 'id_user' = Foreign key di tabel bloksensus
        // - 'id' = Primary key di tabel users
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

    // ✅ TAMBAHAN: Method helper untuk mendapatkan wilayah tugas user
    public function getWilayahTugasCount()
    {
        return $this->wilayahTugas()->count();
    }

    // ✅ TAMBAHAN: Scope untuk filter user yang punya wilayah tugas
    public function scopeHasWilayahTugas($query)
    {
        return $query->whereHas('wilayahTugas');
    }

    // ✅ TAMBAHAN: Scope untuk filter user yang tidak punya wilayah tugas
    public function scopeWithoutWilayahTugas($query)
    {
        return $query->whereDoesntHave('wilayahTugas');
    }
}
