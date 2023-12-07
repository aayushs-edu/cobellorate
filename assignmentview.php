<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="styleSheet" type="text/css" href="css/home.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
        <title>Assignment View</title>
</head>
<body style="background-color: #212529;">
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark justify-content-center" aria-label="Navigation Bar">
        <div class="container-fluid">
          <a class="navbar-brand" href="dashboard_page.html">Cobellorate</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
    
          <div class="collapse navbar-collapse" id="navbarsExample02">
            <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <a class="nav-link" href="login_page.html">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="make_account_page.html">Sign Up</a>
              </li>
            </ul>
          </div>
          
        </div>
      </nav>
    <div style="text-align: center; transform: translate(0%, 90%);">
        <div class="slider-slow">
          <?php
            include "scraper/scraper.php";
          ?>
        </div>
    </div>
    <nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark justify-content-center" style="bottom: -3%; background-color: #5a5a5a;">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav justify-content-center center2">
            <li class="nav-item">
              <a class="nav-link" href="https://github.com/aayushs-edu/group-project-manager">GitHub</a>
            </li>
            <li class="nav-item">
              <a class="nav-link">|</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login_page.html">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link">|</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="make_account_page.html">Sign Up</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <script src="../js/home_page_events.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>