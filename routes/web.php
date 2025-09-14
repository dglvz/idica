<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/registrarusuarios', function () {
    return view('registrarUsuarios');
});

Route::get('/inicio', function () {
    return view('inicio');
});    

use App\Models\Paciente;    
use App\Http\Controllers\PacienteController;

Route::resource('pacientes', PacienteController::class);

use App\Http\Controllers\ImagenController;
use App\Models\InfoMedica;

Route::post(
    'pacientes/{paciente}/imagenes',
    [ImagenController::class, 'store']
)->name('pacientes.imagenes.store');

Route::delete(
    'pacientes/{paciente}/imagenes/{imagen}',
    [ImagenController::class, 'destroy']
)->name('pacientes.imagenes.destroy');

use App\Http\Controllers\InfoMedicaController;

Route::resource('info_medica', InfoMedicaController::class);
Route::get('/info_medica/create', [InfoMedicaController::class, 'create'])->name('info_medica.create');

/*Route::get('/examenes', function () {
    return view('examenes.index');
});*/
use App\Http\Controllers\UsuariosController;
Route::resource('usuarios', UsuariosController::class);