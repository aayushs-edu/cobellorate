const express = require('express');
const router = express.Router();

router.get('/signup', (req, res) => {
  res.render('signup');
})

router.post('/signup', (req, res) => {
  res.send(`Your username is ${req.body.username} and your password is ${req.body.pwd}`);
})

module.exports = router;