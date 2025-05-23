<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$id_usuario_sesion = $_SESSION['usuario']['id_usuario'];
$rol = $_SESSION['usuario']['rol'];

// Obtener el filtro enviado por GET (si existe)
$id_usuario_consulta = $_GET['id_usuario'] ?? $id_usuario_sesion;

// Construir la URL para la API
$url = "http://192.168.100.3:3003/notificaciones?usuario=" . urlencode($id_usuario_consulta);

// Obtener notificaciones
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$notificaciones = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Notificaciones - <?= htmlspecialchars(ucfirst($rol)) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
    <div class="container">
        <h1 class="mb-4">Notificaciones de <?= htmlspecialchars(ucfirst($rol)) ?></h1>

        <!-- Formulario filtro por ID Usuario -->
        <form method="GET" action="notificaciones.php" class="mb-4">
            <div class="mb-3">
                <label for="id_usuario" class="form-label">Filtrar por ID Usuario</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="id_usuario" 
                    name="id_usuario" 
                    value="<?= htmlspecialchars($id_usuario_consulta) ?>" 
                    placeholder="ID Usuario" 
                    required
                >
            </div>
            <button type="submit" class="btn btn-success">Filtrar Notificaciones</button>
            <?php if ($id_usuario_consulta !== $id_usuario_sesion): ?>
                <a href="notificaciones.php" class="btn btn-secondary ms-2">Limpiar filtro</a>
            <?php endif; ?>
        </form>

        <?php if (empty($notificaciones)): ?>
            <div class="alert alert-info">No hay notificaciones para mostrar.</div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($notificaciones as $notificacion): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($notificacion['mensaje']) ?><br>
                        <small class="text-muted">Estado: <?= htmlspecialchars($notificacion['estado']) ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="<?= $rol === 'rescatista' ? 'index_rescatista.php' : 'index_adoptante.php' ?>" class="btn btn-secondary mt-4">← Volver al panel</a>
    </div>
</body>
</html>
