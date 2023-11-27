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
    $scanSQL = ""; // query here to scan the db for the username and password submitted 

    if ($conn->query($scanSQL) === TRUE) {
        echo ""; // logic here executed based on the username and password found
    } else {
        echo "Error: " . $scanSQL . "<br>" . $conn->error;
    }
}
$conn->close();
?>