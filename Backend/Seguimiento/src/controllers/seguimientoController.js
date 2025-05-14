const {Router} = require('express');
const router = Router();
const  seguimientoModel = require ('../models/seguimientoModel');

router.get('/seguimiento', async (req, res) =>{
    var result;
    result = await seguimientoModel.traerSeguimientos();
    res.json(result);
})

router.get('/seguimiento/:id_seguimiento', async (req, res) => {
  const id_seguimiento = req.params.id_seguimiento;
  var result = await seguimientoModel.traerSeguimiento(id_seguimiento);

  // Verificar si no se encontró el seguimiento
  if (!result || result.length === 0) {
    return res.status(404).json({ message: 'Seguimiento no encontrado' });
  }

  res.json(result[0]); // Devuelve el primer resultado
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



router.post('/seguimiento', async (req, res) =>{
    const id_solicitud = req.body.id_solicitud;
    const id_adoptante = req.body.id_adoptante;
    const id_animal = req.body.id_animal;
    const fecha_seguimiento = req.body.fecha_seguimiento;
    const comentarios = req.body.comentarios;
    const estado = req.body.estado;

    var result = await seguimientoModel.crearSeguimiento(id_solicitud, id_adoptante,id_animal, fecha_seguimiento, comentarios, estado);
    res.send("seguimiento creado");
});


router.delete('/seguimiento/:id_seguimiento', async (req, res) =>{
  const id_seguimiento = req.params.id_seguimiento;
  var result;
  result = await seguimientoModel.borrarSeguimiento(id_seguimiento);
  res.send("seguimiento borrado");
});

module.exports = router;