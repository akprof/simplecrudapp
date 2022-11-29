<?php
    session_start();
    include 'config/connect_database.php';
    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $login ="SELECT * FROM profiledata_tbl WHERE emailaddress = '$username' AND phonenumber = '$password'";
        $result = $conn->query($login);
        if($result == true){
            if(mysqli_num_rows($result) ==1) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['full_name'] = $row['surname']. ' '.$row['firstname']. ' '.$row['lastname']; 
                $_SESSION['identity']= $row['emailaddress'];
                header("location:dashboard.php");
            }
            else{
                echo "You have no login details";
            }
        }
        else{
            die("SQL Error [.$conn->error.]");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="contianer-fluid">
        <div class="conatiner">
            <center><h4 class="text-danger">Login Here</h4></center>
                <form class="ml-5" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <div class="col-sm-8 offset-4">
                        <div class="row mb-2">
                            <div class="col-sm-2">
                                <label for="username">Username</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="email" class="form-control" id="username" name="username">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="username">Password</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>
                    </div>
                    <center><button type="submit" class="btn btn-block btn-primary mt-4" name="login">Login Now</button></center>
                </form>
            
        </div>
    </div>
</body>
</html>