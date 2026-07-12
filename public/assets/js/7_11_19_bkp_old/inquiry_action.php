<?php
 include('acess_admin/include/connection.php'); 
if(isset($_POST['submit']))
 {
	if(isset($_POST['name']) && isset($_POST['email'])){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$subject = $_POST['subject'];
	$comments = $_POST['comments'];
		if(mysqli_query($con,"INSERT INTO inquiry (name,email,phone,subject,comments) VALUES('$name','$email','$phone','$subject','$comments')")){
		}
	}
	include("include/emailformet.php");
	$headers  = 'From: info@dwarkeshit.com' . "\r\n";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	mail($email,'inquiry',$messaage,$headers);
	header("Location:".$usertpath."contactus.php?success=1");
 }
?>