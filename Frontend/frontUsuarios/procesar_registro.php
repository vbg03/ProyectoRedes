<?php
// Obtener los datos del formulario
$cc         = $_POST['cedula'];
$nombre     = $_POST['nombre'];
$usuario    = $_POST['usuario'];
$correo     = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$rol        = $_POST['rol'];

// Armar el arreglo para enviar (sin encriptar porque el backend ya debe hacerlo)
$data = array(
  'cc'       => $cc,
  'nombre'   => $nombre,
  'usuario'  => $usuario,
  'email'    => $correo,
  'password' => $contrasena,
  'rol'      => $rol
);

// Convertir a JSON
$jsonData = json_encode($data);

// URL CORRECTA del endpoint de registro
$url = 'http://localhost:3005/register';

// Inicializar cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutar y capturar respuesta
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Mostrar mensaje según el resultado
if ($http_code == 201) {
  echo "<div style='text-align:center;margin-top:50px;'>
          <h3 style='color:green;'>Usuario registrado exitosamente. Pendiente de aprobación.</h3>
          <a href='registro.php'>Volver al formulario de registro</a>
        </div>";
} else {
  echo "<div style='text-align:center;margin-top:50px; color:red;'>
          <h3>Error en el registro. Código HTTP: $http_code</h3>
          <pre>$response</pre>
          <a href='registro.php'>Volver al formulario</a>
        </div>";
}
?>
