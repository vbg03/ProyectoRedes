// controllers/reportesController.js

const { Router } = require('express');
const router = Router();
const reportesModel = require('../models/reportesModels');
const axios = require('axios');

// Ruta para crear una notificación
router.post('/notificaciones', async (req, res) => {
    const { id_usuario, mensaje, estado } = req.body;

    if (!id_usuario || !mensaje || !estado) {
        return res.status(400).json({ error: 'Faltan datos obligatorios' });
    }

    try {
        const result = await reportesModel.crearNotificacion(id_usuario, mensaje, estado);
        res.status(201).json({ message: 'Notificación creada', result });
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al crear la notificación' });
    }
});

// Ruta para obtener todas las notificaciones de un usuario
router.get('/notificaciones', async (req, res) => {
    const { usuario } = req.query;

    if (!usuario) {
        return res.status(400).json({ error: 'Falta el parámetro usuario' });
    }

    try {
        const notifications = await reportesModel.obtenerNotificacionesPorUsuario(usuario);
        res.status(200).json(notifications);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al obtener las notificaciones' });
    }
});

// Ruta para obtener una notificación específica por ID
router.get('/notificaciones/:id', async (req, res) => {
    const { id } = req.params;

    try {
        const notification = await reportesModel.obtenerNotificacionPorId(id);
        if (!notification) {
            return res.status(404).json({ error: 'Notificación no encontrada' });
        }
        res.status(200).json(notification);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al obtener la notificación' });
    }
});

// Ruta para marcar una notificación como leída
router.put('/notificaciones/:id', async (req, res) => {
    const { id } = req.params;

    try {
        const result = await reportesModel.marcarNotificacionComoLeida(id);
        if (!result) {
            return res.status(404).json({ error: 'Notificación no encontrada' });
        }
        res.status(200).json({ message: 'Notificación marcada como leída' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al actualizar la notificación' });
    }
});

// Ruta para eliminar una notificación
router.delete('/notificaciones/:id', async (req, res) => {
    const { id } = req.params;

    try {
        const result = await reportesModel.eliminarNotificacion(id);
        if (!result) {
            return res.status(404).json({ error: 'Notificación no encontrada' });
        }
        res.status(200).json({ message: 'Notificación eliminada' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al eliminar la notificación' });
    }
});

module.exports = router;