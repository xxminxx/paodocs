<?php include("database.php");
$user = $_POST["uname"];
$name = $_POST["evtname"];
$date = $_POST["evtdate"];
$start = $_POST["evttimefrom"];
if(isset($_POST["evttimeto"])){
	$end = $_POST["evttimeto"];
	mysql_query("INSERT INTO user_agenda(evt_user, evt_name, evt_date, evt_time_from, evt_time_to) VALUES('$user','$name',DATE_FORMAT(STR_TO_DATE('$date', '%m/%d/%Y'), '%Y-%m-%d'), DATE_FORMAT(STR_TO_DATE('$start', '%l:%i %p'), '%H:%i:%s'), DATE_FORMAT(STR_TO_DATE('$end', '%l:%i %p'), '%H:%i:%s'))") or die(mysql_error());
} else {
	mysql_query("INSERT INTO user_agenda(evt_user, evt_name, evt_date, evt_time_from) VALUES('$user','$name',DATE_FORMAT(STR_TO_DATE('$date', '%m/%d/%Y'), '%Y-%m-%d'),DATE_FORMAT(STR_TO_DATE('$start', '%l:%i %p'), '%H:%i:%s'))") or die(mysql_error());
}



$alert = 'show';
$class = urlencode('alert-success');
$alertmsg = urlencode('New agenda has been added!');
if($_POST["view"] == "admin"){
	header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg");
} else {
	header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg");
}
?>