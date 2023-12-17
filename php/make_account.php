<?php
session_start();

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

function generateRandomHex() {
    $length = 32; // 64 char, each char => 4 bit
    $randBytes = random_bytes($length / 2);
    $hexString = bin2hex($randBytes);
    return $hexString;
}

$userID = generateRandomHex();
$hashedUserID = hash('sha256', $userID);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $name = $_POST['username'];
    $raw_pwd = $_POST['pwd'];
    $hashed_pwd = hash('sha256', $raw_pwd); #hash the passwords using sha256 for security
    
    //Check if account with that username already exists
    $scanSQL = "SELECT * FROM accounts WHERE username = '$name'"; // query here to scan the db for the username and password submitted 
    $result = $conn->query($scanSQL);
    
    if ($result) {
        if ($result->num_rows > 0) {
            echo "An account with that username already exists.";
            die();
        }
    } else {
        echo "Error: " . $scanSQL . "<br>" . $conn->error;
    }
    
    $insertSQL = "INSERT INTO accounts (userID, email, username, password) VALUES ('$hashedUserID', '$email', '$name', '$hashed_pwd')";

    if ($conn->query($insertSQL) === TRUE) {
        echo "New record added successfully";
    } else {
        echo "Error: " . $insertSQL . "<br>" . $conn->error;
    }
}
$conn->close();
?>