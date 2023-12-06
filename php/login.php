<?php

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
    $name = $_POST['username'];
    $raw_pwd = $_POST['pwd'];
    $hashed_pwd = hash('sha256', $raw_pwd); # this hash here will be the same as the hashed password in the database for the user
    $scanSQL = "SELECT * FROM accounts WHERE username = '$name' AND password = '$hashed_pwd'"; // query here to scan the db for the username and password submitted 
    $result = $conn->query($scanSQL);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();

            echo "Login successful!";
            session_start();
            $_SESSION['user'] = $name;
            $_SESSION['userID'] = $row[0];
            header("Location: dashboard_page.php");
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Error: " . $scanSQL . "<br>" . $conn->error;
    }
    
}
$conn->close();
?>