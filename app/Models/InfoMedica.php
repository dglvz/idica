<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoMedica extends Model
{
    protected $table = 'info_medica';
    protected $primaryKey = 'id_historia';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
    'paciente_id',
    'informacion',
    'tipo_examen',
    'orthanc_study_id',
    ];

    public function paciente()
{
    return $this->belongsTo(Paciente::class, 'paciente_id');
}

    public function imagenes()
    {
        return $this->hasMany(Imagen::class, 'info_medica_id');
    }   
}


