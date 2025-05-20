<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Animales disponibles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <h2 class="mb-4">🐾 Animales Disponibles para Adopción</h2>

  <!-- Botón para abrir el modal -->
  <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearModal">
    ➕ Registrar Nuevo Animal
  </button>

  <!-- Tabla de animales -->
  <table class="table table-striped table-hover">
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
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $url = "http://localhost:3002/animales";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $response = mb_convert_encoding($response, 'UTF-8', 'ISO-8859-1');
        curl_close($curl);

        if ($response === false) {
          echo "<tr><td colspan='11'>Error al conectar con el servidor</td></tr>";
        } else {
          $animales = json_decode($response);
          foreach ($animales as $animal) {
            echo "<tr>
                    <td>{$animal->id}</td>
                    <td><img src='{$animal->foto}' alt='Foto de {$animal->nombre}' style='width: 60px; height: 60px; object-fit: cover; border-radius: 5px;'></td>
                    <td>{$animal->nombre}</td>
                    <td>{$animal->especie}</td>
                    <td>{$animal->raza}</td>
                    <td>{$animal->edad}</td>
                    <td>" . ($animal->vacunado ? 'Sí' : 'No') . "</td>
                    <td>" . ($animal->esterilizado ? 'Sí' : 'No') . "</td>
                    <td>{$animal->estado_salud}</td>
                    <td>{$animal->ubicacion}</td>
                    <td>{$animal->estado}</td>
                    

                    <td>
                      <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editarModal{$animal->id}'>✏️</button>
                      <a href='eliminarAnimal.php?id={$animal->id}' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro que deseas eliminar este animal?\")'>🗑️</a>
                    </td>
                  </tr>";

            echo "<div class='modal fade' id='editarModal{$animal->id}' tabindex='-1' aria-labelledby='editarModalLabel{$animal->id}' aria-hidden='true'>
              <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                  <form action='editarAnimal.php' method='POST' class='p-3'>
                    <input type='hidden' name='id' value='{$animal->id}'>
                    <div class='modal-header'>
                      <h5 class='modal-title' id='editarModalLabel{$animal->id}'>Editar Animal - {$animal->nombre}</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body row g-3'>
                      <div class='col-md-6'>
                        <label class='form-label'>Nombre</label>
                        <input type='text' name='nombre' class='form-control' value='{$animal->nombre}' required>
                      </div>
                      <div class='col-md-6'>
                        <label class='form-label'>Especie</label>
                        <input type='text' name='especie' class='form-control' value='{$animal->especie}' required>
                      </div>
                      <div class='col-md-6'>
                        <label class='form-label'>Raza</label>
                        <input type='text' name='raza' class='form-control' value='{$animal->raza}'>
                      </div>
                      <div class='col-md-6'>
                        <label class='form-label'>Edad</label>
                        <input type='number' name='edad' class='form-control' value='{$animal->edad}'>
                      </div>
                      <div class='col-md-6'>
                        <label class='form-label'>Ubicación</label>
                        <input type='text' name='ubicacion' class='form-control' value='{$animal->ubicacion}'>
                      </div>
                      <div class='col-md-6'>
                        <label class='form-label'>Estado</label>
                        <input type='text' name='estado' class='form-control' value='{$animal->estado}'>
                      </div>
                      <div class='col-md-12'>
                        <label class='form-label'>Estado de Salud</label>
                        <textarea name='estado_salud' class='form-control'>{$animal->estado_salud}</textarea>
                      </div>
                      <div class='col-md-12'>
                        <label class='form-label'>URL Foto</label>
                        <input type='text' name='foto' class='form-control' value='{$animal->foto}'>
                      </div>
                      <div class='col-md-6 form-check'>
                        <input class='form-check-input' type='checkbox' name='vacunado' id='vacunado{$animal->id}'" . ($animal->vacunado ? " checked" : "") . ">
                        <label class='form-check-label' for='vacunado{$animal->id}'>Vacunado</label>
                      </div>
                      <div class='col-md-6 form-check'>
                        <input class='form-check-input' type='checkbox' name='esterilizado' id='esterilizado{$animal->id}'" . ($animal->esterilizado ? " checked" : "") . ">
                        <label class='form-check-label' for='esterilizado{$animal->id}'>Esterilizado</label>
                      </div>
                    </div>
                    <div class='modal-footer mt-3'>
                      <button type='submit' class='btn btn-success'>Guardar Cambios</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>";
          }
        }
      ?>
    </tbody>
  </table>

  <!-- Modal para crear nuevo animal -->
  <div class="modal fade" id="crearModal" tabindex="-1" aria-labelledby="crearModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="crearAnimal.php" method="POST" class="p-3">
          <div class="modal-header">
            <h5 class="modal-title" id="crearModalLabel">Registrar Nuevo Animal</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Especie</label>
              <input type="text" name="especie" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Raza</label>
              <input type="text" name="raza" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Edad</label>
              <input type="number" name="edad" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Ubicación</label>
              <input type="text" name="ubicacion" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Estado (Disponible / Adoptado)</label>
              <input type="text" name="estado" class="form-control" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Estado de Salud</label>
              <textarea name="estado_salud" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-md-12">
              <label class="form-label">URL de la Foto</label>
              <input type="text" name="foto" class="form-control">
            </div>
            <div class="col-md-6 form-check">
              <input class="form-check-input" type="checkbox" name="vacunado" id="vacunado">
              <label class="form-check-label" for="vacunado">Vacunado</label>
            </div>
            <div class="col-md-6 form-check">
              <input class="form-check-input" type="checkbox" name="esterilizado" id="esterilizado">
              <label class="form-check-label" for="esterilizado">Esterilizado</label>
            </div>
          </div>
          <div class="modal-footer mt-3">
            <button type="submit" class="btn btn-success">Guardar Animal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>



