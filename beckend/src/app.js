const express = require('express');
const router = require('./router');
const cors = require('cors');
const app = express();

app.use(express.json());
app.set("json spaces", 3);

app.use(cors({
    //origin: ['https://www.3wonline.com', 'http://www.3wonline.com', 'https://www.3wonline.com.br', 'http://www.3wonline.com.br', 'https://3wonline.com', 'http://3wonline.com', 'https://3wonline.com.br', 'http://3wonline.com.br']
    origin: '*'
}));


app.use(router);

module.exports = app;