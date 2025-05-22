const express = require('express');
const morgan = require('morgan');
const usuariosController = require('./controllers/usuarioController');

const app = express();
app.use(morgan('dev'));
app.use(express.json());

// Asegúrate de que esto esté bien conectado
app.use(usuariosController);

// Escuchar en el puerto 3005
app.listen(3005, () => {
  console.log('🚀 Microservicio de Usuarios corriendo en puerto 3005');
});
