<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Información detallada</title>
    <style>
        .details-wrapper {
            display: flex;
            gap: 24px;
        }
        .details-main {
            flex: 1;
        }
        .details-medical {
            flex: 0 0 300px;
            background-color: #f1f1f1;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 0 6px rgba(0,0,0,0.05);
        }
        .details-medical h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <x-navbar title="Información detallada"/> 

    <div class="dashboard-container">
        <h2>Detalles de {{ $paciente->nombre }}</h2>

        <div class="details-wrapper">
            <!-- Bloque principal: datos y galerías -->
            <div class="details-main">
                <ul>
                    <li>Edad: {{ $paciente->edad }}</li>
                    <li>Sexo: {{ $paciente->sexo }}</li>
                    <li>Cédula: {{ $paciente->cedula }}</li>
                    <li>Teléfono: {{ $paciente->telefono }}</li>
                    <li>Correo: {{ $paciente->correo }}</li>
                    <li>Tipo: {{ $paciente->tipo_paciente }}</li>
                </ul>

                <h3>Imágenes asociadas</h3>
                @if($paciente->imagenes->isEmpty())
                    <p>No hay imágenes cargadas para este paciente.</p>
                @else
                    <div style="display:flex; gap:16px; flex-wrap: wrap;">
                        @foreach($paciente->imagenes as $img)
                            <div style="width:300px; position: relative;">
                                <a href="{{ asset('storage/' . $img->ruta) }}" target="_blank">
                                    <img src="{{ asset('storage/' . ($img->thumbnail ?? $img->ruta)) }}"
                                         alt="{{ $img->descripcion }}"
                                         style="width:100%; border-radius:14px; border:1px solid #ccc;">
                                </a>

                                <form action="{{ route('pacientes.imagenes.destroy', [$paciente, $img]) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar esta imagen?');"
                                      style="position:absolute; top:8px; right:8px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="background:rgba(255, 0, 0, 1); color:#fff;
                                                   border:none; border-radius:50%;
                                                   width:24px; height:24px; line-height:0;
                                                   cursor:pointer;">
                                        &times;
                                    </button>
                                </form>

                                @if($img->descripcion)
                                    <p style="font-size:0.9em; margin-top:4px;">{{ $img->descripcion }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div style="margin:24px 0;">
                    <h4>Agregar nueva imagen</h4>
                    @if(session('success'))
                        <p style="color:green;">{{ session('success') }}</p>
                    @endif

                    <form action="{{ route('pacientes.imagenes.store', $paciente) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="imagen" accept="image/*" required>
                        <input type="text" name="descripcion" placeholder="Descripción (opcional)">
                        <button type="submit"
                                style="background:#17a2b8; color:#fff; padding:8px 16px; border:none; border-radius:4px; margin-left:8px;">
                            Subir
                        </button>
                    </form>

                    @error('imagen')
                        <p style="color:red;">{{ $message }}</p>
                    @enderror
                </div>

                <a href="{{ route('pacientes.index') }}"
                   style="display:inline-block; margin-top:24px; padding:8px 16px; background:#f53003; color:#fff; text-decoration:none; border-radius:4px;">
                   ← Volver a la lista
                </a>
            </div>

            <!-- Nuevo bloque a la derecha: Info. Médica -->
            <div class="details-medical">
                <h3>Información Médica</h3>
                <div style="margin-bottom: 15px;">
                    <a href="{{ route('info_medica.create', ['paciente_id' => $paciente->id]) }}" 
                       style="background: #255caf; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
                        + Agregar Examen
                    </a>
                </div>

                @if($paciente->infoMedica->isEmpty())
                    <p>Sin registros de exámenes médicos.</p>
                @else
                    <ul style="list-style: none; padding: 0;">
                        @foreach($paciente->infoMedica as $info)
                            <li style="background: #fff; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 6px; opacity: {{ $info->status === 'processing' ? '0.6' : '1' }};">
                                <strong>{{ $info->tipo_examen }}</strong> <br>
                                <small style="color: #666;">{{ $info->created_at->format('d/m/Y') }}</small>
                                <p style="margin: 5px 0; font-size: 0.9em;">{{ $info->informacion }}</p>
                                
                                @if($info->status === 'completed' && !empty($info->orthanc_study_id))
                                    <a href="{{ $orthanc_url . config('orthanc.viewer_path') }}?study={{ $info->orthanc_study_id }}" 
                                       target="_blank"
                                       style="display: inline-block; margin-top: 5px; background: #4a90e2; color: #fff; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px;">
                                        Ver DICOM en Orthanc
                                    </a>
                                @elseif($info->status === 'processing')
                                    <small style="display: inline-block; margin-top: 5px; background: #f0ad4e; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Procesando...</small>
                                @elseif($info->status === 'failed')
                                    <small style="display: inline-block; margin-top: 5px; background: #d9534f; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Falló</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
