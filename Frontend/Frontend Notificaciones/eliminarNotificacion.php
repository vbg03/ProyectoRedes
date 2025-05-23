?> <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // URL de la API para eliminar la notificación
    $url = 'http://192.168.100.3:3003/api/notificaciones/' . $id;

    // Configurar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    if ($response === false) {
        echo "Error al eliminar la notificación.";
    } else {
        header("Location: index.php");  // Redirigir al índice después de eliminar
    }

    curl_close($ch);
}
?>