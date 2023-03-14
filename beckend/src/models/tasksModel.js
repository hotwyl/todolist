const connection = require('./connection');

const getAll = async() => {
    const [tasks] = await connection.execute('SELECT * FROM tasks;');
    return tasks;
};

const getOne = async(id) => {
    const [consulta] = await connection.execute('SELECT * FROM tasks WHERE id = ?', [id]);

    if (consulta.length === 0) {
        return null;
    } else {
        return consulta;
    }
};

const createTask = async(task) => {
    const { title } = task;

    const dateUTC = new Date(Date.now()).toUTCString();

    const query = 'INSERT INTO tasks (title, status, created_at) VALUES (?, ?, ?)';

    const binds = [title, 'pendente', dateUTC];

    const [createdTask] = await connection.execute(query, binds);

    return { id: createdTask.insertId, title, status: 'pendente', created_at: dateUTC };
};

const updateTask = async(id, task) => {

    const [consulta] = await connection.execute('SELECT * FROM tasks WHERE id = ?', [id]);

    if (consulta.length === 0) {
        return null;
    }

    const { title, status } = task;

    const query = 'UPDATE tasks SET title = ?, status = ? WHERE id = ?';

    const binds = [title, status, id];

    const [updateTask] = await connection.execute(query, binds);

    return { id, title, status };
};

const deleteTask = async(id) => {
    const [consulta] = await connection.execute('SELECT * FROM tasks WHERE id = ?', [id]);

    if (consulta.length === 0) {
        return null;
    }

    const [removedTask] = await connection.execute('DELETE FROM tasks WHERE id = ?', [id]);
    return removedTask;
};

module.exports = {
    getAll,
    getOne,
    createTask,
    updateTask,
    deleteTask,
};