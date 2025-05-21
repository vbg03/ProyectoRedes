<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificador = trim($_POST['identificador']);
    $contrasena = $_POST['contrasena'];

    // Buscar por email o usuario
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? OR usuario = ?");
    $stmt->bind_param("ss", $identificador, $identificador);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Comparar contraseñas (sin hash)
        if ($contrasena === $usuario['password']) {
            if ($usuario['estado'] === 'activo') {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];

                // Redirección por rol
                if ($usuario['rol'] === 'administrador') {
                    header("Location: gestion_usuarios.php");
                } elseif ($usuario['rol'] === 'rescatista') {
                    header("Location: index_rescatista.php");
                } elseif ($usuario['rol'] === 'adoptante') {
                    header("Location: index_adoptante.php");
                } else {
                    $_SESSION['error'] = "Rol desconocido.";
                    header("Location: login.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Usuario inactivo.";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Usuario no encontrado.";
        header("Location: login.php");
        exit();
    }
}
?>
