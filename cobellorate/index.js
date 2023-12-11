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

// session muiddleware
app.use(session({
    secret: secret_key,
    resave: false,
    saveUninitialized: true
}));

app.use(cors());

app.use('/css', express.static(__dirname + 'public/css'));
app.use('/js', express.static(__dirname + 'public/js'));
app.use('/img', express.static(__dirname + 'public/img'));

app.set('views', './views')
app.set('view engine', 'ejs');
// file contains routes that the app needs to use
const userRoute = require('./routes/users')
// for url 
app.use('/users', userRoute);

app.use(cors());

app.get('/', (req, res) => {
    res.render('home');
});

app.get('/signup', (req, res) => {
    res.render('signup')
});

app.listen(port, () => {
    console.info(`Listening on port ${port}`);
});