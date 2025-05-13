// src/index.js
const express = require('express');
const morgan = require('morgan');
const app = express();
const adopcionController = require('./controllers/adopcionController'); // nombre actualizado ✅

app.use(morgan('dev'));
app.use(express.json());
app.use(adopcionController);

app.listen(3002, () => {
  console.log('Microservicio de Adopción ejecutándose en el puerto 3002');
});
