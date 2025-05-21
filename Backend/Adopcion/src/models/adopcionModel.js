// src/models/adopcionModel.js
const mysql = require('mysql2/promise');
const axios = require('axios'); // Para llamar al microservicio de seguimiento

const conexion = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'adopcionMS'
});

// Crear solicitud y devolver el ID insertado
async function crearSolicitud(id_usuario, id_animal, fecha) {
    const [result] = await conexion.query(
        'INSERT INTO adopcion VALUES(null, ?, ?, ?, "pendiente")',
        [id_usuario, id_animal, fecha]
    );
    return result; // ✅ Devuelve insertId
}

// Traer solicitudes por usuario
async function traerSolicitudesPorUsuario(id_usuario) {
    const [result] = await conexion.query(
        'SELECT * FROM adopcion WHERE id_usuario = ?', [id_usuario]
    );
    return result;
}

// Traer todas las solicitudes
async function traerTodasSolicitudes() {
    const [result] = await conexion.query('SELECT * FROM adopcion');
    return result;
}

// Actualizar estado de solicitud
async function actualizarEstado(id_solicitud, estado) {
    const [result] = await conexion.query(
        'UPDATE adopcion SET estado = ? WHERE id_solicitud = ?',
        [estado, id_solicitud]
    );
    return result;
}

// Eliminar solicitud + su seguimiento
async function eliminarSolicitud(id_solicitud) {
    // 1. Eliminar el seguimiento relacionado (si existe)
    try {
        await axios.delete(`http://localhost:3004/seguimiento/solicitud/${id_solicitud}`);
    } catch (error) {
        console.warn(`⚠️ No se pudo borrar el seguimiento relacionado a la solicitud ${id_solicitud}:`, error.message);
        // No cortamos el flujo si no hay seguimiento
    }

    // 2. Eliminar la solicitud
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
