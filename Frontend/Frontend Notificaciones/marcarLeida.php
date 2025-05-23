<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // URL de la API para marcar la notificación como leída
    $url = 'http://192.168.100.3:3003/api/notificaciones/' . $id;

    $data = array(
        'estado' => 'leída'
    );
    $json_data = json_encode($data);

    // Configurar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    if ($response === false) {
        echo "Error al marcar la notificación como leída.";
    } else {
        header("Location: index.php");  // Redirigir al índice después de actualizar
    }

    curl_close($ch);
}
?>
