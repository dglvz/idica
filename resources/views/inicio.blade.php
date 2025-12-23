
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8", name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
        body { font-family: 'Instrument Sans', sans-serif;  margin: 0; }
     
        .dashboard-container {
            max-width: 600px;
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
   <x-navbar title="Inicio"/>
    <div class="dashboard-container">
        <h2>Bienvenido al Dashboard</h2>
        <p>Selecciona una opción del menú superior para continuar.</p>