const mysql = require('mysql2/promise');

// Pool de conexión a MySQL
const connection = mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'adopcionMS' // ⚠️ Cambia esto si ya creaste la base como 'pawpalMS'
});

const solicitud = await modelo.traerSolicitudPorId(id_solicitud);
if (!solicitud) {
  return res.status(404).send("Solicitud no encontrada");
}
const id_usuario = solicitud.id_usuario;


// Obtener todos los animales
async function traerAnimales() {
  const [rows] = await connection.query('SELECT * FROM animales');
  return rows;
}

// Obtener un solo animal por ID
async function traerAnimal(id) {
  const [rows] = await connection.query('SELECT * FROM animales WHERE id = ?', [id]);
  return rows;
}

// Crear un nuevo animal
async function crearAnimal(datos) {
  const {
    nombre, especie, raza, edad,
    vacunado, esterilizado, estado_salud,
    foto, estado, ubicacion
  } = datos;

  const sql = `INSERT INTO animales
    (nombre, especie, raza, edad, vacunado, esterilizado, estado_salud, foto, estado, ubicacion)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;

  const valores = [
    nombre, especie, raza, edad,
    vacunado, esterilizado, estado_salud,
    foto, estado, ubicacion
  ];

  await connection.query(sql, valores);
}

// Actualizar un animal
async function actualizarAnimal(id, datos) {
  const valores = [
    datos.nombre,
    datos.especie,
    datos.raza,
    datos.edad,
    datos.vacunado,
    datos.esterilizado,
    datos.estado_salud,
    datos.foto,
    datos.estado,
    datos.ubicacion,
    id // <- El ID va al final para el WHERE
  ];

  const sql = `
    UPDATE animales SET 
      nombre = ?, 
      especie = ?, 
      raza = ?, 
      edad = ?, 
      vacunado = ?, 
      esterilizado = ?, 
      estado_salud = ?, 
      foto = ?, 
      estado = ?, 
      ubicacion = ?
    WHERE id = ?`;

  await connection.query(sql, valores);
}

// Eliminar un animal por ID
async function borrarAnimal(id) {
  await connection.query('DELETE FROM animales WHERE id = ?', [id]);
}

// Exportar funciones para el controlador
module.exports = {
  traerAnimales,
  traerAnimal,
  crearAnimal,
  actualizarAnimal,
  borrarAnimal
};
