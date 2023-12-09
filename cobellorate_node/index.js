// entry point for node
const express = require('express');
const path = require('path');

const app = express();
const port = process.env.PORT || 3000; // Set the port

app.use(express.static(path.join(__dirname, 'public')));
app.get('/', (req, res) => {
    res.send('Hello, World!'); 
});

app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});
