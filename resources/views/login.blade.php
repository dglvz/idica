
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
     <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: 'Instrument Sans', sans-serif; background: #fdfdfc; }
        .login-container { max-width: 400px; margin: 80px auto; padding: 32px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 500; }
        input[type="email"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #e3e3e0; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #f53003; color: #fff; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; }
        button:hover { background: #d42a00; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="/login">
    @csrf
    <div class="form-group">
        <label for="nombre">Nombre de Usuario</label>
        <input type="text" id="nombre" name="nombre" required autofocus>
    </div>
    <div class="form-group">
        <label for="clave">Clave</label>
        <input type="password" id="clave" name="clave" required>
    </div>
    

<div style="margin-top: 16px; text-align: center;">
    <a href="/inicio" class= "btn btn-primary"style="background: #f53003; color: #fff; padding: 10px 18px; border-radius: 4px; text-decoration: none; font-weight: 600;" type="submit">Ingresar</a> 


        <a href="{{ route('usuarios.create') }}" class="btn btn-primary" style="background: #f53003; color: #fff; padding: 10px 18px; border-radius: 4px; text-decoration: none; font-weight: 600;">
    Registrar un Usuario
            
        </a>
    </div>
        <div style="margin-top: 16px; text-align: center;">
            <a href="/forgot-password" style="color: #f53003; text-decoration: none;">¿Olvidaste tu clave?</a>
        </div>
    </div>
    </form> 
</body>
</html>