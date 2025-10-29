<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desa';
    protected $primaryKey = 'id_desa';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id_desa', 'id_kec', 'nama_desa', 'nama_kec'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kec', 'id_kec');
    }
}
