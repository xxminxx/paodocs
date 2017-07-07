<?php include("database.php");
$id = $_POST["docid"];
$utype = $_POST["utype"];

if(isset($_POST["delallver"]) && $_POST["delallver"]=="deleteall"){
	list($parentid) = mysql_fetch_row(mysql_query("SELECT doc_parent_id FROM document WHERE doc_id='$id'"));
	$docpath = mysql_query("SELECT doc_path FROM document WHERE doc_id='$parentid'");
	if(mysql_num_rows($docpath) > 0){
		while($path = mysql_fetch_array($docpath)){
			unlink("$path");
	    	mysql_query("DELETE FROM document WHERE doc_parent_id='$parentid'");
			
			$alert = 'show';
			$class = urlencode('alert-success');
			$alertmsg = urlencode('File has been deleted.');
			if($utype == "Administrator"){
				header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
			} else {
				header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
			}
		}
		
	} else {
		$alert = 'show';
		$class = urlencode('alert-danger');
		$alertmsg = urlencode('There was an error reading the file.');
		if($utype == "Administrator"){
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
		} else {
			header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
		}
		
		
	}
	
} else{
	$docpath = mysql_query("SELECT doc_path FROM document WHERE doc_id='$id'");
	if(mysql_num_rows($docpath) > 0){
		list($path) = mysql_fetch_row($docpath);
		unlink("$path");
		mysql_query("DELETE FROM document WHERE doc_id='$id'");
		
		$alert = 'show';
		$class = urlencode('alert-success');
		$alertmsg = urlencode('File has been deleted.');
		if($utype == "Administrator"){
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
		} else {
			header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
		}
		
	} else {
		$alert = 'show';
		$class = urlencode('alert-danger');
		$alertmsg = urlencode('There was an error reading the file.');
		if($utype == "Administrator"){
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
		} else {
			header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents");
		}
		
		
	}

}


?>