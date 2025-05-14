const mysql = require('mysql2/promise');

// Crear la conexión a la base de datos
const connection = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '', 
    database: 'notificaciones', // Nombre de la base de datos
});

// Crear una nueva notificación
async function crearNotificacion(id_usuario, mensaje, estado) {
    const fecha = new Date(); // Obtiene la fecha y hora actuales
    const query = 'INSERT INTO notificaciones (id_usuario, mensaje, estado, fecha) VALUES (?, ?, ?, ?)';
    
    try {
        const [result] = await connection.query(query, [id_usuario, mensaje, estado, fecha]);
        return { 
            id_notificacion: result.insertId, 
            id_usuario, 
            mensaje, 
            estado, 
            fecha 
        }; // Incluye la fecha en el retorno
    } catch (error) {
        console.error('Error al crear la notificación:', error);
        throw new Error('Error al crear la notificación');
    }
}

// Consultar todas las notificaciones con solo el filtro de usuario
async function obtenerNotificaciones(id_usuario) {
    // Comenzamos la consulta básica
    let query = 'SELECT * FROM notificaciones WHERE 1=1'; // Siempre es verdadero, lo que permite agregar más condiciones
    let params = [];

    // Si se pasó un id_usuario, agregamos el filtro correspondiente
    if (id_usuario) {
        query += ' AND id_usuario = ?';
        params.push(id_usuario);
    }

    try {
        const [rows] = await connection.query(query, params);
        return rows;
    } catch (error) {
        console.error('Error al obtener las notificaciones:', error);
        throw new Error('Error al obtener las notificaciones');
    }
}

// Marcar una notificación como leída
async function marcarNotificacionComoLeida(id_notificacion) {
    const query = 'UPDATE notificaciones SET estado = "leída" WHERE id_notificacion = ?';
    
    try {
        const [result] = await connection.query(query, [id_notificacion]);
        return result.affectedRows > 0;  // Retorna true si la fila fue actualizada
    } catch (error) {
        console.error('Error al marcar la notificación como leída:', error);
        throw new Error('Error al marcar la notificación como leída');
    }
}

// Eliminar una notificación
async function eliminarNotificacion(id_notificacion) {
    const query = 'DELETE FROM notificaciones WHERE id_notificacion = ?';
    
    try {
        const [result] = await connection.query(query, [id_notificacion]);
        return result.affectedRows > 0;  // Retorna true si la fila fue eliminada
    } catch (error) {
        console.error('Error al eliminar la notificación:', error);
        throw new Error('Error al eliminar la notificación');
    }
}

module.exports = {
    crearNotificacion,
    obtenerNotificaciones,  // Esta es la única que mantiene el filtro por ID Usuario
    marcarNotificacionComoLeida,
    eliminarNotificacion
};
