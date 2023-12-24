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
//const upload = multer({ dest: ':/projectID', storage: storage});
//const upload = multer({ storage: multer.memoryStorage() })

router.get('/:projectID', (req, res) => {
    if(!req.session.authenticated) res.render('error');
    else 
        res.render('project_dashboard', {session: req.session});
})
router.post('/:projectID', (req, res) => {
    let fileContent = req.files.file_upload
    console.log(fileContent.data)
    const rawID = generateRandomHex();
    const fileID = crypto.createHash('sha256').update(rawID).digest('hex');
    const projectID = req.params.projectID;
    const fileName = fileContent.name;
    const insertSQL = `INSERT INTO files VALUES (?, ?, ?, ?);`;
    db.query(insertSQL, [fileID, fileName, fileContent.data.toString(), projectID], function (err, result) {
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