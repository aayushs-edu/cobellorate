<?php
session_start();

$currentProjectID = $_POST['id'];

function generateRandomHex() {
    $length = 32; // 64 char, each char => 4 bit
    $randBytes = random_bytes($length / 2);
    $hexString = bin2hex($randBytes);
    return $hexString;
}

$rawFileID = generateRandomHex();
$hashedFileID = hash('sha256', $rawFileID);

$env = parse_ini_file('.env');
$servername = $env["SQL_SERVER"];
$username = $env["SQL_USERNAME"];
$password = $env["SQL_PASSWORD"];
$dbname = "group-project-manager";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->select_db($dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = file_get_contents($_FILES['fileInput']['tmp_name']);
    $insertSQL = "INSERT into files (fileID, fileContent, projectID) VALUES ('TEST', 'test', 'test')";

    if ($conn->query($insertSQL) === TRUE) {
        echo "New record added successfully";
    } else {
        echo "Error: " . $insertSQL . "<br>" . $conn->error;
    }
}
$conn->close();

?>