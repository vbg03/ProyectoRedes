<?php
$id = $_POST['id'];
$url = "http://192.168.100.3:3001/solicitudes/$id";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 200) {
  header("Location: index.php?ok=Solicitud eliminada");
} else {
  header("Location: index.php?error=No se pudo eliminar la solicitud");
}
