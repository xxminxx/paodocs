<?php
include("database.php");

if(isset($_GET["uid"])){
	$uid = $_GET["uid"];
	
	$dir = "uploads/docs/".$uid."usr/";
	$files = array_diff(scandir($dir), array('.','..')); 
	foreach ($files as $file) { 
	  (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
	  mysql_query("DELETE FROM document WHERE doc_path='$dir/$file'") or die(mysql_error());
	} 
	rmdir($dir); 
	
	
	mysql_query("DELETE u, p FROM user u, user_profile p WHERE p.user_id=u.user_id AND u.user_id='$uid'") or die(mysql_error());
	
	$alert = 'show';
	$class = urlencode('alert-success');
	$alerthead = urlencode('Success!');
	$alertmsg = urlencode('Account deleted.');
	header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#settings");
	
} else{
	//get unverify accounts who exceeded max valid date of 3
	$unverify = mysql_query("SELECT user_id FROM user_profile WHERE user_account_status <= 0 AND DATE_ADD(date_created, INTERVAL 3 DAY)<= curdate()");
	while($del = mysql_fetch_array($unverify)){
		$id = $del["user_id"];
		
		$dir = "uploads/docs/".$uid."usr/";
		$files = array_diff(scandir($dir), array('.','..')); 
		foreach ($files as $file) { 
		  (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
		  mysql_query("DELETE FROM document WHERE doc_path='$dir/$file'") or die(mysql_error());
		} 
		rmdir($dir); 
		
		mysql_query("DELETE FROM user u JOIN user_profile p WHERE u.user_id = p.user_id AND u.user_id='$id'") or die(mysql_error());

	}
}

?>