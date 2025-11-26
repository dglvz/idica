<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        body { font-family: 'Instrument Sans', sans-serif; background: #fdfdfc; margin: 0; }
        .container { max-width: 1000px; margin: 48px auto; padding: 32px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Navbar o cabecera global --}}
 
<x-navbar :title="config('app.name')" />

    <main class="container">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>