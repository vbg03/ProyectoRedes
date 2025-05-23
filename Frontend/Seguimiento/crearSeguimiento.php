<?php
// crear_seguimiento.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_solicitud = $_POST['id_solicitud'] ?? null;
    $id_adoptante = $_POST['id_adoptante'] ?? null;
    $id_animal = $_POST['id_animal'] ?? null;
    $comentarios = $_POST['comentarios'] ?? null;
    $estado = $_POST['estado'] ?? null;

    $data = [
        "id_solicitud" => $id_solicitud,
        "id_adoptante" => $id_adoptante,
        "id_animal" => $id_animal,
        "comentarios" => $comentarios,
        "estado" => $estado
    ];

    $json_data = json_encode($data);

    $curl = curl_init('http://192.168.100.3:3004/seguimiento');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        $error_message = "Error en la conexión: $error";
    } else {
        // Redirige a index con mensaje de éxito
        header("Location: index.php?creado=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8' />
  <meta http-equiv='X-UA-Compatible' content='IE=edge' />
  <meta name='viewport' content='width=device-width, initial-scale=1.0' />
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
  <title>Crear Seguimiento</title>
</head>
<body>
<div class="container mt-4">
  <h1>Crear Seguimiento</h1>

  <?php if (!empty($error_message)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <div class="mb-3">
      <label for="id_solicitud" class="form-label">ID Solicitud</label>
      <input type="number" class="form-control" id="id_solicitud" name="id_solicitud" required>
    </div>
    <div class="mb-3">
      <label for="id_adoptante" class="form-label">ID Adoptante</label>
      <input type="number" class="form-control" id="id_adoptante" name="id_adoptante" required>
    </div>
    <div class="mb-3">
      <label for="id_animal" class="form-label">ID Animal</label>
      <input type="number" class="form-control" id="id_animal" name="id_animal" required>
    </div>
    <div class="mb-3">
      <label for="comentarios" class="form-label">Comentarios</label>
      <textarea class="form-control" id="comentarios" name="comentarios" required></textarea>
    </div>
    <div class="mb-3">
      <label for="estado" class="form-label">Estado</label>
      <input type="text" class="form-control" id="estado" name="estado" required>
    </div>
    <button type="submit" class="btn btn-primary">Crear Seguimiento</button>
    <a href="index.php" class="btn btn-secondary">Volver a la lista</a>
  </form>
</div>
</body>
</html>
