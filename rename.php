<?php include("database.php");
$docid = $_POST["docid"];
$docname = $_POST["rename"];
$ext = $_POST["ext"];

$ownerid = $_POST["uid"];
$utype = $_POST["utype"];
if($utype == "Administrator"){
	$dir = "uploads/docs/".$ownerid."ad/";
} else {
	$dir = "uploads/docs/".$ownerid."usr/";
}

//check if file exists
if(file_exists($dir.$docname.'__VERSION1.'.$ext)){
	$newname = $docname.'(1)__VERSION1.'.$ext;
	$disname = $docname.'(1).'.$ext;
} else{
	$newname = $docname.'__VERSION1.'.$ext;
	$disname = $docname.'.'.$ext;
}

//old file name
$oldnames = mysql_query("SELECT doc_name FROM document WHERE doc_id='$docid'");
if(mysql_num_rows($oldnames) > 0){
	list($oldname) = mysql_fetch_row($oldnames);
} else {
	$alert = 'show';
	$class = urlencode('alert-danger');
	$alertmsg = urlencode("There was an error renaming your file.");
	if($utype == "Administrator"){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
	} else {
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
	}
}

rename($dir.$oldname,$dir.$newname);
//max id
list($maxid) = mysql_fetch_row(mysql_query("SELECT MAX(doc_id)+1 FROM document"));
mysql_query("UPDATE document SET doc_id='$maxid', doc_parent_id='$maxid', doc_name='$disname', doc_path='".$dir.$newname."', doc_version=1 WHERE doc_id='$docid'") or die(mysql_error());
//parent id
list($parentid) = mysql_fetch_row(mysql_query("SELECT doc_parent_id FROM document WHERE doc_id='$docid'"));

//save to document history
mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$parentid', 'renamed the file', 'from <b>$oldname</b> to <b>$disname</b>.<br>File <b>$disname</b> has been saved as a separate file.', 'fa fa-pencil', now(), '$ownerid')") or die(mysql_error());
mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_date_modified, doc_modified_by_id) VALUES('$maxid', 'created the file', now(), '$ownerid')") or die(mysql_error());

$alert = 'show';
$class = urlencode('alert-success');
$alertmsg = urlencode("File has been renamed to <b>$disname</b>.");
if($utype == "Administrator"){
	header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
} else {
	header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
}
?>