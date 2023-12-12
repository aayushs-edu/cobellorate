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
    if (req.session && req.session.user){
        res.render('dashboard', {session: req.session });
    }
})

// render create project page
router.get('/new_project', (req, res) => {
    res.render('new_project');
})

// render file upload page
router.get('/file_upload', (req, res) => {
    res.render('file_upload');
})


module.exports = router;