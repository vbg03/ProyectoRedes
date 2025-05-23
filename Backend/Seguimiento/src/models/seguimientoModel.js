const mysql = require ('mysql2/promise');

const connection = mysql.createPool({
    host: '192.168.100.2',
    user: 'root',
    password: 'Juanpabloh18@',
    database: 'adopcionms',
    port: '3306'
});

async function traerSeguimientos() {
 const result = await connection.query('SELECT * FROM seguimiento');
 return result[0];
}

async function traerSeguimiento(id_seguimiento) {
  const result = await connection.query('SELECT * FROM seguimiento WHERE id_seguimiento = ?', [id_seguimiento]);
  return result[0];  // Asegúrate de que devuelves el primer registro
}

async function traerSeguimientosPorAdoptante(id_adoptante) {
  const result = await connection.query('SELECT * FROM seguimiento WHERE id_adoptante = ?', [id_adoptante]);
  return result[0];  // Devuelve todos los seguimientos encontrados para ese adoptante
}

async function traerSeguimientosPorAnimal(id_animal) {
  const result = await connection.query('SELECT * FROM seguimiento WHERE id_animal = ?', [id_animal]);
  return result[0];  // Devuelve todos los seguimientos encontrados para ese adoptante
}




async function actualizarSeguimiento(id_seguimiento, fecha_seguimiento, comentarios, estado) {
  const result = await connection.query(
    'UPDATE seguimiento SET fecha_seguimiento = ?, comentarios = ?, estado = ? WHERE id_seguimiento = ?',
    [fecha_seguimiento, comentarios, estado, id_seguimiento]
  );
  return result;
}



async function crearSeguimiento(id_solicitud, id_adoptante, id_animal, comentarios, estado) {
    const fecha_seguimiento = new Date();  // Fecha actual automática
    const result = await connection.query(
        'INSERT INTO seguimiento VALUES (null,?,?,?,?,?,?)',
        [id_solicitud, id_adoptante, id_animal, fecha_seguimiento, comentarios, estado]
    );
    return result;
}


async function borrarSeguimiento(id_seguimiento) {
    const result = await connection.query('DELETE FROM seguimiento WHERE id_seguimiento = ?', id_seguimiento);
    return result;
}

async function borrarSeguimientoPorSolicitud(id_solicitud) {
  const [result] = await conexion.query(
    'DELETE FROM seguimiento WHERE id_solicitud = ?', [id_solicitud]
  );
  return result;
}

module.exports = {
    crearSeguimiento, traerSeguimientos, traerSeguimiento, actualizarSeguimiento, borrarSeguimiento, traerSeguimientosPorAdoptante, traerSeguimientosPorAnimal
};