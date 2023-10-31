<?php
session_start();
include_once('connection.php');
if(isset($_REQUEST['login']))
{
  $email = $_REQUEST['email'];
  $select_query = mysqli_query($connection,"select * from tbl_student where email='$email'");
  $res = mysqli_num_rows($select_query);
  if($res>0)
  {
    $data = mysqli_fetch_array($select_query);
    $name = $data['name'];
    $_SESSION['name'] = $name;
    $otp = rand(10000, 99999);   //Generate OTP
    include_once("SMTP/class.phpmailer.php");
    include_once("SMTP/class.smtp.php");
    $message = '<div>
     <p><b>Hello!</b></p>
     <p>You are recieving this email because we recieved a OTP request for your account.</p>
     <br>
     <p>Your OTP is: <b>'.$otp.'</b></p>
     <br>
     <p>If you did not request OTP, no further action is required.</p>
    </div>';
$email = $email; 
$mail = new PHPMailer;
$mail->IsSMTP();
$mail->SMTPAuth = true;                 
$mail->SMTPSecure = "tls";      
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587; 
$mail->Username = ""; // Enter your username
$mail->Password = ""; // Enter Password
$mail->FromName = "Tech Area";
$mail->AddAddress($email);
$mail->Subject = "OTP";
$mail->isHTML( TRUE );
$mail->Body =$message;
if($mail->send())
{
  $insert_query = mysqli_query($connection,"insert into tbl_otp_check set otp='$otp', is_expired='0'");
  header('location:otpverify.php');
}
else
{
  $msg = "Email not delivered";
}
}
  else
  {
    $msg = "Invalid Email";
  }
}

?>