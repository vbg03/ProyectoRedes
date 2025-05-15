<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
  <title>Solicitudes de Adopción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .main-container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    h2 {
      color: #2c3e50;
    }
    table {
      background: #ffffff;
      border-radius: 10px;
      overflow: hidden;
    }
    th {
      background-color: #f1f1f1;
    }
    select {
      border-radius: 5px;
    }
    .btn-success, .btn-danger, .btn-primary {
      border-radius: 10px;
    }
    .modal-content {
      border-radius: 15px;
    }
  </style>
</head>
<body class="p-4">

  <div class="main-container">

    <?php
      if (isset($_GET['ok'])) {
        echo "<script>
          Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{$_GET['ok']}',
            showConfirmButton: false,
            timer: 2000
          });
        </script>";
      } elseif (isset($_GET['error'])) {
        echo "<script>
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{$_GET['error']}',
            showConfirmButton: false,
            timer: 2500
          });
        </script>";
      }
    ?>

    <h2 class="mb-4 text-center">🐾 Panel de Solicitudes de Adopción</h2>

    <table class="table table-bordered text-center align-middle">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Animal</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $url = "http://192.168.100.3:3001/solicitudes/todas";
          $curl = curl_init($url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($curl);
          curl_close($curl);

          $solicitudes = json_decode($response, true);
          foreach ($solicitudes as $s) {
            echo "<tr>
              <td>{$s['id_solicitud']}</td>
              <td>{$s['id_usuario']}</td>
              <td>{$s['id_animal']}</td>
              <td>{$s['fecha']}</td>
              <td>{$s['estado']}</td>
              <td>
                <form action='actualizarEstado.php' method='post' style='display:inline-block;'>
                  <input type='hidden' name='id' value='{$s['id_solicitud']}'>
                  <select name='estado' class='form-select form-select-sm d-inline w-auto'>
                    <option>pendiente</option>
                    <option>aprobada</option>
                    <option>rechazada</option>
                  </select>
                  <button class='btn btn-sm btn-success'>✔</button>
                </form>
                <form action='eliminarSolicitud.php' method='post' style='display:inline-block;'>
                  <input type='hidden' name='id' value='{$s['id_solicitud']}'>
                  <button class='btn btn-sm btn-danger'>🗑</button>
                </form>
              </td>
            </tr>";
          }
        ?>
      </tbody>
    </table>

    <!-- Botón para abrir modal -->
    <div class="text-center my-3">
      <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modalCrear">+ Crear Solicitud</button>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog">
      <form class="modal-content" action="crearSolicitud.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Nueva Solicitud</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input class="form-control mb-2" type="text" name="id_usuario" placeholder="ID Usuario" required pattern="\d+" title="Solo números">
          <input class="form-control mb-2" type="text" name="id_animal" placeholder="ID Animal" required pattern="\d+" title="Solo números">
          <input class="form-control mb-2" type="date" name="fecha" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary w-100">Crear Solicitud</button>
        </div>
      </form>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
