<?php
// gestion_usuarios.php

// Obtener todos los usuarios vía API REST
$url = "http://localhost:3005/admin/users";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$usuarios = json_decode($response, true);

// Separar usuarios activos e inactivos
$activos = [];
$inactivos = [];

if ($usuarios && is_array($usuarios)) {
    foreach ($usuarios as $u) {
        if (isset($u['estado']) && $u['estado'] === 'activo') {
            $activos[] = $u;
        } else {
            $inactivos[] = $u;
        }
    }
} else {
    $activos = [];
    $inactivos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Usuarios</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #f2f2f2, #e6e6e6);
      margin: 0;
      padding: 2rem;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 2rem;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }
    th {
      background-color: #ff6600;
      color: white;
    }
    button {
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .btn-editar {
      background-color: #007bff;
      color: white;
    }
    .btn-eliminar {
      background-color: #dc3545;
      color: white;
    }
    .btn-estado {
      background-color: #28a745;
      color: white;
    }
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0,0,0,0.5);
      display: none;
      align-items: center;
      justify-content: center;
    }
    .modal-content {
      background: #fff;
      padding: 2rem;
      border-radius: 10px;
      width: 400px;
      position: relative;
    }
    .modal-content input, .modal-content select {
      width: 100%;
      margin: 0.5rem 0;
      padding: 8px;
    }
    .close-modal {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
      font-weight: bold;
    }
    h3 {
      margin-top: 3rem;
      margin-bottom: 1rem;
      color: #333;
    }
  </style>
</head>
<body>
  <div style="text-align: right; margin-bottom: 1rem;">
  <a href="logout.php" style="color: #dc3545; font-weight: bold; text-decoration:none;">Cerrar Sesión</a>
 </div>
  <div class="container">
    <h2>Gestión de Usuarios</h2>

    <!-- Tabla de usuarios ACTIVOS -->
    <h3>Usuarios Activos</h3>
    <table>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>id_usuario</th>
        <th>Email</th>
        <th>Usuario</th>
        <th>Estado</th>
        <th>Rol</th>
        <th>Acciones</th>
      </tr>
      <?php foreach($activos as $fila): ?>
      <tr>
        <td><?= htmlspecialchars($fila['id_usuario']) ?></td>
        <td><?= htmlspecialchars($fila['nombre']) ?></td>
        <td><?= htmlspecialchars($fila['id_usuario']) ?></td>
        <td><?= htmlspecialchars($fila['email']) ?></td>
        <td><?= htmlspecialchars($fila['usuario']) ?></td>
        <td><?= htmlspecialchars($fila['estado']) ?></td>
        <td><?= htmlspecialchars($fila['rol']) ?></td>
        <td>
          <button class="btn-estado" onclick="cambiarEstado('<?= htmlspecialchars($fila['id_usuario']) ?>')">Desactivar</button>
          <!-- <button class="btn-editar" onclick='abrirEditarModal(<?= json_encode($fila, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>Editar</button> -->
          <button class="btn-eliminar" onclick="confirmarEliminar('<?= htmlspecialchars($fila['id_usuario']) ?>')">Eliminar</button>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>

    <!-- Tabla de usuarios INACTIVOS -->
    <h3>Usuarios Inactivos</h3>
    <table>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>id_usuario</th>
        <th>Email</th>
        <th>Usuario</th>
        <th>Estado</th>
        <th>Rol</th>
        <th>Acciones</th>
      </tr>
      <?php foreach($inactivos as $fila): ?>
      <tr>
        <td><?= htmlspecialchars($fila['id_usuario']) ?></td>
        <td><?= htmlspecialchars($fila['nombre']) ?></td>
        <td><?= htmlspecialchars($fila['id_usuario']) ?></td>
        <td><?= htmlspecialchars($fila['email']) ?></td>
        <td><?= htmlspecialchars($fila['usuario']) ?></td>
        <td><?= htmlspecialchars($fila['estado']) ?></td>
        <td><?= htmlspecialchars($fila['rol']) ?></td>
        <td>
          <button class="btn-estado" onclick="cambiarEstado('<?= htmlspecialchars($fila['id_usuario']) ?>')">Activar</button>
          <button class="btn-editar" onclick='abrirEditarModal(<?= json_encode($fila, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>Editar</button>
          <button class="btn-eliminar" onclick="confirmarEliminar('<?= htmlspecialchars($fila['id_usuario']) ?>')">Eliminar</button>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <!-- Modal de edición -->
 <div class="modal" id="editarModal">
   <div class="modal-content">
     <span class="close-modal" onclick="cerrarModales()">X</span>
     <h3>Editar Usuario</h3>
     <form id="formEditar">
       <input type="hidden" id="edit-id" name="id">
       <input type="text" id="edit-nombre" name="nombre" placeholder="Nombre" required>
       <input type="number" id="edit-id_usuario" name="id_usuario" placeholder="Cédula" readonly>
       <input type="email" id="edit-email" name="email" placeholder="Correo" required>
       <input type="text" id="edit-usuario" name="usuario" placeholder="Usuario" required>
       <!-- No se edita rol ni contraseña aquí -->
       <button type="submit">Guardar</button>
     </form>
   </div>
 </div>

  <!-- Modal de eliminación -->
  <div class="modal" id="eliminarModal">
    <div class="modal-content">
      <span class="close-modal" onclick="cerrarModales()">X</span>
      <h3>¿Estás seguro de eliminar este usuario?</h3>
      <button onclick="eliminarUsuario()">Sí, eliminar</button>
      <button onclick="cerrarModales()">Cancelar</button>
    </div>
  </div>

  <script>
    let usuarioAEliminar = null;

    function cambiarEstado(id) {
      fetch('cambiar_estado.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${encodeURIComponent(id)}`
      }).then(() => location.reload());
    }

    function abrirEditarModal(data) {
        document.getElementById('edit-id').value = data.id_usuario;
        document.getElementById('edit-nombre').value = data.nombre;
        document.getElementById('edit-id_usuario').value = data.id_usuario;
        document.getElementById('edit-email').value = data.email;
        document.getElementById('edit-usuario').value = data.usuario;
        document.getElementById('editarModal').style.display = 'flex';
    }

    document.getElementById('formEditar').addEventListener('submit', function(e) {
        e.preventDefault();
        const datos = new FormData(this);
        const id = datos.get('id');

        fetch(`http://localhost:3005/admin/users/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nombre: datos.get('nombre'),
                email: datos.get('email'),
                usuario: datos.get('usuario'),
                password: '' // No actualizamos contraseña aquí
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.message) });
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            cerrarModales();
            location.reload();
        })
        .catch(error => {
            alert("Error al actualizar: " + error.message);
            console.error('Error:', error);
        });
    });

    function confirmarEliminar(id) {
      usuarioAEliminar = id;
      document.getElementById('eliminarModal').style.display = 'flex';
    }

    function eliminarUsuario() {
      fetch('eliminar_usuario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${encodeURIComponent(usuarioAEliminar)}`
      }).then(() => location.reload());
    }

    function cerrarModales() {
      document.querySelectorAll('.modal').forEach(modal => modal.style.display = 'none');
    }

    window.addEventListener('click', function(e) {
      if (e.target.classList.contains('modal')) {
        cerrarModales();
      }
    });
  </script>
</body>
</html>
