const express = require('express');

const tasksController = require('./controllers/tasksController');
const tasksMiddleware = require('./middlewares/tasksMiddleware');
const router = express.Router();

router.get('/tasks', tasksController.getAll);
router.get('/task/:id', tasksController.getOne);
router.post('/task', tasksMiddleware.validateFieldTitle, tasksController.createTask);
router.delete('/task/:id', tasksController.deleteTask);
router.put('/task/:id', tasksController.updateTask);

module.exports = router;