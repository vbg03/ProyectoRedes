// src/controllers/adopcionController.js
const { Router } = require('express');
const router = Router();
const modelo = require('../models/adopcionModel');
const axios = require('axios');

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
    const id_solicitud = result.insertId;

    // 4. Crear el seguimiento correspondiente
    await axios.post('http://localhost:3004/seguimiento', {
      id_solicitud,
      id_adoptante: id_usuario,
      id_animal,
      comentarios: "Solicitud de adopción creada",
      estado: "pendiente"
    });

    // 5. Notificar al usuario
    await axios.post('http://localhost:3003/notificaciones', {
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


// Obtener todas las solicitudes
router.get('/solicitudes', async (req, res) => {
  try {
    const result = await modelo.traerTodasSolicitudes();
    res.json(result);
  } catch (error) {
    console.error('Error al obtener las solicitudes:', error);
    res.status(500).send('Error al obtener solicitudes');
  }
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
    // Actualizar estado de la solicitud
    const result = await modelo.actualizarEstado(id_solicitud, estado);
    if (result.affectedRows === 0) {
      return res.status(404).send("Solicitud no encontrada");
    }

    // Obtener la solicitud para obtener info
    const solicitudes = await modelo.traerTodasSolicitudes();
    const solicitud = solicitudes.find(s => s.id_solicitud == id_solicitud);

    const id_usuario = solicitud?.id_usuario;
    const id_animal = solicitud?.id_animal;

    // 1. Notificar cambio de estado
    await axios.post('http://localhost:3003/notificaciones', {
      id_usuario,
      mensaje: `Tu solicitud de adopción fue ${estado}`,
      estado
    });

    // 2. Si fue aprobada, crear seguimiento automáticamente
    if (estado === 'aprobada') {
      await axios.post('http://localhost:3004/seguimiento', {
        id_solicitud,
        id_adoptante: id_usuario,
        id_animal,
        comentarios: "Seguimiento iniciado tras aprobación",
        estado: "en proceso"
      });
    }

    res.send("Estado actualizado y notificación enviada");

  } catch (error) {
    console.error("Error actualizando solicitud:", error);
    res.status(500).send("Error al actualizar estado");
  }
});



module.exports = router;