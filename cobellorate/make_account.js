const express = require('express');
const mysql = require('mysql2');
const crypto = require('crypto');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.json());

const env = require('dotenv').config().parsed;
const servername = env.SQL_SERVER;
const username = env.SQL_USERNAME;
const password = env.SQL_PASSWORD;
const dbname = env.SQL_DB;

const connection = mysql.createConnection({
  host: servername,
  user: username,
  password: password,
});

connection.connect(function (err) {
  if (err) {
    console.error('Error connecting to MySQL database: ' + err.stack);
    return;
  }
  console.log('Connected to MySQL database as id ' + connection.threadId);
});

connection.query('USE ' + dbname);

function generateRandomHex() {
  const length = 32; // 64 char, each char => 4 bit
  const randBytes = crypto.randomBytes(length / 2);
  const hexString = randBytes.toString('hex');
  return hexString;
}

app.post('/makeAccountForm', function (req, res) {
  const email = req.body.email;
  const name = req.body.username;
  const rawPwd = req.body.pwd;
  // hash the pwd
  const hashedPwd = crypto.createHash('sha256').update(rawPwd).digest('hex');
  // sql query
  const scanSQL = `SELECT * FROM accounts WHERE username = '${name}'`;

  connection.query(scanSQL, function (err, result) {
    if (err) {
      console.error('Error executing query: ' + err.stack);
      res.send('Error executing query.');
      return;
    }

    if (result && result.length > 0) {
      res.send('An account with that username already exists.');
      return;
    }

    const userID = generateRandomHex();
    const hashedUserID = crypto.createHash('sha256').update(userID).digest('hex');

    const insertSQL = `INSERT INTO accounts (userID, email, username, password) VALUES ('${hashedUserID}', '${email}', '${name}', '${hashedPwd}')`;

    connection.query(insertSQL, function (err, res) {
      if (err) {
        console.error('Error executing query: ' + err.stack);
        res.send('Error executing query.');
        return;
      }

      res.send('New record added successfully');
    });
  });
});

const server = app.listen(3000, function () {
  console.log('server listening');
});

process.on('SIGINT', function () {
  console.log('closing ');
  connection.end(function (err) {
    console.log('MySQL connection closed.');
    process.exit(err ? 1 : 0);
  });
});
