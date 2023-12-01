<?php
    if(isset($_GET['id'])) {
        echo nl2br ("Project Dashboard \n");
        echo "ProjectID: {$_GET['id']}";
    }
    else{
        header('Location: ../dashboard_page.html');
    }
?>