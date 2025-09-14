<?php
namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    // Mostrar todos los pacientes
   public function index(Request $request)
{
    // Con eager loading de ultimoExamen para evitar consultas N+1
    $query = Paciente::query();

    if ($request->filled('buscar')) {
        $buscar = $request->input('buscar');
        $query->where('nombre', 'like', "%{$buscar}%")
              ->orWhere('cedula', 'like', "%{$buscar}%")
              ->orWhere('correo', 'like', "%{$buscar}%");
    }

    $pacientes = $query->orderBy('nombre')->paginate(15);

    return view('pacientes', compact('pacientes'));
}
    // Mostrar el formulario para crear un nuevo paciente
    public function create()
    {
        return view('pacientes_create');
    }

    // Guardar un nuevo paciente
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer',
            'sexo' => 'required|string',
            'cedula' => 'required|string|unique:pacientes',
            'telefono' => 'required|string',
            'correo' => 'required|email|unique:pacientes',
            'informacion' => 'nullable|string',
            'tipo_paciente' => 'required|string',
        ]);

        Paciente::create($request->all());

        return redirect()->route('pacientes.index')->with('success', 'Paciente registrado correctamente.');
    }

    // Mostrar un paciente específico
    public function show($id)
    {
       $paciente = Paciente::with('imagenes')->findOrFail($id);
    return view('pacientes_show', compact('paciente'));
 }

    // Mostrar el formulario para editar un paciente
    public function edit(Paciente $paciente)
    {
        return view('pacientes_edit', compact('paciente'));
    }

    // Actualizar un paciente
    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer',
            'sexo' => 'required|string',
            'cedula' => 'required|string|unique:pacientes,cedula,' . $paciente->id,
            'telefono' => 'required|string',
            'correo' => 'required|email|unique:pacientes,correo,' . $paciente->id,
            'informacion' => 'nullable|string',
            'tipo_paciente' => 'required|string',
        ]);

        $paciente->update($request->all());

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    // Eliminar un paciente
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado correctamente.');
    }
}