<?php
$notificaciones = [];
$error = "";

// Si se envió el formulario para crear notificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'], $_POST['mensaje'], $_POST['estado'])) {
    $id_usuario = $_POST['id_usuario'];
    $mensaje = $_POST['mensaje'];
    $estado = $_POST['estado'];

    if ($id_usuario && $mensaje && $estado) {
        $data = json_encode([
            'id_usuario' => $id_usuario,
            'mensaje' => $mensaje,
            'estado' => $estado
        ]);

        $ch = curl_init("http://localhost:3003/api/notificaciones");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);

        // Redirigir para evitar reenvíos
        header("Location: index.php?id_usuario=$id_usuario");
        exit;
    }
}

// Si se pasó un id_usuario por GET, consultar notificaciones
$id_usuario_consulta = $_GET['id_usuario'] ?? null;

if ($id_usuario_consulta) {
    $url = "http://localhost:3003/api/notificaciones?usuario=" . urlencode($id_usuario_consulta);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $resp = json_decode($response);
    if (is_array($resp)) {
        $notificaciones = $resp;
    } else {
        $error = "No se pudieron obtener notificaciones.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificaciones - PawPal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Notificaciones de PawPal</h1>

    <!-- Formulario para crear una nueva notificación -->
    <form method="POST" action="index.php">
        <div class="mb-3">
            <label for="id_usuario" class="form-label">ID Usuario</label>
            <input type="number" class="form-control" id="id_usuario" name="id_usuario" value="<?= htmlspecialchars($id_usuario_consulta ?? '') ?>" placeholder="ID Usuario">
        </div>

        <div class="mb-3">
            <label for="mensaje" class="form-label">Selecciona un mensaje</label>
            <select class="form-control" id="mensaje" name="mensaje" onchange="actualizarEstado()" required>
                <option value="Tu solicitud ha sido aceptada">Tu solicitud ha sido aceptada</option>
                <option value="Tu solicitud ha sido rechazada">Tu solicitud ha sido rechazada</option>
                <option value="Tu solicitud está en proceso de revisión">Tu solicitud está en proceso de revisión</option>
                <option value="El estado de tu mascota ha cambiado">El estado de tu mascota ha cambiado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="Pendiente">Pendiente</option>
                <option value="Aceptada">Aceptada</option>
                <option value="Rechazada">Rechazada</option>
                <option value="En proceso">En proceso</option>
                <option value="Completada">Completada</option>
                <option value="No leída">No leída</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Notificación</button>
    </form>

    <!-- Mostrar mensaje de error si ocurre alguno -->
    <?php if ($error): ?>
        <div class="alert alert-warning mt-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Formulario para filtrar notificaciones solo por ID Usuario -->
    <form method="GET" action="index.php">
        <div class="mb-3">
            <label for="id_usuario" class="form-label">Filtrar por ID Usuario</label>
            <input type="number" class="form-control" id="id_usuario" name="id_usuario" value="<?= htmlspecialchars($id_usuario_consulta ?? '') ?>" placeholder="ID Usuario">
        </div>

        <button type="submit" class="btn btn-success">Filtrar Notificaciones</button>
    </form>

    <!-- Tabla para mostrar las notificaciones -->
    <table class="table mt-4">
        <thead>
        <tr>
            <th>ID Notificación</th>
            <th>ID Usuario</th>
            <th>Mensaje</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($notificaciones)): ?>
            <?php foreach ($notificaciones as $n): ?>
                <tr>
                    <td><?= $n->id_notificacion ?></td>
                    <td><?= $n->id_usuario ?></td>
                    <td><?= $n->mensaje ?></td>
                    <td><?= $n->estado ?></td>
                    <td><?= date('Y-m-d H:i:s', strtotime($n->fecha)) ?></td>
                    <td>
                        <form method="POST" action="eliminarNotificacion.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $n->id_notificacion ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                        <form method="POST" action="marcarLeida.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $n->id_notificacion ?>">
                            <button type="submit" class="btn btn-warning btn-sm">Marcar como leída</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No hay notificaciones para mostrar.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function actualizarEstado() {
        const mensaje = document.getElementById("mensaje").value;
        const estado = document.getElementById("estado");

        switch (mensaje) {
            case "Tu solicitud ha sido aceptada":
                estado.value = "Aceptada";
                break;
            case "Tu solicitud ha sido rechazada":
                estado.value = "Rechazada";
                break;
            case "Tu solicitud está en proceso de revisión":
                estado.value = "En proceso";
                break;
            case "El estado de tu mascota ha cambiado":
                estado.value = "Pendiente";
                break;
            default:
                estado.value = "Pendiente";
        }
    }
</script>
</body>
</html>
