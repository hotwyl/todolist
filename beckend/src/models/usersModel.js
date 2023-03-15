const connection = require('./connection');

const search = async(email) => {
    const [consulta] = await connection.execute('SELECT * FROM users WHERE email = ?;', [email]);
    if (consulta.length === 0) {
        return null;
    } else {
        return consulta;
    }
};

const register = async(name, email, password) => {

    const dateUTC = new Date(Date.now()).toUTCString();

    const query = 'INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, ?)';

    const binds = [name, email, password, dateUTC];

    const [createdUser] = await connection.execute(query, binds);

    return { id: createdUser.insertId, name: name, email: email, created_at: dateUTC };
};



module.exports = {
    search,
    register
};