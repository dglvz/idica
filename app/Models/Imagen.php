<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{   
    protected $fillable = ['paciente_id', 'ruta', 'thumbnail','descripcion'];
    protected $table = 'imagenes';
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
    public function infoMedica()
{
    return $this->belongsTo(InfoMedica::class, 'info_medica_id');
}
}
