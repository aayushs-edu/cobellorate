<?php
    if(isset($_GET['id'])) {
        $currentProjectID = $_GET['id'];
    }
    else{
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
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="file" id="fileInput" />
            <button type="button" onclick="uploadFile()">Upload</button>
        </form>
        <div id="response"></div>
    </div>

    <script src="app.js"></script>
</body>
</html>

