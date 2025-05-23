const { Router } = require('express');
const router = Router();
const animalesModel = require('../models/animalesModels');
const axios = require('axios');

// Obtener todos los animales
router.get('/animales', async (req, res) => {
  try {
    const result = await animalesModel.traerAnimales();
    res.json(result);
  } catch (error) {
    res.status(500).send('Error al obtener animales');
  }
});

// Obtener un solo animal por ID
router.get('/animales/:id', async (req, res) => {
  try {
    const result = await animalesModel.traerAnimal(req.params.id);
    if (result.length === 0) {
      return res.status(404).send('Animal no encontrado');
    }
    res.json(result[0]);
  } catch (error) {
    res.status(500).send('Error al buscar animal');
  }
});

// Registrar un nuevo animal
router.post('/animales', async (req, res) => {
  try {
    const datos = req.body;
    const { id_usuario } = datos;

    if (!id_usuario) {
      return res.status(400).send("Falta el ID del usuario");
    }

    // Consultar el usuario desde microservicio de usuarios
    const usuarioResp = await axios.get(`http://192.168.100.2:3005/usuarios/${id_usuario}`);
    const usuario = usuarioResp.data;

    if (!usuario || usuario.rol !== 'rescatista' || usuario.estado !== 'activo') {
      return res.status(403).send("Solo los rescatistas activos pueden registrar animales");
    }
    console.log("Usuario obtenido desde microservicio:", usuario);


    // Crear el animal si el usuario es válido
    await animalesModel.crearAnimal(datos);
    res.send("Animal registrado con éxito");
  } catch (error) {
    console.error("Error al registrar animal:", error.message);
    res.status(500).send('Error al registrar animal');
  }
});


// Actualizar animal existente
router.put('/animales/:id', async (req, res) => {
  try {
    await animalesModel.actualizarAnimal(req.params.id, req.body);
    res.send("Animal actualizado correctamente");
  } catch (error) {
    res.status(500).send('Error al actualizar animal');
  }
});

// Eliminar animal
router.delete('/animales/:id', async (req, res) => {
  try {
    await animalesModel.borrarAnimal(req.params.id);
    res.send("Animal eliminado correctamente");
  } catch (error) {
    res.status(500).send('Error al eliminar animal');
  }
});

module.exports = router;
