require('dotenv').config()  // for salting the cookie ID
const express = require('express');
const mysql = require('mysql2');
const session = require('express-session');
const { hash } = require('crypto');

// salt and hash the ID
const originalID = process.env.sessionIDCookie;
const salt = crypto.randomBytes(16).toString('hex');
const saltedID = originalID + salt;
const hashedID = crypto.createHash('sha256').update(saltedID).digest('hex');

const app = express();
const port = 3000;
// handling session - JSON
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(session({ secret: hashedID, resave: true, saveUninitialized: true }));

// connect to SQL
const db = mysql.createConnection({
    host: process.env.SQL_SERVER,
    user: process.env.SQL_USERNAME,
    password: process.env.SQL_PASSWORD,
    database: process.env.SQL_DB
});

db.connect((err) => {
    if (err) {
        console.error("error connecting to SQL: ", err.message);
    } else {
        console.log('connected to SQL');
    }
});

// handle login form
app.post('/login', (req, res) => {
    const { username, pwd } = req.body;
    const hashedPwd = hash('sha256').update(pwd).digest('hex');

    // sql query
    const scanSQL = 'SELECT * FROM accounts WHERE username = ? AND password = ?';
    db.query(scanSQL, [username, hashedPwd], (err, results) => {
        if (err) {
            console.error('error with sql query: ', err.message);
            return res.status(500).send('internal server error');
        }

        if (results.length > 0) {
            const user = results[0];
            req.session.user = username;
            req.session.userID = user.id;
            res.redirect('/dashboard.js');
        } else {
            // invalid username
        }
    });
});

app.listen(port, () => {
    console.log('server is running');
});