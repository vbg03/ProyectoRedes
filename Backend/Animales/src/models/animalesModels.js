const mysql = require('mysql2/promise');

const connection = mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'animalesMS'
});

async function traerAnimales() {
  const [rows] = await connection.query('SELECT * FROM animales');
  return rows;
}

async function traerAnimal(id) {
  const [rows] = await connection.query('SELECT * FROM animales WHERE id = ?', [id]);
  return rows;
}

async function crearAnimal(datos) {
  const { nombre, especie, raza, edad, vacunado, esterilizado, estado_salud, foto, estado, ubicacion } = datos;
  await connection.query(
    'INSERT INTO animales VALUES (NULL,?,?,?,?,?,?,?,?,?,?)',
    [nombre, especie, raza, edad, vacunado, esterilizado, estado_salud, foto, estado, ubicacion]
  );
}

async function actualizarAnimal(id, datos) {
  const campos = [
    datos.nombre, datos.especie, datos.raza, datos.edad,
    datos.vacunado, datos.esterilizado, datos.estado_salud,
    datos.foto, datos.estado, datos.ubicacion, id
  ];
  await connection.query(`
    UPDATE animales SET nombre=?, especie=?, raza=?, edad=?, vacunado=?, 
    esterilizado=?, estado_salud=?, foto=?, estado=?, ubicacion=? WHERE id=?`,
    campos
  );
}

async function borrarAnimal(id) {
  await connection.query('DELETE FROM animales WHERE id = ?', [id]);
}

module.exports = {
  traerAnimales,
  traerAnimal,
  crearAnimal,
  actualizarAnimal,
  borrarAnimal
};
