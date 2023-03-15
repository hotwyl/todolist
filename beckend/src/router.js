const express = require('express');

const tasksController = require('./controllers/tasksController');
const usersController = require('./controllers/usersController');
const tasksMiddleware = require('./middlewares/tasksMiddleware');
const usersMiddleware = require('./middlewares/usersMiddleware');
const router = express.Router();


router.post('/register', usersMiddleware.validateFieldsCad, usersController.register);
// router.post('/login', usersController.login);
// router.post('/logout', usersController.logout);
// router.get('/user', usersController.show);
// router.put('/user', usersController.update);

router.get('/tasks', tasksController.getAll);
router.get('/task/:id', tasksController.getOne);
router.post('/task', tasksMiddleware.validateFieldTitle, tasksController.createTask);
router.delete('/task/:id', tasksController.deleteTask);
router.put('/task/:id', tasksController.updateTask);

module.exports = router;