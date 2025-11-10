<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WilayahTugas extends Model
{
    use HasFactory;

    protected $table = 'bloksensus';
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $fillable = [
        'id_kec',
        'id_desa',
        'id_bs',
        'id_nks',
        'id_user', 
        'nama',
    ];

    // Relasi ke Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kec', 'id_kec');
    }

    // Relasi ke Desa
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa', 'id_desa');
    }

    // Relasi ke User (Pengawas)
    public function pengawas()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke DSRT
    public function dsrt()
    {
        return $this->hasMany(Dsrt::class, 'id_bs', 'id_bs');
    }
}
