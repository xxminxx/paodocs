<?php include("database.php");
$utype = $_POST["utype"];
$view = $_POST["view"];

if(!isset($_POST["casename"])){ 
	
	
	if($utype == "Administrator"){
		$alert = 'show';
		$class = urlencode('alert-danger');
		$alerthead = urlencode('Error.');
		$alertmsg = urlencode('Unable to add the additional information.');
		header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#$view");
	} else {
		$alert = 'show';
		$class = urlencode('alert-danger');
		$alerthead = urlencode('Error.');
		$alertmsg = urlencode('Unable to add the additional information.');
		header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#$view");
	}
	
	
} else {
	$name = $_POST["casename"];
	$note = $_POST["notes"];
	mysql_query("UPDATE case_matter SET cm_notes='$note' WHERE cm_name='$name'");
	
	if($utype == "Administrator"){
		header("Location: admin_home.php#$view");
	} else {
		header("Location: home.php#$view");
	}
}
?>