
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8", name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/navbar-user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
        body { font-family: 'Instrument Sans', sans-serif; margin: 0; }
     
        .dashboard-container {
            max-width: 650px;
            margin: 48px auto 0 auto;
            padding: 32px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #0001;
        }
        h2 { margin-bottom: 24px; }
    </style>
</head>
<body>
    <x-navbar-user title="Inicio"/>
    <div class="dashboard-container">
        <h2>Bienvenido a la plataforma digital de IDICA</h2>
        <p>Selecciona una opción del menú superior para navegar a través de nuestras funciones</p>
        <p>para revisar sus examenes inicie sesion usando su respectivo usuario y contraseña
        </p>