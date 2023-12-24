const express = require('express');
const mysql = require('mysql2');
const crypto = require('crypto');
const router = express.Router();
const session = require('express-session')
const env = require('dotenv').config().parsed;
const multer = require('multer');
const path = require('path');
const db = require('../sql');
const fileUpload = require('express-fileupload');
//const fileUpload = require('express-fileupload');
function generateRandomHex() {
    const length = 32;
    const randBytes = crypto.randomBytes(length / 2);
    const hexString = randBytes.toString('hex');
    return hexString;
}

// multer config


router.get('/:projectID', (req, res) => {
    // if(!req.session.authenticated) res.render('error');
    // else
    const scanSQL = `SELECT * from files WHERE projectID = '${req.params.projectID}'`
    db.query(scanSQL, function (err, result) {
        if (err) {
            console.error('error executing query: ' + err.message);
            console.log('error executing query');
            return;
        }
        if (result) {
            console.log(Buffer.from(result[0].fileContent).toString("base64"));
            
            res.render('project_dashboard', {session: req.session, result});
        }
    });
})
router.post('/:projectID', (req, res) => {
    let file = req.files.file_upload;
    const rawID = generateRandomHex();
    const fileID = crypto.createHash('sha256').update(rawID).digest('hex');
    const projectID = req.params.projectID;
    const fileName = file.name;
    const insertSQL = `INSERT INTO files VALUES (?, ?, ?, ?);`;
    db.query(insertSQL, [fileID, fileName, file.data, projectID], function (err, result) {
        if (err) {
            console.error('error executing query: ' + err.message);
            console.log('error executing query');
            res.render('project_dashboard');
            return;
        }
        if (result) {
            console.log('file upload succesful')
        }
    });

})
module.exports = router;