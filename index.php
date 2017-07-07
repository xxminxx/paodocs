<?php
session_start();
if(!isset($_COOKIE["paodms"]) or !isset($_SESSION["uname"])) {
	header("Location: homepage.php");
}
else {
	include("database.php");
	if(isset($_COOKIE["paodms"])) {
		$cookie = $_COOKIE["paodms"];
		$sqla = mysql_fetch_row(mysql_query("SELECT admin_uname FROM admin WHERE admin_id='$cookie'"));
		$sqlu = mysql_fetch_row(mysql_query("SELECT user_uname FROM user WHERE user_id='$cookie'"));
		if($sqla != null) {
			list($adun) = $sqla;
			$uname = $adun;
		} 
		if($sqlu != null) {
			list($usun) = $sqlu;
			$uname = $usun;
		}	
	}
	else {
		$uname = $_SESSION["uname"];
	}
	
	$sqlA = "SELECT * FROM admin WHERE admin_uname='$uname'";
	$sqlU = "SELECT * FROM user WHERE user_uname='$uname'";
	
	while($row = mysql_fetch_array(mysql_query($sqlA))) {
			$_SESSION["uname"] = $uname;
			header("Location: admin_home.php");
	}
	
	while($row = mysql_fetch_array(mysql_query($sqlU))) {
			$_SESSION["uname"] = $uname;
			header("Location: home.php");
	}
}
?>