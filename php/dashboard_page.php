<?php
    session_start();

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
    // fetch projects from the database
    $selectProjectsSQL = "SELECT projectID, name, owner FROM projects WHERE owner = '$sessionUser'";
    $result = $conn->query($selectProjectsSQL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cobellorate Dashboard</title>
    <link rel="styleSheet" type="text/css" href="../css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
<body>
    
    <nav class="navbar navbar-dark bg-dark justify-content-between">
        <a class="navbar-brand" style="margin:auto">Dashboard</a>
        <ul class="navbar-nav" style="margin-right: 15px">
            <li class="nav-item active">
              <a class="nav-link" href="#">Account</a>
            </li>
        </ul>
      </nav>

    <section>
        <div class="panel">
            <div class="projects">
                <h2>Projects</h2>
                <div id="project-list">
                    <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<a style='text-decoration: none' href='/php/projects.php?id={$row['projectID']}'>{$row['name']}</a><br>\n";
                            }
                        }
                    ?>
                </div>
                <button id="new-project-button" type="button" class="btn btn-primary" onclick="location.href='../project_creation_page.html'">New Project</button>
            </div>

            <div style="position: absolute; bottom: 0">
                <p>Settings</p>
            </div>
        </div>

        <div class="content">
            <div class="widget-container">
                <div class="card">
                    <h2>Widget 1</h2>
                    <p>This is the content of widget 1.</p>
                </div>

                <div class="card">
                    <h2>Widget 2</h2>
                    <p>This is the content of widget 2.</p>
                </div>

                <div class="card">
                    <h2>Widget 3</h2>
                    <p>This is the content of widget 3.</p>
                </div>
            </div>
        </div>
    </section>
    <?php
        $conn->close();
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/projects_update.js"></script>
</body>
</html>
