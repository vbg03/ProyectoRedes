<?php
include 'conexion.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Obtener estado actual
    $query = "SELECT estado FROM usuarios WHERE id = $id";
    $resultado = $conn->query($query);
    $fila = $resultado->fetch_assoc();
    $estado_actual = $fila['estado'];

    // Cambiar estado
    $nuevo_estado = ($estado_actual == 'activo') ? 'inactivo' : 'activo';

    // Actualizar estado en la base de datos
    $update = "UPDATE usuarios SET estado = '$nuevo_estado' WHERE id = $id";
    $conn->query($update);
}

$conn->close();
?>
