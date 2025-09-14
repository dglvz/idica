<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;

class UsuariosController extends Controller
{
    // Listar todos los usuarios
    public function index(Request $request)
    {
     $query = Usuarios::query();

    if ($request->filled('buscar')) {
        $buscar = $request->input('buscar');
        $query->where('nombre', 'like', "%{$buscar}%")
              ->orWhere('rol', 'like', "%{$buscar}%")
              ->orWhere('correo', 'like', "%{$buscar}%");
    }

    $usuarios = $query->orderBy('nombre')->paginate(15);

    return view('usuarios.index', compact('usuarios'));
    }

    // Mostrar un usuario específico
    public function show($id)
    {
        return Usuarios::findOrFail($id);
    }

    // Crear un nuevo usuario
   public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'rol' => 'required|string|max:50',
        'correo' => 'required|email|unique:usuarios,correo',
        'clave' => 'required|string|min:6',
    ]);

    // Guarda el usuario (puedes encriptar la clave si lo deseas)
    $validated['clave'] = bcrypt($validated['clave']);
    Usuarios::create($validated);

    return redirect('/')->with('success', 'Usuario registrado correctamente. Ahora puedes iniciar sesión.');
}
    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        $usuario = Usuarios::findOrFail($id);
        $usuario->update($request->all());
        return response()->json($usuario, 200);
    }

    // Eliminar un usuario
    public function destroy(Usuarios $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function create()
    {
        return view('usuarios.create');
    }   
}
