<?php

$env = parse_ini_file('.env');
$servername = $env["SQL_SERVER"];
$username = $env["SQL_USERNAME"];
$password = $env["SQL_PASSWORD"];
$dbname = $env["SQL_DB"];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->select_db($dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senderID = $_POST['sender_id'];
    $receiverID = $_POST['receiver_id'];
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')";
    $conn->query($sql);

    $response = [
        'status' => 'success',
        'message' => 'Message sent succesfully',
        'new_message' => [
            'senderID' => $sender_id,
            'receiverID' => $receiverID,
            'message' => $message
        ]
    ];
}

$conn->close();
?>