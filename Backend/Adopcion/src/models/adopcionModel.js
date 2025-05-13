// src/models/adopcionModel.js
const mysql = require('mysql2/promise');

const conexion = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'adopcionMS' // tu base de datos ✅
});

async function crearSolicitud(id_usuario, id_animal, fecha) {
    const result = await conexion.query(
        'INSERT INTO adopcion VALUES(null, ?, ?, ?, "pendiente")',
        [id_usuario, id_animal, fecha]
    );
    return result;
}

async function traerSolicitudesPorUsuario(id_usuario) {
    const [result] = await conexion.query(
        'SELECT * FROM adopcion WHERE id_usuario = ?', [id_usuario]
    );
    return result;
}

async function traerTodasSolicitudes() {
    const [result] = await conexion.query('SELECT * FROM adopcion');
    return result;
}

async function actualizarEstado(id_solicitud, estado) {
    const [result] = await conexion.query(
        'UPDATE adopcion SET estado = ? WHERE id_solicitud = ?',
        [estado, id_solicitud]
    );
    return result;
}

async function eliminarSolicitud(id_solicitud) {
    const [result] = await conexion.query(
        'DELETE FROM adopcion WHERE id_solicitud = ?',
        [id_solicitud]
    );
    return result;
}


module.exports = {
    crearSolicitud,
    traerSolicitudesPorUsuario,
    traerTodasSolicitudes,
    actualizarEstado,
    eliminarSolicitud 
};

