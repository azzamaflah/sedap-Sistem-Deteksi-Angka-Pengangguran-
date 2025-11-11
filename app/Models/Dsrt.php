<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dsrt extends Model
{
    use HasFactory;

    protected $table = 'dsrt';
    protected $primaryKey = 'no';
    public $timestamps = true;

    protected $fillable = [
        'id_kec',
        'id_desa',
        'id_bs',
        'id_nks',
        'id_nurt',
        'respon', // Ganti dari 'hasil_pencacahan' jadi 'respon'
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

    // Relasi ke Wilayah Tugas (Blok Sensus)
    public function wilayahTugas()
    {
        return $this->belongsTo(WilayahTugas::class, 'id_bs', 'id_bs');
    }

    // Relasi ke Responden
    public function responden()
    {
        return $this->hasMany(Responden::class, 'id_nurt', 'id_nurt');
    }
}
