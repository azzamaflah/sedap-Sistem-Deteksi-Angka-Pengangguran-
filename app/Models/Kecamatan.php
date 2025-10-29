<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kec';
    protected $primaryKey = 'id_kec';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id_kec', 'nama_kec'];

    public function desa()
    {
        return $this->hasMany(Desa::class, 'id_kec', 'id_kec');
    }
}
