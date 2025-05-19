<?php
// Procesar creación seguimiento antes de cualquier salida
if (isset($_POST['crear_seguimiento'])) {
    $id_solicitud = $_POST['id_solicitud'];
    $id_adoptante = $_POST['id_adoptante'];
    $id_animal = $_POST['id_animal'];
    $comentarios = $_POST['comentarios'];
    $estado = $_POST['estado'];

    $data = [
        "id_solicitud" => $id_solicitud,
        "id_adoptante" => $id_adoptante,
        "id_animal" => $id_animal,
        "comentarios" => $comentarios,
        "estado" => $estado
    ];

    $json_data = json_encode($data);

    $curl = curl_init('http://localhost:3004/seguimiento');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    header("Location: " . $_SERVER['PHP_SELF'] . "?creado=1");
    exit();
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
  <title>Lista de Seguimientos</title>
</head>
<body>
<div class="container mt-4">
  <h1>Seguimientos</h1>

  <div class="d-flex mb-3 gap-2">
    <!-- Botón Crear Seguimiento -->
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearModal">
      Crear Seguimiento
    </button>

    <!-- Botón Filtrar Seguimientos -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filtrarModal">
      Filtrar Seguimientos
    </button>

    <!-- Botón para limpiar filtro -->
    <?php if (isset($_GET['filtro_id_animal'])): ?>
      <a href="index.php" class="btn btn-secondary">Quitar filtro</a>
    <?php endif; ?>
  </div>

  <!-- Modal Crear Seguimiento -->
  <div class="modal fade" id="crearModal" tabindex="-1" aria-labelledby="crearModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="">
          <div class="modal-header">
            <h5 class="modal-title" id="crearModalLabel">Crear Seguimiento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
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
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" name="crear_seguimiento">Crear</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Filtrar Seguimientos -->
  <div class="modal fade" id="filtrarModal" tabindex="-1" aria-labelledby="filtrarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="get" action="">
          <div class="modal-header">
            <h5 class="modal-title" id="filtrarModalLabel">Filtrar Seguimientos por ID Animal</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="filtro_id_animal" class="form-label">ID Animal</label>
              <input type="number" class="form-control" id="filtro_id_animal" name="filtro_id_animal" value="<?= isset($_GET['filtro_id_animal']) ? htmlspecialchars($_GET['filtro_id_animal']) : '' ?>" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Aplicar filtro</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
// Mostrar mensaje éxito y limpiar parámetro
if (isset($_GET['creado'])) {
    echo "<div id='success-message' class='alert alert-success'>Seguimiento creado correctamente.</div>";
    ?>
    <script>
      if (window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('creado');
        window.history.replaceState({}, document.title, url.toString());
      }
      setTimeout(() => {
        const msg = document.getElementById('success-message');
        if (msg) {
          msg.style.display = 'none';
        }
      }, 5000);
    </script>
    <?php
}

// Consultar seguimientos con posible filtro
$filtro_id_animal = $_GET['filtro_id_animal'] ?? null;
$url_api = 'http://localhost:3004/seguimiento';

if ($filtro_id_animal !== null && $filtro_id_animal !== '') {
    $url_api .= '/animal/' . intval($filtro_id_animal);
}

$curl = curl_init($url_api);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

if ($error) {
    echo "<div class='alert alert-danger'>Error al cargar los seguimientos: $error</div>";
} else {
    $seguimientos = json_decode($response);
    if (!$seguimientos || count($seguimientos) == 0) {
        echo "<p>No hay seguimientos registrados.</p>";
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
