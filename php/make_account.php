<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "dummypassword";
$dbname = "group-project-manager";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->select_db($dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $name = $_POST['username'];
    $raw_pwd = $_POST['pwd'];
    $hashed_pwd = hash('sha256', $raw_pwd); #hash the passwords using sha256 for security
    $insertSQL = "INSERT INTO accounts (username, email, password) VALUES ('$name', '$email', '$hashed_pwd')";

    if ($conn->query($insertSQL) === TRUE) {
        echo "New record added successfully";
    } else {
        echo "Error: " . $insertSQL . "<br>" . $conn->error;
    }
}
$conn->close();
?>