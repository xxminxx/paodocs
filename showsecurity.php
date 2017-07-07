<?php
$user = $_POST["user"];
include("database.php");
$sqla = mysql_query("SELECT admin_id AS id FROM admin WHERE admin_uname='$user'");
$sqlu = mysql_query("SELECT user_id AS id FROM user WHERE user_uname='$user'");

if (mysql_num_rows($sqla) > 0) {
	list($id) = mysql_fetch_row($sqla);
	$a = mysql_query("SELECT sq_question FROM security_questions WHERE sq_user_id=$id");
	if (mysql_num_rows($a) > 0){
		list($q) = mysql_fetch_row($a);
		$question = urlencode($q);
		header("Location: securityquestion.php?question=$question&user=$user");
	} else {
		$error = urlencode("Oops! It seems that your account did NOT make any security questions. <br><br>To retrieve password contact us at <br><b>ldmspao@gmail.com</b> or<br><b> +639353482525</b>");
		$div = 'hide';
	    header("Location: forgotpassword.php?msg=$error&div=$div");
	}
	
} else if (mysql_num_rows($sqlu) > 0) {
	list($id) = mysql_fetch_row($sqlu);
	$u = mysql_query("SELECT sq_question FROM security_questions WHERE sq_user_id=$id");
	if (mysql_num_rows($u) > 0){
		list($q) = mysql_fetch_row($u);
		$question = $q;
		header("Location: securityquestion.php?question=$question&user=$user");
	} else {
		$error = urlencode("Oops! It seems that your account did NOT make any security questions. <br><br>To retrieve password contact us at <br><b>ldmspao@gmail.com</b> or<br><b> +639353482525</b>");
	    $div = 'hide';
	    header("Location: forgotpassword.php?msg=$error&div=$div");
	}
	
} else {
	$error = urlencode("Username does not match any database record.");
	$change = 'user';
	header("Location: forgotpassword.php?msg=$error&changebdr=$change");
}

?>

