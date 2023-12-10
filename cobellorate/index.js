const express = require('express');
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

app.use(express.static('public'))
app.use(express.json())
app.use(express.urlencoded({ 
    extended: true,
}));

app.use(cors());

app.use('/css', express.static(__dirname + 'public/css'));
app.use('/js', express.static(__dirname + 'public/js'));
app.use('/img', express.static(__dirname + 'public/img'));

app.set('views', './views')
app.set('view engine', 'ejs');

app.get('/', (req, res) => {
    res.render('home_page');
});

app.get('/make_account_page.ejs', (req, res) => {
    res.render('make_account_page');
});
// sign up handling
app.post('/signup', (req, res) => {

    // from the forms
    const email = req.body.email;
    const name = req.body.username;
    const rawPwd = req.body.pwd;

    const connection = mysql.createConnection({
        host: servername,
        username: username,
        password: password
    });
    connection.connect(function (err) {
        if (err) {
            console.log('error connecting to sql database: ' + err.stack);
            return;
        }
        console.log('connected to database as id ' + connection.threadId); 
    });
    connection.query('USE ' + dbname);

    // for hashing the pwd
    function generateRandomHex() {
        const length = 32;
        const randBytes = crypto.randomBytes(length / 2);
        const hexString = randBytes.toString('hex');
        return hexString;
    }
    // hash the pwd
    const hashedPwd = crypto.createHash('sha256').update(rawPwd).digest('hex');
    // sql query
    const scanSQL = `SELECT * FROM accounts WHERE username = '${name}'`;
    connection.query(scanSQL, function (err, result) {
        if (err) {
            console.error('error executing query: ' + err.stack);
            res.send('error executing query');
            return;
        }
        res.send('new record added succesfully');
    });
});

app.listen(port, () => {
    console.info(`Listening on port ${port}`);
});