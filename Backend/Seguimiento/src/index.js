const express = require('express');
const SeguimientoController = require('./controllers/seguimientoController');
const morgan = require('morgan');
const app = express();
app.use(morgan('dev'));
app.use(express.json());
app.use(SeguimientoController);

app.listen(3004, () => {
 console.log('Seguimiento ejecutandose en el puerto 3004');
});