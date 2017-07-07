<?php $type = $_POST["rpttype"];
include("database.php");
header("Content-Type: text/csv; charset=utf-8");

//CSV FOR CLIENT ACTIVITY PER DATE
if($type == "dailyclientact"){
	$filename = $_POST["filename"];
	header("Content-Disposition: attachment; filename=".$filename .".csv");
	
	$output = fopen("php://output", "w");
	
	$date = $_POST["datedailyreport"];
    fputcsv($output, array("($date)", "", "", "", ""));
    fputcsv($output, array("First Name", "Last Name", "Purpose", "Address", "", "Contact Number", "Log Time"));
	
	
	$rows = mysql_query("SELECT log_client_fname, log_client_lname, log_purpose, log_address, log_location, log_client_contact, log_timein FROM client_log WHERE log_date=DATE_FORMAT(STR_TO_DATE('$date', '%m/%d/%Y'), '%Y-%m-%d')");
	while($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);	

	exit();
	
//CSV FOR CLIENT ACTIVITY PER MONTH	
} else if($type == "monthlyclientact"){
	$filename = $_POST["filename"];
	header("Content-Disposition: attachment; filename=".$filename .".csv");
	
	$output = fopen("php://output", "w");
	
	$my = $_POST["datemonthlyreport"];
	$date = date_format(date_create($my .'-01'), "F Y");
    fputcsv($output, array("($date)", "", "", "", ""));
    fputcsv($output, array("First Name", "Last Name", "Purpose", "Address", "", "Contact Number", "Log Time", "Date"));
	
	$rows = mysql_query("SELECT log_client_fname, log_client_lname, log_purpose, log_address, log_location, log_client_contact, log_timein, log_date FROM client_log WHERE log_date BETWEEN '".$my ."-01' AND '".$my ."-31' ORDER BY log_date");
	while($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);	

	exit();
	
//CSV FOR CLIENT ACTIVITY PER YEAR	
} else if($type == "annualclientact"){
	$filename = $_POST["filename"];
	header("Content-Disposition: attachment; filename=".$filename .".csv");
	
	$output = fopen("php://output", "w");
	
	$year = $_POST["yearannualreport"];
	fputcsv($output, array("($year)", "", "", "", ""));
    fputcsv($output, array("First Name", "Last Name", "Purpose", "Address", "", "Contact Number", "Log Time", "Date"));
	
	$rows = mysql_query("SELECT log_client_fname, log_client_lname, log_purpose, log_address, log_location, log_client_contact, log_timein, log_date FROM client_log WHERE log_date BETWEEN '".$year ."-01-01' AND '".$year ."-12-31' ORDER BY log_date");
	while($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);	

	exit();

//CSV FOR MATTER REPORT	
} else if($type == "allmatreport"){
	$filename = $_POST["filename"];
	header("Content-Disposition: attachment; filename=".$filename .".csv");
	
	$output = fopen("php://output", "w");
	
	fputcsv($output, array("Matter Name", "Description", "Status", "Date", "Responsible Attorney", "Originating Attorney"));
	
	$rows = mysql_query("SELECT cm_name, cm_description, CASE cm_status WHEN 'New' THEN cm_date_created WHEN 'Pending' THEN cm_date_pending ELSE cm_date_dismissed END AS date, cm_resp_atty, cm_orig_atty FROM case_matter");
	while($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);	
	exit();
	
//CSV FOR MATTERS BY STATUS	
} else if($type == "matbystatus"){
	$filename = $_POST["filename"];
	header("Content-Disposition: attachment; filename=".$filename .".csv");
	
	$output = fopen("php://output", "w");
	fputcsv($output, array("Matter Name", "Description", "Date", "Responsible Attorney", "Originating Attorney, Status"));
	
	$new = mysql_query("SELECT cm_name, cm_description, cm_date_created, cm_resp_atty, cm_orig_atty, cm_status FROM case_matter WHERE cm_status='New'");
	while($n = mysql_fetch_assoc($new)) fputcsv($output, $n);	

	$pending = mysql_query("SELECT cm_name, cm_description, cm_date_pending, cm_resp_atty, cm_orig_atty, cm_status FROM case_matter WHERE cm_status='Pending'");
	while($p = mysql_fetch_assoc($pending)) fputcsv($output, $p);	
	
	$dismissed = mysql_query("SELECT cm_name, cm_description, cm_date_dismissed, cm_resp_atty, cm_orig_atty, cm_status FROM case_matter WHERE cm_status='Dismissed'");
	while($d = mysql_fetch_assoc($dismissed)) fputcsv($output, $d);	
	exit();

//CSV FOR CLIENTS WITH ONGOING CASES	
} else if($type == "ongoingcases"){
	$filename = $_POST["filename"];
	header("Content-Disposition: attachment; filename=".$filename .".csv");
	
	$output = fopen("php://output", "w");
	fputcsv($output, array("First Name", "Last Name", "Case Number", "Description", "Open Date","Responsible Attorney"));
	
	$rows = mysql_query("SELECT c.cc_fname, c.cc_lname, m.cm_name, m.cm_description, m.cm_date_created, m.cm_resp_atty FROM case_client c, case_matter m WHERE c.cc_matter_id=m.cm_id AND m.cm_status='New' OR m.cm_status='Pending'");
	while($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);	

	exit();

//CSV FOR MATTER BY RESPONSIBLE ATTORNEY	
} else if($type == "matbyatty"){
	$filename = $_POST["filename"];
	header("Content-Disposition: attachment; filename=".$filename .".csv");
	
	$output = fopen("php://output", "w");
	fputcsv($output, array("Matter Number", "Description", "Status", "Date", "Originating Attorney","Responsible Attorney"));

	$respattys = mysql_query("SELECT cm_resp_atty AS atty FROM case_matter");
	while($atty = mysql_fetch_assoc($respattys)){
		$at = $atty["atty"];
		$rows = mysql_query("SELECT cm_name, cm_description, cm_status, CASE cm_status WHEN 'New' THEN cm_date_created WHEN 'Pending' THEN cm_date_pending ELSE cm_date_dismissed END AS date, cm_orig_atty, cm_resp_atty FROM case_matter WHERE cm_resp_atty='$at'");
	    while($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);	
	}
	
	exit();
}

?>