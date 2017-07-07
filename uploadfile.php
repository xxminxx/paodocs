<?php $utype = $_POST["utype"];
if(isset($_POST["doctype"])){
	if($_POST["doctype"] == "mydocs"){
		if($utype == "Administrator"){
			$udir = $_POST["uid"]."ad";
			$dir = "uploads/docs/$udir/"; //folder ni admin
		} else{
			$udir = $_POST["uid"]."usr";
			$dir = "uploads/docs/$udir/"; //folder ng users
		}
	} else {
		$dir = "uploads/templates/";
	}
} else {
	$alert = 'show';
	$class = urlencode('alert-danger');
	$alertmsg = urlencode('The kind of document to be uploaded is not selected.');
	if($utype == "Administrator"){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
	} else {
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
	}
}

$file = $dir . basename($_FILES["fileupload"]["name"]);
$fileType = pathinfo($file,PATHINFO_EXTENSION);
$fileExt = array("doc", "docx", "xls", "xlsx", "pdf", "csv", "tiff", "gif", "jpg", "jpeg", "png");	

//check file size
if($_FILES["fileupload"]["size"] > 25000000) {	// 25MB = 25000000Bytes 
	$alert = 'show';
	$class = urlencode('alert-danger');
	$alertmsg = urlencode('Your file exceeded our uploading capacity. We can only administer up to 25MB.');
	if($utype == "Administrator"){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
	} else {
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
	}
	
} 
//check file extension
// foreach($fileExt as $ext) {
	// if($fileType == $ext) {
		// $alert = 'show';
		// $class = urlencode('alert-danger');
		// $alertmsg = urlencode('Your file is not a valid document file.');
		// if($utype == "Administrator"){
			// header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
		// } else {
			// header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
		// }
	// }
	
// }


include("database.php");
$filename = $_FILES["fileupload"]["name"];
$filesize = $_FILES["fileupload"]["size"];
$filetype = $_FILES["fileupload"]["type"];
$author = $_POST["uname"];
$cat = $_POST["doccat"];

//get author id
$adminid = mysql_query("SELECT admin_id FROM admin WHERE admin_uname='$author'");
$userid = mysql_query("SELECT user_id FROM user WHERE user_uname='$author'");
if(empty($adminid)){
	list($authorid) = mysql_fetch_row($userid);
} else {
	list($authorid) = mysql_fetch_row($adminid);
}

list($category) = mysql_fetch_row(mysql_query("SELECT cat_name FROM document_category WHERE cat_id='$cat'"));

