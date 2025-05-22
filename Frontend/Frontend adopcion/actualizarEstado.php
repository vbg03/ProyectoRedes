<?php
$id = $_POST['id'] ?? null;
$estado = $_POST['estado'] ?? null;

echo "<script>console.log('ID recibido: " . json_encode($id) . "');</script>";
echo "<script>console.log('Estado recibido: " . json_encode($estado) . "');</script>";

$data = json_encode(['estado' => $estado]);
echo "<script>console.log('Datos JSON enviados: " . addslashes($data) . "');</script>";

$url = "http://localhost:3001/solicitudes/$id";
echo "<script>console.log('URL destino: " . addslashes($url) . "');</script>";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($response === false) {
    echo "<script>console.log('Error cURL: " . curl_error($ch) . "');</script>";
}

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<script>console.log('Código HTTP recibido: " . $httpcode . "');</script>";
echo "<script>console.log('Respuesta del backend: " . addslashes($response) . "');</script>";

if ($httpcode == 200) {
  header("Location: index.php?ok=Estado actualizado");
} else {
  header("Location: index.php?error=No se pudo actualizar el estado");
}
exit();
?>
