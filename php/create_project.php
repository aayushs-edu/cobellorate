<?php
include 'login.php';

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
    $name = $_POST['project-name'];
    $desc = $_POST['project-desc'];
    $user = $session->get_user();
    $insertSQL = "INSERT INTO projects (name, owner, description, num_files) VALUES ('$name', '$user', '$desc', 0)";

    if ($conn->query($insertSQL) === TRUE) {
        echo "New record added successfully";
    } else {
        echo "Error: " . $insertSQL . "<br>" . $conn->error;
    }
}
$conn->close();
?>