<?php
$id = $_POST['id'];
$estado = $_POST['estado'];

$data = json_encode(array('estado' => $estado));
$url = "http://192.168.100.3:3001/solicitudes/$id";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 200) {
  header("Location: index.php?ok=Estado actualizado");
} else {
  header("Location: index.php?error=No se pudo actualizar el estado");
}
