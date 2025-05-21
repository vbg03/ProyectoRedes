<?php
// Recibimos los datos del formulario
$id_usuario = $_POST['id_usuario']; // Asegúrate de que el valor de id_usuario se obtiene correctamente
$mensaje = $_POST['mensaje'];
$estado = $_POST['estado'];

// Datos para la solicitud POST
$data = array(
    'id_usuario' => $id_usuario,
    'mensaje' => $mensaje,
    'estado' => $estado
);

// Convertimos los datos a JSON
$json_data = json_encode($data);

// Inicializamos cURL
$ch = curl_init();

// Configuramos las opciones de cURL para enviar la solicitud POST
curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/api/notificaciones");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutamos la solicitud
$response = curl_exec($ch);

// Verificamos si hubo algún error
if ($response === false) {
    echo "Error en la conexión con la API";
    exit;
}

// Cerramos la conexión cURL
curl_close($ch);

// Redirigir al formulario después de la creación
header('Location: index.php');
exit;
?>
