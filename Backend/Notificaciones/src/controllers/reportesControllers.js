const { Router } = require('express');
const router = Router();
const reportesModel = require('../models/reportesModels');

// Ruta para obtener todas las notificaciones de un usuario
router.get('/notificaciones', async (req, res) => {
    const { usuario, estado } = req.query;

    let query = 'SELECT * FROM notificaciones WHERE 1=1'; // Always true, to build flexible query
    let params = [];

    // Si el parámetro 'usuario' está presente, lo agregamos a la consulta
    if (usuario) {
        query += ' AND id_usuario = ?';
        params.push(usuario);
    }

    // Si el parámetro 'estado' está presente, lo agregamos a la consulta
    if (estado) {
        query += ' AND estado = ?';
        params.push(estado);
    }

    try {
        console.log('Query:', query);  // Imprimir la consulta para depurar
        console.log('Params:', params);  // Imprimir los parámetros para depurar

        const notifications = await reportesModel.obtenerNotificaciones(query, params);

        if (notifications.length === 0) {
            return res.status(404).json({ error: 'No se encontraron notificaciones.' });
        }

        res.status(200).json(notifications);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al obtener las notificaciones' });
    }
});

module.exports = router;
