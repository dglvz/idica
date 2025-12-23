
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8", name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        body { font-family: 'Instrument Sans', sans-serif; background: #fdfdfc; }
        .register-container { max-width: 400px; margin: 80px auto; padding: 32px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 500; }
        input, select { width: 100%; padding: 8px; border: 1px solid #e3e3e0; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #f53003; color: #fff; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; }
        button:hover { background: #d42a00; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registrar Usuario</h2>
        <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre de Usuario</label>
                <input type="text" id="nombre" name="nombre" required autofocus>
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" required>
                    <option value="">Seleccione un rol</option>
                    <option value="admin">Administrador</option>
                    <option value="usuario">Usuario</option>
                </select>
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="clave">Clave</label>
                <input type="password" id="clave" name="clave" required>
            </div>
            <button type="submit">Registrar</button>
        </form>
        <div style="margin-top: 16px; text-align: center;">
            <a href="/login" style="color: #f53003; text-decoration: none;">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html>