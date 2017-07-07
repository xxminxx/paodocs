<?php include("database.php");
$docid = $_POST["docid"];
$docname = $_POST["renametemp"];
$ext = $_POST["ext"];
$newname = $docname.".".$ext;

$ownerid = $_POST["uid"];
$utype = $_POST["utype"];
$dir = "uploads/templates/";


$oldnames = mysql_query("SELECT doc_name FROM document_template WHERE doc_id='$docid'");
if(mysql_num_rows($oldnames) > 0){
	list($oldname) = mysql_fetch_row($oldnames);
} else {
	$alert = 'show';
	$class = urlencode('alert-danger');
	$alertmsg = urlencode("There was an error renaming your file template.");
	if($utype == "Administrator"){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
	} else {
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
	}
}
echo $dir.$oldname." ".$dir.$newname;
rename($dir.$oldname,$dir.$newname);
mysql_query("UPDATE document_template SET doc_name='$newname', doc_path='".$dir.$newname."' WHERE doc_id='$docid'") or die(mysql_error());

//save to document history
mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$docid', 'renamed the file', 'from <b>$oldname</b> to <b>$newname</b>', 'fa fa-pencil', now(), '$ownerid')") or die(mysql_error());


$alert = 'show';
$class = urlencode('alert-success');
$alertmsg = urlencode("Template has been renamed to <b>$newname</b>.");
if($utype == "Administrator"){
	header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
} else {
	header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
}
?>