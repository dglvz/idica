
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <title>Document</title>
</head>
<body>
    <x-navbar title="Usuarios"/>
    <div class="container">
        <h2>Listado de Usuarios</h2>
       
        <form method="GET" action="{{ route('usuarios.index') }}" style="margin-bottom: 16px;" display: flex; gap: 8px;">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar usuario..." style="padding: 7px; border-radius: 4px; border: 1px solid #ccc;">
        <button type="submit" style="background: #17a2b8; color: #fff; padding: 7px 14px; border-radius: 4px; border: none; font-weight: 600;">Buscar</button>
    </form>
    
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary" style="background: #f53003; color: #fff; padding: 10px 18px; border-radius: 4px; text-decoration: none; font-weight: 600;">
            + Nuevo Usuario
        </a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Correo</th>
                    <th>Fecha de Registro</th>
                    <th style="width: 140px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>{{ $usuario->correo }}</td>
                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td style="white-space: nowrap;">
                            <a href="{{     route('usuarios.show', $usuario) }}" 
                               title="Visualizar usuario"
                               style="background: #17a2b8; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: 600; margin-right: 6px;">
                                👁️
                            </a>
                            <a href="{{ route('usuarios.edit', $usuario) }}" 
                               title="Editar usuario"
                               style="background: #ffc107; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: 600; margin-right: 6px;">
                                ✏️
                            </a>
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        title="Eliminar usuario"
                                        style="background: #dc3545; color: #fff; padding: 6px 12px; border: none; border-radius: 4px; font-weight: 600; cursor:pointer;"
                                        onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>