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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $name = $_POST['username'];
    $raw_pwd = $_POST['pwd'];
    $hashed_pwd = hash('sha256', $raw_pwd); #hash the passwords using sha256 for security
    $insertSQL = "INSERT INTO accounts (username, email, password) VALUES ('$name', '$email', '$hashed_pwd')";

    $scanSQL = "SELECT * FROM accounts WHERE username = '$name'";
    $result = $conn->query($scanSQL);

    if ($result->num_rows > 0) {
        echo 'Username already exists. Please choose a different username.';
    } else {
        if ($conn->query($insertSQL) === TRUE) {
            echo "New record added successfully";
        } else {
            echo "Error: " . $insertSQL . "<br>" . $conn->error;
        }
    }
}
$conn->close();
?>