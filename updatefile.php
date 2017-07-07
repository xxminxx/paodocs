<?php include("database.php");
$utype = $_POST["utype"];
$id = $_POST["docid"];
$userid = $_POST["uid"];

if(isset($_POST["docmat"])){ //associated matter
	$mat = $_POST["docmat"]; // new matter
	$matid = mysql_query("SELECT cm_id FROM case_matter WHERE cm_name='$mat'");
	if(mysql_num_rows($matid) <= 0){
		$alert = 'show';
		$class = urlencode('alert-danger');
		$alertmsg = urlencode('Can\'t find the matter <b>'.$mat.'</b>.');
		if($utype== "Administrator"){
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
		} else {
			header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
		}
		
	} else{
		//fetch old matter
		$oldmatter = mysql_query("SELECT m.cm_name FROM case_matter m, document d WHERE m.cm_id=d.doc_matter_tailored_id AND d.doc_matter_tailored_id='$matid'") or die(mysql_error());
		
		//update document
		mysql_query("UPDATE document SET doc_matter_tailored_id='$matid', doc_date_modified=now() WHERE doc_id='$id'") or die(mysql_error());
		
		//save to document history
		if(mysql_num_rows($oldmatter) > 0){
			list($oldmat) = mysql_fetch_row($oldmatter);
			mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$id', 'edited the file', 'with matter associated, from <b>$oldmat</b> to <b>$mat</b>', 'fa fa-edit', now(), '$userid')") or die(mysql_error());
			
		} else {
			mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$id', 'edited the file', 'with matter associated set to <b>$mat</b>', 'fa fa-edit', now(), '$userid')") or die(mysql_error());
		}
		
	
		
		
		$alert = 'show';
		$class = urlencode('alert-success');
		$alertmsg = urlencode('File has been updated!');
		if($utype== "Administrator"){
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
		} else {
			header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
		}
		
	}
	
}

if(isset($_POST["doccat"])){ //category
	$catid = $_POST["doccat"];
	list($cat) = mysql_fetch_row(mysql_query("SELECT cat_name FROM document_category WHERE cat_id='$catid'"));
	
	if(isset($_POST["doctype"])){ //template document
		$temp = $_POST["doctype"];
		
		//fetch old category
		$oldcategory = mysql_query("SELECT doc_cat FROM document_template WHERE doc_id='$id'") or die(mysql_error());
		
		//update document
		mysql_query("UPDATE document_template SET doc_cat='$cat' WHERE doc_id='$id'") or die(mysql_query());
		
		//save to document history
		if(mysql_num_rows($oldcategory) <= 0){
			mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$id', 'edited the file', 'category set to <b>$cat</b>', 'fa fa-edit', now(), '$userid')") or die(mysql_error());
		} else {
			list($oldcat) = mysql_fetch_row($oldcategory);
			mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$id', 'edited the file', 'category from <b>$oldcat</b> to <b>$cat</b>', 'fa fa-edit', now(), '$userid')") or die(mysql_error());
		}
		
			$alert = 'show';
			$class = urlencode('alert-success');
			$alertmsg = urlencode('File has been updated!');
			if($utype== "Administrator"){
				header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
			} else {
				header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#template_uploaded");
			}
			

		
	} else { //ordinary doc
		//fetch old category
		$oldcategory = mysql_query("SELECT doc_cat FROM document WHERE doc_id='$id'") or die(mysql_error());
		
		//update document
		mysql_query("UPDATE document SET doc_cat='$cat', doc_date_modified=now() WHERE doc_id='$id'");
		
		//save to document history
		if(mysql_num_rows($oldcategory) <= 0){
			mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$id', 'edited the file', 'with category set to <b>$cat</b>', 'fa fa-edit', now(), '$userid')") or die(mysql_error());
		} else {
			list($oldcat) = mysql_fetch_row($oldcategory);
			mysql_query("INSERT INTO document_history(doc_id, doc_activity, doc_activity_details, doc_activity_fa_icon, doc_date_modified, doc_modified_by_id) VALUES('$id', 'edited the file', 'with category, from <b>$oldcat</b> to <b>$cat</b>', 'fa fa-edit', now(), '$userid')") or die(mysql_error());
		}
		
			$alert = 'show';
			$class = urlencode('alert-success');
			$alertmsg = urlencode('File has been updated!');
			if($utype== "Administrator"){
				header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
			} else {
				header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#mydocs");
			}
			

	}
}

?>