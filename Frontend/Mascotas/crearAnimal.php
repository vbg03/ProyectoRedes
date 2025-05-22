<?php
session_start(); // ✅ Necesario para acceder a la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // ✅ Asegúrate que el usuario esté autenticado
  if (!isset($_SESSION["usuario"])) {
    die("Acceso denegado. Debes iniciar sesión.");
  }

  // ✅ Obtener el ID del usuario desde la sesión
  $id_usuario = $_SESSION["usuario"]["id_usuario"];

  $data = [
    "id_usuario"    => $id_usuario, // ✅ Correcto nombre del campo
    "nombre"        => $_POST["nombre"],
    "especie"       => $_POST["especie"],
    "raza"          => $_POST["raza"],
    "edad"          => (int) $_POST["edad"],
    "vacunado"      => isset($_POST["vacunado"]),
    "esterilizado"  => isset($_POST["esterilizado"]),
    "estado_salud"  => $_POST["estado_salud"],
    "foto"          => $_POST["foto"],
    "estado"        => $_POST["estado"],
    "ubicacion"     => $_POST["ubicacion"]
  ];

  $url = "http://localhost:3002/animales";
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
  ]);

  $response = curl_exec($ch);
  curl_close($ch);

  // ✅ Redirige después de guardar
  header("Location: index.php");
  exit();
}
?>
