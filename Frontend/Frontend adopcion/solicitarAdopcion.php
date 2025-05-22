<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'adoptante') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_adoptante = $_SESSION['usuario']['id_usuario']; // ID del adoptante
    $id_animal = $_POST['id_animal'];
    $fecha = date('Y-m-d H:i:s'); // Fecha actual

    $data = [
        'id_usuario' => $id_adoptante,
        'id_animal' => $id_animal,
        'fecha' => $fecha
    ];

    // Enviar solicitud de adopción al microservicio
    $url = 'http://localhost:3001/solicitudes'; // Microservicio de Adopción
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        header("Location: index_adoptante.php?ok=Solicitud de adopción enviada con éxito");
    } else {
        header("Location: index_adoptante.php?error=Error al enviar la solicitud");
    }
}
?>
