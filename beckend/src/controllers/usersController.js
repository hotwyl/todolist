const userModel = require('../models/usersModel');
const bcrypt = require('bcrypt');

//register
const register = async(request, response) => {
    try {
        const { name, email, password } = request.body;

        // Verificar se o usuário já está registrado
        const usuarioExistente = await userModel.search(email);

        if (usuarioExistente !== null) {
            return response.status(409).json({ erro: 'Usuário já registrado' });
        }

        // Criptografar a senha
        const senha = await bcrypt.hash(password, 10);

        // Criar um novo usuário
        const newUser = await userModel.register(name, email, senha);

        if (newUser === null) {
            return response.status(404).json({ message: 'Invalid entries. Try again.' });
        }

        return response.status(201).json(newUser);

    } catch (err) {
        return response.status(400).json({
            err: err.message
        });
    }
};

//login
//logout
//show
//update

module.exports = {
    register,
    // login,
    // logout,
    // show,
    // update
};