// models/reportesModels.js

const mysql = require('mysql2/promise');

// Crear la conexión a la base de datos
const connection = mysql.createPool({
    host: '192.168.100.2',
    user: 'root',
    password: 'Juanpabloh18@', 
    database: 'adopcionms',
    port: '3306' // Nombre de la base de datos
});

// Crear una nueva notificación
async function crearNotificacion(id_usuario, mensaje, estado) {
    const fecha = new Date(); // Obtiene la fecha y hora actuales
    const query = 'INSERT INTO notificaciones (id_usuario, mensaje, estado, fecha) VALUES (?, ?, ?, ?)';
    const [result] = await connection.query(query, [id_usuario, mensaje, estado, fecha]);
    return { id_notificacion: result.insertId, id_usuario, mensaje, estado, fecha };
}


// Consultar todas las notificaciones de un usuario
async function obtenerNotificacionesPorUsuario(id_usuario) {
    const query = 'SELECT * FROM notificaciones WHERE id_usuario = ?';
    const [rows] = await connection.query(query, [id_usuario]);
    return rows;
}

// Consultar una notificación específica por ID
async function obtenerNotificacionPorId(id_notificacion) {
    const query = 'SELECT * FROM notificaciones WHERE id_notificacion = ?';
    const [rows] = await connection.query(query, [id_notificacion]);
    return rows[0];  // Retorna la primera fila (un único registro)
}

// Marcar una notificación como leída
async function marcarNotificacionComoLeida(id_notificacion) {
    const query = 'UPDATE notificaciones SET estado = "leída" WHERE id_notificacion = ?';
    const [result] = await connection.query(query, [id_notificacion]);
    return result.affectedRows > 0;  // Retorna true si la fila fue actualizada
}

// Eliminar una notificación
async function eliminarNotificacion(id_notificacion) {
    const query = 'DELETE FROM notificaciones WHERE id_notificacion = ?';
    const [result] = await connection.query(query, [id_notificacion]);
    return result.affectedRows > 0;  // Retorna true si la fila fue eliminada
}

module.exports = {
    crearNotificacion,
    obtenerNotificacionesPorUsuario,
    obtenerNotificacionPorId,
    marcarNotificacionComoLeida,
    eliminarNotificacion
};