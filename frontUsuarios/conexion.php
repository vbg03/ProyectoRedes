<?php
$host = "localhost";
$user = "root";         // Cambia esto si usas otro usuario
$pass = "";             // Cambia esto si tu usuario tiene contraseña
$db = "usuarios"; // Asegúrate que esta base exista

$conn = new mysqli($host, $user, $pass, $db);

// Verifica conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
