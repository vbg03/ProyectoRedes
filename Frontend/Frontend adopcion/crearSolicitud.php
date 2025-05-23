<?php
$data = array(
  'id_usuario' => $_POST['id_usuario'] ?? null,
  'id_animal' => $_POST['id_animal'] ?? null,
  'fecha' => $_POST['fecha'] ?? null,
);

if (!$data['id_usuario'] || !$data['id_animal'] || !$data['fecha']) {
    die("Error: Datos incompletos. Recibido: " . json_encode($data));
}

$json = json_encode($data);
$url = 'http://192.168.100.3:3001/solicitudes';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($response === false) {
    $error_msg = curl_error($ch);
    curl_close($ch);
    die("Error en la petición cURL: $error_msg");
}

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Mostrar para depuración
echo "<pre>";
echo "Código HTTP recibido: $httpcode\n";
echo "Respuesta completa:\n$response\n";
echo "</pre>";

// Dependiendo del código HTTP redirige o muestra error
if ($httpcode == 200 || $httpcode == 201) {
  header("Location: index.php?ok=Solicitud creada con éxito");
  exit();
} else {
  header("Location: index.php?error=Error al crear solicitud (HTTP $httpcode)");
  exit();
}
