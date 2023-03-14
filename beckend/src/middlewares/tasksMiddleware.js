const validateFieldTitle = (req, res, next) => {
    const { body } = req;
    if (body.title === undefined) {
        return res.status(400).json({ message: 'The field "title" is required' });
    }

    if ((body.title).trim() === '') {
        return res.status(400).json({ message: 'The "title" field must not be empty' });
    }

    if (typeof body.title !== 'string') {
        return res.status(400).json({ message: 'The "title" field must be a string' });
    }

    if (body.title.length < 3) {
        return res.status(400).json({ message: 'The "title" field must have at least 3 characters' });
    }

    if (body.title.length > 45) {
        return res.status(400).json({ message: 'The "title" field must have less than 45 characters' });
    }

    next();
};

const validateFieldStatus = (req, res, next) => {
    const { body } = req;
    if (body.status === undefined) {
        return res.status(400).json({ message: 'The field "status" is required' });
    }

    if ((body.status).trim() === '') {
        return res.status(400).json({ message: 'The "status" field must not be empty' });
    }

    if (typeof body.status !== 'string') {
        return res.status(400).json({ message: 'The "status" field must be a string' });
    }

    if (body.status.length < 3) {
        return res.status(400).json({ message: 'The "status" field must have at least 3 characters' });
    }

    if (body.status.length > 45) {
        return res.status(400).json({ message: 'The "status" field must have less than 45 characters' });
    }

    next();
};

module.exports = {
    validateFieldTitle,
    validateFieldStatus,
};