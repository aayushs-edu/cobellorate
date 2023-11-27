<?php

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
    $name = $_POST['username'];
    $pwd = $_POST['pwd'];
    $insertSQL = "INSERT INTO accounts (username, password) VALUES ('$name', '$pwd')";

    if ($conn->query($insertSQL) === TRUE) {
        echo "New record added successfully";
    } else {
        echo "Error: " . $insertSQL . "<br>" . $conn->error;
    }
}
$conn->close();
?>