<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Document</title>
</head>
<style>
    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    form {
        display: flex;
        flex-direction: column;
    }
    label {
        margin-top: 10px;
        font-weight: bold;
    }
    input, select, textarea {
        margin-top: 5px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    button {
        margin-top: 20px;
        padding: 10px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    button:hover {
        background-color: #218838;
    }
    </style>
<body>
        <x-navbar title="Historias Médicas"/>
   <div class="container">
    <h2>Crear Nueva Historia Médica</h2>
       <form action="{{ route('info_medica.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label for="paciente_id">Seleccionar Paciente:</label>
    <select name="paciente_id" id="paciente_id" required>
        @foreach($pacientes as $paciente)
            <option value="{{ $paciente->id }}" {{ request('paciente_id') == $paciente->id ? 'selected' : '' }}>
                {{ $paciente->nombre }} - {{ $paciente->cedula }}
            </option>
        @endforeach
    </select>

    <!-- Campos de información médica -->
    <label for="informacion">Información Médica:</label>
    <textarea name="informacion" id="informacion"></textarea>

    <label for="tipo_examen">Tipo de Examen:</label>
    <input type="text" name="tipo_examen" id="tipo_examen" required>

    <!-- Campos para imagen -->
    <label for="imagen">Imagen (opcional):</label>
    <input type="file" name="imagen" id="imagen" accept="image/*">

    <label for="descripcion_imagen">Descripción de la imagen (opcional):</label>
    <input type="text" name="descripcion_imagen" id="descripcion_imagen">

    <!-- Campo para archivo DICOM -->
    <label for="archivo_dicom">Archivo DICOM (opcional):</label>
    <input type="file" name="archivo_dicom" id="archivo_dicom" accept=".dcm,application/dicom">

    <button type="submit">Guardar</button>
</form>
</body>
</html>