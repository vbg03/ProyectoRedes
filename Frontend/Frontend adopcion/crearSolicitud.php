<?php
$data = array(
  'id_usuario' => $_POST['id_usuario'],
  'id_animal' => $_POST['id_animal'],
  'fecha' => $_POST['fecha']
);

$json = json_encode($data);
$url = 'http://192.168.100.3:3001/solicitudes';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 200) {
  header("Location: index.php?ok=Solicitud creada con éxito");
} else {
  header("Location: index.php?error=Error al crear solicitud");
}
