const express = require('express');
const session = require('express-session');
const cookieParser = require('cookie-parser');
const crypto = require('crypto');
const cors = require('cors');
const env = require('dotenv').config().parsed;
const app = express();

const port = 3000;

//sql connection
const mysql = require('mysql2');
const db = require('./sql');

app.use(express.static('public'))
app.use(express.json())
app.use(express.urlencoded({ 
    extended: true,
}));

// session middleware
const secret_key = env.SESSION_SECRET;
var sessionMiddleware = session({
    secret: secret_key,
    cookie: { secure: false, maxAge: 1000 * 60 * 60 * 24 },
    resave: false,
    saveUninitialized: false
})

app.use(sessionMiddleware);
app.use(cookieParser());
app.use(cors());

app.use('/css', express.static(__dirname + 'public/css'));
app.use('/js', express.static(__dirname + 'public/js'));
app.use('/img', express.static(__dirname + 'public/img'));

app.set('views', './views')
app.set('view engine', 'ejs');

//routers
const userRoute = require('./routes/users')
app.use('/users', userRoute);

const sessionRoute = require('./routes/session')
app.use('/session', sessionMiddleware, sessionRoute);

const projectsRoute = require('./routes/projects')
app.use('/projects', projectsRoute);

app.use(cors());

app.get('/', (req, res) => {
    res.render('home');
});

app.listen(port, () => {
    console.info(`Listening on port ${port}`);
});

app.get('/signup', (req, res) => {
    res.render('signup');
})
  
app.get('/login', (req, res) => {
    res.render('login', {error: ''});
})
  
  
app.post('/signup', (req, res) => {
    // from the forms
    const email = req.body.email;
    const name = req.body.username;
    const rawPwd = req.body.pwd;
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
    db.query(scanSQL, function (err, res) {
        if (err) {
            console.error('error executing query: ' + err.stack);
            res.send('error executing query');
            return;
        }
        if (res && res.length > 0) {
            res.send('an account with that username already exists');
            return;
        }
    });
    const userID = generateRandomHex();
    const hashedUserID = crypto.createHash('sha256').update(userID).digest('hex');
  
    //query for creating the new account in the db
    const insertSQL = `INSERT INTO accounts (userID, email, username, password) VALUES ('${hashedUserID}', '${email}', '${name}', '${hashedPwd}')`;
    db.query(insertSQL, function (err, res) {
      if (err) {
          console.error('error executing query: ' + err.stack);
          res.send('error executing query');
          return;
      }
      console.log('new account added sucesfully');
    });
})
  // login handling
app.post('/login', (req, res) => {
    // from the forms
    const name = req.body.username;
    const rawPwd = req.body.pwd;

    // hash the pwd
    const hashedPwd = crypto.createHash('sha256').update(rawPwd).digest('hex');
    // sql query
    const scanSQL = `SELECT * FROM accounts WHERE username = '${name}' and password = '${hashedPwd}';`;
    db.query(scanSQL, function (err, result) {
        if (err) {
            console.error('error executing query: ' + err.stack);
            console.log('error executing query');
            res.render('login', {error: 'SQL Error: ' + err.message});
            return;
        }
        if(result.length > 0) {
            if (req.session.authenticated) 
                res.redirect('./session/dashboard');
            else {
                req.session.authenticated = true;
                req.session.user = result[0].username;
                req.session.email = result[0].email;
                req.session.userID = result[0].userID;
                // update session user
                console.log(req.session);
                // redirect user to session route
                res.redirect('./session/dashboard'); // add userID as paramater to the http query so that we can access it in session
            }    
        }
        else {
            res.render('login', {error: 'Account does not exist'});
        }
    });
});