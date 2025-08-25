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

    <div class="container">
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
                <p>
                    {{ optional($paciente->ultimoExamen)->informacion 
                       ?? 'Sin registros de exámenes médicos.' }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
