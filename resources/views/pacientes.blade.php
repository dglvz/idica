
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pacientes</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body>
    <x-navbar title="Pacientes"/>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Lista de Pacientes</h2>
            <a href="{{ route('pacientes.create') }}" style="background: #f53003; color: #fff; padding: 10px 18px; border-radius: 4px; text-decoration: none; font-weight: 600;">
                + Agregar Paciente
            </a>
        </div>
        <table>
            <thead>
                <tr>
                    <!--<th>ID</th>-->
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Sexo</th>
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Tipo de Paciente</th>
                    <th>Información</th>
                    <th style="width: 140px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pacientes as $paciente)
                    <tr>
                        <!--<td>{{ $paciente->id }}</td>-->
                        <td>{{ $paciente->nombre }}</td>
                        <td>{{ $paciente->edad }}</td>
                        <td>{{ $paciente->sexo }}</td>
                        <td>{{ $paciente->cedula }}</td>
                        <td>{{ $paciente->telefono }}</td>
                        <td>{{ $paciente->correo }}</td>
                        <td>{{ $paciente->tipo_paciente }}</td>
                        <td>{{ $paciente->informacion }}</td>
                        <td style="white-space: nowrap;">
                            <a href="{{ route('pacientes.show', $paciente->id) }}" 
                             title="Visualizar paciente"
                            style="background: #17a2b8; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: 600; margin-right: 6px;">
                                            👁️
                             <a href="{{ route('pacientes.edit', $paciente->id) }}" 
                              title="Editar paciente"
                                 style="background: #ffc107; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: 600; margin-right: 6px;">
                                            ✏️
                            </a>
                             <form action="{{ route('pacientes.destroy', $paciente->id) }}" method="POST" style="display:inline;">
                              @csrf
                             @method('DELETE')
                                <button type="submit" 
                                     title="Eliminar paciente"
                                      style="background: #dc3545; color: #fff; padding: 6px 12px; border: none; border-radius: 4px; font-weight: 600; cursor:pointer;"
                                      onclick="return confirm('¿Seguro que deseas eliminar este paciente?')">
                                             🗑️
                                </button>
                             </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">No hay pacientes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>