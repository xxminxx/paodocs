<?php include("database.php");
$do = $_POST["action"];

$view = $_POST["view"];
if(isset($_POST["docu"])){
	$docu = $_POST["docu"];
} else {
	$docu = "own";
}
if(isset($_POST["page"])){
	$page = $_POST["page"];
} else {
	$page = "1";
}

//ADD TAG
if($do == "add"){
	$tag = $_POST["tag"];
	$docid = $_POST["docid"];
	mysql_query("INSERT INTO document_tags(tag_name, tag_doc_id) VALUES('$tag', '$docid')") or die(mysql_error());
	
	if($view == "admin"){
		header("Location: admin_home.php?docu=$docu&page=$page#mydocs");
	} else {
		header("Location: home.php?docu=$docu&page=$page#mydocs");
	}
}
?>