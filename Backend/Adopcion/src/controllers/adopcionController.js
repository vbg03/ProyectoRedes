// src/controllers/adopcionController.js
const { Router } = require('express');
const router = Router();
const modelo = require('../models/adopcionModel');

// Crear solicitud
router.post('/solicitudes', async (req, res) => {
  const { id_usuario, id_animal, fecha } = req.body;
  const result = await modelo.crearSolicitud(id_usuario, id_animal, fecha);
  res.send("Solicitud registrada");
});

// Consultar historial por usuario
router.get('/solicitudes', async (req, res) => {
  const usuario = req.query.usuario;
  const result = await modelo.traerSolicitudesPorUsuario(usuario);
  res.json(result);
});

// Consultar todas las solicitudes
router.get('/solicitudes/todas', async (req, res) => {
  const result = await modelo.traerTodasSolicitudes();
  res.json(result);
});

// Cambiar estado de solicitud
router.put('/solicitudes/:id', async (req, res) => {
  try {
    const id = req.params.id;
    const estado = req.body.estado;

    const estadosValidos = ['pendiente', 'aprobada', 'rechazada'];
    if (!estadosValidos.includes(estado)) {
      return res.status(400).send("Estado inválido");
    }

    const result = await modelo.actualizarEstado(id, estado);

    if (result.affectedRows === 0) {
      return res.status(404).send("Solicitud no encontrada");
    }

    res.send("Estado actualizado");
  } catch (error) {
    console.error("Error actualizando estado:", error);
    res.status(500).send("Error interno del servidor");
  }
});

router.delete('/solicitudes/:id', async (req, res) => {
  console.log("DELETE /solicitudes/:id activada");
  try {
    const id = req.params.id;
    const result = await modelo.eliminarSolicitud(id);

    if (result.affectedRows === 0) {
      return res.status(404).send("Solicitud no encontrada");
    }

    res.send("Solicitud eliminada");
  } catch (error) {
    console.error("Error eliminando solicitud:", error);
    res.status(500).send("Error interno del servidor");
  }
});


module.exports = router;
