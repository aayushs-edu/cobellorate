const express = require('express');
const mysql = require('mysql2');
const crypto = require('crypto');
const router = express.Router();

const env = require('dotenv').config().parsed;
const servername = env.SQL_SERVER;
const username = env.SQL_USERNAME;
const password = env.SQL_PASSWORD;
const dbname = env.SQL_DB;
// render signup.ejs page
router.get('/signup', (req, res) => {
  res.render('signup');
})
// render login.ejs page
router.get('/login', (req, res) => {
  res.render('login');
})
// signup handling
router.post('/signup', (req, res) => {
  // from the forms
  const email = req.body.email;
  const name = req.body.username;
  const rawPwd = req.body.pwd;

  const connection = mysql.createConnection({
      host: servername,
      user: username,
      password: password,
      database: dbname
  });
  connection.connect(function (err) {
      if (err) {
          console.log('error connecting to sql database: ' + err.stack);
          return;
      }
      console.log('connected to database as id ' + connection.threadId); 
  });

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
      if (result && result.length > 0) {
        res.send('an account with that username already exists');
        return;
      }
  });
  const userID = generateRandomHex();
  const hashedUserID = crypto.createHash('sha256').update(userID).digest('hex');

  //query for creating the new account in the db
  const insertSQL = `INSERT INTO accounts (userID, email, username, password) VALUES ('${hashedUserID}', '${email}', '${name}', '${hashedPwd}')`;
  connection.query(insertSQL, function (err, res) {
    if (err) {
        console.error('error executing query: ' + err.stack);
        res.send('error executing query');
        return;
    }
    res.send('new record added succesfully');
  });
})
// login handling
router.post('/login', (req, res) => {
  
});

module.exports = router;