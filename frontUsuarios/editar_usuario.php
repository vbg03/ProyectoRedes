<?php
header("Access-Control-Allow-Origin: *"); // Puedes reemplazar * por tu dominio exacto
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Manejo de preflight (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'conexion.php';

// Asegura que la solicitud sea PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Obtener y decodificar JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar datos
    if (
        isset($data['id']) &&
        isset($data['nombre']) &&
        isset($data['cc']) &&
        isset($data['email']) &&
        isset($data['usuario'])
    ) {
        $id = intval($data['id']);
        $nombre = $data['nombre'];
        $cc = intval($data['cc']);
        $email = $data['email'];
        $usuario = $data['usuario'];
        $rol = isset($data['rol']) ? $data['rol'] : 'rescatista'; // opcional

        // Consulta preparada
        $sql = "UPDATE usuarios SET nombre=?, cc=?, email=?, usuario=?, rol=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssi", $nombre, $cc, $email, $usuario, $rol, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Usuario actualizado."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error en actualización: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Faltan campos requeridos."]);
    }

    $conn->close();
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>
