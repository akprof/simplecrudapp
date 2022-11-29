<?php
    ob_start();
    session_start();
    include 'config/connect_database.php';
    $userId = $_SESSION['identity'];
    $checkuser = "SELECT * FROM profiledata_tbl WHERE emailaddress = '$userId'";
    $result = $conn->query($checkuser);
    if($result == true){
        if(mysqli_num_rows($result)>0) {
            $row = mysqli_fetch_assoc($result);
        }
        else{
            header('location:login.php');
        }
    }
    else{
        header('location:login.php');
    }