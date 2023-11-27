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
    $scanSQL = "SELECT * FROM accounts WHERE username = '$name' AND password = '$pwd'"; // query here to scan the db for the username and password submitted 
    $result = $conn->query($scanSQL);

    if ($result) {
        if ($result->num_rows > 0) {
            echo "Login successful!";
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Error: " . $scanSQL . "<br>" . $conn->error;
    }
}
$conn->close();
?>