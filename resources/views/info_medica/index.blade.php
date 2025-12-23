<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8", name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historias Médicas</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <x-navbar title="Historias Médicas"/>
    <div class="container">
        <h2>Listado de Historias Médicas</h2>
        <a href="{{ route('info_medica.create') }}" class="btn btn-primary" style="background: #255caf; color: #fff; padding: 10px 18px; border-radius: 4px; text-decoration: none; font-weight: 600;">
            + Nueva Historia Médica
        </a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Cédula</th>
                    <th>Tipo de Examen</th>
                    <th>Información</th>
                    <th>Fecha de Registro</th>
                    <th>DICOM</th>
                    <th style="width: 140px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($info_medica as $info)
                    <tr>
                        <td>{{ $info->paciente->nombre ?? 'Desconocido' }}</td>
                        <td>{{ $info->paciente->cedula ?? 'N/A' }}</td>
                        <td>{{ $info->tipo_examen }}</td>
                        <td class="truncate-text">{{ $info->informacion }}</td>
                        <td>{{ $info->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if(!empty($info->orthanc_study_id))
                                <a href="{{ $orthanc_url . config('orthanc.viewer_path') }}?study={{ $info->orthanc_study_id }}" 
                                   target="_blank"
                                   style="background: #4a90e2; color: #fff; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px;">
                                    Ver Viewer
                                </a>
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            <a href="{{ route('info_medica.show', $info) }}" 
                               title="Visualizar historia"
                               style="background: #17a2b8; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: 600; margin-right: 6px;">
                                👁️
                            </a>
                            <a href="{{ route('info_medica.edit', $info) }}" 
                               title="Editar historia"
                               style="background: #ffc107; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: 600; margin-right: 6px;">
                                ✏️
                            </a>
                            <form action="{{ route('info_medica.destroy', $info) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        title="Eliminar historia"
                                        style="background: #dc3545; color: #fff; padding: 6px 12px; border: none; border-radius: 4px; font-weight: 600; cursor:pointer;"
                                        onclick="return confirm('¿Seguro que deseas eliminar esta historia médica?')">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">No hay historias médicas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
