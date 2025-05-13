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

module.exports = router;