<?php
$Title= filter_input(INPUT_POST,'Title');
$Department= filter_input(INPUT_POST,'Department');
$Floor= filter_input(INPUT_POST,'Floor');
$Phone= filter_input(INPUT_POST,'Phone');
$User= filter_input(INPUT_POST,'User');
$Engineer= filter_input(INPUT_POST,'Engineer');
$Date= filter_input(INPUT_POST,'Date');

$conn = new mysqli("localhost","root","","supportdb");

if(!empty($Title)){
  if(!empty($Engineer)){
$host = "localhost";
$dbusername="root";
$dbpassword ="";
$dbname="supportdb";


if (mysqli_connect_error()) {
    die("Connection error: " . mysqli_connect_error());
}
else{
  $sql="INSERT INTO tasks (Title,Department,Floor,Phone,User,Engineer,Date)
        VALUES('$Title','$Department','$Floor','$Phone','$User','$Engineer','$Date')";
        if($conn->query($sql)){
          //echo "new record inserted :)";
          header("Location: index.php");
          exit();

        }else{
          echo "error :(";
        }
        $conn->close();
}
  }
}
 ?>
