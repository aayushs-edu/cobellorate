const express = require('express');
const crypto = require('crypto');
const router = express.Router();
const session = require('express-session')
const env = require('dotenv').config().parsed;

const mysql = require('mysql2');
const db = require('../sql');

router.get('/dashboard', (req, res) => {
    console.log(req.session.user)
    // check if user session is set
    if (!req.session.authenticated) res.send('Log in first');
    else {
        // get current session user as owner
        const owner = req.session.user;
        // sql query                
        const scanSQL = `SELECT * FROM projects where owner = '${owner}';`;
        db.query(scanSQL, function (err, result) {
            if (err) {
                console.error('error executing query: ' + err.message);
                console.log('error executing query');
                res.render('new_project');
                return;
            }
            if(result) {
                res.render('dashboard', {session: req.session, result});
            }
        });
    }
})

// render create project page
router.get('/new_project', (req, res) => {
    if (req.session.authenticated) {
        res.render('newProject');
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
    // get current session user as owner
    const owner = req.session.user;
    // sql query                
    const insertSQL = `INSERT INTO projects VALUES ('${hashedProjectID}', '${project_name}', '${project_desc}', '${owner}', 0);`;
    db.query(insertSQL, function (err, result) {
        if (err) {
            console.error('error executing query: ' + err.message);
            console.log('error executing query');
            res.render('new_project');
            return;
        }
        else {
            console.log('new project succesfully added');
            res.redirect(303, './dashboard');
        }
    });
})
module.exports = router;