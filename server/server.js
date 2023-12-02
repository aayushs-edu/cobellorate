const express = require('express');
const http = require('http');
const socketIO = require('socket.io');
const app = express();
const server = http.createServer(app);
const io = socketIO(server);

app.use(express.static('public'));

app.get('/', (req, res) => {
    res.sendFile(__dirname + '/chat.html');
});

const users = {};

io.on('connection', (socket) => {
    console.log('A user has connected!');

    const userID = socket.id;
    users[userID] = true;

    socket.on('disconnect', () => {
        console.log('The user has disconnected :( ');
        delete users[userID];
    });
    socket.on('chat message', (msg) => {
        io.emit('chat message', msg);
    });
});

server.listen(3000, () => {
    console.log('Server is running on http://0.0.0.0:3000');
})