const tasksModel = require('../models/tasksModel');

const getAll = async(_request, response) => {
    try {
        const tasks = await tasksModel.getAll();

        if (tasks === null) {
            return response.status(404).json({ message: 'Tasks not found' });
        } else if (tasks.length === 0) {
            return response.status(200).json({ message: 'No tasks found' });
        }

        if (tasks.length > 0) {
            return response.status(200).json(tasks);
        }

        return response.status(200).json(tasks);
    } catch (err) {
        return response.status(400).json({
            err: err.message
        });
    }
};

const getOne = async(request, response) => {
    try {
        const { id } = request.params;

        const tasks = await tasksModel.getOne(id);

        if (tasks === null || tasks.length === 0 || tasks.length > 1) {
            return response.status(404).json({ message: 'Tasks not found' });
        }

        return response.status(200).json(tasks);
    } catch (err) {
        return response.status(400).json({
            err: err.message
        });
    }
};

const createTask = async(request, response) => {
    try {
        const createTask = await tasksModel.createTask(request.body);

        if (createTask === null) {
            return response.status(404).json({ message: 'Invalid entries. Try again.' });
        }

        return response.status(201).json(createTask);
    } catch (err) {
        return response.status(400).json({
            err: err.message
        });
    }
};

const updateTask = async(request, response) => {
    try {
        const { id } = request.params;

        const tasks = await tasksModel.getOne(id);

        if (tasks === null || tasks.length === 0 || tasks.length > 1) {
            return response.status(404).json({ message: 'Tasks not found' });
        }

        const taskTitle = tasks[0].title;
        const taskStatus = tasks[0].status;

        const requestTitle = request.body.title;
        const requestStatus = request.body.status;

        const title = (requestTitle === undefined || !requestTitle.length > 0 || !requestTitle) ? taskTitle : requestTitle;
        const status = (requestStatus === undefined || !requestStatus.length > 0 || !requestStatus) ? taskStatus : requestStatus;

        const updatedTask = await tasksModel.updateTask(id, { title, status });

        if (updatedTask === null) {
            return response.status(404).json({ message: 'Tasks not found' });
        }

        return response.status(204).json(updatedTask);
    } catch (err) {
        return response.status(400).json({
            err: err.message
        });
    }
};

const deleteTask = async(request, response) => {
    try {
        const { id } = request.params;

        const tasks = await tasksModel.getOne(id);

        if (tasks === null || tasks.length === 0 || tasks.length > 1) {
            return response.status(404).json({ message: 'Tasks not found' });
        }

        const deletedTask = await tasksModel.deleteTask(id);

        if (deletedTask === null) {
            return response.status(404).json({ message: 'No tasks found' });
        }

        return response.status(204).json(deletedTask);
    } catch (err) {
        return response.status(400).json({
            err: err.message
        });
    }
};

module.exports = {
    getAll,
    getOne,
    createTask,
    updateTask,
    deleteTask,
};