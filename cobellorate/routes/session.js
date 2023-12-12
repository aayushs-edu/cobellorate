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


module.exports = router;