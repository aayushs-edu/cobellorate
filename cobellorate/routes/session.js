const express = require('express');
const mysql = require('mysql2');
const crypto = require('crypto');
const router = express.Router();
const session = require('express-session')
const env = require('dotenv').config().parsed;
const servername = env.SQL_SERVER;
const username = env.SQL_USERNAME;
const password = env.SQL_PASSWORD;
const dbname = env.SQL_DB;
const sessionSecret = env.SESSION_SECRET
// session middleware for the router
router.use(session({
    secret: sessionSecret,
    resave: true,
    saveUninitialized: true
}));

router.get('/dashboard', (req, res) => {
    // check if user session is set
    if (req.session && req.session.user) {
        userId = req.query.userId
        req.session.userId = userId;
        userName = req.session.user; 
        const connection = mysql.createConnection({
            host: servername,
            user: username,
            password: password,
            database: dbname
        });
        connection.connect(function (err) {
            if (err) {
                console.log('error connection to sql database: ' + err.stack)
            }
            console.log('connected to database as id ' + connection.threadId);
        });
        // get current session user as owner
        const owner = req.session.user;
        // sql query                
        const scanSQL = `SELECT * FROM projects where owner = '${req.session.username}';`;
        connection.query(scanSQL, function (err, result) {
            if (err) {
                console.error('error executing query: ' + err.message);
                console.log('error executing query');
                res.render('new_project');
                return;
            }
            if(result) {
                console.log(result);
                res.render('dashboard', {session: req.session, userId, result});
            }
        });
    }
})

// render create project page
router.get('/new_project', (req, res) => {
    // check if user session is set
    console.log(req.session.user);
    if (req.session && req.session.user) {
        const userId = req.session.userId //access users id
        console.log(userId)
        res.render('new_project', {session: req.session, userId});
    }
})

// render file upload page
router.get('/file_upload', (req, res) => {
    res.render('file_upload');
})

// handle project creation
router.post('/new_project', (req, res) => {
    const project_name = req.body.name;
    const project_desc = req.body.desc;

    function generateRandomHex() {
        const length = 32;
        const randBytes = crypto.randomBytes(length / 2);
        const hexString = randBytes.toString('hex');
        return hexString;
    }
    // hash project id
    const rawProjectID = generateRandomHex();
    const hashedProjectID = crypto.createHash('sha256').update(rawProjectID).digest('hex');
    
    const connection = mysql.createConnection({
        host: servername,
        user: username,
        password: password,
        database: dbname
    });
    connection.connect(function (err) {
        if (err) {
            console.log('error connection to sql database: ' + err.stack)
        }
        console.log('connected to database as id ' + connection.threadId);
    });
    // get current session user as owner
    const owner = req.session.user;
    // sql query                
    const insertSQL = `INSERT INTO projects VALUES ('${hashedProjectID}', '${project_name}', '${project_desc}', '${owner}', 0);`;
    connection.query(insertSQL, function (err, result) {
        if (err) {
            console.error('error executing query: ' + err.message);
            console.log('error executing query');
            res.render('new_project');
            return;
        }
        if(result) {
            console.log('new project succesfully added');
            res.render('dashboard', {session: req.session });
        }
    });
})
module.exports = router;