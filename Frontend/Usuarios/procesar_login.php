<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

$identificador = $_POST['identificador'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

$data = [
    'usuario' => $identificador,
    'email' => $identificador,
    'password' => $contrasena
];

$json = json_encode($data);

$ch = curl_init('http://localhost:3005/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 200) {
    $usuario = json_decode($response, true)['usuario'];
    $_SESSION['usuario'] = $usuario;

    // Redireccionar según rol
    switch ($usuario['rol']) {
        case 'administrador':
            header('Location: gestion_usuarios.php');
            break;
        case 'rescatista':
            header('Location: index_rescatista.php');
            break;
        case 'adoptante':
            header('Location: index_adoptante.php');
            break;
        default:
            session_destroy();
            header('Location: login.php?error=Rol no reconocido');
            break;
    }
    exit();
} else {
    $resp = json_decode($response, true);
    $error = $resp['message'] ?? 'Credenciales incorrectas.';
    header('Location: login.php?error=' . urlencode($error));
    exit();
}
