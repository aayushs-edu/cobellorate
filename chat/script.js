$(document).ready(function () {
    function fetchMessages(senderID, receiverID) {
        $.ajax({
            url: 'get_messages.php',
            type: 'GET',
            data: { senderID: senderID, receiverID: receiverID },
            success: function (data) {
                displayMessages(data);
            }
        });
    }

    function displayMessages(messages) {
        $('#chat-box').empty();
        messages.forEach(function (message) {
            $('#chat-box').append('<p><strong>' + message.senderID + ':</strong> ' + message.message + '</p>');
        });
    }

    function sendMessage(senderID, receiverID, message) {
        $.ajax({
            url: 'chat.php',
            type: 'POST',
            data: { senderID: senderID, receiverID: receiverID, message: message },
            success: function () {
                fetchMessages(senderID, receiverID);
                $('#message-input').val('');
            }
        });
    }
    // replace these with actual IDs
    var senderID = 1; // current userID
    var receiverID = 2; // receiverID

    setInterval(function () {
        fetchMessages(senderID, receiverID); //updates msgs on 2 sec interval
    }, 2000);

    fetchMessages(senderID, receiverID);

    $('#send-button').click(function () {
        var message = $('#message-input').val();
        sendMessage(senderID, receiverID, message);
    });
});
