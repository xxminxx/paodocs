<?php include("database.php");
$id = $_GET["evtid"];
$view = $_GET["view"];
mysql_query("DELETE FROM user_agenda WHERE evt_id='$id'") or die(mysql_query());

if($view == "admin"){
	header("Location: admin_home.php");
} else {
	header("Location: home.php");
}
?>