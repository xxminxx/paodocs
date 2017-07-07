<?php include("database.php");
$casetype = $_POST["casetype"];
$casenum = $_POST["casenumber"];
$casename = $casetype ."-" .$casenum; //case name

$des = $_POST["casedesc"];          //description

$clientname = $_POST["clientname"];
$name = explode(", ", $clientname);
$lname = $name[0];                  //client first name
$fname = $name[1];                  //client last name
$add = "";
$loc = "";
$phone ="";
$refid = date('YmdHi');

//check if client exists
$chkclient = mysql_query("SELECT log_address, log_location, log_client_contact FROM client_log WHERE log_client_fname='$fname' AND log_client_lname='$lname'") or die(mysql_error());
if(mysql_num_rows($chkclient) > 0){
	list($add, $loc, $phone) = mysql_fetch_row($chkclient);
} else {
	$alert = 'show';
	$class = urlencode('alert-danger');
	$alertmsg = urlencode("Client is not registered on logbook.");
	header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters_new");
}

//check if matter number already exists
$count = mysql_query("SELECT * FROM case_matter WHERE cm_name='$casename'") or die(mysql_error());
if(mysql_num_rows($count) > 0){
	$al = 'show';
	$class = urlencode('alert-danger');
	$alertmessage = urlencode("Matter number (<b>".$casename."</b>) already exists.");
	header("Location: home.php?alert=$al&class=$class&alertmsg=$alertmessage#matters_new");
	exit();
} 

$opendate = $_POST["opendate"];     //case open date

if(isset($_POST["closedate"])){
	$closedate = $_POST["closedate"];
} else{ $closedate = "";}
if(isset($_POST["pendingdatedate"])){
	$pendingdate = $_POST["pendingdate"];
} else{ $pendingdate = "";}

$status = $_POST["matterstatus"];   //status
$atty = $_POST["matterresponsibleatty"];      //responsible attorney


mysql_query("INSERT INTO case_matter(cm_name, cm_number, cm_description, cm_date_created, cm_date_dismissed, cm_date_pending, cm_status, cm_resp_atty, cm_orig_atty) VALUES('$casename', '$casenum', '$des', DATE_FORMAT(STR_TO_DATE('$opendate', '%m/%d/%Y'), '%Y-%m-%d'), DATE_FORMAT(STR_TO_DATE('$closedate', '%m/%d/%Y'), '%Y-%m-%d'), DATE_FORMAT(STR_TO_DATE('$pendingdate', '%m/%d/%Y'), '%Y-%m-%d'), '$status', '$atty', '$atty')");

//get case matter id
list($id) = mysql_fetch_row(mysql_query("SELECT cm_id FROM case_matter WHERE cm_name='$casename'"));

//assign matter to client
    mysql_query("INSERT INTO case_client VALUES('$refid', '$fname', '$lname', '$add', '$loc', '$phone', '$id')");


$alert = 'show';
$class = urlencode('alert-success');
$alerthead = urlencode($casename);
$alertmsg = urlencode('has been created!');
header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
?>