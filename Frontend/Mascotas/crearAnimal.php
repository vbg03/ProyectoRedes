<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $data = [
    "nombre" => $_POST["nombre"],
    "especie" => $_POST["especie"],
    "raza" => $_POST["raza"],
    "edad" => (int) $_POST["edad"],
    "vacunado" => isset($_POST["vacunado"]) ? true : false,
    "esterilizado" => isset($_POST["esterilizado"]) ? true : false,
    "estado_salud" => $_POST["estado_salud"],
    "foto" => $_POST["foto"],
    "estado" => $_POST["estado"],
    "ubicacion" => $_POST["ubicacion"]
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

  // Redirige de nuevo a index.php
  header("Location: index.php");
  exit();
}
?>
