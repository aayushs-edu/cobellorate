const mysql = require('mysql2');
const env = require('dotenv').config().parsed;
const servername = env.SQL_SERVER;
const username = env.SQL_USERNAME;
const password = env.SQL_PASSWORD;
const dbname = env.SQL_DB;

var db;

function connectDB() {
    if (!db) {
        db = mysql.createConnection({
            host: servername,
            user: username,
            password: password,
            database: dbname
        });
        db.connect(function (err) {
            if (err) {
                console.log('error connection to sql database: ' + err.stack)
            }
        });
    }
    return db;
}

module.exports = connectDB();