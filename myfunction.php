<?php
$conn = '';
include 'config/connect_database.php';

    function table_body()
    {
        $sql = "SELECT * FROM profileData_tbl ORDER BY surname ASC";
        if($result=$conn->query($sql)){
            while($fetch=$result->fetch_assoc()){?>
                <tr>
                    <td></td>
                    <td><?php echo $fetch['surname']?></td>
                    <td><?php echo $fetch['firstname']?></td>
                    <td><?php echo $fetch['middlename']?></td>
                    <td><?php echo $fetch['gender']?></td>
                    <td><?php echo $fetch['phonenumber']?></td>
                    <td><?php echo $fetch['emailaddress']?></td>
                    <td></td>
                </tr>
    <?php    }
        }
        else{
           die("SQL Error [' . $conn->error . ']'");
        } 
    }
?>