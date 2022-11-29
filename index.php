<?php 
    session_start();
    include 'config/connect_database.php';
    $surname = $firstname = $middlename = $gender = $emailaddress= $phonenumber = '';
    $update_status = false;
    if(isset($_GET['update'])){
        $update_status = true;
        $update_id = $_GET['update'];
        $update_sql = "SELECT * FROM profileData_tbl WHERE profile_id = '$update_id'";
        if($result=$conn->query($update_sql)){
            while($fetch=$result->fetch_assoc()){
                $surname = $fetch['surname'];
                $firstname = $fetch['firstname'];
                $middlename = $fetch['middlename'];
                $phonenumber = $fetch['phonenumber'];
                $gender = $fetch['gender'];
                $emailaddress = $fetch['emailaddress'];

            }
        }     
    }
    if(isset($_GET['delete'])){
        $delete_id = $_GET['delete'];
        $delete_sql = "DELETE FROM profileData_tbl WHERE profile_id = '$delete_id'";
        if($result=$conn->query($delete_sql)){
            $_SESSION['message'] ='Profile Deleted Successfully';
            $_SESSION['type']= 'warning';
        }
        else{
            die("Profile not deleted[' . $conn->error . ']'");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD 2 CLASS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="container">
            <?php if(isset($_SESSION['message'])){?>
                <div class="w-100 sufee-alert alert with-close alert-<?=$_SESSION['type']?> alert-dismissible fade show" style="font-weight:lighter">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    ?>
            </div>
            <?php }?>
        
            <?php if($update_status==false):?>
                <center><h4 class="text-danger">Add Profile Data</h4></center>
            <?php endif ?>
            <?php if($update_status==true):?>
                <center><h4 class="text-success">Update Profile Data</h4></center>
            <?php endif ?>
            <div id="form_input">
                <form action="process.php" method="POST" enctype="multipart/form-data">
                <div class="row form-group mb-2">
                    <div class="col-sm-6 offset-3">
                        <input type="file" class="form-control" id="image" " name="image">
                    </div>
                </div>
                    <div class="row form-group mb-2">
                        <div class="col-5">
                            <label class="form-label">Surname</label>
                            <input type="text" class="form-control" id="surname" value="<?=$surname?>" name="surname">
                            <input type="hidden" name="id" value="<?=$update_id?>">
                        </div>
                        <div class="col-5">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstname" value="<?php echo $firstname?>" name="firstname">
                        </div>
                    </div>
                    <div class="row form-group mb-2">
                        <div class="col-5">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middlename" value="<?php echo $middlename?>" name="middlename">
                        </div>
                        <div class="col-5">
                            <label class="form-label">Phone Number</label>
                            <input type="number" class="form-control" id="phonenumber" value="<?php echo $phonenumber?>"  name="phonenumber">
                        </div>
                    </div>
                    <div class="row form-group mb-2">
                        <div class="col-5">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="<?php echo $emailaddress?>" id="emailaddress" name="emailaddress">
                        </div>
                        <div class="col-5">
                            <label class="form-label">Gender</label><br>
                            <input type="radio" class="form-check-input" id="gender" value="Male" name="gender">&nbsp;Male&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="form-check-input" id="gender" value="Female" name="gender">&nbsp;Female
                        </div>
                    </div>
                    <?php if($update_status == false):?>
                        <center><button type="submit" class="btn btn-primary" name="save_profile">Save Profile</button></center>
                    <?php endif?>
                    <?php if($update_status == true):?>
                        <center><button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button></center>
                    <?php endif?>
                </form>
            </div>
            <span>Have an Account? <a href="login.php"><button type="button" class="btn btn-info">Log In</button></a></span>
            <hr>
            <button class="btn btn-block btn-info w-100" id="show" onclick="showrecord()">Show My Profile Records</button>
            <div id="table_content" style="display: none;">
                <center><h4 class="text-success">Saved Profile Data</h4></center>
                <table class="table table-striped ml-5">
                    <thead>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Surname</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Gender</th>
                        <th>Phone Number</th>
                        <th>Email Address</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php 
                            $sql = "SELECT * FROM profileData_tbl ORDER BY surname ASC";
                            if($result=$conn->query($sql)){
                                $sn =0;
                                while($fetch=$result->fetch_assoc()){
                                        $sn++;
                                    ?>  
                                    <tr>
                                        <td><?php echo $sn ?></td>
                                        <td><img class=rounded-circle src="profile_images/<?=$fetch['profile_image']?>" width="40px" height="40px"></td>
                                        <td><?php echo $fetch['surname']?></td>
                                        <td><?php echo $fetch['firstname']?></td>
                                        <td><?php echo $fetch['middlename']?></td>
                                        <td><?php echo $fetch['gender']?></td>
                                        <td><?php echo $fetch['phonenumber']?></td>
                                        <td><?php echo $fetch['emailaddress']?></td>
                                        <td>
                                            <a href="index.php?update=<?=$fetch['profile_id']?>"><button class="btn btn-primary">Update</button></a>
                                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletemodal-<?=$fetch['profile_id']?>">Remove</button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="deletemodal-<?=$fetch['profile_id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title" id="exampleModalLabel">Alert!</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                    <div class="modal-body">
                                                        <h5>Do You Want To Remove This Record of <?=$fetch['surname']?>?</h5>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <center>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                                            <a href="index.php?delete=<?=$fetch['profile_id'];?>"><button type="button" class="btn btn-danger">Yes</button></a>
                                                        </center>
                                                        
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                        <?php    }
                            }
                            else{
                                die("SQL Error [' . $conn->error . ']'");
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function showrecord()
        {
            document.getElementById("table_content").style.display='';
            
        }
    </script>
</body>
</html>
