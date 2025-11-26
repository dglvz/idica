
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: 'Instrument Sans', sans-serif; background: #fdfdfc; margin: 0; }
        .container {
            max-width: 500px;
            margin: 48px auto 0 auto;
            padding: 32px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #0001;
        }
        h2 { margin-bottom: 24px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 500; }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #e3e3e0;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #f53003;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background: #d42a00; }
    </style>
</head>
<body>
   <x-navbar title="Editar Paciente"/> 
    <div class="container">
        <h2>Editar Paciente</h2>
        <form method="POST" action="{{ route('pacientes.update', $paciente->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $paciente->nombre) }}" required>
            </div>
            <div class="form-group">
                <label for="edad">Edad</label>
                <input type="number" id="edad" name="edad" value="{{ old('edad', $paciente->edad) }}" required>
            </div>
            <div class="form-group">
                <label for="sexo">Sexo</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Seleccione</option>
                    <option value="Masculino" {{ old('sexo', $paciente->sexo) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ old('sexo', $paciente->sexo) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="Otro" {{ old('sexo', $paciente->sexo) == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cedula">Cédula</label>
                <input type="text" id="cedula" name="cedula" value="{{ old('cedula', $paciente->cedula) }}" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $paciente->telefono) }}" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo', $paciente->correo) }}" required>
            </div>
            <div class="form-group">
                <label for="tipo_paciente">Tipo de Paciente</label>
                <input type="text" id="tipo_paciente" name="tipo_paciente" value="{{ old('tipo_paciente', $paciente->tipo_paciente) }}" required>
            </div>
            <div class="form-group">
                <label for="informacion">Información Adicional</label>
                <textarea id="informacion" name="informacion" rows="3">{{ old('informacion', $paciente->informacion) }}</textarea>
            </div>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>