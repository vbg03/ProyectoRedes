<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Notificaciones - PawPal</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Notificaciones de PawPal</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Mensaje</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // URL de la API REST
                $servurl = "http://localhost:3003/api/notificaciones?usuario=1";  // Cambia el ID según corresponda
                $curl = curl_init($servurl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                curl_close($curl);

                // Comprobación de la respuesta
                if ($response === false) {
                    die("Error en la conexión a la API.");
                }

                // Decodificando la respuesta JSON
                $resp = json_decode($response, true);
                $long = count($resp);
                for ($i = 0; $i < $long; $i++) {
                    $dec = $resp[$i];
                    $id = $dec['id_notificacion'];
                    $mensaje = $dec['mensaje'];
                    $estado = $dec['estado'];
                    $fecha = $dec['fecha'] ?? 'No disponible'; // Si no hay fecha, se muestra "No disponible"
                ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $mensaje; ?></td>
                        <td><?php echo $estado; ?></td>
                        <td><?php echo $fecha; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
