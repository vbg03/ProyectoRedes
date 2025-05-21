// src/controllers/adopcionController.js
const { Router } = require('express');
const router = Router();
const modelo = require('../models/adopcionModel');
const axios = require('axios');

router.post('/solicitudes', async (req, res) => {
  const { id_usuario, id_animal, fecha } = req.body;

  try {
    // Verificar usuario
    const usuarioResp = await axios.get(`http://localhost:3005/usuarios/${id_usuario}`);
    if (!usuarioResp.data) {
      return res.status(404).send("El usuario no existe");
    }

    // Verificar animal
    const animalResp = await axios.get(`http://localhost:3002/animales/${id_animal}`);
    if (!animalResp.data) {
      return res.status(404).send("El animal no existe");
    }

    // Crear la solicitud si ambos existen
    await modelo.crearSolicitud(id_usuario, id_animal, fecha);
    res.send("Solicitud registrada");
  } catch (error) {
    console.error("Error al procesar la solicitud:", error.message);
    res.status(500).send("Error interno del servidor");
  }
});

// Consultar historial por usuario
router.post('/solicitudes', async (req, res) => {
  const { id_usuario, id_animal, fecha } = req.body;

  try {
    // 1. Verificar que el usuario existe
    const usuarioResp = await axios.get(`http://localhost:3005/usuarios/${id_usuario}`);
    if (!usuarioResp.data || usuarioResp.data.length === 0) {
      return res.status(404).send("El usuario no existe");
    }

    // 2. Verificar que el animal existe
    const animalResp = await axios.get(`http://localhost:3002/animales/${id_animal}`);
    if (!animalResp.data) {
      return res.status(404).send("El animal no existe");
    }

    // 3. Crear la solicitud de adopción
    const result = await modelo.crearSolicitud(id_usuario, id_animal, fecha);
    const id_solicitud = result[0].insertId;

    // 4. Crear el seguimiento correspondiente
    await axios.post('http://localhost:3004/seguimiento', {
      id_solicitud,
      id_adoptante: id_usuario,
      id_animal,
      comentarios: "Solicitud de adopción creada",
      estado: "pendiente"
    });

    // 5. (Opcional) Notificar al usuario
    await axios.post('http://localhost:3006/notificaciones', {
      id_usuario,
      mensaje: "Tu solicitud de adopción ha sido registrada",
      estado: "pendiente"
    });

    res.send("Solicitud registrada y seguimiento creado");
  } catch (error) {
    console.error("Error procesando la solicitud de adopción:", error.message);
    res.status(500).send("Error al registrar la solicitud de adopción");
  }
});


// Consultar todas las solicitudes
router.get('/solicitudes/todas', async (req, res) => {
  const result = await modelo.traerTodasSolicitudes();
  res.json(result);
});

// Cambiar estado de solicitud
router.put('/solicitudes/:id', async (req, res) => {
  const id_solicitud = req.params.id;
  const { estado } = req.body;

  const estadosValidos = ['pendiente', 'aprobada', 'rechazada'];
  if (!estadosValidos.includes(estado)) {
    return res.status(400).send("Estado inválido");
  }

  try {
    // Actualizar estado
    const result = await modelo.actualizarEstado(id_solicitud, estado);
    if (result.affectedRows === 0) {
      return res.status(404).send("Solicitud no encontrada");
    }

    // Obtener la solicitud para saber el id_usuario
    const solicitudes = await modelo.traerTodasSolicitudes(); // ⚠️ Puedes optimizar si tienes traerSolicitudPorId
    const solicitud = solicitudes.find(s => s.id_solicitud == id_solicitud);
    const id_usuario = solicitud?.id_usuario;

    if (!id_usuario) {
      return res.status(404).send("Usuario relacionado no encontrado");
    }

    // Enviar notificación
    await axios.post('http://localhost:3006/notificaciones', {
      id_usuario,
      mensaje: `Tu solicitud de adopción fue ${estado}`,
      estado
    });

    res.send("Estado actualizado y notificación enviada");
  } catch (error) {
    console.error("Error actualizando estado:", error.message);
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