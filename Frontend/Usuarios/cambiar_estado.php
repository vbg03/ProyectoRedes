<?php
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Paso 1: Obtener usuario para saber estado actual
    $url_get = "http://192.168.100.3:3005/admin/users/$id";

    $ch = curl_init($url_get);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode != 200) {
        // Usuario no encontrado o error
        http_response_code(400);
        echo "Usuario no encontrado o error al obtener datos";
        exit();
    }

    $usuario = json_decode($response, true);
    $estado_actual = $usuario['estado'] ?? null;

    if ($estado_actual === null) {
        http_response_code(400);
        echo "No se pudo obtener estado actual";
        exit();
    }

    // Paso 2: Calcular nuevo estado
    $nuevo_estado = ($estado_actual === 'activo') ? 'inactivo' : 'activo';

    // Paso 3: Actualizar estado con PATCH
    $url_patch = "http://192.168.100.3:3005/admin/users/$id/estado";

    $data = json_encode(['estado' => $nuevo_estado]);

    $ch = curl_init($url_patch);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response_patch = curl_exec($ch);
    $httpcode_patch = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode_patch == 200) {
        // Éxito, redirigir o imprimir respuesta
        header("Location: gestion_usuarios.php?ok=Estado actualizado");
        exit();
    } else {
        http_response_code(500);
        echo "Error al actualizar estado";
        exit();
    }
} else {
    http_response_code(400);
    echo "No se recibió id";
    exit();
}
