const validateFieldsCad = (req, res, next) => {
    const { body } = req;

    if (body.name === undefined) {
        return res.status(400).json({ message: 'The field "name" is required' });
    }

    if ((body.name).trim() === '') {
        return res.status(400).json({ message: 'The "name" field must not be empty' });
    }

    if (typeof body.name !== 'string') {
        return res.status(400).json({ message: 'The "name" field must be a string' });
    }

    if (body.name.length < 3) {
        return res.status(400).json({ message: 'The "name" field must have at least 3 characters' });
    }

    if (body.name.length > 45) {
        return res.status(400).json({ message: 'The "name" field must have less than 45 characters' });
    }

    if (body.email === undefined) {
        return res.status(400).json({ message: 'The field "email" is required' });
    }

    if ((body.email).trim() === '') {
        return res.status(400).json({ message: 'The "email" field must not be empty' });
    }

    if (typeof body.email !== 'string') {
        return res.status(400).json({ message: 'The "email" field must be a string' });
    }

    if (body.email.length < 5) {
        return res.status(400).json({ message: 'The "email" field must have at least 5 characters' });
    }

    if (body.email.length > 45) {
        return res.status(400).json({ message: 'The "email" field must have less than 45 characters' });
    }

    if (body.password === undefined) {
        return res.status(400).json({ message: 'The field "password" is required' });
    }

    if ((body.password).trim() === '') {
        return res.status(400).json({ message: 'The "password" field must not be empty' });
    }

    if (typeof body.password !== 'string') {
        return res.status(400).json({ message: 'The "password" field must be a string' });
    }

    if (body.password.length < 6) {
        return res.status(400).json({ message: 'The "password" field must have at least 6 characters' });
    }

    if (body.password.length > 12) {
        return res.status(400).json({ message: 'The "password" field must have less than 12 characters' });
    }

    next();
};

module.exports = {
    validateFieldsCad,
};