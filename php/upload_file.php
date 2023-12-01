<?php
session_start();

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
    $getProjectIDSQL = "SELECT from ";
}
$conn->close();
?>