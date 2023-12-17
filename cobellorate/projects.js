const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs');
const path = require('path');
const dotenv = require('dotenv');
const mysql = require('mysql2');

const app = express();
const port = 3000;

dotenv.config();
// middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.set('view engine', 'ejs');
//sql
const { SQL_SERVER, SQL_USERNAME, SQL_PASSWORD, SQL_DB } = process.env;
const connection = mysql.createConnection({
    host: SQL_SERVER,
    user: SQL_USERNAME, 
    password: SQL_PASSWORD,
});

connection.connect((err) => {
    if (err) {
        console.error('Connection error: ', err);
    }
    connection.query('USE ${SQL_DB}', (useDbErr) => {
        if (useDBErr) {
            console.error('database selection error: ', useDbErr);
            process.exit(1);
        }
        console.log('connected to the DB');
    });
});

//route handling
app.get('/', (req, res) => {
    const currentProjectID = req.query.id;
    const sessionUser = req.session.user;
    //query
    const selectFilesSQL = "SELECT fileID, \'fileName\', fileContent FROM files WHERE projectID = '${currentProjectID}/'";

    connection.query(selectFilesSQL, (queryErr, result) => {
        if (queryErr) {
            console.error('query error: ', queryErr);
            res.status(500).send('internal server error');
            return;
        }
        res.render('dashboard', { currentProjectID, sessionUser, files: result });
    });
});
app.post('/upload_file', (req, res) => {
    //handle file upload
    //redirect back to dash
    res.redirect('/?id=${req.body.id}');
});
app.listen(port, () => {
    console.log('server is running on http://localhost:${port}');
});