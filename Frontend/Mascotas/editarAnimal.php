<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["id"];
  $data = [
    "nombre" => $_POST["nombre"],
    "especie" => $_POST["especie"],
    "raza" => $_POST["raza"],
    "edad" => (int) $_POST["edad"],
    "vacunado" => isset($_POST["vacunado"]),
    "esterilizado" => isset($_POST["esterilizado"]),
    "estado_salud" => $_POST["estado_salud"],
    "foto" => $_POST["foto"],
    "estado" => $_POST["estado"],
    "ubicacion" => $_POST["ubicacion"]
  ];

  $url = "http://localhost:3002/animales/$id";
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
  ]);

  curl_exec($ch);
  curl_close($ch);
  header("Location: index.php");
  exit();
}
?>
