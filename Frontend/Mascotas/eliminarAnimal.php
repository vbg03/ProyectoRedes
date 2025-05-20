<?php
if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $url = "http://localhost:3002/animales/$id";

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  curl_exec($ch);
  curl_close($ch);
}

header("Location: index.php");
exit();
?>
