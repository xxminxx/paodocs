<?php include("database.php");
$id = $_POST["docid"];
$utype = $_POST["utype"];

$docpath = mysql_query("SELECT doc_path FROM document_template WHERE doc_id='$id'");
if(mysql_num_rows($docpath) > 0){
	list($path) = mysql_fetch_row($docpath);
	unlink("$path");
	mysql_query("DELETE FROM document_template WHERE doc_id='$id'");
	
	$alert = 'show';
	$class = urlencode('alert-success');
	$alertmsg = urlencode('File has been deleted.');
	if($utype == "Administrator"){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
	} else {
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
	}
	
} else {
	$alert = 'show';
	$class = urlencode('alert-danger');
	$alertmsg = urlencode('There was an error reading the file.');
	if($utype == "Administrator"){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
	} else {
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
	}
	
	
}


?>