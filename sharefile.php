<?php include("database.php");
$sharedto = $_POST["shareduser"];
$sharedto = explode(", ",$sharedto);
$lname = $sharedto[0];
$fname = $sharedto[1];
$owner = $_POST["owner"];

//get owner id 
$getadminid = mysql_query("SELECT admin_id FROM admin WHERE admin_uname='$owner'");
$getuserid = mysql_query("SELECT user_id FROM user WHERE user_uname='$owner'");  
if(empty($getadminid)){
	list($sharerid) = mysql_fetch_row($getuserid);
} else {
	list($sharerid) = mysql_fetch_row($getadminid);
}

$type = $_POST["utype"];
$docid = $_POST["docid"];

$getadminname = mysql_query("SELECT a.admin_id, a.admin_uname FROM admin a, admin_profile p WHERE a.admin_id=p.admin_id AND p.admin_fname='$fname' AND p.admin_lname='$lname'");
$getusername = mysql_query("SELECT u.user_id, u.user_uname FROM user u, user_profile p WHERE u.user_id=p.user_id AND p.user_fname='$fname' AND p.user_lname='$lname'");
if(isset($_POST["shareall"]) && $_POST["shareall"] == "all"){
	mysql_query("UPDATE document SET doc_sharing_opt='2' WHERE doc_id='$docid'");
	
	//save to document history
	mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$docid', 'shared the file', 'with <b>everyone</b>', 'fa fa-group', now(), '$sharerid')");
	
	$alert = 'show';
	$class = urlencode('alert-success');
	$alertmsg = urlencode('File has been shared to <b>everyone</b>.');

	if($type == "Administrator"){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
	} else{
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
	}
} else {
	if(mysql_num_rows($getusername) > 0 || mysql_num_rows($getadminname) > 0){
		list($adminid, $adminname) = mysql_fetch_row($getadminname);
		list($userid, $username) = mysql_fetch_row($getusername);
		
		mysql_query("UPDATE document SET doc_sharing_opt='1' WHERE doc_id='$docid'") or die(mysql_error());
		//check if file already exists, update if true insert if not
		$fileexist = mysql_query("SELECT * FROM document_share WHERE ds_doc_id='$docid'");
		
		
		if(mysql_num_rows($fileexist) > 0){
			if(empty($adminname)){
				mysql_query("UPDATE document_share SET ds_doc_shared_user='$username' WHERE ds_doc_id='$docid'") or die(mysql_error());
				
				//save document history
				mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_date_modified, doc_modified_by_id, doc_modified_with_id) VALUES('$docid', 'shared the file', now(), '$sharerid', '$userid')");
				
			} else {
				mysql_query("UPDATE document_share SET ds_doc_shared_user='$adminname' WHERE ds_doc_id='$docid'") or die(mysql_error());
				
				//save document history
				mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_date_modified, doc_modified_by_id, doc_modified_with_id) VALUES('$docid', 'shared the file', now(), '$sharerid', '$adminid')");
			}
		
		
		}  else {
			if(empty($adminname)){
				mysql_query("INSERT INTO document_share(ds_doc_id,ds_doc_owner,ds_doc_shared_user) VALUES('$docid','$owner','$username')") or die(mysql_error());
				
				//save document history
				mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_date_modified, doc_modified_by_id, doc_modified_with_id) VALUES('$docid', 'shared the file', now(), '$sharerid', '$userid')");
				
			} else {
				mysql_query("INSERT INTO document_share(ds_doc_id,ds_doc_owner,ds_doc_shared_user) VALUES('$docid','$owner','$adminname')") or die(mysql_error());
				
				//save document history
				mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_date_modified, doc_modified_by_id, doc_modified_with_id) VALUES('$docid', 'shared the file', now(), '$sharerid', '$adminid')");
			}
			
		}
		
		$alert = 'show';
		$class = urlencode('alert-success');
		$alertmsg = urlencode('File has been shared to <b>'.$fname.' '.$lname.'</b>.');

		if($type == "Administrator"){
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
		} else{
			header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
		}
		
	} else{
		$alert = 'show';
		$class = urlencode('alert-danger');
		$alertmsg = urlencode('Can\'t find the user entered.');

		if($type == "Administrator"){
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
		} else{
			header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
		}
	}
}


?>