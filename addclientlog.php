<?php include("database.php");
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$address = $_POST["address"];
$location = $_POST["location"];
$contact = $_POST["contact"];
$purpose = $_POST["purpose"];
$timein = $_POST["timein"];

//convert string time to time format
$date = DateTime::createFromFormat('H:i A', $timein);
$time = $date->format('H:i:s');

//get location name
list($area) = mysql_fetch_row(mysql_query("SELECT des_name FROM designation WHERE des_id='$location'"));

//save info
mysql_query("INSERT INTO client_log(log_client_fname, log_client_lname, log_address, log_location, log_client_contact, log_purpose, log_timein, log_date) " 
."VALUES('$fname', '$lname', '$address', '$area', '$contact', '$purpose', '$time', curdate())");
header("Location: clientlog.php#submit");
?>