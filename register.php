<?php
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$uname = $_POST["uname"];
$pword = $_POST["pword"];
$repword = $_POST["repword"];
$utype = "";
//Check if USER TYPE is selected or not
if($_POST["utype"] == "atty") {
	$utype = "Attorney";
}
else if($_POST["utype"] == "staff") {
	$utype = "Staff";
}
else if($_POST["utype"] == "null" || $_POST["utype"] == null){
	$err = "Please choose your user type";
	$in = "usertype";
	header("Location: signup.php?msg=$err&changebdr=$in");
	exit();
} 
//Check if name only contains letters and whitespace
if(!preg_match("/^[a-zA-Z ]*$/",$fname) || !preg_match("/^[a-zA-Z ]*$/",$lname)){
	$err = "Only letters and white space allowed in names";
	$in = "name";
	header("Location: signup.php?msg=$err&changebdr=$in");
	exit();
}

//Check if password match
if($pword != $repword){
	$err = "Passwords do not match";
	$in = "pword";
	header("Location: signup.php?msg=$err&changebdr=$in");
	exit();
}

include("database.php");

$duplicate = mysql_query("SELECT count(*) AS total FROM user WHERE user_uname='$uname'") or die(mysql_error());
list($total) = mysql_fetch_row($duplicate);
if ($total > 0)
	{
		$err = urlencode("The username <b>$uname</b> already exists. Try again with different username.");
		header("Location: signup.php?msg=$err");
	}
else {
	$id = mysql_query("SELECT MAX(user_id) + 1 FROM user");
	if (is_null($id)) {
		$id = "154";
		list($user_id) = $id;
	}
	else {
		list($user_id) = mysql_fetch_row($id);
	}

	mysql_query("INSERT INTO user VALUES ('$id', '$uname', '$pword', '$utype')");
	list($sql_id) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'"));
	mysql_query("INSERT INTO user_profile (user_id, user_fname, user_lname, date_created, user_dp) VALUES ('$sql_id', '$fname', '$lname', now(), 'default')");
	
	//create folder
	mkdir("uploads/docs/".$sql_id."usr");
	
	session_start();
	$_SESSION["uname"] = $uname;
	header("Location: home.php");
}
?>