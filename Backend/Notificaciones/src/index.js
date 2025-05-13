// src/index.js

const express = require('express');
const morgan = require('morgan');
const reportesController = require('./controllers/reportesControllers');  // Asegúrate de que la ruta sea correcta
const app = express();

// Middlewares
app.use(morgan('dev'));
app.use(express.json());

// Usar las rutas del controlador
app.use('/api', reportesController);

// Iniciar el servidor
app.listen(3003, () => {
    console.log('Servidor ejecutándose en el puerto 3003');
});
