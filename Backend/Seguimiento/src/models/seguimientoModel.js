const mysql = require ('mysql2/promise');

const connection = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'seguimientoPW'
});

async function traerSeguimientos() {
 const result = await connection.query('SELECT * FROM seguimiento');
 return result[0];
}

async function crearSeguimiento(id_solicitud, id_adoptante, id_animal, fecha_seguimiento, comentarios, estado) {
    const result = await connection.query('INSERT INTO seguimiento VALUES (null,?,?,?,?,?,?)', [id_solicitud, id_adoptante, id_animal, fecha_seguimiento, comentarios, estado]);
    return result;
    
    
}


module.exports = {
    crearSeguimiento, traerSeguimientos
};