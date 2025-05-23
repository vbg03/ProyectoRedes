const { Router } = require("express");
const router = Router();
const usuarioModel = require("../models/usuarioModel");
const axios = require('axios');


function verificarRolAdmin(req, res, next) {
  if (!req.user) {
    return res.status(403).json({ message: 'Aid_usuarioeso denegado: Usuario no autenticado' });
  }

  const { rol } = req.user;

  if (rol !== 'administrador') {
    return res.status(403).json({ message: 'Aid_usuarioeso denegado: Solo los administradores pueden hacer esto :)' });
  }

  next();
}

router.post("/register", async (req, res) => {
  const { nombre, id_usuario, email, usuario, password, rol } = req.body;
  const estado = 'inactivo';

  try {
    // Log para verificar los datos que recibimos
    console.log('Datos recibidos para registro:', req.body);

    // Validar el rol recibido
    const rolesPermitidos = ["adoptante", "rescatista"];
    console.log('Rol recibido:', rol);  // Log para verificar el valor del rol
    if (!rolesPermitidos.includes(rol)) {
      return res
        .status(400)
        .json({
          message: 'Rol no válido. Debe ser "adoptante" o "rescatista"',
        });
    }

    // Verificar si ya existe un usuario con ese nombre o email
    console.log('Verificando si el usuario o email ya existen...');
    const usuarioExistente = await usuarioModel.traerUsuarioEmail(usuario || email);
    if (usuarioExistente !== undefined && usuarioExistente !== null) {
      return res.status(400).json({ message: "El correo o nombre ya están en uso" });
    }

    // Crear el nuevo usuario
    console.log('Creando el nuevo usuario...');
    await usuarioModel.crearUsuario(
      nombre,
      id_usuario,
      email,
      usuario,
      password,
      estado,
      rol
    );

    return res.status(201).json({ message: "Usuario creado exitosamente" });
  } catch (error) {
    console.error('Error al registrar usuario:', error);  // Log detallado del error
    console.log("Stack del error:", error.stack);
    return res.status(500).json({ message: "Error al registrar el usuario" });
  }
});


// Iniciar sesión 


router.post('/login', async (req, res) => {
  const { usuario, email, password } = req.body;

  // Usamos cualquiera de los dos (usuario o email)
  const identificador = usuario || email;

  try {
    const usuarioBuscado = await usuarioModel.traerUsuarioEmail(identificador);

    if (!usuarioBuscado) {
      return res.status(404).json({ message: 'Usuario no encontrado' });
    }

    if (usuarioBuscado.estado !== 'activo') {
      return res.status(403).json({
        message: 'Tu cuenta aún no ha sido activada por un administrador'
      });
    }

    if (password !== usuarioBuscado.password) {
      return res.status(401).json({ message: 'Contraseña incorrecta' });
    }

    res.json({ message: 'Inicio de sesión exitoso', usuario: usuarioBuscado });

  } catch (error) {
    console.error('Error al iniciar sesión:', error);
    return res.status(500).json({ message: 'Error al iniciar sesión' });
  }
});


router.use((req, res, next) => {
  req.user = { rol: 'administrador' };
  next();
});


// Obtener todos los usuarios (solo administradores)
router.get('/admin/users', verificarRolAdmin, async (req, res) => {
  try {
    const usuarios = await usuarioModel.traerUsuarios();
    res.json(usuarios);
  } catch (error) {
    console.error('Error al obtener usuarios:', error);
    res.status(500).json({ message: 'Error al obtener usuarios' });
  }
});

// Obtener un usuario (solo administradores)
router.get('/admin/users/:id', verificarRolAdmin, async (req, res) => {
  const { id } = req.params;

  try {
    const usuario = await usuarioModel.traerUsuario(id);
    res.json(usuario);
  } catch (error) {
    console.error('Error al obtener usuarios:', error);
    res.status(500).json({ message: 'Error al obtener usuario' });
  }
});

// Eliminar un usuario (solo administradores)
router.delete('/admin/users/:id', verificarRolAdmin, async (req, res) => {
  const { id } = req.params;

  try {
    await usuarioModel.borrarUsuario(id);
    res.json({ message: 'Usuario eliminado exitosamente' });
  } catch (error) {
    console.error('Error al eliminar usuario:', error);
    res.status(500).json({ message: 'Error al eliminar usuario' });
  }
});

// Actualizar usuario 
router.put('/admin/users/:id', async (req, res) => {
  const { id } = req.params;
  const { nombre, email, usuario, password } = req.body;

  try {
    await usuarioModel.actualizarUsuario(id, nombre, email, usuario, password);
    res.json({ message: 'Usuario actualizado correctamente' });
  } catch (error) {
    console.error('Error al actualizar usuario:', error);
    res.status(500).json({ message: 'Error al actualizar usuario' });
  }
});


// Actualizar estado 
router.patch('/admin/users/:id/estado', async (req, res) => {
  const { id } = req.params;
  const { estado } = req.body;

  try {
    await usuarioModel.actualizarEstado(id, estado);

    // Notificar al usuario sobre el cambio de estado
    await axios.post('http://192.168.100.2:3003/notificaciones', {
      id_usuario: id,
      mensaje: `Tu cuenta ha sido ${estado}`,
      estado
    });

    res.json({ message: 'Estado actualizado correctamente y notificación enviada' });
  } catch (error) {
    console.error('Error al actualizar estado:', error);
    res.status(500).json({ message: 'Error al actualizar estado del usuario' });
  }
});


router.get('/usuarios/:id', async (req, res) => {
  const { id } = req.params;

  try {
    const usuario = await usuarioModel.traerUsuario(id);

    if (!usuario) {
      return res.status(404).json({ message: "Usuario no encontrado" });
    }

    // Solo devuelve lo que necesitas (seguridad)
    const { id_usuario, nombre, rol, estado } = usuario;

    res.json({ id_usuario, nombre, rol, estado }); // ✅ Asegúrate que rol y estado sí están aquí
  } catch (error) {
    console.error('Error al consultar usuario:', error);
    res.status(500).json({ message: 'Error al consultar usuario' });
  }
});



  module.exports = router;
//Crear, ActualizarU, EliminarU, ConsultarTU, ConsultarU, ValidarCredencialesAUT = Front, Login, ActualizarE
