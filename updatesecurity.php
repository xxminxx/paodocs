<?php 
$question = $_POST["upquest"];
$answer = $_POST["upans"];
$uname = $_POST["upuname"];
$type = $_POST["uptype"];
include("database.php");

    $alert = 'show';
	$class = urlencode('alert-success');
	$alerthead = urlencode('Success!');
	$alertmsg = urlencode('Security question changed.');
	
if($type == 'admin'){
	while($aid = mysql_fetch_array(mysql_query("SELECT admin_id AS id FROM admin WHERE admin_uname='$uname'"))){
		$ida = $aid["id"];
		mysql_query("UPDATE security_questions SET sq_question='$question', sq_answer='$answer' WHERE sq_user_id='$ida'");
    	header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#settings");
	}
	
} else if($type == 'user'){
	while($uid = mysql_fetch_array(mysql_query("SELECT user_id AS id FROM user WHERE user_uname='$uname'"))){
		$idu = $uid["id"];
		mysql_query("UPDATE security_questions SET sq_question='$question', sq_answer='$answer' WHERE sq_user_id='$idu'");
    	header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#settings");
	}
	
	
}
?>