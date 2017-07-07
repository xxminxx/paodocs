<?php
include("database.php");
$id = $_POST["dp"];
$uname = $_POST["uname"];

//check if username is from admin table or user table
$typeadmin = mysql_num_rows(mysql_query("SELECT admin_id FROM admin WHERE admin_uname='$uname'"));
$typeuser = mysql_num_rows(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'"));

//get ID value of picture
$name= mysql_query("SELECT dis_name FROM display_picture WHERE dis_id=$id");
while ($row = mysql_fetch_array($name)) {
	$dname = $row["dis_name"];
	if($typeadmin > 0) {
		mysql_query("UPDATE admin_profile p, admin a SET p.admin_dp='$dname' WHERE p.admin_id=a.admin_id AND a.admin_uname='$uname'");
		header("Location: admin_home.php#accounts");
	} else if ($typeuser > 0){
		mysql_query("UPDATE user_profile p,user u SET p.user_dp='$dname' WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
		header("Location: home.php#myaccount");
	}
}
?>