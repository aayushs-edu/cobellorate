<?php
    if(isset($_GET['id'])) {
        session_start();
        $currentProjectID = $_GET['id'];

        $env = parse_ini_file('.env');
        $servername = $env["SQL_SERVER"];
        $username = $env["SQL_USERNAME"];
        $password = $env["SQL_PASSWORD"];
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
            header('Location: dashboard_page.html');
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/project_dashboard.css">
    <title>Project Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .dashboard {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .panel {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px;
            flex: 1;
        }

        .feed {
            flex: 2;
            padding: 20px;
        }

        .file-list, .collaborator-list {
            list-style: none;
            padding: 0;
        }

        .file-list li, .collaborator-list li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Project Dashboard</h1>
        <form action="upload_file.php" method="post" id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="fileInput" id="fileInput" />
            <?php echo "<input type='hidden' name='id' value={$currentProjectID}/>" ?>
            <input type="submit">Upload</input>
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
    <div class="dashboard">
        <div class="panel">
            <h2>Files</h2>
            <ul class="file-list">
                <li>File 1.txt</li>
                <li>File 2.jpg</li>
                <li>Folder 1
                    <ul>
                        <li>Subfile 1.txt</li>
                        <li>Subfile 2.jpg</li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="feed">
            <h2>Recent Changes</h2>
            <ul>
                <li>User A uploaded File 1.txt</li>
                <li>User B edited Subfile 2.jpg</li>
                <li>User C commented on Folder 1</li>
            </ul>
        </div>

        <div class="panel">
            <h2>Collaborators</h2>
            <ul class="collaborator-list">
                <li>User A</li>
                <li>User B</li>
                <li>User C</li>
            </ul>
        </div>
    </div>
</body>
</html>