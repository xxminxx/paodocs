<?php
$id = $_POST["accid"];
$type = $_POST["utype"];
include("database.php");


//FIRST NAME
if(isset($_POST["accfname"])){
	$fname = $_POST["accfname"];
	if($type == "Administrator"){
		mysql_query("UPDATE admin_profile SET admin_fname='$fname' WHERE admin_id='$id'");
	} else {
		mysql_query("UPDATE user_profile SET user_fname='$fname' WHERE user_id='$id'");
	}
}

//LAST NAME
if(isset($_POST["acclname"])){
	$lname = $_POST["acclname"];
	if($type == "Administrator"){
		mysql_query("UPDATE admin_profile SET admin_lname='$lname' WHERE admin_id='$id'");
	} else {
		mysql_query("UPDATE user_profile SET user_lname='$lname' WHERE user_id='$id'");
	}
}

//USERNAME
if(isset($_POST["accuname"])){
	$uname = $_POST["accuname"];
	if($type == "Administrator"){
		mysql_query("UPDATE admin SET admin_uname='$uname' WHERE admin_id='$id'");
	} else {
		mysql_query("UPDATE user SET user_uname='$uname' WHERE user_id='$id'");
	}
}

//POSITION
if(isset($_POST["accpos"])){
	$pos = $_POST["accpos"];
	if($type == "Administrator"){
		mysql_query("UPDATE admin_profile SET admin_pos='$pos' WHERE admin_id='$id'");
	} else {
		mysql_query("UPDATE user_profile SET user_pos='$pos' WHERE user_id='$id'");
	}
}

//CONTACT NUMBER
if(isset($_POST["accphone"])){
	$phone = $_POST["accphone"];
	if($type == "Administrator"){
		mysql_query("UPDATE admin_profile SET admin_contact='$phone' WHERE admin_id='$id'");
	} else {
		mysql_query("UPDATE user_profile SET user_contact='$phone' WHERE user_id='$id'");
	}
}

//EMAIL
if(isset($_POST["accemail"])){
	$email = $_POST["accemail"];
	if($type == "Administrator"){
		mysql_query("UPDATE admin_profile SET admin_email='$email' WHERE admin_id='$id'");
	} else {
		mysql_query("UPDATE user_profile SET user_email='$email' WHERE user_id='$id'");
	}
}

//return to page
$alert = 'show';
$class = urlencode('alert-success');
$alerthead = urlencode('Success!');
$alertmsg = urlencode('Information has been added to your account.');

if($type == "Administrator"){
	header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#accounts");
} else{
	header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#myaccount");
}
?>