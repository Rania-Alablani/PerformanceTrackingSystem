<?php

require_once('connect.php');

if(isset($_post['Delete'])){
  $TaskNo = $_post['TaskNo'];
  $Deletequery = "DELETE FROM tasks WHERE TaskNo='$TaskNo'";
  $query_run=mysqli_query($conn,$Deletequery);

if($Deletequery){
  echo '<script>alert("one row deleted")</script>';
  header("location:index.php");
}else{
  echo '<script>alert("not deleted")</script>';
}
}
?>
