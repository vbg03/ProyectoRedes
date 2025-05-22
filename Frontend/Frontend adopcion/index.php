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
      // Mostrar alertas con SweetAlert según parámetros GET
      if (isset($_GET['ok'])) {
        $msg = htmlspecialchars($_GET['ok']);
        echo "<script>
          Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{$msg}',
            showConfirmButton: false,
            timer: 2000
          });
        </script>";
      } elseif (isset($_GET['error'])) {
        $msg = htmlspecialchars($_GET['error']);
        echo "<script>
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{$msg}',
            showConfirmButton: false,
            timer: 2500
          });
        </script>";
      }

      // Obtener solicitudes desde el microservicio
      $url = "http://localhost:3001/solicitudes";
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);

      if ($response === false) {
        echo "<div class='alert alert-danger'>Error al obtener solicitudes.</div>";
        curl_close($curl);
        $solicitudes = [];
      } else {
        curl_close($curl);
        $solicitudes = json_decode($response, true);
        if (!is_array($solicitudes)) {
          echo "<div class='alert alert-danger'>Error al procesar datos JSON.</div>";
          $solicitudes = [];
        }
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
        <?php if (count($solicitudes) === 0): ?>
          <tr>
            <td colspan="6">No hay solicitudes para mostrar.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($solicitudes as $s): ?>
            <tr>
              <td><?= htmlspecialchars($s['id_solicitud']) ?></td>
              <td><?= htmlspecialchars($s['id_usuario']) ?></td>
              <td><?= htmlspecialchars($s['id_animal']) ?></td>
              <td><?= htmlspecialchars($s['fecha']) ?></td>
              <td><?= htmlspecialchars($s['estado']) ?></td>
              <td>
                <form action="actualizarEstado.php" method="post" style="display:inline-block;">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($s['id_solicitud']) ?>">
                  <select name="estado" class="form-select form-select-sm d-inline w-auto">
                    <option value="pendiente" <?= $s['estado'] === 'pendiente' ? 'selected' : '' ?>>pendiente</option>
                    <option value="aprobada" <?= $s['estado'] === 'aprobada' ? 'selected' : '' ?>>aprobada</option>
                    <option value="rechazada" <?= $s['estado'] === 'rechazada' ? 'selected' : '' ?>>rechazada</option>
                  </select>
                  <button class="btn btn-sm btn-success" type="submit">✔</button>
                </form>
                <form action="eliminarSolicitud.php" method="post" style="display:inline-block;">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($s['id_solicitud']) ?>">
                  <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('¿Seguro que deseas eliminar esta solicitud?')">🗑</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Botón para abrir modal de crear solicitud -->
    <!-- <div class="text-center my-3">
      <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modalCrear">+ Crear Solicitud</button>
    </div>
  </div> -->
  <div class="text-center my-3">
  <a href="http://localhost/Usuarios/index_rescatista.php" class="btn btn-secondary">
    ← Volver al Panel Rescatista
  </a>
</div>


  <!-- Modal para crear nueva solicitud -->
  <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" action="crearSolicitud.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Nueva Solicitud</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input class="form-control mb-2" type="number" name="id_usuario" placeholder="ID Usuario" required min="1" title="Solo números positivos">
          <input class="form-control mb-2" type="number" name="id_animal" placeholder="ID Animal" required min="1" title="Solo números positivos">
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
