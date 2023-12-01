<?php
    if(isset($_GET['id'])) {
        session_start();
        $currentProjectID = $_GET['id'];

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
        $selectFilesSQL = "SELECT fileContent FROM files WHERE projectID = '$currentProjectID'";
        $result = $conn->query($selectFilesSQL);
        } else {
            header('Location: ../dashboard_page.html');
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/project_dashboard.css">
    <title>Project Dashboard</title>
</head>
<body>
    <div class="container">
        <h1>Project Dashboard</h1>
        <form action='upload_file.php' method='post' id='uploadForm' enctype='multipart/form-data'>
            <input type="file" name="fileInput" id="fileInput" />
            <?php echo "<input type='hidden' name='id' value={$currentProjectID}/>" ?>
            <button type="submit">Upload</button>
        </form>
        <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    echo "<p style='text-decoration: none'>{$row['fileContent']}</p>\n";
                }
            }
        ?>
        <div id="response"></div>
    </div>
</body>
</html>

