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
        $fileName = $_FILES['fileInput']['name'];
        $fileTmpName = $_FILES['fileInput']['tmp_name'];
        $fileContent =  file_get_contents($fileTmpName);
        $fileSize = $_FILES['fileInput']['size'];
        $sql = "INSERT into files (fileID, `fileName`, fileContent, projectID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssbs", $hashedFileID, $fileName, $fileContent, $currentProjectID);
        $stmt->send_long_data(2, file_get_contents($fileTmpName)); 
        $stmt->execute();
        echo "File successfully inserted";
        
    }
    $conn->close();

?>