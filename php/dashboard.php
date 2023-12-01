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

$sessionUser = $_SESSION['user'];
// fetch projects from the database
$selectProjectsSQL = "SELECT name, creator FROM projects WHERE name = '$sessionUser'";
$result = $conn->query($selectProjectsSQL);
$projects = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row['name'];
    }
}
// output the projects array as JSON
echo json_encode($projects);
$conn->close();
?>