if($_POST["doctype"] == "mydocs"){
	if(isset($_POST["docmat"])){
		if($utype == "Administrator"){
			$names = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND a.admin_uname='$author'");
			if(mysql_num_rows($names) > 0){
				list($fname, $lname) = mysql_fetch_row($names);
				$name = $fname." ".$lname;
			}
		} else {
			$names = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.admin_id=u.admin_id AND u.admin_uname='$author'");
			if(mysql_num_rows($names) > 0){
				list($fname, $lname) = mysql_fetch_row($names);
				$name = $fname." ".$lname;
			}
		}
		
		
		
		
		//check if file exists
		$existfile = mysql_query("SELECT * FROM document WHERE doc_author='$author' AND doc_name='$filename'");
		if(mysql_num_rows($existfile) > 0){
			list($matid) = mysql_fetch_row(mysql_query("SELECT cm_id FROM case_matter WHERE cm_name='".$_POST["docmat"]."' AND cm_resp_atty='$name'"));
			//get old size
			list($oldfilesize) = mysql_fetch_row(mysql_query("SELECT doc_size FROM document WHERE doc_name='$filename' AND doc_author='$author' GROUP BY doc_name ORDER BY doc_version DESC"));
			//get parent id
			list($parentid) = mysql_fetch_row(mysql_query("SELECT doc_parent_id FROM document WHERE doc_name='$filename' AND doc_author='$author' GROUP BY doc_name ORDER BY doc_version DESC"));
			//get new version
			list($version) = mysql_fetch_row(mysql_query("SELECT MAX(doc_version)+1 FROM document  WHERE doc_name='$filename' AND doc_author='$author'"));
			//change name first before uploaded
			$tempname = explode(".", $_FILES["fileupload"]["name"]);
			$newfilename =  $tempname[0]."__VERSION".$version.".".end($tempname);
			//get date created
			list($initdate) = mysql_fetch_row(mysql_query("SELECT doc_date_created FROM document WHeRE doc_name='$filename' AND doc_author='$author' AND doc_version=1"));
			//get new doc id
			list($docid) = mysql_fetch_row(mysql_query("SELECT MAX(doc_id)+1 FROM document"));
			//get sharing option
			list($shareopt) = mysql_fetch_row(mysql_query("SELECT doc_sharing_opt FROM document WHERE doc_parent_id='$parentid' GROUP BY doc_name ORDER BY doc_version DESC"));

			
			//save to database
			mysql_query("INSERT INTO document VALUES('$docid', '$parentid', '$filename', '".$dir.$newfilename."','$filetype', '$filesize', '$author', '$initdate', now(), '$category', '$version', '$shareopt', '$matid')") or die("Line 116: ".mysql_error());
		    
			//save to folder
			move_uploaded_file($_FILES["fileupload"]["tmp_name"], $dir.$newfilename);	

			//save document history
			mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$parentid', 'uploaded a new version of the file', 'Version $version', 'fa fa-history', now(), '$authorid')") or die("Line 121: ".mysql_error());
			
			
		} else{ //if file does not exists
			list($matid) = mysql_fetch_row(mysql_query("SELECT cm_id FROM case_matter WHERE cm_name='".$_POST["docmat"]."' AND cm_resp_atty='$name'"));
			list($maxid) = mysql_fetch_row(mysql_query("SELECT MAX(doc_id)+1 FROM document"));
			//change name first before uploaded
			$tempname = explode(".", $_FILES["fileupload"]["name"]);
			$newfilename =  $tempname[0]."__VERSION1.".end($tempname);
			
			//save to database
			mysql_query("INSERT INTO document VALUES('$maxid', '$maxid', '$filename', '".$dir.$newfilename."', '$filetype', '$filesize', '$author', now(), now(), '$category', 1, 0, '$matid')") or die("Line 133: ".mysql_error());
			
			//save to folder
			move_uploaded_file($_FILES["fileupload"]["tmp_name"], $dir.$newfilename);	
			
			//save document history
			$getdocid = mysql_query("SELECT doc_parent_id FROM document WHERE doc_name='$filename' GROUP BY doc_name ORDER BY doc_version DESC");
			if(mysql_num_rows($getdocid) > 0){
				list($docid) = mysql_fetch_row($getdocid);
				mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_date_modified, doc_modified_by_id) VALUES('$docid', 'created the file', now(), '$authorid')") or die("Line 140: ".mysql_error());
			} else {
				if($utype == "Administrator"){
					header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
				} else {
					header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
				}
			}
		}

		
	//no doc matter available	
	} else {
		//check if file exists
		$existfile = mysql_query("SELECT * FROM document WHERE doc_author='$author' AND doc_name='$filename'");
		if(mysql_num_rows($existfile) > 0){
			//get old size
			list($oldfilesize) = mysql_fetch_row(mysql_query("SELECT doc_size FROM document WHERE doc_name='$filename' AND doc_author='$author' GROUP BY doc_name ORDER BY doc_version DESC"));
			//get parent id
			list($parentid) = mysql_fetch_row(mysql_query("SELECT doc_parent_id FROM document WHERE doc_name='$filename' AND doc_author='$author' GROUP BY doc_name ORDER BY doc_version DESC"));
			//get new version
			list($version) = mysql_fetch_row(mysql_query("SELECT MAX(doc_version)+1 FROM document  WHERE doc_name='$filename' AND doc_author='$author'"));
			//change name first before uploaded
			$tempname = explode(".", $_FILES["fileupload"]["name"]);
			$newfilename =  $tempname[0]."__VERSION".$version.".".end($tempname);
			//get date created
			list($initdate) = mysql_fetch_row(mysql_query("SELECT doc_date_created FROM document WHeRE doc_name='$filename' AND doc_author='$author' AND doc_version=1"));
			//get new doc id
			list($docid) = mysql_fetch_row(mysql_query("SELECT MAX(doc_id)+1 FROM document"));
			//get sharing option
			list($shareopt) = mysql_fetch_row(mysql_query("SELECT doc_sharing_opt FROM document WHERE doc_parent_id='$parentid' GROUP BY doc_name ORDER BY doc_version DESC"));

			
			//save to database
			mysql_query("INSERT INTO document VALUES($docid,$parentid, '$filename', '".$dir.$newfilename."','$filetype', '$filesize', '$author', '$initdate', now(), '$category', $version, $shareopt, 0)") or die("Line 174: ".mysql_error());
		    
			//save to folder
			move_uploaded_file($_FILES["fileupload"]["tmp_name"], $dir.$newfilename);	

			//save document history
			mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$parentid', 'uploaded a new version of the file', 'Version $version', 'fa fa-history', now(), '$authorid')") or die("Line 1eighty: ".mysql_error());
			
			
		} else{ //if file does not exists
			list($maxid) = mysql_fetch_row(mysql_query("SELECT MAX(doc_id)+1 FROM document"));
			//change name first before uploaded
			$tempname = explode(".", $_FILES["fileupload"]["name"]);
			$newfilename =  $tempname[0]."__VERSION1.".end($tempname);
			
			//save to database
			mysql_query("INSERT INTO document VALUES($maxid, $maxid, '$filename', '".$dir.$newfilename."', '$filetype', '$filesize', '$author', now(), now(), '$category', 1, 0, 0)") or die("Line 190: ".mysql_error());
			
			//save to folder
			move_uploaded_file($_FILES["fileupload"]["tmp_name"], $dir.$newfilename);	
			
			//save document history
			$getdocid = mysql_query("SELECT doc_parent_id FROM document WHERE doc_name='$filename' GROUP BY doc_name ORDER BY doc_version DESC");
			if(mysql_num_rows($getdocid) > 0){
				list($docid) = mysql_fetch_row($getdocid);
				mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_date_modified, doc_modified_by_id) VALUES('$docid', 'created the file', now(), '$authorid')") or die("Line 199: ".mysql_error());
			} else {
				if($utype == "Administrator"){
					header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
				} else {
					header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
				}
			}
		}
		
	}
	
	
} else {
	mysql_query("INSERT INTO document_template(doc_name, doc_path, doc_type, doc_size, doc_uploader, doc_date_uploaded, doc_cat) VALUES('$filename', '$filepath', '$filetype', '$filesize', '$author', now(), '$category')") or die("Line 213: ".mysql_error());
	
	//save document history
	$getdocid = mysql_query("SELECT doc_id FROM document_template WHERE doc_path='$filepath' OR doc_name='$filename'");
	if(mysql_num_rows($getdocid) > 0){
		list($docid) = mysql_fetch_row($getdocid);
		mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_date_modified, doc_modified_by_id) VALUES('$docid', 'uploaded the template', now(), '$authorid')") or die("Line 219: ".mysql_error());
	} else {
		if($utype == "Administrator"){
			header("Location: admin_home.php?#documents");
		} else {
			header("Location: home.php?alert=#documents");
		}
	}
}

$alert = 'show';
$class = urlencode('alert-success');
$alertmsg = urlencode('Your file has been uploaded.');
if($utype == "Administrator"){
	if($_POST["doctype"] == "mydocs"){
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents_list");
	} else {
		header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents_template");
	}
	
} else {
	if($_POST["doctype"] == "mydocs"){
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents_list");
	} else {
		header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents_template");
	}
}

?>


