<?php
$id = $_POST['id'] ?? null;

// Validar ID
if (!$id || !is_numeric($id)) {
    header("Location: index.php?error=ID inválido");
    exit();
}

$url = "http://localhost:3001/solicitudes/$id";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Si se requiere token o header de autenticación, agregar aquí:
// curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer tu_token']);

$response = curl_exec($ch);
if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);
    header("Location: index.php?error=Error cURL: " . urlencode($error));
    exit();
}

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 200) {
    header("Location: index.php?ok=Solicitud eliminada");
} else {
    // Opcional: analizar la respuesta para dar más detalle
    $msg = "No se pudo eliminar la solicitud (HTTP $httpcode)";
    if (!empty($response)) {
        $msg .= ": " . $response;
    }
    header("Location: index.php?error=" . urlencode($msg));
}
exit();
?>
