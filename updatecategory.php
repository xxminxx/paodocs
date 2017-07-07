<?php
include("database.php");
$cat = $_POST["dcategory"];
$opt = $_POST["doption"];

if ($opt == 1){
	//ADD category
	mysql_query("INSERT INTO document_category(cat_name) VALUES('$cat')");
	$alert = 'show';
	$class = urlencode('alert-success');
	$alertmsg = urlencode('Category <b>'.$cat.'</b> has been added!');
	header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents_category");
	
	
} else if($opt == 2){
	//EDIT category
	$new = $_POST["newname"];
	mysql_query("UPDATE document_category SET cat_name='$new' WHERE cat_name='$cat'");
	
	$alert = 'show';
	$class = urlencode('alert-success');
	$alerthead = urlencode($cat);
	$alertmsg = urlencode('has been changed to '.$new);
	header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#documents_category");
	
} else if($opt == 3){
	//DELETE category
	mysql_query("DELETE FROM document_category WHERE cat_name='$cat'");
	$alert = 'show';
	$class = urlencode('alert-warning');
	$alertmsg = urlencode('Category <b>'.$cat.'</b> has been deleted!');
	header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#documents_category");
}
?>