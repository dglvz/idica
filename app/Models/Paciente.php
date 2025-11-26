<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'edad',
        'sexo',
        'cedula',
        'telefono',
        'correo',
        'informacion',
        'tipo_paciente',
    ];

    public function imagenes()
    {
        return $this->hasMany(Imagen::class);
    }

    public function historiasMedicas()
{
    return $this->hasMany(InfoMedica::class, 'paciente_id');
}

public function ultimoExamen(): HasOne
    {
        // Laravel ≥8: latestOfMany(); en versiones anteriores, usa orderBy + limit(1)
        return $this->hasOne(InfoMedica::class)
                    ->latestOfMany('created_at');
    }
}