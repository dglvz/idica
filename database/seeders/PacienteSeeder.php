<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paciente;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        Paciente::create([
            'nombre' => 'Juan Pérez',
            'edad' => 30,
            'sexo' => 'Masculino',
            'cedula' => '12345678',
            'telefono' => '04141234567',
            'correo' => 'juan.perez@example.com',
            'informacion' => 'Paciente sin antecedentes.',
            'tipo_paciente' => 'Regular',
        ]);

        Paciente::create([
            'nombre' => 'María Gómez',
            'edad' => 25,
            'sexo' => 'Femenino',
            'cedula' => '87654321',
            'telefono' => '04147654321',
            'correo' => 'maria.gomez@example.com',
            'informacion' => 'Alergia a penicilina.',
            'tipo_paciente' => 'Nuevo',
        ]);
    }
}
