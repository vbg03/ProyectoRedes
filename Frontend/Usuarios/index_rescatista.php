<?php
session_start();
// Validar rol rescatista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'rescatista') {
    header('Location: login.php');
    exit();
}

$ver_todos = isset($_GET['ver_todos']) && $_GET['ver_todos'] == '1';

// Obtener todos los animales del microservicio
$url = "http://localhost:3002/animales";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$animales = json_decode($response, true);

if ($animales && is_array($animales)) {
    // Ordenar animales por id descendente
    usort($animales, function($a, $b) {
        return $b['id'] <=> $a['id'];
    });

    if (!$ver_todos) {
        $animales = array_slice($animales, 0, 3); // Solo últimos 3 si no está ver_todos
    }
} else {
    $animales = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel Rescatista</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">

  <div class="container text-center">
    <h2 class="mb-4">🐾 Panel Rescatista</h2>

    <div class="text-right mb-3">
  <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
</div>

<div class="text-center mt-4">
  <a href="notificaciones.php" class="btn btn-primary">Ver Notificaciones</a>
</div>

    <a href="http://localhost/Mascotas/index.php" target="_self" class="btn btn-primary btn-lg mb-4">
      Gestión de mascotas
    </a>

    <a href="http://localhost/Frontend%20adopcion/index.php" target="_self" class="btn btn-success btn-lg mb-4">
      Ver solicitudes de adopción
    </a>

    <a href="http://localhost/seguimiento/index.php" target="_self" class="btn btn-info btn-lg mb-4">
      Ver Seguimientos
    </a>

    <h3><?= $ver_todos ? 'Todos los animales publicados' : 'Últimos 3 animales publicados' ?></h3>

    <?php if (count($animales) === 0): ?>
      <p>No hay animales publicados aún.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Foto</th>
              <th>Nombre</th>
              <th>Especie</th>
              <th>Raza</th>
              <th>Edad</th>
              <th>Vacunado</th>
              <th>Esterilizado</th>
              <th>Estado Salud</th>
              <th>Ubicación</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($animales as $animal): ?>
              <tr>
                <td><?= htmlspecialchars($animal['id']) ?></td>
                <td>
                  <?php if (!empty($animal['foto'])): ?>
                    <img src="<?= htmlspecialchars($animal['foto']) ?>" alt="Foto de <?= htmlspecialchars($animal['nombre']) ?>" style="width:60px; height:60px; object-fit:cover; border-radius:5px;">
                  <?php else: ?>
                    N/A
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($animal['nombre']) ?></td>
                <td><?= htmlspecialchars($animal['especie']) ?></td>
                <td><?= htmlspecialchars($animal['raza']) ?></td>
                <td><?= htmlspecialchars($animal['edad']) ?></td>
                <td><?= $animal['vacunado'] ? 'Sí' : 'No' ?></td>
                <td><?= $animal['esterilizado'] ? 'Sí' : 'No' ?></td>
                <td><?= htmlspecialchars($animal['estado_salud']) ?></td>
                <td><?= htmlspecialchars($animal['ubicacion']) ?></td>
                <td><?= htmlspecialchars($animal['estado']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <?php if (!$ver_todos && count($animales) > 0): ?>
      <a href="?ver_todos=1" class="btn btn-secondary mt-3">Ver todos</a>
    <?php endif; ?>

    <?php if ($ver_todos): ?>
      <a href="index_rescatista.php" class="btn btn-secondary mt-3">Mostrar menos</a>
    <?php endif; ?>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
