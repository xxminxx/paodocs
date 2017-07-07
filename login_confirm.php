<?php
	include("database.php");
	$uname = $_POST["uname"];
	$pword = $_POST["pword"];
	
	//remove first unverify accounts
	// include("deactivate.php");

	$sqlA = "SELECT * FROM admin WHERE admin_uname='$uname'";
	$sqlU = "SELECT * FROM user WHERE user_uname='$uname'";
	
	while($row = mysql_fetch_array(mysql_query($sqlA))) {
		if($row['admin_pword'] == $pword) {
			session_start();
			$_SESSION["uname"] = $uname;
			$_SESSION["pword"] = $pword;
			if($_POST["session"] == "ON") {
				setcookie("paodms", $row["admin_id"], time() + (10 * 365 * 24 * 60 * 60), "/");
			}
			header("Location: admin_home.php");
		}
		else {
			$error = urlencode("Password is incorrect!");
			$in = "password";
			header("Location: login.php?msg=$error&changebdr=$in");
		}
	}
	
	while($row = mysql_fetch_array(mysql_query($sqlU))) {
		if($row['user_pword'] == $pword) {
			session_start();
			$_SESSION["uname"] = $uname;
			$_SESSION["pword"] = $pword;
			if($_POST["session"] == "ON") {
				setcookie("paodms", $row["user_id"], time() + (10 * 365 * 24 * 60 * 60), "/");
			}
			header("Location: home.php");
		}
		else {
			$error = urlencode("Password is incorrect!");
			$in = "password";
			header("Location: login.php?msg=$error&changebdr=$in");
		}
	}
	
	$error = 'Username is not recognized.';
	$in = "username";
	header("Location: login.php?msg=$error&changebdr=$in");
?>