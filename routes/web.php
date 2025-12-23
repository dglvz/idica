<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
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

use App\Http\Controllers\OrthancController;
// routes/web.php
Route::get('/orthanc/patients', [OrthancController::class, 'patients']);
Route::get('/orthanc/studies', [OrthancController::class, 'studies']);
Route::get('/orthanc/study/{id}', [OrthancController::class, 'study']);
Route::post('/orthanc/upload', [OrthancController::class, 'uploadDicom']);

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Asegúrate de que las rutas 'usuarios.index' e 'inicio' existan y estén protegidas por middleware auth si es necesario.
