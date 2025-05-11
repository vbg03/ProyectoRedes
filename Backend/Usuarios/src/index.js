const express = require('express');
const usuariosController = require('./controllers/usuarioController');
const morgan = require('morgan');
const app = express();
app.use(morgan('dev'));
app.use(express.json());
app.use(usuariosController);

app.listen(3001, () => {
 console.log('backProductos ejecutandose en el puerto 3005');
});