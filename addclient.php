<?php $utype = $_POST["acctype"];
$fullname = $_POST["nameclient"];
include("database.php");

$name = explode(", ", $fullname);
$lname = $name[0];
$fname = $name[1];
$refid = date('YmdHi');

//check if exists, return error message if yes
$sql = mysql_query("SELECT * FROM case_client WHERE cc_fname='$fname' AND cc_lname='$lname'");
if(mysql_num_rows($sql) > 0){
	$alert = 'show';
	$class = urlencode('alert-warning');
	$alertmsg = urlencode('Record for client <b>' .$fname .' ' .$lname .'</b> has already been entered.');
	
	if($utype== 'admin'){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters_client");
	} else if($utype == 'user'){
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters_client");
	}
	
// if not, create entry for client	
} else{
	list($add, $loc, $phone) = mysql_fetch_row(mysql_query("SELECT log_address, log_location, log_client_contact FROM client_log WHERE log_client_fname='$fname' AND log_client_lname='$lname'"));
	//get location i
	$location = mysql_query("SELECT des_id FROM designation WHERE des_name='$loc'") or die(mysql_error());
	if(mysql_num_rows($location) > 0){
		list($locid) = mysql_fetch_row($location);
	} else {
		$locid = 1;
	}
	

	
	mysql_query("INSERT INTO case_client VALUES('$refid', '$fname', '$lname', '$add', '$locid', '$phone', 0)");
	
	$alert = 'show';
	$class = urlencode('alert-success');
	$alertmsg = urlencode('A client has been added to record!');
	
	if($utype== 'admin'){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters_client");
	} else if($utype == 'user'){
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters_client");
	}
}


?>
