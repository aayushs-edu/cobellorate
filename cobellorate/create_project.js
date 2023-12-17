const express = require('express');
const mysql = require('mysql2');
const crypto = require('crypto');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.urlencoded({ extended: true }));

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
// get the user ID
app.post('/', function (req, res) {
  const userID = req.session.userID;

  if (!userID) {
    res.send('Log in first');
    return;
  }

  const projectRawID = generateRandomHex();
  const fileRawID = generateRandomHex();
  const projectHashedID = crypto.createHash('sha256').update(projectRawID).digest('hex');

  const name = req.body['project-name'];
  const desc = req.body['project-desc'];
  const user = req.session.user || null;

  const insertProjectSQL = `INSERT INTO projects (projectID, name, owner, description, numFiles) VALUES ('${projectHashedID}', '${name}', '${user}', '${desc}', 0)`;

  connection.query(insertProjectSQL, function (err, result) {
    if (err) {
      console.error('Error executing project query: ' + err.stack);
      res.send('Error executing project query.');
      return;
    }

    res.send('New record added successfully');
  });

  const insertCollaboratorSQL = `INSERT INTO collaborators (projectID, userID) VALUES ('${projectHashedID}', '${userID}')`;

  connection.query(insertCollaboratorSQL, function (err, result) {
    if (err) {
      console.error('Error executing collaborator query: ' + err.stack);
      res.send('Error executing collaborator query.');
      return;
    }

    res.send('New record added successfully');
  });
});

const server = app.listen(3000, function () {
  console.log('Server listening on port 3000');
});

process.on('SIGINT', function () {
  console.log('Closing MySQL connection...');
  connection.end(function (err) {
    console.log('MySQL connection closed.');
    process.exit(err ? 1 : 0);
  });
});
