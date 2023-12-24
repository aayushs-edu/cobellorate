const express = require('express');
const mysql = require('mysql2');
const crypto = require('crypto');
const router = express.Router();
const session = require('express-session')
const env = require('dotenv').config().parsed;



router.get('/:projectID', (req, res) => {
    if(!req.session.authenticated) res.render('error');
    else 
        res.render('project_dashboard', {session: req.session});
})

module.exports = router;