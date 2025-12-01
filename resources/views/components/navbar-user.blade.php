<div>
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/navbar-user.css') }}">
<nav class="navbar">
    <div class="navbar-content">
        <div class="navbar-title">{{ $title ?? 'Titulo' }}</div>
        <div class="navbar-menu">
            <a href="/inicio">Inicio</a>
            <a href="/pacientes">Contactanos</a>
            <a href="/usuarios.create">Registrarse</a>    
            <a href="/login">Iniciar sesion</a>
        </div>
    </div>
</nav>
</div>
