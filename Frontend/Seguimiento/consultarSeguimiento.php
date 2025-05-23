<?php
// consultarSeguimiento.php

// Obtener filtro id_animal del GET
$filtro_id_animal = $_GET['filtro_id_animal'] ?? null;

$url_api = 'http://192.168.100.3:3004/seguimiento';

if ($filtro_id_animal !== null && $filtro_id_animal !== '') {
    $url_api .= '/animal/' . intval($filtro_id_animal);
}

$curl = curl_init($url_api);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);
?>

<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8' />
  <meta http-equiv='X-UA-Compatible' content='IE=edge' />
  <meta name='viewport' content='width=device-width, initial-scale=1.0' />
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
  <title>Filtrar Seguimientos</title>
</head>
<body>
<div class="container mt-4">
  <h1>Filtrar Seguimientos por ID Animal</h1>

  <form method="get" action="">
    <div class="mb-3">
      <label for="filtro_id_animal" class="form-label">ID Animal</label>
      <input type="number" class="form-control" id="filtro_id_animal" name="filtro_id_animal" value="<?= htmlspecialchars($filtro_id_animal) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Aplicar filtro</button>
    <a href="index.php" class="btn btn-secondary ms-2">Volver a la lista</a>
  </form>

  <hr>

  <?php
  if ($error) {
      echo "<div class='alert alert-danger'>Error al cargar los seguimientos: $error</div>";
  } else {
      $seguimientos = json_decode($response);
      if (!$seguimientos || count($seguimientos) == 0) {
          echo "<p>No se encontraron seguimientos para el ID animal especificado.</p>";
      } else {
          echo '<table class="table table-striped">';
          echo '<thead><tr>
                  <th>ID</th>
                  <th>ID Solicitud</th>
                  <th>ID Adoptante</th>
                  <th>ID Animal</th>
                  <th>Fecha Seguimiento</th>
                  <th>Comentarios</th>
                  <th>Estado</th>
                </tr></thead><tbody>';
          foreach ($seguimientos as $seg) {
              echo '<tr>';
              echo '<td>' . htmlspecialchars($seg->id_seguimiento) . '</td>';
              echo '<td>' . htmlspecialchars($seg->id_solicitud) . '</td>';
              echo '<td>' . htmlspecialchars($seg->id_adoptante) . '</td>';
              echo '<td>' . htmlspecialchars($seg->id_animal) . '</td>';

              $fecha_original = $seg->fecha_seguimiento;
              $fecha_obj = new DateTime($fecha_original, new DateTimeZone('UTC'));
              $fecha_obj->setTimezone(new DateTimeZone('America/Bogota'));
              $fecha_mostrar = $fecha_obj->format('Y-m-d H:i:s');

              echo '<td>' . htmlspecialchars($fecha_mostrar) . '</td>';
              echo '<td>' . htmlspecialchars($seg->comentarios) . '</td>';
              echo '<td>' . htmlspecialchars($seg->estado) . '</td>';
              echo '</tr>';
          }
          echo '</tbody></table>';
      }
  }
  ?>
</div>
</body>
</html>
