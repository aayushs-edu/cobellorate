<?php
session_start();

$env = parse_ini_file('.env');

$servername = "localhost";
$username = $env["SQL_USER"];
$password = $env["SQL_PASS"];
$dbname = "group-project-manager";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}
$conn->select_db($dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['project-name'];
    $desc = $_POST['project-desc'];
    $user = NULL;
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    } else {
        echo "Log in first";
        exit;
    }
    $insertSQL = "INSERT INTO projects (name, creator, description, numFiles) VALUES ('$name', '$user', '$desc', 0)";

    if ($conn->query($insertSQL) === TRUE) {
        echo "New record added successfully";
    } else {
        echo "Error: " . $insertSQL . "<br>" . $conn->error;
    }
}
$conn->close();
?>