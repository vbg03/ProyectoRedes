const mysql = require('mysql2/promise');

const connection = mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'usuarios',
});

async function traerUsuarios() {
  const result = await connection.query('SELECT * FROM usuarios');
  return result[0];
}

async function traerUsuario(id) {
  const result = await connection.query(
    'SELECT * FROM usuarios WHERE id = ?',
    id
  );
  return result[0];
}

async function traerUsuarioEmail(identificador) {
  const [rows] = await connection.query(
    'SELECT * FROM usuarios WHERE usuario = ? OR email = ?',
    [identificador, identificador]
  );
  console.log("Resultado SQL:", rows);
  return rows[0];
}

async function actualizarUsuario(id, nombre, email, usuario, password) {
  const [result] = await connection.query(
    'UPDATE usuarios SET nombre = ?, email = ?, usuario = ?, password = ? WHERE id = ?',
    [nombre, email, usuario, password, id]
  );
  return result;
}

async function actualizarEstado(id, estado) {
  const resultEstado = await connection.query(
    'UPDATE usuarios SET estado = ? WHERE id = ?',
    [estado, id]
  );
  return resultEstado;
}

async function crearUsuario(nombre, id_usuario, email, usuario, password, estado, rol) {
  const result = await connection.query(
    'INSERT INTO usuarios VALUES(NULL,?,?,?,?,?,?,?)',
    [nombre, id_usuario, email, usuario, password, estado, rol]
  );
  return result;
}

async function borrarUsuario(id) {
  const result = await connection.query(
    'DELETE FROM usuarios WHERE id = ?',
    id
  );
  return result;
}

module.exports = {
  traerUsuarios,
  traerUsuario,
  traerUsuarioEmail,
  actualizarUsuario,
  actualizarEstado,
  crearUsuario,
  borrarUsuario,
};

