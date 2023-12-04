<?php
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
    $selectFilesSQL = "SELECT fileID, `fileName`, fileContent FROM files WHERE projectID = '$currentProjectID/'";
    $result = $conn->query($selectFilesSQL);
    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/project_dashboard.css">
    <title>Project Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
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
    </div>
    <div class="dashboard">
        <div class="panel">
            <h2>Files</h2>
            <ul class="file-list">
                <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            if(!file_exists('./Files')) mkdir('./Files');
                            $filePath = './Files/' . $row['fileName'];
                            if(!file_exists($filePath)) file_put_contents($filePath, $row['fileContent']);
                            echo "<button class=\"btn btn-primary\" onclick=\"location.href='Files/{$row['fileName']}';\">{$row['fileName']}</button><br>\n";
                        }
                    }
                ?>
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
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>