// entry point for node
const express = require('express');
const path = require('path');

const app = express();
const port = process.env.PORT || 3000; // Set the port
const DIR = __dirname

app.use(express.static(DIR));
app.get('/', (req, res) => {
    res.sendFile(path.join(DIR, 'home_page.html'));
});

app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});
