const express = require('express');
const session = require('express-session');
const cors = require('cors');
const mysql = require('mysql2');
const env = require('dotenv').config().parsed;
const app = express();

const port = 3000;

// ENV VARIABLES
const servername = env.SQL_SERVER;
const username = env.SQL_USERNAME;
const password = env.SQL_PASSWORD;
const dbname = env.SQL_DB;
const secret_key = env.SESSION_SECRET;

app.use(express.static('public'))
app.use(express.json())
app.use(express.urlencoded({ 
    extended: true,
}));

// session middleware
app.use(session({
    secret: secret_key,
    resave: true,
    saveUninitialized: true
}));

app.use(cors());

app.use('/css', express.static(__dirname + 'public/css'));
app.use('/js', express.static(__dirname + 'public/js'));
app.use('/img', express.static(__dirname + 'public/img'));

app.set('views', './views')
app.set('view engine', 'ejs');

//routers
const userRoute = require('./routes/users')
app.use('/users', userRoute);

const sessionRoute = require('./routes/session')
app.use('/session', sessionRoute);

const projectsRoute = require('./routes/projects')
app.use('/projects', projectsRoute);

app.use(cors());

app.get('/', (req, res) => {
    res.render('home');
});

app.listen(port, () => {
    console.info(`Listening on port ${port}`);
});