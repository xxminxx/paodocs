<?php include("database.php");

if(isset($_POST['docid']) && isset($_POST["uname"])) {
$id = $_POST['docid'];
$uname = $_POST["uname"];

//get user id //requester
$getadminid = mysql_query("SELECT admin_id FROM admin WHERE admin_uname='$uname'");
$getuserid = mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'");
if(mysql_num_rows($getadminid) > 0){
	list($selfid) = mysql_fetch_row($getadminid);
} else {
	list($selfid) = mysql_fetch_row($getuserid);
}

$activities = mysql_query("SELECT * FROM document_history WHERE doc_id='$id' ORDER BY doc_date_modified DESC") or die(mysql_error());

if(mysql_num_rows($activities) > 0){
	
	//fetch document activities
	while($act = mysql_fetch_assoc($activities)){
		//get owner name and picture
		$getadminname = mysql_query("SELECT admin_fname, admin_lname, admin_dp FROM admin_profile WHERE admin_id='".$act["doc_modified_by_id"]."'");
		$getusername = mysql_query("SELECT user_fname, user_lname, user_dp FROM user_profile WHERE user_id='".$act["doc_modified_by_id"]."'");
		if(mysql_num_rows($getadminname) > 0){
			list($f, $l, $dp) = mysql_fetch_row($getadminname);
			if($f == null || $f == "" || $l == null || $l == ""){
				echo "Administrator";
			} else {
				$ownername = $f." ".$l;
			}
			
		} else {
			list($f, $l, $dp) = mysql_fetch_row($getusername);
			if($f == null || $f == "" || $l == null || $l == ""){
				echo "User ".$selfid;
			} else {
				$ownername = $f." ".$l;
			}
		}	
		
		//get formatted date
		$date = strtotime($act["doc_date_modified"]);
	  
		if($date >= strtotime("today 6:00")){
			$reldate = "Today at ". date("g:i A", $date);
		} else if ($date >= strtotime("yesterday 6:00")){
			$reldate = "Yesterday at ". date("g:i A", $date);
		} else if ($date >= strtotime("-6 day 6:00")){
			$reldate = date("l \\a\\t g:i A", $date);
		} else {
			$reldate = date("F j, Y", $date);
		}
			

		//display
		if(substr($act["doc_activity"],0,strlen('shared')) === "shared"){ //if activity is sharing
			echo "<div class='media'>";
				echo "<div class='media-left'>";
					if(empty($dp)){
						echo "<img src='image/png/default.png' class='media-object circular-square' style='width:45px'>";
					} else {
						echo "<img src='image/png/".$dp.".png' class='media-object circular-square' style='width:45px'>";
					}
				echo "</div>";
				echo "<div class='media-body'>";
					if($selfid == $act["doc_modified_by_id"]){
						echo "<h5 class='media-heading'><b>You</b> ".$act["doc_activity"]." <br><small>".$reldate."</small></h5>";
					} else {
						echo "<h5 class='media-heading'><b>$ownername</b> ".$act["doc_activity"]." <br><small>".$reldate."</small></h5>";
					}
					
					if($act["doc_modified_with_id"] == null || $act["doc_modified_with_id"] == 0){
						echo "<p class='small'><i class='".$act["doc_activity_fa_icon"]."'></i> ".$act["doc_activity_details"]."</p>";
					} else {
						//get sharee name and picture
						$shareeid = $act["doc_modified_with_id"];
						$adminsharee = mysql_query("SELECT admin_fname, admin_lname, admin_dp FROM admin_profile WHERE admin_id='$shareeid'") or die(mysql_error());
						$usersharee = mysql_query("SELECT user_fname, user_lname, user_dp FROM user_profile WHERE user_id='$shareeid'") or die(mysql_error());
						if(mysql_num_rows($adminsharee) > 0){
							$sharee = mysql_fetch_assoc($adminsharee);
							if($shareeid == $selfid){
								echo "<p class='small'>with <b>you</b></p>";
							} else {
								echo "<p class='small'>with<br><img src='image/png/".$sharee["admin_dp"].".png' class='circular-square' style='width:25px'> <b>".$sharee["admin_fname"]." ".$sharee["admin_lname"]."</b></p>";
							}
							
						} else {
							$sharee = mysql_fetch_assoc($usersharee);
							if($shareeid == $selfid){
								echo "<p class='small'>with <b>you</b></p>";
							} else {
								echo "<p class='small'>with<br><img src='image/png/".$sharee["user_dp"].".png' class='circular-square' style='width:25px'> <b>".$sharee["user_fname"]." ".$sharee["user_lname"]."</b></p>";
							}
						}
						
						
					}
					
				echo "</div>";
			echo "</div><hr>";
		
		
		} else if(substr($act["doc_activity"],0,strlen('uploaded a new version')) === "uploaded a new version"){ //if activity is new version
			echo "<div class='media'>";
				echo "<div class='media-left'>";
					echo "<img src='image/png/".$dp.".png' class='media-object circular-square' style='width:45px'>";
				echo "</div>";
				echo "<div class='media-body'>";
					if($selfid == $act["doc_modified_by_id"]){
						echo "<h5 class='media-heading'><b>You</b> ".$act["doc_activity"]." <br><small>".$reldate."</small></h5>";
					} else {
						echo "<h5 class='media-heading'><b>$ownername</b> ".$act["doc_activity"]." <br><small>".$reldate."</small></h5>";
					}
					$tab = "&nbsp;&nbsp;&nbsp;";
					echo "<p> ".$act["doc_activity_details"]." <a href='#' class='btn btn-link  btn-lg text-muted' data-toggle='collapse' data-target='#versions' title='Manage versions'><i class='".$act["doc_activity_fa_icon"]."'></i></a></p>";
					echo "<div id='versions' class='collapse'>";
					//get current version
					$ver = explode(" ", $act["doc_activity_details"]);
					$versions = mysql_query("SELECT * FROM document WHERE doc_parent_id='$id' AND NOT doc_version='".end($ver)."' ORDER BY doc_version DESC") or die(mysql_error());
														
					if(mysql_num_rows($versions) > 0){
						while($version = mysql_fetch_assoc($versions)){
							//get userid
							$checkadmin = mysql_query("SELECT admin_id FROM admin WHERE admin_uname='".$version["doc_author"]."'") or die(mysql_error());
							$checkuser = mysql_query("SELECT user_id FROM user WHERE user_uname='".$version["doc_author"]."'") or die(mysql_error());
							if(mysql_num_rows($checkadmin) > 0){
								list($fldid) = mysql_fetch_row($checkadmin);
								$folder = $fldid."ad";
							} else {
								list($fldid) = mysql_fetch_row($checkuser);
								$folder = $fldid."usr";
							}
							
							//get basename and extension
							$basename = pathinfo($version["doc_path"], PATHINFO_FILENAME);
							$extension = pathinfo($version["doc_path"], PATHINFO_EXTENSION); 
							
							echo "<p class='small text-muted'><b>Version ".$version["doc_version"].$tab."<small><a href='download.php?folder=docs/$folder&filename=$basename"."."."$extension'>Download</a></b><br/>";
							
							echo "Modified: ".date("F d, Y", strtotime($version["doc_date_modified"]))."<br/>";
							
							if ($version["doc_size"] >= 1073741824){
								$size = number_format($version["doc_size"] / 1073741824, 2) . ' GB';
							}
							elseif ($version["doc_size"] >= 1048576){
								$size = number_format($version["doc_size"] / 1048576, 2) . ' MB';
							}
							elseif ($version["doc_size"] >= 1024){ 
								$size = number_format($version["doc_size"] / 1024, 2) . ' KB';
							}elseif ($version["doc_size"] > 1){
								$size = $version["doc_size"] . ' bytes';
							}elseif ($version["doc_size"] == 1){
								$size = $version["doc_size"] . ' byte';
							}else{
								$size = '0 bytes';
							}
								
							echo "Size: ".$size."</small></p>";
						}
					}
					echo "</div>";
				echo "</div>";
			echo "</div><hr>";
			
		} else { //if not
			echo "<div class='media'>";
				echo "<div class='media-left'>";
					echo "<img src='image/png/".$dp.".png' class='media-object circular-square' style='width:45px'>";
				echo "</div>";
				echo "<div class='media-body'>";
					if($selfid == $act["doc_modified_by_id"]){
						echo "<h5 class='media-heading'><b>You</b> ".$act["doc_activity"]." <br><small>".$reldate."</small></h5>";
					} else {
						echo "<h5 class='media-heading'><b>$ownername</b> ".$act["doc_activity"]." <br><small>".$reldate."</small></h5>";
					}
					echo "<p class='small'><i class='".$act["doc_activity_fa_icon"]."'></i> ".$act["doc_activity_details"]."</p>";
				echo "</div>";
			echo "</div>";
			if(substr($act["doc_activity"],0,strlen('created')) === "created"){
				echo "";
			} else {
				echo "<hr/>";
			}
		}
	}

} else {
	echo "<span class='text-danger'><b>Error!</b> Can't display document activities.</span>";
}


}
?>