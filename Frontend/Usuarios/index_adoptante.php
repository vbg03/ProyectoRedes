<?php
session_start();
// Validar rol adoptante
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'adoptante') {
    header('Location: login.php');
    exit();
}

$msg = '';
$error = '';

// Procesar POST cuando se envía formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_animal'], $_POST['fecha'])) {
    $data = [
        'id_usuario' => $_SESSION['usuario']['id_usuario'],
        'id_animal' => $_POST['id_animal'],
        'fecha' => $_POST['fecha']
    ];

    $json = json_encode($data);
    $url = 'http://localhost:3001/solicitudes';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = 'Error en la petición cURL: ' . curl_error($ch);
    } else {
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpcode == 200 || $httpcode == 201) {
            $msg = 'Solicitud creada con éxito';
        } else {
            $error = "Error al crear solicitud (HTTP $httpcode)";
        }
    }

    curl_close($ch);
}

// Obtener animales disponibles
$url_animales = "http://localhost:3002/animales";
$curl = curl_init($url_animales);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response_animales = curl_exec($curl);
curl_close($curl);
$animales = json_decode($response_animales, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Animales Disponibles para Adopción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">

<div class="container">

    <h2 class="mb-4 text-center">🐾 Animales Disponibles para Adopción</h2>
    <div class="text-center mt-4">
  <a href="notificaciones.php" class="btn btn-primary">Ver Notificaciones</a>
</div>

    <div class="text-right mb-3">
  <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
</div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Especie</th>
                <th>Raza</th>
                <th>Edad</th>
                <th>Solicitar Adopción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($animales as $animal): ?>
                <tr>
                    <td><?= htmlspecialchars($animal['id']) ?></td>
                    <td>
                        <?php if (!empty($animal['foto'])): ?>
                            <img src="<?= htmlspecialchars($animal['foto']) ?>" alt="Foto de <?= htmlspecialchars($animal['nombre']) ?>" style="width: 60px; height: 60px; object-fit: cover;">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($animal['nombre']) ?></td>
                    <td><?= htmlspecialchars($animal['especie']) ?></td>
                    <td><?= htmlspecialchars($animal['raza']) ?></td>
                    <td><?= htmlspecialchars($animal['edad']) ?></td>
                    <td>
                        <form method="POST" class="d-flex gap-2 justify-content-center align-items-center">
                            <input type="hidden" name="id_animal" value="<?= htmlspecialchars($animal['id']) ?>" />
                            <input type="date" name="fecha" required class="form-control form-control-sm" />
                            <button type="submit" class="btn btn-primary btn-sm">Solicitar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

</body>
</html>
