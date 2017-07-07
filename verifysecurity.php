<?php
$ans = $_POST["answer"];
$user = $_POST["user"];
$question = $_POST["question"];
include("database.php");


list($corrans) = mysql_fetch_row(mysql_query("SELECT sq_answer FROM security_questions WHERE sq_question='$question'"));
if($ans == $corrans) {
	header("Location: resetpassword.php?u=$user");
} else {
	$error=urlencode("Wrong!");
	header("Location: securityquestion.php?user=$user&question=$question&answeris=$error");
}
?>