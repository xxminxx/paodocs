<?php 
$fullname = $_POST["val"];
$name = explode(", ", $fullname);
$lname = $name[0];                          
$fname = $name[1];                         

include("database.php");
//get ID
list($id) = mysql_fetch_row(mysql_query("SELECT cc_id FROM case_client WHERE cc_fname='$fname' AND cc_lname='$lname'"));

if(isset($_POST["clientfname"])){
	$firstn = $_POST["clientfname"];
	if($firstn != ""){
		mysql_query("UPDATE case_client SET cc_fname='$firstn' WHERE cc_id='$id'");
	}
}

if(isset($_POST["clientlname"])){
	$lastn = $_POST["clientlname"];
	if($lastn != ""){
		mysql_query("UPDATE case_client SET cc_lname='$lastn' WHERE cc_id='$id'");
	}
	
}

if(isset($_POST["clientcontact"])){
	$contact = $_POST["clientcontact"];
	if($contact != ""){
		mysql_query("UPDATE case_client SET cc_contact='$contact' WHERE cc_id='$id'");
	}
	
}

if(isset($_POST["clientaddress"])){
	$addr = $_POST["clientaddress"];
	if($addr != ""){
		mysql_query("UPDATE case_client SET cc_address='$addr' WHERE cc_id='$id'");
	}
	
}

if(isset($_POST["clientlocation"])){
	$loc = $_POST["clientlocation"];
	if($loc != null || $loc != ""){
		mysql_query("UPDATE case_client SET cc_location_id='$loc' WHERE cc_id='$id'");
	} 
}

$alert = 'show';
$class = urlencode('alert-success');
$alerthead = urlencode($fname .' ' .$lname);
$alertmsg = urlencode('has been updated!');
header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
?>