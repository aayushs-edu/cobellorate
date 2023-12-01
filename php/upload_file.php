<?php
session_start();

if(isset($_POST['id'])) {
    $currentProjectID = $_POST['id'];
    
    function generateRandomHex() {
        $length = 32; // 64 char, each char => 4 bit
        $randBytes = random_bytes($length / 2);
        $hexString = bin2hex($randBytes);
        return $hexString;
    }
    
    $rawFileID = generateRandomHex();
    $hashedFileID = hash('sha256', $rawFileID);
    
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
        $fileBLOB = $_FILES["fileInput"]["name"];
        $insertSQL = "INSERT into files (fileID, fileContent, projectID) VALUES ('$hashedFileID', '$fileBLOB', '$currentProjectID')";
    
        if ($conn->query($insertSQL) === TRUE) {
            echo "New record added successfully";
        } else {
            echo "Error: " . $insertSQL . "<br>" . $conn->error;
        }
    } 
else {
    header('Location: ../dashboard_page.html');
}
$conn->close();
}
?>