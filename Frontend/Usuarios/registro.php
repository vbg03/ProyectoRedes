<?php
// Procesar formulario si se envía
$mensaje = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "id_usuario" => $_POST['cedula'],  // asumiendo cédula es id_usuario
        "nombre" => $_POST['nombre'],
        "usuario" => $_POST['usuario'],
        "email" => $_POST['correo'],
        "password" => $_POST['contrasena'],
        "rol" => $_POST['rol']
    ];

    $json = json_encode($data);

    $ch = curl_init('http://192.168.100.3:3005/register');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 201) {
        $mensaje = "Usuario registrado con éxito";
    } else {
        $error = "Error al registrar usuario";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #E6F0F3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }
        h2 {
            text-align: center;
            color: #4C4C6D;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        button {
            width: 100%;
            background-color: #4C4C6D;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #3a3a58;
        }
        a {
            color: #4C4C6D;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        p {
            text-align: center;
            margin-top: 16px;
        }
        .mensaje {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .exito {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro de Usuario</h2>

        <?php if ($mensaje): ?>
            <div class="mensaje exito"><?= htmlspecialchars($mensaje) ?></div>
        <?php elseif ($error): ?>
            <div class="mensaje error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="cedula" placeholder="Cédula de ciudadanía" required pattern="\d+" title="Solo números" />
            <input type="text" name="nombre" placeholder="Nombre completo" required />
            <input type="text" name="usuario" placeholder="Nombre de usuario" required />
            <input type="email" name="correo" placeholder="Correo electrónico" required />
            <input type="password" name="contrasena" placeholder="Contraseña" required />
            <select name="rol" required>
                <option value="">Seleccione un rol</option>
                <option value="rescatista">Rescatista</option>
                <option value="adoptante">adoptante</option>
            </select>
            <button type="submit">Registrarse</button>
        </form>

        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>
