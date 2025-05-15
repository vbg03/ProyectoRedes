<?php
$notificaciones = [];
$error = "";

// Si se envió el formulario para crear notificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'], $_POST['mensaje'], $_POST['estado'])) {
    $id_usuario = $_POST['id_usuario'];
    $mensaje = $_POST['mensaje'];
    $estado = $_POST['estado'];

    if ($id_usuario && $mensaje && $estado) {
        $data = json_encode([
            'id_usuario' => $id_usuario,
            'mensaje' => $mensaje,
            'estado' => $estado
        ]);

        $ch = curl_init("http://localhost:3003/api/notificaciones");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);

        // Redirigir para evitar reenvíos
        header("Location: index.php?id_usuario=$id_usuario");
        exit;
    }
}

// Si se pasó un id_usuario o estado por GET, consultar notificaciones
$id_usuario_consulta = $_GET['id_usuario'] ?? null;
$estado_consulta = $_GET['estado'] ?? null;

if ($id_usuario_consulta) {
    // Llamada a la API para obtener las notificaciones filtradas
    $url = "http://localhost:3003/api/notificaciones?usuario=" . urlencode($id_usuario_consulta);
    
    if ($estado_consulta) {
        $url .= "&estado=" . urlencode($estado_consulta);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $resp = json_decode($response);
    if (is_array($resp)) {
        $notificaciones = $resp;
    } else {
        $error = "No se pudieron obtener notificaciones.";
    }
}
?>
