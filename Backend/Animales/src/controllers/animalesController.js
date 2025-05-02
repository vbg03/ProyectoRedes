const { Router } = require('express');
const router = Router();
const animalesModel = require('../models/animalesModel');

router.get('/animales', async (req, res) => {
  const result = await animalesModel.traerAnimales();
  res.json(result);
});

router.get('/animales/:id', async (req, res) => {
  const result = await animalesModel.traerAnimal(req.params.id);
  res.json(result[0]);
});

router.post('/animales', async (req, res) => {
  const datos = req.body;
  await animalesModel.crearAnimal(datos);
  res.send("Animal registrado");
});

router.put('/animales/:id', async (req, res) => {
  await animalesModel.actualizarAnimal(req.params.id, req.body);
  res.send("Animal actualizado");
});

router.delete('/animales/:id', async (req, res) => {
  await animalesModel.borrarAnimal(req.params.id);
  res.send("Animal eliminado");
});

module.exports = router;
