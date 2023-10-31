<?php
session_start();
if(empty($_SESSION['name']))
{
  header('location:index.php');
}
include_once('connection.php');
if(isset($_REQUEST['otp_verify']))
{
  $otp = $_REQUEST['otp'];
  $select_query = mysqli_query($connection,"select * from tbl_otp_check where otp='$otp' and is_expired!=1 and NOW()<=DATE_ADD(create_at,interval 5 minute)");
  $count = mysqli_num_rows($select_query);
  if($count>0)
  {
    $select_query = mysqli_query($connection, "update tbl_otp_check set is_expired=1 where otp='$otp'");
    header('location:dashboard.php');
  }
  else
  {
    $msg = "Invalid OTP!";
  }
}
?>