<?php $id = $_POST["val"];
include("database.php");

list($f, $l) = mysql_fetch_row(mysql_query("SELECT cc_fname, cc_lname FROM case_client WHERE cc_id='$id'"));

mysql_query("DELETE FROM case_client WHERE cc_id='$id'");

$alert = 'show';
$class = urlencode('alert-warning');
$alertmsg = urlencode("Client <b>" .$f ." " .$l ."</b> has been removed from the record.");
header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters_client");
?>