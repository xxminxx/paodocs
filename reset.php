<?php
$user = $_POST["user"];
$pw = $_POST["password"];
include("database.php");

$sqla = mysql_query("SELECT admin_id AS id FROM admin WHERE admin_uname='$user'");
$sqlu = mysql_query("SELECT user_id AS id FROM user WHERE user_uname='$user'");
if(mysql_num_rows($sqla) > 0) {
	mysql_query("UPDATE admin SET admin_pword='$pw' WHERE admin_uname='$user'");
	$msg = urlencode("Password changed successfully.");
	header("Location: login.php?ok=$msg");
} else if(mysql_num_rows($sqlu) > 0){
	mysql_query("UPDATE user SET user_pword='$pw' WHERE user_uname='$user'");
	$msg = urlencode("Password changed successfully.");
	header("Location: login.php?ok=$msg");
} else {
	header("Location: index.php");
}
?>