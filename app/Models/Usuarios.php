<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = 'usuarios';
    protected $hidden = ['clave'];

    protected $fillable = [
        'nombre',
        'rol',
        'correo',
        'clave',
    ];
}