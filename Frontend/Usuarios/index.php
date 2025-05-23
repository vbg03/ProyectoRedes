<?php
// index.php
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Lista de Usuarios</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
<h2>Usuarios registrados</h2>

<?php
$url = "http://192.168.100.3:3005/admin/users";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$usuarios = json_decode($response, true);

if (!$usuarios || count($usuarios) == 0) {
    echo "<p>No hay usuarios registrados.</p>";
} else {
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>ID</th><th>Nombre</th><th>Usuario</th><th>Email</th><th>Rol</th><th>Estado</th></tr></thead><tbody>';

    foreach ($usuarios as $u) {
        echo "<tr>
            <td>" . htmlspecialchars($u['id_usuario']) . "</td>
            <td>" . htmlspecialchars($u['nombre']) . "</td>
            <td>" . htmlspecialchars($u['usuario']) . "</td>
            <td>" . htmlspecialchars($u['email']) . "</td>
            <td>" . htmlspecialchars($u['rol']) . "</td>
            <td>" . htmlspecialchars($u['estado']) . "</td>
        </tr>";
    }
    echo '</tbody></table>';
}
?>

</div>
</body>
</html>
