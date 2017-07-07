<?php
$question = $_POST["question"];
$answer = $_POST["answer"];
$uname = $_POST["username"];
$type = $_POST["type"];
include("database.php");

if($type == "admin"){
	list($aid) = mysql_fetch_array(mysql_query("SELECT admin_id AS id FROM admin WHERE admin_uname='$uname'"));
	mysql_query("INSERT INTO security_questions(sq_question, sq_answer, sq_user_id) VALUES('$question', '$answer', $aid)");
	header("Location: admin_home.php#settings");
} else if($type == "user"){
	list($uid) = mysql_fetch_array(mysql_query("SELECT user_id AS id FROM user WHERE user_uname='$uname'"));
	mysql_query("INSERT INTO security_questions(sq_question, sq_answer, sq_user_id) VALUES('$question', '$answer', $uid)");
	header("Location: home.php#settings");
}

?>