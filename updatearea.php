<?php include("database.php");
$option = $_POST["doption"];

if($option == 1){
	//ADD NEW AREA/LOCATION
	$new = $_POST["desname"];
	mysql_query("INSERT INTO designation(des_name, des_resp_atty_id) VALUES('$new', 0)");
	
	$alert = 'show';
	$class = urlencode('alert-success');
	$alertmsg = urlencode('<b>' .$new .'</b> has been added to assigned locations.');
	header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#settings");
	
} else if($option == 2){
	//EDIT AREA/LOCATION NAME
	$oldname = $_POST["desname"];
	$newname = $_POST["newdesname"];
	mysql_query("UPDATE designation SET des_name='$newname' WHERE des_name='$oldname'");
	
	$alert = 'show';
	$alerthead = urlencode('Success!');
	$class = urlencode('alert-success');
	$alertmsg = urlencode('Designation name changed.');
	header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#settings");
	
} else if($option == 3){
	//DELETE AREA/LOCATION
	$designame = $_POST["desname"];
	mysql_query("DELETE FROM designation WHERE des_name='$designame'");
	
	$alert = 'show';
	$class = urlencode('alert-warning');
	$alertmsg = urlencode('<b>' .$designame .'</b> has been deleted.');
	header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#settings");
	
} else if($option == 4){
	//ADD NEW ASSIGNEE
	$assignee = $_POST["assign"]; //user id ang value
	$desname = $_POST["desname"]; //location name
	mysql_query("UPDATE designation SET des_resp_atty_id='$assignee' WHERE des_name='$desname'");
	
	$alert = 'show';
	$class = urlencode('alert-success');
	$alertmsg = urlencode('An attorney was assigned to <b>' .$desname .'</b>');
	header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#settings");
	
}

?>