<?php
session_start();

function generateRandomHex() {
    $length = 32; // 64 char, each char => 4 bit
    $randBytes = random_bytes($length / 2);
    $hexString = bin2hex($randBytes);
    return $hexString;
}

$projectRawID = generateRandomHex();
$fileRawID = generateRandomHex();
$projectHashedID = hash('sha256', $projectRawID);
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
    $name = $_POST['project-name'];
    $desc = $_POST['project-desc'];
    $user = NULL;
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    } else {
        echo "Log in first";
        exit;
    }
    $insertSQL = "INSERT INTO projects (projectID, name, creator, description, numFiles) VALUES ('$projectHashedID', '$name', '$user', '$desc', 0)";

    if ($conn->query($insertSQL) === TRUE) {
        echo "New record added successfully";
    } else {
        echo "Error: " . $insertSQL . "<br>" . $conn->error;
    }
}
$sessionUser = $_SESSION['user'];
// fetch projects from the database
$selectProjectsSQL = "SELECT name AND owner FROM projects WHERE name = '$sessionUser'";
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