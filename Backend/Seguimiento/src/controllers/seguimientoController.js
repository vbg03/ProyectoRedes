const { Router } = require('express');
const router = Router();
const seguimientoModel = require('../models/seguimientoModel');
const axios = require('axios');

router.get('/seguimiento', async (req, res) => {
  var result;
  result = await seguimientoModel.traerSeguimientos();
  res.json(result);
})

router.put('/seguimiento/:id_seguimiento', async (req, res) => {
  const id_seguimiento = req.params.id_seguimiento;
  const { fecha_seguimiento, comentarios, estado } = req.body;

  if (!fecha_seguimiento || !comentarios?.trim() || !estado?.trim()) {
    return res.status(400).send("Todos los campos son obligatorios");
  }

  try {
    // Actualizar seguimiento
    const result = await seguimientoModel.actualizarSeguimiento(id_seguimiento, fecha_seguimiento, comentarios, estado);
    if (result.affectedRows === 0) {
      return res.status(404).send("El seguimiento no fue encontrado o no se actualizó");
    }

    // Obtener info de seguimiento
    const seguimientoData = await seguimientoModel.traerSeguimiento(id_seguimiento);
    const id_adoptante = seguimientoData[0]?.id_adoptante;

    if (!id_adoptante) {
      return res.status(404).send("No se encontró el adoptante relacionado");
    }

    // Enviar notificación
    await axios.post('http://192.168.100.2:3003/notificaciones', {
      id_usuario: id_adoptante,
      mensaje: `El estado de seguimiento de tu adopción ha cambiado a: ${estado}`,
      estado
    });

    res.status(200).send("Seguimiento actualizado y notificación enviada");

  } catch (error) {
    console.error("Error en seguimiento:", error.message);
    res.status(500).send("Error al actualizar seguimiento");
  }
});



router.get('/seguimiento/adoptante/:id_adoptante', async (req, res) => {
  const id_adoptante = req.params.id_adoptante;  // Obtener el id_adoptante desde los parámetros de la URL

  try {
    // Consultar los seguimientos del adoptante
    const result = await seguimientoModel.traerSeguimientosPorAdoptante(id_adoptante);

    // Verificar si no se encontraron seguimientos
    if (!result || result.length === 0) {
      return res.status(404).json({ message: 'No se encontraron seguimientos para este adoptante' });
    }

    // Responder con los seguimientos encontrados
    res.json(result);  // Devolver todos los seguimientos encontrados para el adoptante
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Error al consultar los seguimientos del adoptante' });
  }
});

router.get('/seguimiento/animal/:id_animal', async (req, res) => {
  const id_animal = req.params.id_animal;  // Obtener el id_adoptante desde los parámetros de la URL

  try {
    // Consultar los seguimientos del adoptante
    const result = await seguimientoModel.traerSeguimientosPorAnimal(id_animal);

    // Verificar si no se encontraron seguimientos
    if (!result || result.length === 0) {
      return res.status(404).json({ message: 'No se encontraron seguimientos para este animal' });
    }

    // Responder con los seguimientos encontrados
    res.json(result);  // Devolver todos los seguimientos encontrados para el adoptante
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: 'Error al consultar los seguimientos del animal' });
  }
});

router.put('/seguimiento/:id_seguimiento', async (req, res) => {
  const id_seguimiento = req.params.id_seguimiento;
  const fecha_seguimiento = req.body.fecha_seguimiento;
  const comentarios = req.body.comentarios;
  const estado = req.body.estado;

  // Verificar si la fecha es válida
  if (!fecha_seguimiento) {
    return res.status(400).send("La fecha de seguimiento no puede ser nula");
  }

  // Verificar si los comentarios son válidos
  if (!comentarios || comentarios.trim() === '') {
    return res.status(400).send("Debe existir un comentario");
  }

  // Verificar si el estado no está vacío
  if (!estado || estado.trim() === '') {
    return res.status(400).send("El estado no puede estar vacío");
  }

  // Realizar la actualización del seguimiento
  try {
    const result = await seguimientoModel.actualizarSeguimiento(id_seguimiento, fecha_seguimiento, comentarios, estado);
    if (result.affectedRows === 0) {
      return res.status(404).send("El seguimiento no fue encontrado o no se actualizó");
    }
    res.status(200).send("Seguimiento actualizado correctamente");
  } catch (error) {
    console.error(error);
    res.status(500).send("Error al actualizar el seguimiento");
  }
});



router.post('/seguimiento', async (req, res) => {
  const id_solicitud = req.body.id_solicitud;
  const id_adoptante = req.body.id_adoptante;
  const id_animal = req.body.id_animal;
  const comentarios = req.body.comentarios;
  const estado = req.body.estado;


  var result = await seguimientoModel.crearSeguimiento(id_solicitud, id_adoptante, id_animal, comentarios, estado);
  res.send("seguimiento creado");
});



router.delete('/seguimiento/:id_seguimiento', async (req, res) => {
  const id_seguimiento = req.params.id_seguimiento;
  var result;
  result = await seguimientoModel.borrarSeguimiento(id_seguimiento);
  res.send("seguimiento borrado");
});


// DELETE seguimiento por id_solicitud (en seguimientoController.js)
router.delete('/seguimiento/solicitud/:id_solicitud', async (req, res) => {
  const id_solicitud = req.params.id_solicitud;

  try {
    const result = await seguimientoModel.borrarSeguimientoPorSolicitud(id_solicitud);

    if (result.affectedRows === 0) {
      return res.status(404).send("No se encontró seguimiento para esa solicitud");
    }

    res.send("Seguimiento eliminado correctamente");
  } catch (error) {
    console.error("Error eliminando seguimiento por solicitud:", error.message);
    res.status(500).send("Error al eliminar seguimiento");
  }
});

module.exports = router;