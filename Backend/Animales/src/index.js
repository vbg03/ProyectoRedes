const express = require('express');
const morgan = require('morgan');
const animalesController = require('./controllers/animalesController');

const app = express();
app.use(morgan('dev'));
app.use(express.json());

app.use(animalesController);

app.listen(3002, () => {
  console.log("backAnimales ejecutándose en el puerto 3002");
});
