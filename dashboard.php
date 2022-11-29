<?php
    //session_start();
    require 'manage_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h4>Welcome <?=$_SESSION['full_name']?></h4><br>
    <a href="logout.php"><button type="button" class="btn btn-danger">Logout</button></a>
</body>
</html>