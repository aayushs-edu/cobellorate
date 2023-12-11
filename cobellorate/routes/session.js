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

router.get('/dashboard', (req, res) => {
    res.render('dashboard');
})

// render file upload page
router.get('/file_upload', (req, res) => {
    res.render('file_upload');
})

// handling for file upload
router.post('/upload', (req, res) => {
    const file = req.body.file;
    const connection = mysql.createConnection({
        host: servername,
        user: username,
        password: password,
        database: dbname
    });
    connection.connect(function (err) {
        if (err) {
            console.log('error connection to sql database: ' + err.stack);
            return;
        }
        console.log('connected to the database as id ' + connection.threadId);
    })

    // for hashing file id
    function generateRandomHex() {
        const length = 32;
        const randBytes = crypto.randomBytes(length / 2);
        const hexString = randBytes.toString('hex');
        return hexString;
    }
    const rawFileID = generateRandomHex();
    const hashedFileID = crypto.createHash('sha256').update(rawFileID).digest('hex');
    // sql query
    const insertSQL = 
}); 

module.exports = router;