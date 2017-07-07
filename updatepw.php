<?php
$old = $_POST["oldpassword"];
$new = $POST["newpassword"];
$uname = $_POST["user"];
$prevpage = $_POST["page"];
include("database.php");

$sqla = mysql_fetch_row(mysql_query("SELECT admin_pword AS pw, count(admin_pword) AS count FROM admin WHERE admin_uname='$uname'"));
$sqlu = mysql_fetch_row(mysql_query("SELECT user_pword AS pw, count(user_pword) AS count FROM user WHERE user_uname='$uname'"));

if($sqla["count"] > 0) {
	mysql_query("UPDATE admin SET admin_pword='$new' WHERE admin_uname='$uname';");
	$alert = 'show';
	$class = urlencode('alert-success');
	$alerthead = urlencode('Success!');
	$alertmsg = urlencode('Password changed.');
	header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#settings");
} else if ($sqlu["count"] > 0){
	mysql_query("UPDATE user SET user_pword='$new' WHERE user_uname='$uname';");
	$alert = 'show';
	$class = urlencode('alert-success');
	$alerthead = urlencode('Success!');
	$alertmsg = urlencode('Password changed.');
	header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#settings");
} else {
    $alert = 'show';
	$class = urlencode('alert-danger');
	$alerthead = urlencode('Error!');
	$alertmsg = urlencode('Wrong old pasword');
	if($prevpage == 'admin'){
		header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#settings");
	} else if($prevpage == 'user'){
		header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#settings");
	}
}
?>