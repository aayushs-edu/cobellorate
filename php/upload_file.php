<?php
session_start();

function generateRandomHex() {
    $length = 32; // 64 char, each char => 4 bit
    $randBytes = random_bytes($length / 2);
    $hexString = bin2hex($randBytes);
    return $hexString;
}

$fileRawID = generateRandomHex();
$fileHashedID = hash('sha256', $fileRawID);

$servername = "localhost";
$username = "root";
$password = "dummypassword";
$dbname = "group-project-manager";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}
$conn->select_db($dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file = $_POST['file'];
    $desc = $_POST['project-desc'];
    $insertSQL = ""
}
$conn->close();
?>