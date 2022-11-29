<?php 
    session_start();
    include 'config/connect_database.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    // require 'C:\xampp\composer\vendor\autoload.php';
  
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    function validate_input($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
    }
    $mail = new PHPMailer(true);
    if(isset($_POST['save_profile']))
    {
        $surname = validate_input($_POST['surname']);
        $firstname = validate_input($_POST['firstname']);
        $middlename = validate_input($_POST['middlename']);
        $phonenumner = validate_input($_POST['phonenumber']);
        $emailaddress = validate_input($_POST['emailaddress']);
        $gender = validate_input($_POST['gender']);
        //Prepare to send data into the database
        $surname = mysqli_real_escape_string($conn, $_POST['surname']);
        $firstname = mysqli_real_escape_string($conn,$_POST['firstname']);
        $middlename = mysqli_real_escape_string($conn,$_POST['middlename']);
        $phonenumner = mysqli_real_escape_string($conn,$_POST['phonenumber']);
        $emailaddress =mysqli_real_escape_string($conn,$_POST['emailaddress']);
        $gender = mysqli_real_escape_string($conn,$_POST['gender']);
        //process image
        $profileImage = $_FILES['image'];
        $imgName= $_FILES['image']['name'];
        $imgType= $_FILES['image']['type'];
        $imgSize=$_FILES['image']['size'];
        $imgTmpName= $_FILES['image']['tmp_name'];
        $imgError= $_FILES['image']['error'];
        $valid_extensions = explode('.', $imgName);
        $img_extensions = strtolower(end($valid_extensions));
        $allowed = array('jpeg', 'jpg', 'png', 'gif');
        if(in_array($img_extensions, $allowed)){
            
                if($imgSize<5000000){
                    $imgNameNew=uniqid($surname.'_'.$emailaddress).".".$img_extensions;
                    $imgDestination= 'profile_images/'.$imgNameNew;
                    move_uploaded_file($imgTmpName, $imgDestination);
                }
               else{
                    $_SESSION['message']= "Image size is too big";
                    $_SESSION['msg_type']= "warning";
                    exit("Image size is too big");
                }
        
        }
        else{
            $_SESSION['message']= "Image format not allowed, upload the correct format";
            $_SESSION['msg_type']= "warning";
            exit("Image format not allowed, upload the correct format");
        }


        //Send input into the database
        // $sql = "INSERT INTO profileData_tbl (surname, firstname, middlename, gender, phonenumber, emailaddress)
        // VALUES ('$surname', '$firstname', '$middlename', '$gender', '$phonenumner', '$emailaddress')";
        $result = $conn->query("INSERT INTO profileData_tbl (surname, firstname, middlename, gender, phonenumber, emailaddress, profile_image)
        VALUES ('$surname', '$firstname', '$middlename', '$gender', '$phonenumner', '$emailaddress', '$imgNameNew')");
        if($result == true)
        {
            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'ayodeleomoyeni123@gmai.com';                     //SMTP username
                $mail->Password   = 'elozoe126';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mail->setFrom('ayodeleomoyeni123@gmai.com', 'Testing My Mailer');
                $mail->addAddress($emailaddress);     //Add a recipient
                // $mail->addAddress('ellen@example.com');               //Name is optional
                // $mail->addReplyTo('info@example.com', 'Information');
                // $mail->addCC('cc@example.com');
                // $mail->addBCC('bcc@example.com');
            
                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
            
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Class PHPMailer Testing';
                $mail->Body    = 'Hello, you have created your profile successfully';
                // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
                $mail->send();
                // echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            $_SESSION['message'] ='Profile Added Successfully';
            $_SESSION['type']= 'success';
            header('location:index.php');
        }
        else{
            die("Profile not submitted[' . $conn->error . ']'");
        }
    }
    if(isset($_POST['update_profile']))
    {
        $surname = validate_input($_POST['surname']);
        $firstname = validate_input($_POST['firstname']);
        $middlename = validate_input($_POST['middlename']);
        $phonenumner = validate_input($_POST['phonenumber']);
        $emailaddress = validate_input($_POST['emailaddress']);
        $gender = validate_input($_POST['gender']);
        //Prepare to send data into the database
        $surname = mysqli_real_escape_string($conn, $_POST['surname']);
        $firstname = mysqli_real_escape_string($conn,$_POST['firstname']);
        $middlename = mysqli_real_escape_string($conn,$_POST['middlename']);
        $phonenumner = mysqli_real_escape_string($conn,$_POST['phonenumber']);
        $emailaddress =mysqli_real_escape_string($conn,$_POST['emailaddress']);
        $gender = mysqli_real_escape_string($conn,$_POST['gender']);
        $id = $_POST['id'];
        //Update record in the database
        
        $result = $conn->query("UPDATE profileData_tbl SET surname= '$surname', firstname= '$firstname', middlename = '$middlename', gender='$gender', phonenumber = '$phonenumner', emailaddress = '$emailaddress' WHERE profile_id = '$id'");
        if($result == true)
        {
            $_SESSION['message'] ='Profile Update Successfully';
            $_SESSION['type']= 'primary';
            header('location:index.php');
        }
        else{
            die("Profile not submitted[' . $conn->error . ']'");
        }
    }
?>