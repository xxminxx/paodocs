<?php include("database.php");
$utype = $_POST["utype"];
$action = $_POST["action"];
	$casename = $_POST["casename"];

	echo $utype ."<br/>".$action ."<br/>" .$casename ."<br><br><br>";
	
//change case status	
if($action == "editstatus"){
	$status = $date = "";
	if(isset($_POST["casestatus"])){
		$status = $_POST["casestatus"];
	}
	
	if(isset($_POST["statusdate"])){
		$date = $_POST["statusdate"];
	}
	
	echo $status ."<br>" .$date ."<br><br>";
	if ($status == "Pending"){
		mysql_query("UPDATE case_matter SET cm_status='$status', cm_date_pending=DATE_FORMAT(STR_TO_DATE('$date', '%m/%d/%Y'), '%Y-%m-%d') WHERE cm_name='$casename'");
	} else if ($status == "Dismissed"){
		mysql_query("UPDATE case_matter SET cm_status='$status', cm_date_dismissed=DATE_FORMAT(STR_TO_DATE('$date', '%m/%d/%Y'), '%Y-%m-%d') WHERE cm_name='$casename'");
	} else {
		mysql_query("UPDATE case_matter SET cm_status='$status' WHERE cm_name='$casename'");
	}
    
	$alert = 'show';
	$class = urlencode('alert-success');
	$alerthead = urlencode($casename);
	$alertmsg = urlencode('status has been updated!');
	if($utype == "Administrator"){
		header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
	} else{
		header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
	}


//edit matter	
} else if($action == "editmatter"){
	//change mattername
	if(isset($_POST["mattername"])){ 
		$newname = $_POST["mattername"];
		
		echo $newname . "<br><br>";
		list($existing) = mysql_query("SELECT cm_name FROM case_matter WHERE cm_name='$newname'");
		if(mysql_num_rows($existing) > 0){
			$alert = 'show';
			$class = urlencode('alert-danger');
			$alerthead = urlencode($newname);
			$alertmsg = urlencode(' already exists.');
			if($utype == "Administrator"){
				header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
			} else{
				header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
			}
		}
		else {
			$aname = str_split($newname, 2);
			$head = $aname[0];
			echo $head ."<br><br>";
			if($head=="CV" || $head=="cv" || $head=="CR" || $head=="cr"){ 
				mysql_query("UPDATE case_matter SET cm_name='$newname' WHERE cm_name='$casename'");
				
				$alert = 'show';
				$class = urlencode('alert-success');
				$alertmsg = urlencode('Matter name has been updated!');
				if($utype == "Administrator"){
					header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters");
				} else{
				    header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters");
				}
			} else {
				$alert = 'show';
				$class = urlencode('alert-danger');
				$alerthead = urlencode('Invalid matter name!');
				$alertmsg = urlencode(' Try again with the correct format');
				if($utype == "Administrator"){
					header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
				} else{
					header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
				}
			}
		}
	}

	//change description
	if(isset($_POST["casedesc"])){
		$des = $_POST["casedesc"];
		
		echo $des ."<br><br>";
		mysql_query("UPDATE case_matter SET cm_description='$des' WHERE cm_name='$casename'");
		
		$alert = 'show';
		$class = urlencode('alert-success');
		$alertmsg = urlencode('Matter description has been updated!');
		if($utype == "Administrator"){
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters");
		} else{
			header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters");
		}
	}
	
	//change responsible attorney
	if(isset($_POST["caseatty"])){
		$attyname = $_POST["caseatty"];
		
		echo $attyname ."<br><br>";
		mysql_query("UPDATE case_matter SET cm_resp_atty='$attyname' WHERE cm_name='$casename'");
		
		$alert = 'show';
		$class = urlencode('alert-success');
		$alertmsg = urlencode('Responsible attorney for <b>'.$casename .'</b> has been updated!');
		if($utype == "Administrator"){
			header("Location: admin_home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters");
		} else{
			header("Location: home.php?alert=$alert&class=$class&alertmsg=$alertmsg#matters");
		}
	}
	
	
	
//delete matter	
}else if($action == "delmatter"){
	list($caseid) = mysql_fetch_row(mysql_query("SELECT cm_id FROM case_matter WHERE cm_name='$casename'"));
	echo $caseid;
	mysql_query("DELETE FROM case_matter WHERE cm_name='$casename'");
	mysql_query("UPDATE case_client SET cc_matter_id=null WHERE cc_matter_id='$caseid'");
	
	$alert = 'show';
	$class = urlencode('alert-warning');
	$alerthead = urlencode($casename);
	$alertmsg = urlencode(' has been deleted.');
	if($utype == "Administrator"){
		header("Location: admin_home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
	} else{
		header("Location: home.php?alert=$alert&class=$class&alerthead=$alerthead&alertmsg=$alertmsg#matters");
	}
	
}?>