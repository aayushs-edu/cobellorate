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
    $raw_pwd = $_POST['pwd'];
    $hashed_pwd = has('sha256', $raw_pwd); # this hash here will be the same as the hashed password in the database for the user
    $scanSQL = "SELECT * FROM accounts WHERE username = '$name' AND password = '$hashed_pwd'"; // query here to scan the db for the username and password submitted 
    $result = $conn->query($scanSQL);

    if ($result) {
        if ($result->num_rows > 0) {
            echo "Login successful!";
            session_start();
            $_SESSION['user'] = $name;
            header("Location: ../html/dashboard_page.html");
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Error: " . $scanSQL . "<br>" . $conn->error;
    }
    
}
$conn->close();
?>