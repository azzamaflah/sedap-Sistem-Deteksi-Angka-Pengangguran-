<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustom extends Model
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama',
        'user_name',
        'password',
        'email',
    ];

    protected $hidden = [
        'password',
    ];
}
