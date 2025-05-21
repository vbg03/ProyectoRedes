<?php
include 'conexion.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM usuarios WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "ok";
    } else {
        echo "error";
    }
}
?>
