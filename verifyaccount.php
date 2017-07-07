<?php
if(isset($_GET["uid"])){
	$id = $_GET["uid"];
	include("database.php");
	
	mysql_query("UPDATE user_profile SET user_account_status='1' WHERE user_id='$id'");
	list($uname) = mysql_fetch_row(mysql_query("SELECT user_uname FROM user WHERE user_id='$id'"));
	
	$alert = 'show';
	$class = urlencode('alert-success');
	$alertmsg = urlencode('Account for <strong>' .$uname .'</strong> has been verified.');
	header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#settings");
}
?>