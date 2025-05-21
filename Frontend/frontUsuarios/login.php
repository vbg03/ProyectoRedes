<?php
session_start();
include 'conexion.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST["input"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE (email = ? OR usuario = ?) AND password = ? AND estado = 'activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $input, $input, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION['usuario'] = $usuario;
        header("Location: gestion_usuarios.php");
        exit();
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
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
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #4C4C6D;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px 0;
            border: 1px solid #id_usuarioc;
            border-radius: 8px;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Iniciar Sesión</h2>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST" action="procesar_login.php">
            <input type="text" name="identificador" placeholder="Correo o Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
        <p style="text-align: center; margin-top: 16px;">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </p>

    </div>
</body>
</html>
