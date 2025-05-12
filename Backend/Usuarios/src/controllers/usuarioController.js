const { Router } = require("express");
const router = Router();
const usuarioModel = require("../models/usuarioModel");

// function VerificarRolAdmin (req, res) {
//     const {rol} = req.user;
//     if(rol !=='administrador'){
//         return res.status(403).json({message: 'Acceso denegado: Solo los administradores pueden hacer esto :)'});
//     }

// }

router.post("/register", async (req, res) => {
  const { nombre, cc, email, usuario, password, estado, rol } = req.body;

  try {
    // Validar el rol recibido
    const rolesPermitidos = ["adoptante", "rescatista"];
    if (!rolesPermitidos.includes(rol)) {
      return res
        .status(400)
        .json({
          message: 'Rol no válido. Debe ser "adoptante" o "rescatista"',
        });
    }

    // Verificar si ya existe un usuario con ese nombre o email
    const usuarioExistente = await usuarioModel.traerUsuario(nombre, email);
    if (usuarioExistente) {
      return res
        .status(400)
        .json({ message: "El correo o nombre ya están en uso" });
    }

    // Crear el nuevo usuario
    await usuarioModel.crearUsuario(
      nombre,
      cc,
      email,
      usuario,
      password,
      estado,
      rol
    );

    return res.status(201).json({ message: "Usuario creado exitosamente" });
  } catch (error) {
    console.error(error);
    return res.status(500).json({ message: "Error al registrar el usuario" });
  }
});

// Iniciar sesión 


router.post('/login', async (req, res) => {
  const { email, password } = req.body;

  try {
    // Buscar el usuario por email
    const usuario = await usuarioModel.traerUsuarioEmail(email);
    if (!usuario) {
      return res.status(404).json({ message: 'Usuario no encontrado' });
    }

    if (password !== usuario.password) {
      return res.status(401).json({ message: 'Contraseña incorrecta' });
    }


   
  } catch (error) {
    console.error(error);
    return res.status(500).json({ message: 'Error al iniciar sesión' });
  }
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

// Eliminar un usuario (solo administradores)
router.delete('/admin/users/:id', verificarRolAdmin, async (req, res) => {
  const { id } = req.params;

  try {
    await usuarioModel.eliminarUsuario(id);
    res.json({ message: 'Usuario eliminado exitosamente' });
  } catch (error) {
    console.error('Error al eliminar usuario:', error);
    res.status(500).json({ message: 'Error al eliminar usuario' });
  }
});

// Actualizar usuario (creo recordar que los usuarios pueden modficar su propia información, o solo es de admin)
router.put('/users/:id', async (req, res) => {
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


// Actualizar estado (pero hay que mirar si esto le pertenece a admin)
router.patch('/users/:id/estado', async (req, res) => {
  const { id } = req.params;
  const { estado } = req.body;

  try {
    await usuarioModel.actualizarEstado(id, estado);
    res.json({ message: 'Estado actualizado correctamente' });
  } catch (error) {
    console.error('Error al actualizar estado:', error);
    res.status(500).json({ message: 'Error al actualizar estado del usuario' });
  }
});

module.exports = router;
//Aea
