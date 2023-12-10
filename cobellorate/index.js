const express = require('express');
const cors = require('cors');
const app = express();

const port = 3000;

app.use(express.static('public'))
app.use(express.json())
app.use(express.urlencoded({ 
    extended: true,
}))

app.use(cors());

app.use('/css', express.static(__dirname + 'public/css'))
app.use('/js', express.static(__dirname + 'public/js'))
app.use('/img', express.static(__dirname + 'public/img'))

app.set('views', './views')
app.set('view engine', 'ejs');

app.get('/', (req, res) => {
    res.render('home_page');
})

app.get('/make_account_page.ejs', (req, res) => {
    res.render('make_account_page');
})

app.post('/signup', (req, res) => {
    res.send(`Your username is ${req.body.username} and your password is ${req.body.pwd}`);
})

app.listen(port, () => {
    console.info(`Listening on port ${port}`);
})