<?php
   session_start();
   if(!isset($_SESSION['uname']) || trim($_SESSION['uname'])=='') {
	   header("Location: index.php");
   }
   else {
	   $uname = $_SESSION["uname"];
  
	   include("database.php");
	   list($utype) = mysql_fetch_row(mysql_query("SELECT user_type AS type FROM user WHERE user_uname='$uname'"));
   }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home - paoDocs</title>
		<link rel="ICON" type="image/png" href="image/icon.png" />
		
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/index.css" />
		<link rel="stylesheet" type="text/css" href="css/user-tab.css" />
		<link rel="stylesheet" type="text/css" href="css/datepicker.css" />
		<link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css"/>
		<link rel="stylesheet" type="text/css" href="css/agenda.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.min.css" />

		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		<script src="js/jquery.timepicker.min.js"></script>
		<style>
			.image-cropper{max-width:100px;height:auto;position:relative;overflow:hidden;} .rounded{display:block;margin:0 auto;height:100px;width:100px;-webkit-border-radius:50%;-moz-border-radius:50%;-ms-border-radius:50%;-o-border-radius:50%;border-radius:50%;background-size:cover;}
			.circular-square {border-radius: 50%;}
			.circular-landscape {display: inline-block; position: relative; width: 200px; height: 200px; overflow: hidden; border-radius: 50%;} .circular-landscape img { width: auto; height: 100%; margin-left: -50px;}
			.circular-portrait { position: relative; width: 200px; height: 200px; overflow: hidden; border-radius: 50%;} .circular--portrait img { width: 100%; height: auto;}
			.main-container {background-color:white; height:90%; box-shadow: 10px 10px 5px #ccc;}
			label > input{ /* HIDE RADIO */
			  visibility: hidden; /* Makes input not-clickable */
			  position: absolute; /* Remove input from document flow */
			}
			label > input + img{ /* IMAGE STYLES */
			  cursor:pointer;
			  border:2px solid transparent;
			}
			label > input:checked + img{ /* (RADIO CHECKED) IMAGE STYLES */
			  border:3px solid #9ADEA2;
			}
			a:link{color:#f8a137;} a:visited{color:#e69941;} a:hover{color:#3399cc;} a:focus{color:#f8a137;}
			
			.nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
				color: black;
				border-bottom: 3px solid #f9aa33;
				border-radius:0;
				background-color: transparent;
			}
			.black-link a:link{color:#333; text-decoration: underline;} .black-link a:visited{color:#333;} .black-link a:hover{color:#f6b247;} .black-link a:active{color:#333;}
			.popover-title {background-color: #333; color: #ffffff; font-weight:bold;}
			.popover-content {width:350px; overflow-y: auto; word-wrap: break-word;}
			.description {white-space: nowrap; width: 12em; overflow: hidden; text-overflow: ellipsis; }
			.pagination>li>a, .pagination>li>span {color:#f8a137;}
			.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {background-color: #f8a137;border-color:#f8a137;}
			.tooltip-inner {max-width: 100px;width: 100px; }
			
			.navbar{border-radius: 0;}
			.navbar-default { border-top: 3px solid #f8a137 !important; background-color: #eaedf1;border-color: #ccc;}
			.popover { max-width: none;}
			input::-webkit-calendar-picker-indicator {display: none;}
		</style>
	</head>
	
	<body onload="openspectab()">
		<!--NAV BAR-->
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!-- Alerts -->
				<?php if(isset($_GET['alert'])){?>
					<script>
					$(document).ready(function showAlert() {
						$("#navbaralert").alert();
						$("#navbaralert").fadeTo(4000, 500).slideUp(500, function(){
							$("#navbaralert").slideUp(500);});   
					});</script><?php
				} else{ ?>
					<script>$(document).ready(function hideAlert() {$("#navbaralert").hide(); });</script><?php
				}?>
				<div id="navbaralert" class="alert alert-dismissable <?php if(isset($_GET['class'])){ echo $_GET['class'];}?>" style="position:fixed; top: 0px; left: 0px;width: 100%;z-index:9999;">
				    <a class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong><?php if(isset($_GET['alerthead'])){ echo $_GET['alerthead'];}?></strong> 
					<?php if(isset($_GET['alertmsg'])){ echo $_GET['alertmsg'];}?>
				</div>
				
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a class="navbar-brand" href="home.php"><img src="image/logo-orange.png" height="40px"></a>
				  <a class="navbar-brand"><font style="color:#f9aa33; font-family:Debussy, Arial; font-size:24px;" >paoDocs</font><br/><small><small>Document Management System</small></small></a>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				  
				  <ul class="nav navbar-nav navbar-right">
					<?php $disname = mysql_query("SELECT p.user_id, p.user_fname AS fname, p.user_lname AS lname, p.user_pos AS pos, p.user_id AS id FROM user u, user_profile p WHERE u.user_id=p.user_id AND u.user_uname='$uname'");
					      $dispic = mysql_query("SELECT d.dis_path AS dp FROM display_picture d, user_profile p, user u WHERE d.dis_name=p.user_dp AND p.user_id=u.user_id AND u.user_uname='$uname'");
					      ?>
					
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle lead" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><!-- name-->
					  <?php list($dn_id, $dn_fname, $dn_lname) = mysql_fetch_row($disname);
						  if($dn_fname == null && $dn_lname == null) {
							  echo "User ".$dn_id;
						  } else {
							  if($utype == "Attorney"){
								echo "Atty. " .$dn_lname;
							  } else {echo $dn_lname;}
							  
						  } ?>
					  <span class="caret"></span></a>
					  <ul class="dropdown-menu" style="width: 250px !important;">
						<li class="disabled"><a href="#" >
						    <div class="row" style="cursor:url(image/tongue.gif),auto !important;">
								<div class="col-md-4"><img class="circular-square" src="
									<?php while($dp = mysql_fetch_array($dispic)) { 
											if($dp['dp']==null){
												echo 'image/png/default.png';} 
											else{
												echo $dp['dp'];} 
										  }?>" height="50px" width="50px">
								</div>
								<div class="col-md-8">
									<h4><?php if($dn_fname == null && $dn_lname == null) {
										  echo "User ".$dn_id;
									  } else {
										  echo $dn_fname." ".$dn_lname;
									  } ?>	
									<br/><small><?php echo $uname;?></small></h4>
								</div>
							</div>
						</a></li>
						<li class="divider"></li>
						<li class="dropdown-header"><small>Not you?</small></li>
						<li><a href="logout.php"><h5><span class="glyphicon glyphicon-log-out text-muted"></span> Log out</h5></a></li>
					  </ul>
					</li>
				  </ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
		
		
		<!-- CONTAINER-->		
		<div class="container">
			<ul class="nav nav-tabs">
				<li style="font-size:19px;" class="active"><a data-toggle="tab" href="#home"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
				<li style="font-size:19px;"><a data-toggle="tab" href="#matters"><span class="glyphicon glyphicon-briefcase"></span> Matters</a></li>
			    <li style="font-size:19px;"><a data-toggle="tab" href="#documents"><span class="glyphicon glyphicon-file"></span> Documents</a></li>
			    <li style="font-size:19px;"><a data-toggle="tab" href="#myaccount"><span class="glyphicon glyphicon-user"></span> Account</a></li>
			  <li style="font-size:19px;"><a data-toggle="tab" href="#settings"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
			</ul>

			<div class="tab-content">
<!--DASHBOARD-->					
				<div id="home" name="home" class="tab-pane fade in active">
					<div class="container main-container">
						<div class="row">
							<br/><br/>
							<div class="col-md-7">
								<div class="row">
									<?php if($utype == "Attorney"){ ?>
										<div class="col-sm-6">
											<p class="lead"><i class="fa fa-files-o"></i> Recent Files</p>
											<div class="panel panel-default" style="border-radius:0; border-bottom: 2px solid #ccc;height:150px; overflow:auto;">
												<div class="panel-body"><label>My Documents</label><br>
													<?php $myrecents = mysql_query("SELECT d.* FROM document d WHERE d.doc_author='$uname' AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) GROUP BY d.doc_name ORDER BY d.doc_date_modified DESC, d.doc_version DESC LIMIT 3");
													if(mysql_num_rows($myrecents) > 0){
														while($myrecent = mysql_fetch_assoc($myrecents)){
															if($myrecent["doc_type"] == "application/msword" || $myrecent["doc_type"] == "application/vnd.ms-word" || $myrecent["doc_type"] == "application/vnd.open" ){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($myrecent["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($myrecent["doc_type"] == "application/vnd.ms-excel" || $myrecent["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($myrecent["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$myrecent["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($myrecent["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															$dmtid = $myrecent["doc_matter_tailored_id"];
															list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
															
															
															if ($myrecent["doc_size"] >= 1073741824){
																$bytes = number_format($myrecent["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($myrecent["doc_size"] >= 1048576){
																$bytes = number_format($myrecent["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($myrecent["doc_size"] >= 1024){ 
																$bytes = number_format($myrecent["doc_size"] / 1024, 2) . ' KB';
															}elseif ($myrecent["doc_size"] > 1){
																$bytes = $myrecent["doc_size"] . ' bytes';
															}elseif ($myrecent["doc_size"] == 1){
																$bytes = $myrecent["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<p><i class="<?php echo $icon; ?> fa-lg text-muted"></i>
															<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$myrecent["doc_parent_id"]."'");
																echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$myrecent["doc_size"]." bytes)</b><br/>Owner: <b>Me</b><br/>Modified: <b>".date("F d, Y", strtotime($myrecent["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($myrecent["doc_date_created"]))."</b><br/>Version ".$myrecent["doc_version"]."<br/><br>Tags:<br/>";
																if(mysql_num_rows($tags) > 0){
																	while($tag = mysql_fetch_assoc($tags)){
																		echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																	}
																} else {
																	echo "";
																} ;?>">
																<?php echo $myrecent["doc_name"];?></a></span>
															</p><?php
														}
													} else {
														echo "<span class='text-muted'>No recent documents.</span>";
													} ?>
													
												</div>
											</div>
										</div>	
										<div class="col-sm-6">
											<p class="lead">&nbsp;</p>
											<div class="panel panel-default" style="border-radius:0; border-bottom: 2px solid #ccc;height:150px; overflow:auto;">
												<div class="panel-body"><label>Shared With Me</label><br>
													<?php $sharedrecents = mysql_query("SELECT d.* FROM document d, document_share s WHERE d.doc_id=s.ds_doc_id AND NOT s.ds_doc_owner='$uname' AND s.ds_doc_shared_user='$uname' AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) ORDER BY d.doc_date_modified DESC, d.doc_version DESC LIMIT 3");
													if(mysql_num_rows($sharedrecents) > 0){
														while($sharedrecent = mysql_fetch_assoc($sharedrecents)){
															if($sharedrecent["doc_type"] == "application/msword" || $sharedrecent["doc_type"] == "application/vnd.ms-word"  || $sharedrecent["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($sharedrecent["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($sharedrecent["doc_type"] == "application/vnd.ms-excel" || $sharedrecent["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($sharedrecent["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$sharedrecent["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($sharedrecent["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															
															if ($sharedrecent["doc_size"] >= 1073741824){
																$bytes = number_format($sharedrecent["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($sharedrecent["doc_size"] >= 1048576){
																$bytes = number_format($sharedrecent["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($sharedrecent["doc_size"] >= 1024){ 
																$bytes = number_format($sharedrecent["doc_size"] / 1024, 2) . ' KB';
															}elseif ($sharedrecent["doc_size"] > 1){
																$bytes = $sharedrecent["doc_size"] . ' bytes';
															}elseif ($sharedrecent["doc_size"] == 1){
																$bytes = $sharedrecent["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<p><i class="<?php echo $icon; ?> fa-lg text-muted"></i>
															<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$sharedrecent["doc_parent_id"]."'");
																
																$adowners = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND a.admin_uname='".$sharedrecent["doc_author"]."'");
																$owners = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='".$sharedrecent["doc_author"]."'");
																if(mysql_num_rows($owners) > 0 || mysql_num_rows($adowners) > 0){
																	if(empty($adowners)){
																		list($ownerfname, $ownerlname) = mysql_fetch_row($owners);
																		$owner = $ownerfname." ".$ownerlname;
																	} else {
																		list($ownerfname, $ownerlname) = mysql_fetch_row($adowners);
																		$owner = $ownerfname." ".$ownerlname;
																	}
																} else {
																	$owner = ""; }
																
																echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$sharedrecent["doc_size"]." bytes)</b><br/>Owner: <b>".$owner."</b><br/>Modified: <b>".date("F d, Y", strtotime($sharedrecent["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($sharedrecent["doc_date_created"]))."</b><br/>Version ".$sharedrecent["doc_version"]."<br/><br>Tags:<br/>";
																if(mysql_num_rows($tags) > 0){
																	while($tag = mysql_fetch_assoc($tags)){
																		echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																	}
																} else {
																	echo "";
																} ;?>">
																<?php echo $sharedrecent["doc_name"];?></a></span>
															</p><?php
														}
													} else {
														echo "<span class='text-muted'>No recent documents.</span>";
													} ?>
												</div>
											</div>
										</div>	<?php
									} else { ?>
										<div class="col-sm-12">
											<p class="lead"><i class="fa fa-files-o"></i> Recent Files</p>
											<div class="panel panel-default" style="border-radius:0; border-bottom: 2px solid #ccc;height:150px; overflow:auto;">
												<div class="panel-body"><label>Shared With Me</label><br>
													<?php $sharedrecents = mysql_query("SELECT d.* FROM document d, document_share s WHERE d.doc_id=s.ds_doc_id AND NOT s.ds_doc_owner='$uname' AND s.ds_doc_shared_user='$uname' ORDER BY doc_date_modified DESC LIMIT 3");
													if(mysql_num_rows($sharedrecents) > 0){
														while($sharedrecent = mysql_fetch_assoc($sharedrecents)){
															if($sharedrecent["doc_type"] == "application/msword" || $sharedrecent["doc_type"] == "application/vnd.ms-word"  || $sharedrecent["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($sharedrecent["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($sharedrecent["doc_type"] == "application/vnd.ms-excel" || $sharedrecent["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($sharedrecent["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$sharedrecent["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($sharedrecent["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															
															if ($sharedrecent["doc_size"] >= 1073741824){
																$bytes = number_format($sharedrecent["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($sharedrecent["doc_size"] >= 1048576){
																$bytes = number_format($sharedrecent["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($sharedrecent["doc_size"] >= 1024){ 
																$bytes = number_format($sharedrecent["doc_size"] / 1024, 2) . ' KB';
															}elseif ($sharedrecent["doc_size"] > 1){
																$bytes = $sharedrecent["doc_size"] . ' bytes';
															}elseif ($sharedrecent["doc_size"] == 1){
																$bytes = $sharedrecent["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<p><i class="<?php echo $icon; ?> fa-lg text-muted"></i>
															<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$sharedrecent["doc_parent_id"]."'");
																
																$adowners = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND a.admin_uname='".$sharedrecent["doc_author"]."'");
																$owners = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='".$sharedrecent["doc_author"]."'");
																if(mysql_num_rows($owners) > 0 || mysql_num_rows($adowners) > 0){
																	if(empty($adowners)){
																		list($ownerfname, $ownerlname) = mysql_fetch_row($owners);
																		$owner = $ownerfname." ".$ownerlname;
																	} else {
																		list($ownerfname, $ownerlname) = mysql_fetch_row($adowners);
																		$owner = $ownerfname." ".$ownerlname;
																	}
																} else {
																	$owner = ""; }
																
																echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$sharedrecent["doc_size"]." bytes)</b><br/>Owner: <b>".$owner."</b><br/>Modified: <b>".date("F d, Y", strtotime($sharedrecent["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($sharedrecent["doc_date_created"]))."</b><br/>Version ".$sharedrecent["doc_version"]."<br/><br>Tags:<br/>";
																if(mysql_num_rows($tags) > 0){
																	while($tag = mysql_fetch_assoc($tags)){
																		echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																	}
																} else {
																	echo "";
																} ;?>">
																<?php echo $sharedrecent["doc_name"];?></a></span>
															</p><?php
														}
													} else {
														echo "<span class='text-muted'>No recent documents.</span>";
													} ?>
												</div>
											</div>
										</div>	<?php
									} ?>
								</div>

								<div class="row" style="overflow-y:auto;">
									<div class="col-sm-12"><br/>
									    <form method="post">
											<p class="lead"><i class="fa fa-search"></i> Matters Quick Search 
											<input type="text" class="form-control input-sm" style="display:inline-block;width:47%;float:right;border-color:#ccc;" name="quicksearch" placeholder="Search cases and client ..."></p>
											
										  <input type="submit" name="btnqcksearch" value="Submit" style="display:none;">
									    </form>
										
									    <div class="panel panel-default" style="border-radius:0; border-bottom: 2px solid #ccc;height:100px; overflow:auto;">
											<div class="panel-body"><?php
											if(isset($_POST["btnqcksearch"])){
												$query = $_POST["quicksearch"];
												list($fatty, $latty) = mysql_fetch_row(mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'"));
												$attyresp = $fatty." ".$latty;
												if($utype == "Attorney"){
													$matqcksearches = mysql_query("SELECT m.*, c.* FROM case_matter m, case_client c WHERE c.cc_matter_id=m.cm_id AND m.cm_resp_atty='$attyresp' AND m.cm_name LIKE '%$query%' OR m.cm_description LIKE '%$query%' GROUP BY m.cm_name");
												    $cntqcksearches = mysql_query("SELECT c.*, m.* FROM case_client c, case_matter m WHERE c.cc_matter_id=m.cm_id AND m.cm_resp_atty='$attyresp' AND c.cc_fname LIKE '%$query%' OR c.cc_lname LIKE '%$query%' GROUP BY c.cc_fname,c.cc_lname");
												} else {
													$matqcksearches = mysql_query("SELECT m.*, c.* FROM case_matter m, case_client c WHERE c.cc_matter_id=m.cm_id AND m.cm_name LIKE '%$query%' OR m.cm_description LIKE '%$query%' GROUP BY m.cm_name");
												    $cntqcksearches = mysql_query("SELECT c.*, m.* FROM case_client c, case_matter m WHERE c.cc_matter_id=m.cm_id AND c.cc_fname LIKE '%$query%' OR c.cc_lname LIKE '%$query%' GROUP BY c.cc_fname,c.cc_lname");
												}
												
												
												$count = mysql_num_rows($matqcksearches) + mysql_num_rows($cntqcksearches);
												if($count > 1){
													echo "<div style='float:right;'><small><small>".$count." results found.</small></small></div><br>";
												} else {echo "<div style='float:right;'><small><small>".$count." result found.</small></small></div><br>";}
												
												//check for matters
												if(mysql_num_rows($matqcksearches) > 0){
													while($matqcksearch = mysql_fetch_assoc($matqcksearches)){ ?>
														<p><i class="fa fa-briefcase text-muted"></i> 
															<span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-placement="right" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $matqcksearch['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$matqcksearch["cm_description"] ."</b><br/>";
																	echo "Client: <b>".$matqcksearch["cc_fname"]." ".$matqcksearch["cc_lname"]."</b><br/>";
																	echo "Status: <b>".$matqcksearch["cm_status"] ."</b><br/>";
																	
																	if($matqcksearch["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($matqcksearch['cm_date_created'])) ."</b><br/>";
																	} else if($matqcksearch["cm_status"] == 'Pending'){
																		if($matqcksearch["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($matqcksearch['cm_date_created'])) ."</b><br/>Pending Date: <br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($matqcksearch['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($matqcksearch['cm_date_pending'])) ."</b><br/>";
																		}
																	} else if($matqcksearch["cm_status"] == 'Dismissed'){
																		if($matqcksearch["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($matqcksearch['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($matqcksearch['cm_date_dismissed'])) ."</b><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($matqcksearch['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($matqcksearch['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($matqcksearch['cm_date_dismissed'])) ."</b><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$matqcksearch["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$matqcksearch["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $matqcksearch['cm_name'];?></a></span>
														</p> <?php
													}
												} else {echo "";}
												
												//check for clients
												if(mysql_num_rows($cntqcksearches) > 0){
													while($cntqcksearch = mysql_fetch_assoc($cntqcksearches)){ ?>
														<p><i class="fa fa-user text-muted"></i> 
															<span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-placement="right" data-trigger="focus" data-html="true" data-content="<?php
																	echo "<b>".$cntqcksearch["cm_name"]."</b> ";
																	$caseheader = explode("-", $cntqcksearch['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "(<b>Civil Case</b> No. <b>" .$numbr ."</b>)<br/><br/>";
																	} else if($headr == "CR"){
																		echo "(<b>Criminal Case</b> No. <b>" .$numbr ."</b>)<br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$cntqcksearch["cm_description"] ."</b><br/>";
																	echo "Status: <b>".$cntqcksearch["cm_status"] ."</b><br/>";
																	
																	if($cntqcksearch["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($cntqcksearch['cm_date_created'])) ."</b><br/>";
																	} else if($cntqcksearch["cm_status"] == 'Pending'){
																		if($cntqcksearch["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cntqcksearch['cm_date_created'])) ."</b><br/>Pending Date: <br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cntqcksearch['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cntqcksearch['cm_date_pending'])) ."</b><br/>";
																		}
																	} else if($cntqcksearch["cm_status"] == 'Dismissed'){
																		if($cntqcksearch["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cntqcksearch['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cntqcksearch['cm_date_dismissed'])) ."</b><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cntqcksearch['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cntqcksearch['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cntqcksearch['cm_date_dismissed'])) ."</b><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$cntqcksearch["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cntqcksearch["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $cntqcksearch["cc_fname"]." ".$cntqcksearch["cc_lname"];?></a></span>
														</p> <?php
													}
												} else {echo "";}
												
											} else {
												echo "<span class='text-muted'>Enter query on the search field.</span>";
											}?>
											
											</div>
										</div>
									</div>
								</div>
							</div>
							
							
							<div class="col-md-5" style="height:420px; overflow-y:auto; overflow-x:hidden;">
								<div class="agenda">
									<p class="lead"><i class="fa fa-calendar-check-o"></i> Agenda
									<button data-toggle="collapse" data-target="#agenda" class="btn btn-default btn-sm" style="float:right;">New</button></p>
									
									<div id="agenda" class="collapse panel panel-default">
										<div class="panel-body">
											<form method="post" action="addagenda.php">
												<div class="form-group">
													<span class="control-label col-sm-12"><p align="left" class="text-muted"><small><b>Event Name</b></small><br/>
													<input type="text" name="evtname" class="form-control" placeholder="Event name" required>
												</div>
												<div class="form-group">
													<span class="control-label col-sm-4" ><p align="left" class="text-muted"><small><b>Event Date</b></small></p>
														<input type="text" name="evtdate" class="form-control datepick"  data-provide="datepicker" placeholder="mm/dd/yyyy" required></span>
													<span class="control-label col-sm-4"><p align="left" class="text-muted"><small><b>From</b></small></p>
														<input type="text" name="evttimefrom" class="form-control evttime"  placeholder="hh:mm am/pm" required></span>
													<span class="control-label col-sm-4"><p align="left" class="text-muted"><small><b>To</b></small></p>
														<input type="text"name="evttimeto" class="form-control evttime" placeholder="hh:mm am/pm"></span>	
												</div>
												<div class="form-group">
													<center><button type="submit" class="btn btn-info btn-sm control-label" style="width:90%;">Submit</button></center>
												</div>
												<input type="hidden" name="uname" value="<?php echo $uname;?>">
												<input type="hidden" name="view" value="admin">
											</form>	
										</div>
									</div>
									
									<div class="table-responsive">
										<table class="table table-condensed table-bordered">
											<thead>
												<tr>
													<th class="col-sm-4">Date</th>
													<th class="col-sm-3">Time</th>
													<th class="col-sm-5" colspan="2">Event</th>
												</tr>
											</thead>
											<tbody>
											<?php $dates = mysql_query("SELECT evt_date FROM user_agenda WHERE evt_date >= curdate() AND evt_user='$uname' GROUP BY evt_date ORDER BY evt_date ASC"); 
											if(mysql_num_rows($dates) > 0){ 
												while($date = mysql_fetch_assoc($dates)){ 
													$ed = $date["evt_date"]; 
													$events = mysql_query("SELECT * FROM user_agenda WHERE evt_date='$ed' AND evt_date >= curdate() AND evt_user='$uname' ORDER BY evt_time_from ASC"); ?>
													<tr>
														<td class="agenda-date" class="active" rowspan="<?php if(mysql_num_rows($events) > 0){ echo mysql_num_rows($events);} else {echo '1';} ?>">
															<div class="dayofmonth"><?php if($ed == date("Y-m-d")){ echo "Today";} else {echo date("d", strtotime($ed));} ?></div>
															<div class="dayofweek"><?php if($ed == date("Y-m-d")){ echo "";} else {echo date("l", strtotime($ed));} ?></div>
															<div class="shortdate text-muted"><?php if($ed == date("Y-m-d")){ echo "";} else {echo date("F, Y", strtotime($ed));} ?></div>
														</td>
														<?php if(mysql_num_rows($events) > 0){ 
															$event0 = mysql_fetch_assoc($events); ?>
															<td class="agenda-time"><?php echo date("h:i a", strtotime($event0["evt_time_from"])); 
																	if($event0["evt_time_to"] == "00:00:00"){
																		echo "";
																	} else {echo " - ".date("h:i a", strtotime($event0["evt_time_to"]));} ?>
																</td>
																<td class="agenda-events">
																	<div class="agenda-event"><?php echo $event0["evt_name"]; ?></div>
																</td>
																<td class="black-link"><a href="deleteagenda.php?evtid=<?php echo $event0["evt_id"];?>&view=admin"><span class="glyphicon glyphicon-trash"></span></a></td>
															
													
													
													<?php	
														while($event = mysql_fetch_assoc($events)){ ?>
														<tr>
															<td class="agenda-time"><?php echo date("h:i a", strtotime($event["evt_time_from"])); 
																if($event["evt_time_to"] == "00:00:00"){
																	echo "";
																} else {echo " - ".date("h:i a", strtotime($event["evt_time_to"]));} ?>
															</td>
															<td class="agenda-events">
																<div class="agenda-event"><?php echo $event["evt_name"]; ?></div>
															</td>
															<td class="black-link"><a href="deleteagenda.php?evtid=<?php echo $event["evt_id"];?>&view=admin"><span class="glyphicon glyphicon-trash"></span></a></td>
														</tr>
														<?php
														} 
													} else {
														echo "<td colspan='3'>No event.</td>";
													} ?>
													</tr><?php
												} 
											} else {
												echo "<tr><td colspan='4'>No new agenda</td></tr>";
											} ?>
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
<!--MATTERS-->					
				<div id="matters" name="matters" class="tab-pane fade">
					<div class="container main-container">
					  <?php list($status) = mysql_fetch_row(mysql_query("SELECT p.user_account_status FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'"));  ?> 
					  <ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#matters_matter">Matter</a></li>
							<?php if($utype == "Staff" && $status > 0){?>
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">Client
								<span class="caret"></span></a>
								<ul class="dropdown-menu">
								  <li><a href="#matters_client">List Client</a></li>
								  <li><a href="clientlog.php#curtime" target="_blank">Client Logbook</a></li>
								</ul>
							 </li>	<?php
							} else{?>
							<li><a data-toggle="tab" href="#matters_client">Client</a></li>	<?php
							}?>
							<li style="float:right;"><button data-toggle="tab" class="btn btn-info btn-sm" href="#matters_new">NEW MATTER</button></li> 
					  </ul>

					<!--Contents of MATTERS-->  
					  <div class="tab-content" style="height:420px; overflow-y:auto; overflow-x:hidden;">
						<!--Matter-->
						<div id="matters_matter" class="tab-pane fade in active"><?php 
							if($status > 0){?>
							
							<div class="container">
							  <?php if($utype=="Attorney"){
								  list($lname) = mysql_fetch_row(mysql_query("SELECT p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'"));
								  $countnew = mysql_query("SELECT * FROM case_matter WHERE cm_status='New' AND cm_resp_atty LIKE '%$lname%'"); 
								  $countpending = mysql_query("SELECT * FROM case_matter WHERE cm_status='Pending' AND cm_resp_atty LIKE '%$lname%'");
								  $countdismissed = mysql_query("SELECT * FROM case_matter WHERE cm_status='Dismissed' AND cm_resp_atty LIKE '%$lname%'");}
								  else {
									$countnew = mysql_query("SELECT * FROM case_matter WHERE cm_status='New'"); 
									$countpending = mysql_query("SELECT * FROM case_matter WHERE cm_status='Pending'");
									$countdismissed = mysql_query("SELECT * FROM case_matter WHERE cm_status='Dismissed'");
									}
								  ?>
								  
							  <ul class="nav nav-pills">
								<li class="active"><a data-toggle="pill" href="#case_new">New <?php if(mysql_num_rows($countnew) > 0){echo "<span class='badge small'>".mysql_num_rows($countnew)."</span>";} else {echo "";} ?></a></li>
								<li><a data-toggle="pill" href="#case_dismissed">Dismissed <?php if(mysql_num_rows($countdismissed) > 0){echo "<span class='badge small'>".mysql_num_rows($countdismissed)."</span>";} else {echo "";} ?></a></li>
								<li><a data-toggle="pill" href="#case_pending">Pending <?php if(mysql_num_rows($countpending) > 0){echo "<span class='badge small'>".mysql_num_rows($countpending)."</span>";} else {echo "";} ?></a></li>
								<li><a data-toggle="pill" href="#case_all">All</a></li>
							  </ul>
							  
							  <div class="tab-content" >
								    <?php list($attyfname, $attylname) = mysql_fetch_row(mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'"));
									$name = $attyfname." ".$attylname; 
									
									$limit = 15;?>
									
									<!--new -->
									<div id="case_new" class="tab-pane fade in active">
									    <br/><?php
										if(isset($_GET["page"]) && isset($_GET["case"])){
											if($_GET["case"] == "new"){
												$page = $_GET["page"]; 
											} else {
												$page = 1;
											}
										} else{ $page = 1; } 
										
										if($utype == "Staff"){
											$countmatter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id  AND cm.cm_status='New' ORDER BY cm_id DESC");
										} else {
											$countmatter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='New' AND cm.cm_resp_atty='$name'  ORDER BY cm_id DESC"); 
										}
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countmatter);
									    $total = ceil($count/ $limit); ?>
										
										<div class="col-sm-12"><div style="height:300px;overflow-y:auto; overflow-x:hidden;">
										<table class="table">
											<thead>
												<div class="form-group"><tr class="active">
													<th><label>Display Number</label></th>
													<th><label>Client</label></th>
													<th><label>Location</label></th>
													<th><label>Open Date</label></th>
													<th><label>Others</label></th>
												</tr></div>
											</thead>
											<tbody>
											<?php 
											//display all for Staff
											if($utype == "Staff"){
												$matter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='New' ORDER BY cm_id DESC LIMIT $start, $limit");
												if(mysql_num_rows($matter) > 0){
													while($cm = mysql_fetch_array($matter)){ ?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $cm['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																	
																	if($cm["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																	} else if($cm["cm_status"] == 'Pending'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($cm["cm_status"] == 'Dismissed'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $cm['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $cm['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp;
																<a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-todo="<?php echo $cm['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															
															</span></td>
														<td><?php echo $cm['cc_fname'] .' ' .$cm['cc_lname'];?></td>
														<td><?php echo $cm['cc_location'];?></td>
														<td><?php echo $cm['cm_date_created'];?></td>
														<td><?php echo nl2br($cm['cm_notes']);?></td>
													 </tr></div> <?php
													} 
												} else{ ?>
														<div class="form-group"><tr>
														<td><span class="control-label col-sm-6">No Results</span></td>
														</tr></div>
														<?php
												}
												
											} //display only matters responsible
											else {
												$matter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='New' AND cm.cm_resp_atty='$name' ORDER BY cm_id DESC LIMIT $start, $limit");
												if(mysql_num_rows($matter) > 0){
													while($cm = mysql_fetch_array($matter)){ ?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $cm['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																	
																	if($cm["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																	} else if($cm["cm_status"] == 'Pending'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($cm["cm_status"] == 'Dismissed'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $cm['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $cm['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#delmatter"> Delete </a>&nbsp;
																<a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-todo="<?php echo $cm['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															</span></td>
														<td><?php echo $cm['cc_fname'] .' ' .$cm['cc_lname'];?></td>
														<td><?php echo $cm['cc_location'];?></td>
														<td><?php echo $cm['cm_date_created'];?></td>
														<td><?php echo nl2br($cm['cm_notes']);?></td>
													 </tr></div> <?php
													} 
												} else{ ?>
														<div class="form-group"><tr>
														<td><span class="control-label col-sm-6">No Results</span></td>
														</tr></div>
														<?php
												}
											} ?>
											</tbody>
										</table>
										</div></div>
										
										<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
											<ul class="pagination pagination-sm">
												<?php for($i = 1; $i<=$total; $i++){
													if($page == $i){
														echo "<li class='active'>";
													} else {
														echo "<li>";
													}
													echo "<a href='home.php?case=new&page=$i#case_new'>$i</a></li>";
												} ?>
												
											</ul>
										</div>
									</div>
									
									<!--pending -->
									<div id="case_pending" class="tab-pane fade">
									    <br/><?php
										if(isset($_GET["page"]) && isset($_GET["case"])){
											if($_GET["case"] == "pending"){
												$page  = $_GET["page"]; 
											} else {
												$page = 1;
											}
										} else{ $page = 1; } 
										
										if($utype == "Staff"){
											$countmatter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='Pending' ORDER BY cm_id DESC");
										} else {
											$countmatter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='Pending' AND cm.cm_resp_atty='$name' ORDER BY cm_id DESC"); 
										}
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countmatter);
									    $total = ceil($count/ $limit); ?>
										
										<div class="col-sm-12"><div style="height:300px;overflow-y:auto; overflow-x:hidden;">
										<table class="table">
											<thead>
												<div class="form-group"><tr class="active">
													<th><label>Display Number</label></th>
													<th><label>Client</label></th>
													<th><label>Location</label></th>
													<th><label>Open Date</label></th>
													<th><label>Others</label></th>
												</tr></div>
											</thead>
											<tbody>
											<?php 
											//display all for Staff
											if($utype == "Staff"){
												$matter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='Pending' LIMIT $start, $limit");
												if(mysql_num_rows($matter) > 0){
													while($cm = mysql_fetch_array($matter)){ ?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $cm['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																	
																	if($cm["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																	} else if($cm["cm_status"] == 'Pending'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($cm["cm_status"] == 'Dismissed'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $cm['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $cm['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp; 
																<a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-todo="<?php echo $cm['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															</span></td>
														<td><?php echo $cm['cc_fname'] .' ' .$cm['cc_lname'];?></td>
														<td><?php echo $cm['cc_location'];?></td>
														<td><?php echo $cm['cm_date_created'];?></td>
														<td><?php echo nl2br($cm['cm_notes']);?></td>
													 </tr></div> <?php
													} 
												} else{ ?>
														<div class="form-group"><tr>
														<td><span class="control-label col-sm-6">No Results</span></td>
														</tr></div>
														<?php
												}
												
											} //display only matters responsible
											else {
												$matter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='Pending' AND cm.cm_resp_atty='$name' ORDER BY cm.cm_id DESC LIMIT $start, $limit");
												if(mysql_num_rows($matter) > 0){
													while($cm = mysql_fetch_array($matter)){ ?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $cm['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																	
																	if($cm["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																	} else if($cm["cm_status"] == 'Pending'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($cm["cm_status"] == 'Dismissed'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $cm['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $cm['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#delmatter"> Delete </a>&nbsp;
																<a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-todo="<?php echo $cm['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															</span></td>
														<td><?php echo $cm['cc_fname'] .' ' .$cm['cc_lname'];?></td>
														<td><?php echo $cm['cc_location'];?></td>
														<td><?php echo $cm['cm_date_created'];?></td>
														<td><?php echo nl2br($cm['cm_notes']);?></td>
													 </tr></div> <?php
													} 
												} else{ ?>
														<div class="form-group"><tr>
														<td><span class="control-label col-sm-6">No Results</span></td>
														</tr></div>
														<?php
												}
											} ?>
											</tbody>
										</table>
										</div></div>
										
										<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
											<ul class="pagination pagination-sm">
												<?php for($i = 1; $i<=$total; $i++){
													if($page == $i){
														echo "<li class='active'>";
													} else {
														echo "<li>";
													}
													echo "<a href='home.php?case=pending&page=$i#case_pending'>$i</a></li>";
												} ?>
												
											</ul>
										</div>
									</div>
									
									<!--dismissed -->
									<div id="case_dismissed" class="tab-pane fade">
									    <br/><?php
										if(isset($_GET["page"]) && isset($_GET["case"])){
											if($_GET["case"] == "dismissed"){
												$page  = $_GET["page"]; 
											} else {
												$page = 1;
											}
										} else{ $page = 1; } 
										
										if($utype == "Staff"){
											$countmatter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='Dismissed' ORDER BY cm_id DESC");
										} else {
											$countmatter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='Dismissed' AND cm.cm_resp_atty='$name' ORDER BY cm_id DESC"); 
										}
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countmatter);
									    $total = ceil($count/ $limit); ?>
										
										<div class="col-sm-12"><div style="height:300px;overflow-y:auto; overflow-x:hidden;">
										<table class="table">
											<thead>
												<div class="form-group"><tr class="active">
													<th><label>Display Number</label></th>
													<th><label>Client</label></th>
													<th><label>Location</label></th>
													<th><label>Open Date</label></th>
													<th><label>Others</label></th>
												</tr></div>
											</thead>
											<tbody>
											<?php 
											//display all for Staff
											if($utype == "Staff"){
												$matter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='Dismissed' ORDER BY cm.cm_id DESC LIMIT $start, $limit");
												if(mysql_num_rows($matter) > 0){
													while($cm = mysql_fetch_array($matter)){ ?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $cm['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																	
																	if($cm["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																	} else if($cm["cm_status"] == 'Pending'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($cm["cm_status"] == 'Dismissed'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $cm['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $cm['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp; 
																<a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-todo="<?php echo $cm['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															</span></td>
														<td><?php echo $cm['cc_fname'] .' ' .$cm['cc_lname'];?></td>
														<td><?php echo $cm['cc_location'];?></td>
														<td><?php echo $cm['cm_date_created'];?></td>
														<td><?php echo nl2br($cm['cm_notes']);?></td>
													 </tr></div> <?php
													} 
												} else{ ?>
														<div class="form-group"><tr>
														<td><span class="control-label col-sm-6">No Results</span></td>
														</tr></div>
														<?php
												}
												
											} //display only matters responsible
											else {
												$matter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_status='Dismissed' AND cm.cm_resp_atty='$name' SELECT cm.*, cc.*, d.* FROM case_matter cm, case_client cc, designation d WHERE cm.cm_id=cc.cc_matter_id AND d.des_id=cc.cc_location AND cm.cm_status='Pending' AND cm.cm_resp_atty='$name' ORDER BY cm.cm_id DESC LIMIT $start, $limitLIMIT $start, $limit");
												if(mysql_num_rows($matter) > 0){
													while($cm = mysql_fetch_array($matter)){ ?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $cm['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																	
																	if($cm["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																	} else if($cm["cm_status"] == 'Pending'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($cm["cm_status"] == 'Dismissed'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $cm['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $cm['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#delmatter"> Delete </a>&nbsp;
																<a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-todo="<?php echo $cm['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															</span></td>
														<td><?php echo $cm['cc_fname'] .' ' .$cm['cc_lname'];?></td>
														<td><?php echo $cm['cc_location'];?></td>
														<td><?php echo $cm['cm_date_created'];?></td>
														<td><?php echo nl2br($cm['cm_notes']);?></td>
													 </tr></div> <?php
													} 
												} else{ ?>
														<div class="form-group"><tr>
														<td><span class="control-label col-sm-6">No Results</span></td>
														</tr></div>
														<?php
												}
											} ?>
											</tbody>
										</table>
										</div></div>
										
										<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
											<ul class="pagination pagination-sm">
												<?php for($i = 1; $i<=$total; $i++){
													if($page == $i){
														echo "<li class='active'>";
													} else {
														echo "<li>";
													}
													echo "<a href='home.php?case=dismissed&page=$i#case_dismissed'>$i</a></li>";
												} ?>
												
											</ul>
										</div>
									</div>
									
									<!--all-->
									<div id="case_all" class="tab-pane fade">
									    <br/><?php
										if(isset($_GET["page"]) && isset($_GET["case"])){
											if($_GET["case"] == "all"){
												$page  = $_GET["page"]; 
											} else {
												$page = 1;
											}
										} else{ $page = 1; } 
										
										if($utype == "Staff"){
											$countmatter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id ORDER BY cm_id DESC");
										} else {
											$countmatter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_resp_atty='$name' ORDER BY cm_id DESC"); 
										}
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countmatter);
									    $total = ceil($count/ $limit); ?>
										
										<div class="col-sm-12"><div style="height:300px;overflow-y:auto; overflow-x:hidden;">
										    <div class="row" style="float:right;">
												<form method="post" action="home.php#case_all">
												  <div class="input-group col-sm-4 col-sm-offset-7">
													<input type="text" name="mattxtsearch" class="form-control input-sm" style="margin:0 !important;" placeholder="Search matter name or description" required>
													<div class="input-group-btn">
													  <button class="btn btn-primary btn-sm" name ="matbtnsearch" type="submit">
														<i class="glyphicon glyphicon-search"></i>
													  </button>
													</div>
												  </div>
												</form>
												<br/>
											</div> <?php 
											
											if(isset($_POST["matbtnsearch"])){
												echo "<br><br><div class='panel panel-default' style='overflow-y:auto;'><div class='panel-body'>";
												$mattxtsearch = $_POST["mattxtsearch"];
												
												if($utype == "Staff"){
													$schmatters = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND (cm.cm_name LIKE '%$mattxtsearch%' OR cm.cm_description LIKE '%$mattxtsearch%')");
												    $schclients = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND (cc.cc_fname LIKE '%$mattxtsearch%' OR cc.cc_lname LIKE '%$mattxtsearch%')");
												} else {
													//get id
													list($getid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'") );
													//get name
													list($fname, $lname) = mysql_fetch_row(mysql_query("SELECT user_fname, user_lname FROM user_profile WHERE user_id='$getid'"));
													
													$schmatters = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_resp_atty='".$fname." ".$lname."' AND (cm.cm_name LIKE '%$mattxtsearch%' OR cm.cm_description LIKE '%$mattxtsearch%')");
												    $schclients = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_resp_atty='".$fname." ".$lname."' AND (cc.cc_fname LIKE '%$mattxtsearch%' OR cc.cc_lname LIKE '%$mattxtsearch%')");
												}
												
												//count total results
												$schresults = mysql_num_rows($schmatters) + mysql_num_rows($schclients); 
												if($schresults > 1){
													echo "<small>".$schresults." results found for <b>".$mattxtsearch."</b>.</small><br>";
												} else {
													echo "<small>".$schresults." result found for <b>".$mattxtsearch."</b>.</small><br>";
												}
												
												echo "<table class='table table-condensed'>";
												echo "<thead><tr class='active'><th><label>Display Number</label></th><th><label>Client</label></th><th><label>Location</label></th><th><label>Others</label></th><th></th></thead>";
												echo "<tbody>";
												
												if(mysql_num_rows($schmatters) > 0){
													echo "<tr><td colspan='5' class='active small'>Search results found from <b>matters</b> <sup><span class='badge small'>".mysql_num_rows($schmatters)."</span></sup></td></tr>";
													while($schmatter = mysql_fetch_assoc($schmatters)){?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $schmatter['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$schmatter["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$schmatter["cm_status"] ."</b><br/><br/>";
																	
																	if($schmatter["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($schmatter['cm_date_created'])) ."</b><br/><br/>";
																	} else if($schmatter["cm_status"] == 'Pending'){
																		if($schmatter["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($schmatter['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($schmatter['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($schmatter['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($schmatter["cm_status"] == 'Dismissed'){
																		if($schmatter["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($schmatter['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($schmatter['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($schmatter['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($schmatter['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($schmatter['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$schmatter["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$schmatter["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $schmatter['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $schmatter['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $schmatter['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $schmatter['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp; 
															    <?php if($utype == "Attorney"){?>
																	<a href="#" class="passidtomodal" data-id="<?php echo $schmatter['cm_name'];?>" data-toggle="modal" data-target="#delmatter"> Delete </a>&nbsp;<?php
																} else {echo "";} ?>
																<a href="#" class="passidtomodal" data-id="<?php echo $schmatter['cm_name'];?>" data-todo="<?php echo $schmatter['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															</span></td>
														<td><?php echo $schmatter['cc_fname'] .' ' .$schmatter['cc_lname'];?></td>
														<td><?php echo $schmatter['cc_location'];?></td>
														<td><?php echo $schmatter['cm_date_created'];?></td>
														<td><?php echo nl2br($schmatter['cm_notes']);?></td>
													 </tr></div><?php
													}
												}
												
												if(mysql_num_rows($schclients) > 0){
													echo "<tr><td colspan='5' class='active small'>Search results found from <b>clients</b> <sup><span class='badge small'>".mysql_num_rows($schclients)."</span></sup></td></tr>";
													while($schclient = mysql_fetch_assoc($schclients)){?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $schclient['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$schclient["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$schclient["cm_status"] ."</b><br/><br/>";
																	
																	if($schclient["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($schclient['cm_date_created'])) ."</b><br/><br/>";
																	} else if($schclient["cm_status"] == 'Pending'){
																		if($schclient["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($schclient['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($schclient['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($schclient['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($schclient["cm_status"] == 'Dismissed'){
																		if($schclient["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($schclient['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($schclient['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($schclient['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($schclient['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($schclient['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$schclient["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$schclient["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $schclient['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $schclient['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $schclient['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $schclient['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp; 
															    <?php if($utype == "Attorney"){?>
																	<a href="#" class="passidtomodal" data-id="<?php echo $schmatter['cm_name'];?>" data-toggle="modal" data-target="#delmatter"> Delete </a>&nbsp;<?php
																} else {echo "";} ?>
																<a href="#" class="passidtomodal" data-id="<?php echo $schclient['cm_name'];?>" data-todo="<?php echo $schclient['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															</span></td>
														<td><?php echo $schclient['cc_fname'] .' ' .$schclient['cc_lname'];?></td>
														<td><?php echo $schclient['des_name'];?></td>
														<td><?php echo $schclient['cm_date_created'];?></td>
														<td><?php echo nl2br($schclient['cm_notes']);?></td>
													 </tr></div><?php
													}
												}
												
											
												echo "</tbody></table>";
												echo "<br><label class='small text-primary'>Click on the page number <label class='label label-warning'>1</label> at the bottom to redisplay previous table.</label></div></div>";
											} ?>
										
										<table class="table" style="<?php if(isset($_POST["matbtnsearch"])){echo "display:none;";}?>">
											<thead>
												<div class="form-group"><tr class="active">
													<th><label>Display Number</label></th>
													<th><label>Client</label></th>
													<th><label>Location</label></th>
													<th><label>Open Date</label></th>
													<th><label>Others</label></th>
												</tr></div>
											</thead>
											<tbody>
											<?php 
											//display all for Staff
											if($utype == "Staff"){
												$matter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id ORDER BY cm.cm_id DESC LIMIT $start, $limit");
												if(mysql_num_rows($matter) > 0){
													while($cm = mysql_fetch_array($matter)){ ?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $cm['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																	
																	if($cm["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																	} else if($cm["cm_status"] == 'Pending'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($cm["cm_status"] == 'Dismissed'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $cm['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $cm['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp; 
																<a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-todo="<?php echo $cm['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															</span></td>
														<td><?php echo $cm['cc_fname'] .' ' .$cm['cc_lname'];?></td>
														<td><?php echo $cm['cc_location'];?></td>
														<td><?php echo $cm['cm_date_created'];?></td>
														<td><?php echo nl2br($cm['cm_notes']);?></td>
													 </tr></div> <?php
													} 
												} else{ ?>
														<div class="form-group"><tr>
														<td><span class="control-label col-sm-6">No Results</span></td>
														</tr></div>
														<?php
												}
												
											} //display only matters responsible
											else {
												$matter = mysql_query("SELECT cm.*, cc.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cm.cm_resp_atty='$name' ORDER BY cm.cm_id DESC LIMIT $start, $limit");
												if(mysql_num_rows($matter) > 0){
													while($cm = mysql_fetch_array($matter)){ ?>
														<div class="form-group"><tr>
														
														<td><span class="control-label col-sm-6">
															<p class="description"><span class="black-link">
																<a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php
																	$caseheader = explode("-", $cm['cm_name']);
																	$headr = $caseheader[0];
																	$numbr = $caseheader[1];
																	if($headr == "CV"){
																		echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else if($headr == "CR"){
																		echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																	} else {echo "";}
																	
																	echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																	echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																	
																	if($cm["cm_status"] == 'New'){
																		echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																	} else if($cm["cm_status"] == 'Pending'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																		} else{
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																		}
																	} else if($cm["cm_status"] == 'Dismissed'){
																		if($cm["cm_date_pending"] == "0000-00-00"){
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		} else {
																			echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																		}
																	}
																	
																	echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																	?>">
																	<?php echo $cm['cm_name'];?></a></span><br/> 
																<span class="description"><?php echo $cm['cm_description'];?></span> 
															</p><small><a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editmatter"> Edit </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#editstatusmatter"> Change Status </a>&nbsp; 
															    <a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-toggle="modal" data-target="#delmatter"> Delete </a>&nbsp;
																<a href="#" class="passidtomodal" data-id="<?php echo $cm['cm_name'];?>" data-todo="<?php echo $cm['cm_notes'];?>" data-toggle="modal" data-target="#addnotes">Add Notes </a>
																</small>
															
															</span></td>
														<td><?php echo $cm['cc_fname'] .' ' .$cm['cc_lname'];?></td>
														<td><?php echo $cm['cc_location'];?></td>
														<td><?php echo $cm['cm_date_created'];?></td>
														<td><?php echo nl2br($cm['cm_notes']);?></td>
													 </tr></div> <?php
													} 
												} else{ ?>
														<div class="form-group"><tr>
														<td><span class="control-label col-sm-6">No Results</span></td>
														</tr></div>
														<?php
												}
											} ?>
											</tbody>
										</table>
										</div></div>
										
										<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
											<ul class="pagination pagination-sm">
												<?php for($i = 1; $i<=$total; $i++){
													if($page == $i){
														echo "<li class='active'>";
													} else {
														echo "<li>";
													}
													echo "<a href='home.php?case=all&page=$i#case_all'>$i</a></li>";
												} ?>
												
											</ul>
										</div>
									</div>
									
									
							  </div>
							</div>
							<?php	
							} else {?>
								<div class="container">
									<div align="center" style="font-size:24px; color:black;">
										<br/><br/><br/><br/>
										<span class="glyphicon glyphicon-eye-close" style="color:#f1f1f1; font-size:70px;"></span><br/>
										You do not have permission to view this.<br/>
										<small><small><small>Your account is not verified yet. Ask the administrator to verify your account.</small></small></small>
									</div>
								</div><?php
							} ?>
						</div>
						
						<!--Client-->
						<div id="matters_client" class="tab-pane fade"><?php
							if($status > 0){ ?>
							<div class="container">
							  <ul class="nav nav-pills">
								<li class="active"><a data-toggle="pill" href="#client_all">All</a></li> &nbsp;
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_a" >A</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_b">B</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_c">C</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_d">D</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_e">E</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_f">F</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_g">G</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_h">H</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_i">I</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_j">J</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_k">K</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_l">L</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_m">M</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_n">N</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_o">O</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_p">P</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_q">Q</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_r">R</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_s">S</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_t">T</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_u">U</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_v">V</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_w">W</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_x">X</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_y">Y</a></li>
								<li><a style="padding-left:5px; padding-right:5px;" data-toggle="pill" href="#client_z">Z</a></li>
							  </ul>
							  
							  <div class="tab-content">
								<?php if($utype == "Staff"){
									$limit = 3;
								} else {$limit = 6;} ?>
								
								<!--All-->
								<div id="client_all" class="tab-pane fade in active">
									<div class="row">
										<form method="post" action="home.php#client_all">
										  <div class="input-group col-sm-4"  style="float:right; margin-right:7%;">
											<input type="text" name="cnttxtsearch" class="form-control input-sm" style="margin:0 !important;" placeholder="Search client name" required>
											<div class="input-group-btn">
											  <button class="btn btn-primary btn-sm" name ="cntbtnsearch" type="submit">
												<i class="glyphicon glyphicon-search"></i>
											  </button>
											</div>
										  </div>
										</form>
										<br/>
									</div> 
									
									<?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "all"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
										
									$countclient = mysql_query("SELECT * FROM case_client");
									$start = ($page-1) * $limit; 
									$count = mysql_num_rows($countclient);
									$total = ceil($count/ $limit);
									
									if(isset($_POST["cntbtnsearch"])){
										echo "<br><br><div class='panel panel-default' style='overflow-y:auto;'><div class='panel-body'>";
										$cnttxtsearch = $_POST["cnttxtsearch"];
										
										$schclients = mysql_query("SELECT cc.* FROM case_client cc WHERE  (cc.cc_fname LIKE '%$cnttxtsearch%' OR cc.cc_lname LIKE '%$cnttxtsearch%')");
										
										//count total results
										$schresults = mysql_num_rows($schclients); 
										if($schresults > 1){
											echo "<small>".$schresults." results found for <b>".$cnttxtsearch."</b>.</small><br>";
										} else {
											echo "<small>".$schresults." result found for <b>".$cnttxtsearch."</b>.</small><br>";
										}
										
										echo "<table class='table table-condensed'>";
										echo "<thead><tr class='active'><th><label>Name</label></th><th><label>Phone</label></th><th><label>Address</label></th></thead>";
										echo "<tbody>";
										
										if(mysql_num_rows($schclients) > 0){
											echo "<tr><td colspan='3' class='active small'>Search results found from <b>clients</b> <sup><span class='badge small'>".mysql_num_rows($schclients)."</span></sup></td></tr>";
											while($schclient = mysql_fetch_assoc($schclients)){?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$schclient["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $schclient['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND a.admin_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $schclient["cc_fname"] ." " .$schclient["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $schclient['cc_fname'].' '.$schclient['cc_lname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $schclient['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $schclient["cc_contact"];?></td>
													<td><?php echo $schclient["cc_address"] .", " .$schclient["des_name"];?></td>
											    </tr></div><?php
											}
										}
										
									
										echo "</tbody></table>";
										echo "<br><label class='small text-primary'>Click on the page number <label class='label label-warning'>1</label> at the bottom to redisplay previous table.</label></div></div>";
									} ?>
									
									
									<table class="table col-sm-11" style ="<?php if(isset($_POST["cntbtnsearch"])){echo "display:none;";}?>">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc ORDER BY cc_id LIMIT $start, $limit");
										
										if(mysql_num_rows($client) > 0){
											while($schclient = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$schclient["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $schclient['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $schclient["cc_fname"] ." " .$schclient["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $schclient['cc_lname'].', '.$schclient['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $schclient['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $schclient["cc_contact"];?></td>
													<td><?php echo $schclient["cc_address"] .", " .$schclient["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table><br/><br/>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=all&page=$i#client_all'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--A-->
								<div id="client_a" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "a"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'a%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'a%' LIMIT $start, $limit");
										
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=a&page=$i#client_a'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--B-->
								<div id="client_b" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "b"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'b%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'b%' LIMIT $start, $limit");
										
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=b&page=$i#client_b'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--C-->
								<div id="client_c" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "c"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'c%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'c%' LIMIT $start, $limit");
										
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=c&page=$i#client_c'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--D-->
								<div id="client_d" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "d"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'd%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'd%' LIMIT $start, $limit");
										
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=d&page=$i#client_d'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--E-->
								<div id="client_e" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "e"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'e%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'e%' LIMIT $start, $limit");
										
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=e&page=$i#client_e'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>

								<!--F-->
								<div id="client_f" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "f"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'f%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'f%' LIMIT $start, $limit");
										
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=f&page=$i#client_f'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--G-->
								<div id="client_g" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "g"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'g%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'g%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=g&page=$i#client_g'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--H-->
								<div id="client_h" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "h"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'h%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'h%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=h&page=$i#client_h'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--I-->
								<div id="client_i" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "i"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'i%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'i%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=i&page=$i#client_i'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--J-->
								<div id="client_j" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "j"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'j%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'j%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=j&page=$i#client_j'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--K-->
								<div id="client_k" class="tab-pane fade">
								     <br/><?php
									 if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "k"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'k%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'k%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=k&page=$i#client_k'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--L-->
								<div id="client_l" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "l"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'l%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'l%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=l&page=$i#client_l'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--M-->
								<div id="client_m" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "m"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'm%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'm%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=m&page=$i#client_m'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--N-->
								<div id="client_n" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "n"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'n%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'n%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=n&page=$i#client_n'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--O-->
								<div id="client_o" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "o"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'o%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'o%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=o&page=$i#client_o'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--P-->
								<div id="client_p" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "p"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'p%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>

									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'p%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=r&page=$i#client_p'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--Q-->
								<div id="client_q" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "q"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'q%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'q%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=q&page=$i#client_q'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--R-->
								<div id="client_r" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "r"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'r%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>

									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'r%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=r&page=$i#client_r'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--S-->
								<div id="client_s" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "s"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 's%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>

									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 's%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=s&page=$i#client_s'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--T-->
								<div id="client_t" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "t"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 't%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 't%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=t&page=$i#client_t'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--U-->
								<div id="client_u" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "u"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'u%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>

									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'u%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=u&page=$i#client_u'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--V-->
								<div id="client_v" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "v"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'v%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'v%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=v&page=$i#client_v'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--W-->
								<div id="client_w" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "w"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'w%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'w%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=w&page=$i#client_w'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--X-->
								<div id="client_x" class="tab-pane fade">
								   <br/><?php
								   if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "x"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'x%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'x%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=x&page=$i#client_x'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--Y-->
								<div id="client_y" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "y"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'y%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'y%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=y&page=$i#client_y'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
								<!--Z-->
								<div id="client_z" class="tab-pane fade">
								    <br/><?php
									if(isset($_GET["page"]) && isset($_GET["client"])){
										if($_GET["client"] == "z"){
											$page  = $_GET["page"]; 
										} else {
											$page = 1;
										}
									} else{ $page = 1; } 
									
										$countclient = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'z%'");
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countclient);
									    $total = ceil($count/ $limit); ?>
									<table class="table col-sm-11">
										<thead>
											<div class="form-group"><tr class="active">
												<th><label>Name</label></th>
												<th><label>Phone</label></th>
												<th><label>Address</label></th>
												
											</tr></div>
										</thead>
										<tbody>
										<?php $client = mysql_query("SELECT cc.* FROM case_client cc WHERE  cc.cc_fname LIKE 'z%' LIMIT $start, $limit");
										if(mysql_num_rows($client) > 0){
											while($cc = mysql_fetch_array($client)){ ?>
												<div class="form-group"><tr>
												    <?php $resatty = mysql_query("SELECT cm.cm_resp_atty FROM case_matter cm, case_client cc WHERE cc.cc_matter_id=cm.cm_id AND cc.cc_id='".$cc["cc_id"] ."'");
													    list($resat) = mysql_fetch_row($resatty);?>
													<td> <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Reference Number: <b><?php echo $cc['cc_id'];?></b><br/> <?php
													    if($utype == "Staff"){
															if(mysql_num_rows($resatty) > 0){
																echo "<br/>Responsible Attorney: <b>" .$resat ."</b>";
															} else {echo "";}
														} else{
															$at = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
															if(mysql_num_rows($resatty) > 0){
																list($f, $l) = mysql_fetch_row($at);
																$n = $f .' ' .$l;
																if($resat == $n){
																	echo "<br/>Responsible Attorney: <b>Me</b>";
																}
															} else{echo "";}
														}?>">
													    <?php echo $cc["cc_fname"] ." " .$cc["cc_lname"];?></a></span><br/>
													    <?php if($utype == "Staff"){?>
															<small><a href="" class="passidtomodal" data-id="<?php echo $cc['cc_lname'].', '.$cc['cc_fname'];?>" data-toggle="modal" data-target="#editclientinfo">Edit</a> &nbsp;
															<a href="" class="passidtomodal" data-id="<?php echo $cc['cc_id'];?>" data-toggle="modal" data-target="#delclientinfo">Delete</a></small><?php
														} else{
															echo "";
														}?>
														</td>
													<td><?php echo $cc["cc_contact"];?></td>
													<td><?php echo $cc["cc_address"] .", " .$cc["cc_location"];?></td>
											 </tr></div> <?php
											} 
										} else{ ?>
												<div class="form-group"><tr>
												<td>No Results</td>
												</tr></div>
												<?php
										} ?>
										</tbody>
									</table>
									<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
										<ul class="pagination pagination-sm">
											<?php for($i = 1; $i<=$total; $i++){
												if($page == $i){
													echo "<li class='active'>";
												} else {
													echo "<li>";
												}
												echo "<a href='home.php?client=z&page=$i#client_z'>$i</a></li>";
											} ?>
											
										</ul>
									</div>
								</div>
								
							  </div>
							</div>
							<?php
							} else { ?>
								<div class="container">
									<div align="center" style="font-size:24px; color:black;">
										<br/><br/><br/><br/>
										<span class="glyphicon glyphicon-eye-close" style="color:#f1f1f1; font-size:70px;"></span><br/>
										You do not have permission to view this.<br/>
										<small><small><small>Your account is not verified yet. Ask the administrator to verify your account.</small></small></small>
									</div>
								</div><?php
							} ?>
						</div>
							
							
						
						<!--New Matter-->
						<div id="matters_new" class="tab-pane fade"><?php
							if($status > 0){ ?>
							<div class="form-horizontal">
							    <h4>Create New Matter</h4><br />
							
								<form method="post" action="addmatter.php">
									<label>Matter Name</label>									
									<div class="form-group">
										<span class="control-label col-sm-6"><p align="left" class="text-muted"><small><b>Criminal/Civil Case Number</b></small></p>
											<div class="panel panel-default">
												<div class="panel-body" style="background-color:#e7e7e7;" align="left">
													<select style="width:65px; display:inline-block;" class="form-control" id="casetype" name="casetype">
														<option value="CV">CV</option>
														<option value="CR">CR</option>
													</select> -
													<?php list($matno) = mysql_fetch_row(mysql_query("SELECT MAX(cm_number)+1 FROM case_matter")); ?>
													<input type="text" style="width:150px; display:inline-block; border-color:#ccc;" class="form-control" name="casenumber" placeholder="00000000" value="<?php if($matno == null){echo 1;} else {echo $matno;}  ?>" required>
												</div>
											</div>
											<hr/>
										</span>   
									</div>
									
									<label>Matter Client</label>									
									<div class="form-group">
										<span class="control-label col-sm-6"><p align="left" class="text-muted"><small><b>Find Client from Logbook</b></small></p>
										<input list="cltnames" class="form-control" name="clientname" placeholder="Type a name" required>
										<datalist id="cltnames">
											<?php $getclientname = mysql_query("SELECT log_client_fname AS fname, log_client_lname AS lname FROM client_log");
											if(mysql_num_rows($getclientname) > 0){
												while($clname = mysql_fetch_array($getclientname)){
													echo "<option value='" .$clname["lname"] .", " .$clname["fname"] ."'>";
												}
											} else{ ?>
												<option value=""><?php
											}?>
									    </datalist> 
										<hr/>
										</span>   
									</div>	
									
									<label>Matter Information</label>
									<div class="form-group">
										<span class="control-label col-sm-6"><p align="left" class="text-muted"><small><b>Description</b></small></p>
										<textarea class="form-control" name="casedesc" required></textarea></span>
									</div>

									<div class="form-group">
										<span class="control-label col-sm-2" ><p align="left" class="text-muted"><small><b>Open Date</b></small></p>
											<input type="text" name="opendate" class="form-control datepick"  data-provide="datepicker" placeholder="mm/dd/yyyy" required></span>
										
									</div>
									
									<div class="form-group">
										<span class="control-label col-sm-6" ><p align="left" class="text-muted"><small><b>Status</b></small></p>
											<select class="form-control" id="matterstatus" name="matterstatus">
												<option value="New">New</option>
											</select>
										</span>
									</div>

									<div class="form-group">
										<span class="control-label col-sm-6" ><p align="left" class="text-muted"><small><b>Responsible Attorney</b></small></p>
											<select class="form-control" id="matterresponsibleatty" name="matterresponsibleatty" required>
												<?php if($utype=="Staff"){
													$atty = mysql_query("SELECT u.user_id AS id, p.user_fname AS fname, p.user_lname AS lname FROM user u, user_profile p WHERE u.user_type='Attorney' AND u.user_id=p.user_id");
													$adminatty = mysql_query("SELECT a.admin_id AS id, p.admin_fname AS fname, p.admin_lname AS lname FROM admin a, admin_profile p WHERE a.user_type='Administrator' AND a.admin_id=p.admin_id");
													} else{
														$atty = mysql_query("SELECT u.user_id AS id, p.user_fname AS fname, p.user_lname AS lname FROM user u, user_profile p WHERE u.user_type='Attorney' AND u.user_id=p.user_id AND u.user_uname='$uname'");												
													}
												while($respadatty = mysql_fetch_array($adminatty)){?>
													<option value="<?php echo $respadatty['fname'] ." " .$respadatty['lname'];?>"><?php echo $respadatty['fname'] ." " .$respadatty['lname'];?></option> <?php
												
												}
												while($respatty = mysql_fetch_array($atty)){?>
													<option value="<?php echo $respatty['fname'] ." " .$respatty['lname'];?>"><?php echo $respatty['fname'] ." " .$respatty['lname'];?></option> <?php
												
												}?>
											</select>
										<hr/>
										</span>
									</div>
									
									<div class="form-group">.
										<span class="control-label col-sm-4"><button type="submit" class="form-control btn btn-primary">Submit</button></span>
									</div>
								</form>
								
							</div><?php
							} else{ ?>
								<div align="center" style="font-size:24px; color:black;">
									<br/><br/><br/><br/>
									<span class="glyphicon glyphicon-eye-close" style="color:#f1f1f1; font-size:70px;"></span><br/>
									You do not have permission to view this.<br/>
									<small><small><small>Your account is not verified yet. Ask the administrator to verify your account.</small></small></small>
								</div><?php
							} ?>
						</div>
						
					  </div>
					</div>
				</div>

<!--DOCUMENTS-->					
				<div id="documents" name="documents" class="tab-pane fade">
					<div class="container main-container" >
					  <ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#documents_list">List Documents</a></li>
							<li><a data-toggle="tab" href="#documents_template">Templates</a></li>
							
							<li style="float:right;margin:4px;"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#uploaddocu">UPLOAD</button></li>
					  </ul>
					  
					  <div class="tab-content" style="height:420px; overflow-y:auto; overflow-x:hidden;">
						<!--List-->
						<div id="documents_list" class="tab-pane fade in active">
							<div class="container">
								<ul class="nav nav-pills">
									<?php if($utype == "Attorney"){ ?>
										<li class="active"><a data-toggle="pill" href="#mydocs">My Documents</a></li><?php
									} else {
										echo "";
									}?>
									<li <?php if($utype=="Staff"){echo "class='active'";} ?>><a data-toggle="pill" href="#shareddocs">Shared With Me</a></li>
								</ul>
								
                                <div class="tab-content">
								    <!--My Documents-->
									
									<div id="mydocs" class="tab-pane fade <?php if($utype=="Attorney"){echo "in active";} ?>">
									    <?php $limit = 10;
										if(isset($_GET["page"]) && isset($_GET["docu"])){
											if($_GET["docu"] == "own"){
												$page  = $_GET["page"]; 
											} else {
												$page = 1;
											}
										} else{ $page = 1; } 
										
										
										$countdocs = mysql_query("SELECT d.* FROM document d WHERE d.doc_author='$uname' AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) "); 
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countdocs);
									    $total = ceil($count/ $limit); 
										
										$docs = mysql_query("SELECT d.* FROM document d WHERE d.doc_author='$uname' AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) GROUP BY d.doc_name ORDER BY d.doc_date_modified DESC, doc_version DESC LIMIT $start, $limit");
										if(mysql_num_rows($docs) <= 0){?>
											<div align="center" style="font-size:24px; color:black;">
												<br/><br/><br/>
												<span class="glyphicon glyphicon-file" style="color:#f1f1f1; font-size:70px;"></span><br/>
												No current documents available.<br/>
												<small><small><small><?php if($utype=="Attorney"){
													echo "Use the button in the top right to upload new document.";
												} ?></small></small></small>
											</div>	<?php
										} else{ ?>
											<div class="col-sm-12"><div style="height:300px;overflow-y:auto; overflow-x:hidden;">
												<div class="row" style="float:right;">
													<form method="post" action="home.php#mydocs">
													  <div class="input-group col-sm-4 col-sm-offset-7">
														<input type="text" name="txtsearch" class="form-control input-sm" style="margin:0 !important;" placeholder="Search file by name, category, tags or matter" required>
														<div class="input-group-btn">
														  <button class="btn btn-primary btn-sm" name ="btnsearch" type="submit">
															<i class="glyphicon glyphicon-search"></i>
														  </button>
														</div>
													  </div>
													</form>
													<br/>
												</div>  
												<?php if(isset($_POST["btnsearch"])){
													echo "<br><br><div class='panel panel-default' style='overflow-y:auto;'><div class='panel-body'>";
													$txtsearch = $_POST["txtsearch"];
													
													$schnames = mysql_query("SELECT d.* FROM document d WHERE d.doc_author='$uname' AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) AND d.doc_name LIKE '%$txtsearch%' GROUP BY d.doc_name ORDER BY d.doc_date_modified DESC, d.doc_version DESC");
													$schcategories = mysql_query("SELECT d.* FROM document d WHERE d.doc_author='$uname' AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) AND d.doc_cat LIKE '%$txtsearch%' GROUP BY d.doc_name ORDER BY d.doc_date_modified DESC, d.doc_version DESC");
													$schtags = mysql_query("SELECT d.* FROM document d, document_tags t WHERE t.tag_doc_id=d.doc_id AND d.doc_author='$uname' AND t.tag_name LIKE '%$txtsearch%' GROUP BY d.doc_name ORDER BY d.doc_date_modified DESC, d.doc_version DESC");
													$schmatters = mysql_query("SELECT d.* FROM document d, case_matter m WHERE d.doc_matter_tailored_id=m.cm_id AND d.doc_author='$uname' AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) AND m.cm_name LIKE '%$txtsearch%'");
													$schclients = mysql_query("SELECT d.* FROM document d, case_matter m, case_client c WHERE c.cc_matter_id=m.cm_id AND m.cm_id=d.doc_matter_tailored_id AND d.doc_author='$uname' AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) AND c.cc_fname LIKE '%$txtsearch%' OR c.cc_lname LIKE '%$txtsearch%' GROUP BY d.doc_name ORDER BY d.doc_date_modified DESC, d.doc_version DESC");
													
													//count total results
													$schresults = mysql_num_rows($schnames) + mysql_num_rows($schcategories) + mysql_num_rows($schtags) + mysql_num_rows($schmatters) + mysql_num_rows($schclients); 
													if($schresults > 1){
														echo "<small>".$schresults." results found for <b>".$txtsearch."</b>.</small><br>";
													} else {
														echo "<small>".$schresults." result found for <b>".$txtsearch."</b>.</small><br>";
													}
													
													echo "<table class='table table-condensed'>";
													echo "<thead><tr class='active'><th>Name</th><th>Matter</th><th>Category</th><th>Uploaded Date</th><th>Last Edit</th></thead>";
													echo "<tbody>";
													
										// --->		//check for filename
													if(mysql_num_rows($schnames) > 0){
														echo "<tr><td colspan='5' class='active small'>Search results found from <b>filename</b> <sup><span class='badge small'>".mysql_num_rows($schnames)."</span></sup></td></tr>";
														while($schname = mysql_fetch_assoc($schnames)){
															if($schname["doc_type"] == "application/msword" || $schname["doc_type"] == "application/vnd.ms-word"  || $schname["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($schname["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($schname["doc_type"] == "application/vnd.ms-excel" || $schname["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($schname["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$schname["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($schname["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															$dmtid = $schname["doc_matter_tailored_id"];
															list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
															
															
															if ($schname["doc_size"] >= 1073741824){
																$bytes = number_format($schname["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($schname["doc_size"] >= 1048576){
																$bytes = number_format($schname["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($schname["doc_size"] >= 1024){ 
																$bytes = number_format($schname["doc_size"] / 1024, 2) . ' KB';
															}elseif ($schname["doc_size"] > 1){
																$bytes = $schname["doc_size"] . ' bytes';
															}elseif ($schname["doc_size"] == 1){
																$bytes = $schname["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schname["doc_parent_id"]."'");
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$schname["doc_size"]." bytes)</b><br/>Owner: <b>Me</b><br/>Modified: <b>".date("F d, Y", strtotime($schname["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($schname["doc_date_created"]))."</b><br>Version ".$schname."<br/><br>Tags:<br/>";
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {
																			echo "";
																		} ;?>">
																		<?php echo $schname["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-id="<?php echo $schname["doc_parent_id"];?>" data-name="<?php echo $schname["doc_name"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schname["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schname["doc_name"];?>" data-target="#docshare"> Share </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schname["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schname["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																		<?php $basename = pathinfo($schname["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($schname["doc_path"], PATHINFO_EXTENSION); 
																		$baseunver = explode("__VERSION", $basename); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $schname['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																		<a href="#" class="documentmodal" data-id="<?php echo $schname["doc_id"]; ?>" data-todo="<?php echo $schname["doc_name"];?>" data-toggle="modal" data-target="#docdelete"> Delete </a>&nbsp;
																		<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $schname["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																		<?php list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
																	    <a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a>
																	</small>
																	<div id="doctags<?php echo $schname["doc_parent_id"];?>" class="collapse"><small>
																	<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schname["doc_parent_id"]."'");
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {echo "";}?>
																		<form method="post" action="tags.php">
																			<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																			<input type="hidden" name="docid" value="<?php echo $schname["doc_parent_id"];?>">
																			<input type="hidden" name="action" value="add">
																			<input type="hidden" name="view" value="users">
																			<input type="hidden" name="docu" value="own">
																			<input type="hidden" name="page" value="<?php echo $page;?>">
																			<input type="submit" style="display:none;" value="Submit">
																		</form>
																	</small>
																</div>
																</td>
																
																<td><?php $matter = mysql_query("SELECT cm.*, cc.*, d.* FROM case_matter cm, case_client cc, designation d WHERE cm.cm_id=cc.cc_matter_id AND d.des_id=cc.cc_location AND cm.cm_name='$doccase'");
																	if(mysql_num_rows($matter) > 0){
																		while($cm = mysql_fetch_array($matter)){ 
																			echo "<p class='description'><span class='black-link'>";
																				echo "<a href='#' title='Details' data-toggle='popover' data-trigger='focus' data-html='true' data-content='";
																					$caseheader = explode("-", $cm['cm_name']);
																					$headr = $caseheader[0];
																					$numbr = $caseheader[1];
																					if($headr == "CV"){
																						echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else if($headr == "CR"){
																						echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else {echo "";}
																					
																					echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																					echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																					
																					if($cm["cm_status"] == 'New'){
																						echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																					} else if($cm["cm_status"] == 'Pending'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																						} else{
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																						}
																					} else if($cm["cm_status"] == 'Dismissed'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						} else {
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						}
																					}
																					
																					echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																		echo "'>";
																		} 
																	}
																	 
																	echo $doccase;?></span></td>
																<td><?php echo $schname["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($schname["doc_date_created"]));?></td>
																<td><?php echo date("m/d/Y", strtotime($schname["doc_date_modified"]));?></td>
															</tr><?php
														
														}
													}
													
										// --->		//check for category	
													if(mysql_num_rows($schcategories) > 0){
														echo "<tr><td colspan='5' class='active small'>Search results found from <b>category</b> <sup><span class='badge small'>".mysql_num_rows($schcategories)."</span></sup></td></tr>";
														while($schcategory = mysql_fetch_assoc($schcategories)){
															if($schcategory["doc_type"] == "application/msword" || $schcategory["doc_type"] == "application/vnd.ms-word" || $schcategory["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($schcategory["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($schcategory["doc_type"] == "application/vnd.ms-excel" || $schcategory["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($schcategory["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$schcategory["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($schcategory["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															$dmtid = $schcategory["doc_matter_tailored_id"];
															list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
															
															
															if ($schcategory["doc_size"] >= 1073741824){
																$bytes = number_format($schcategory["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($schcategory["doc_size"] >= 1048576){
																$bytes = number_format($schcategory["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($schcategory["doc_size"] >= 1024){ 
																$bytes = number_format($schcategory["doc_size"] / 1024, 2) . ' KB';
															}elseif ($schcategory["doc_size"] > 1){
																$bytes = $schcategory["doc_size"] . ' bytes';
															}elseif ($schcategory["doc_size"] == 1){
																$bytes = $schcategory["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schcategory["doc_parent_id"]."'");
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$schcategory["doc_size"]." bytes)</b><br/>Owner: <b>Me</b><br/>Modified: <b>".date("F d, Y", strtotime($schcategory["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($schcategory["doc_date_created"]))."</b><br>Version ".$schcategory["doc_version"]."<br/><br>Tags:<br/>";
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {
																			echo "";
																		} ;?>">
																		<?php echo $schcategory["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-id="<?php echo $schcategory["doc_parent_id"];?>" data-name="<?php echo $schcategory["doc_name"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schcategory["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schcategory["doc_name"];?>" data-target="#docshare"> Share </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schcategory["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schcategory["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																		<?php $basename = pathinfo($schcategory["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($schcategory["doc_path"], PATHINFO_EXTENSION);
																		$baseunver = explode("__VERSION", $basename); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $schcategory['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																		<a href="#" class="documentmodal" data-id="<?php echo $schcategory["doc_id"]; ?>" data-todo="<?php echo $schcategory["doc_name"];?>" data-toggle="modal" data-target="#docdelete"> Delete </a>&nbsp;
																		<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $schcategory["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																		<?php list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
																	    <a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a>
																	</small>
																	<div id="doctags<?php echo $schcategory["doc_parent_id"];?>" class="collapse"><small>
																	<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schcategory["doc_parent_id"]."'");
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {echo "";}?>
																		<form method="post" action="tags.php">
																			<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																			<input type="hidden" name="docid" value="<?php echo $schcategory["doc_parent_id"];?>">
																			<input type="hidden" name="action" value="add">
																			<input type="hidden" name="view" value="users">
																			<input type="hidden" name="docu" value="own">
																			<input type="hidden" name="page" value="<?php echo $page;?>">
																			<input type="submit" style="display:none;" value="Submit">
																		</form>
																	</small>
																</div>
																</td>
																
																<td><?php $matter = mysql_query("SELECT cm.*, cc.*, d.* FROM case_matter cm, case_client cc, designation d WHERE cm.cm_id=cc.cc_matter_id AND d.des_id=cc.cc_location AND cm.cm_name='$doccase'");
																	if(mysql_num_rows($matter) > 0){
																		while($cm = mysql_fetch_array($matter)){ 
																			echo "<p class='description'><span class='black-link'>";
																				echo "<a href='#' title='Details' data-toggle='popover' data-trigger='focus' data-html='true' data-content='";
																					$caseheader = explode("-", $cm['cm_name']);
																					$headr = $caseheader[0];
																					$numbr = $caseheader[1];
																					if($headr == "CV"){
																						echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else if($headr == "CR"){
																						echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else {echo "";}
																					
																					echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																					echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																					
																					if($cm["cm_status"] == 'New'){
																						echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																					} else if($cm["cm_status"] == 'Pending'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																						} else{
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																						}
																					} else if($cm["cm_status"] == 'Dismissed'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						} else {
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						}
																					}
																					
																					echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																		echo "'>";
																		} 
																	}
																	 
																	echo $doccase;?></span></td>
																<td><?php echo $schcategory["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($schcategory["doc_date_created"]));?></td>
																<td><?php echo date("m/d/Y", strtotime($schcategory["doc_date_modified"]));?></td>
															</tr><?php
														
														}
													}
													
										// --->		//check for tags	
													if(mysql_num_rows($schtags) > 0){
														echo "<tr><td colspan='5' class='active small'>Search results found from <b>tags</b> <sup><span class='badge small'>".mysql_num_rows($schtags)."</span></sup></td></tr>";
														while($schtag = mysql_fetch_assoc($schtags)){
															if($schtag["doc_type"] == "application/msword" || $schtag["doc_type"] == "application/vnd.ms-word" || $schtag["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($schtag["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($schtag["doc_type"] == "application/vnd.ms-excel" || $schtag["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($schtag["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$schtag["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($schtag["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															$dmtid = $schtag["doc_matter_tailored_id"];
															list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
															
															
															if ($schtag["doc_size"] >= 1073741824){
																$bytes = number_format($schtag["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($schtag["doc_size"] >= 1048576){
																$bytes = number_format($schtag["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($schtag["doc_size"] >= 1024){ 
																$bytes = number_format($schtag["doc_size"] / 1024, 2) . ' KB';
															}elseif ($schtag["doc_size"] > 1){
																$bytes = $schtag["doc_size"] . ' bytes';
															}elseif ($schtag["doc_size"] == 1){
																$bytes = $schtag["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schtag["doc_parent_id"]."'");
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$schtag["doc_size"]." bytes)</b><br/>Owner: <b>Me</b><br/>Modified: <b>".date("F d, Y", strtotime($schtag["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($schtag["doc_date_created"]))."</b><br/>Version ".$schtag["doc_version"]."<br/><br>Tags:<br/>";
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {
																			echo "";
																		} ;?>">
																		<?php echo $schtag["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-id="<?php echo $schtag["doc_parent_id"];?>" data-name="<?php echo $schtag["doc_name"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schtag["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schtag["doc_name"];?>" data-target="#docshare"> Share </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schtag["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schtag["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																		<?php $basename = pathinfo($schtag["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($schtag["doc_path"], PATHINFO_EXTENSION); 
																		$baseunver = explode("__VERSION", $basename); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $schtag['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																		<a href="#" class="documentmodal" data-id="<?php echo $schtag["doc_id"]; ?>" data-todo="<?php echo $schtag["doc_name"];?>" data-toggle="modal" data-target="#docdelete"> Delete </a>&nbsp;
																		<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $schtag["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																		<?php list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
																	    <a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a>
																	</small>
																	<div id="doctags<?php echo $schtag["doc_parent_id"];?>" class="collapse"><small>
																	<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schtag["doc_parent_id"]."'");
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {echo "";}?>
																		<form method="post" action="tags.php">
																			<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																			<input type="hidden" name="docid" value="<?php echo $schtag["doc_id"];?>">
																			<input type="hidden" name="action" value="add">
																			<input type="hidden" name="view" value="users">
																			<input type="hidden" name="docu" value="own">
																			<input type="hidden" name="page" value="<?php echo $page;?>">
																			<input type="submit" style="display:none;" value="Submit">
																		</form>
																	</small>
																</div>
																</td>
																
																<td><?php $matter = mysql_query("SELECT cm.*, cc.*, d.* FROM case_matter cm, case_client cc, designation d WHERE cm.cm_id=cc.cc_matter_id AND d.des_id=cc.cc_location AND cm.cm_name='$doccase'");
																	if(mysql_num_rows($matter) > 0){
																		while($cm = mysql_fetch_array($matter)){ 
																			echo "<p class='description'><span class='black-link'>";
																				echo "<a href='#' title='Details' data-toggle='popover' data-trigger='focus' data-html='true' data-content='";
																					$caseheader = explode("-", $cm['cm_name']);
																					$headr = $caseheader[0];
																					$numbr = $caseheader[1];
																					if($headr == "CV"){
																						echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else if($headr == "CR"){
																						echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else {echo "";}
																					
																					echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																					echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																					
																					if($cm["cm_status"] == 'New'){
																						echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																					} else if($cm["cm_status"] == 'Pending'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																						} else{
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																						}
																					} else if($cm["cm_status"] == 'Dismissed'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						} else {
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						}
																					}
																					
																					echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																		echo "'>";
																		} 
																	}
																	 
																	echo $doccase;?></span></td>
																<td><?php echo $schtag["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($schtag["doc_date_created"]));?></td>
																<td><?php echo date("m/d/Y", strtotime($schtag["doc_date_modified"]));?></td>
															</tr><?php
														
														}
													}
																
													
										// --->		//check for matters	
													if(mysql_num_rows($schmatters) > 0){
														echo "<tr><td colspan='5' class='active small'>Search results found from <b>matters</b> <sup><span class='badge small'>".mysql_num_rows($schmatters)."</span></sup></td></tr>";
														while($schmatter = mysql_fetch_assoc($schmatters)){
															if($schmatter["doc_type"] == "application/msword" || $schmatter["doc_type"] == "application/vnd.ms-word" || $schmatter["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($schmatter["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($schmatter["doc_type"] == "application/vnd.ms-excel" || $schmatter["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($schmatter["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$schmatter["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($schmatter["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															$dmtid = $schmatter["doc_matter_tailored_id"];
															list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
															
															
															if ($schmatter["doc_size"] >= 1073741824){
																$bytes = number_format($schmatter["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($schmatter["doc_size"] >= 1048576){
																$bytes = number_format($schmatter["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($schmatter["doc_size"] >= 1024){ 
																$bytes = number_format($schmatter["doc_size"] / 1024, 2) . ' KB';
															}elseif ($schmatter["doc_size"] > 1){
																$bytes = $schmatter["doc_size"] . ' bytes';
															}elseif ($schmatter["doc_size"] == 1){
																$bytes = $schmatter["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schmatter["doc_parent_id"]."'");
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$schmatter["doc_size"]." bytes)</b><br/>Owner: <b>Me</b><br/>Modified: <b>".date("F d, Y", strtotime($schmatter["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($schmatter["doc_date_created"]))."</b><br/>Version ".$schmatter["doc_version"]."<br/><br>Tags:<br/>";
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {
																			echo "";
																		} ;?>">
																		<?php echo $schmatter["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-id="<?php echo $schmatter["doc_parent_id"];?>" data-name="<?php echo $schmatter["doc_name"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schmatter["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schmatter["doc_name"];?>" data-target="#docshare"> Share </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schmatter["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schmatter["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																		<?php $basename = pathinfo($schmatter["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($schmatter["doc_path"], PATHINFO_EXTENSION); 
																		$baseunver = explode("__VERSION", $basename); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $schmatter['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																		<a href="#" class="documentmodal" data-id="<?php echo $schmatter["doc_id"]; ?>" data-todo="<?php echo $schmatter["doc_name"];?>" data-toggle="modal" data-target="#docdelete"> Delete </a>&nbsp;
																		<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $schmatter["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																		<?php list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
																	    <a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a>
																	</small>
																	<div id="doctags<?php echo $schmatter["doc_parent_id"];?>" class="collapse"><small>
																	<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schmatter["doc_parent_id"]."'");
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {echo "";}?>
																		<form method="post" action="tags.php">
																			<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																			<input type="hidden" name="docid" value="<?php echo $schmatter["doc_parent_id"];?>">
																			<input type="hidden" name="action" value="add">
																			<input type="hidden" name="view" value="users">
																			<input type="hidden" name="docu" value="own">
																			<input type="hidden" name="page" value="<?php echo $page;?>">
																			<input type="submit" style="display:none;" value="Submit">
																		</form>
																	</small>
																</div>
																</td>
																
																<td><?php $matter = mysql_query("SELECT cm.*, cc.*, d.* FROM case_matter cm, case_client cc, designation d WHERE cm.cm_id=cc.cc_matter_id AND d.des_id=cc.cc_location AND cm.cm_name='$doccase'");
																	if(mysql_num_rows($matter) > 0){
																		while($cm = mysql_fetch_array($matter)){ 
																			echo "<p class='description'><span class='black-link'>";
																				echo "<a href='#' title='Details' data-toggle='popover' data-trigger='focus' data-html='true' data-content='";
																					$caseheader = explode("-", $cm['cm_name']);
																					$headr = $caseheader[0];
																					$numbr = $caseheader[1];
																					if($headr == "CV"){
																						echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else if($headr == "CR"){
																						echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else {echo "";}
																					
																					echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																					echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																					
																					if($cm["cm_status"] == 'New'){
																						echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																					} else if($cm["cm_status"] == 'Pending'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																						} else{
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																						}
																					} else if($cm["cm_status"] == 'Dismissed'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						} else {
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						}
																					}
																					
																					echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																		echo "'>";
																		} 
																	}
																	 
																	echo $doccase;?></span></td>
																<td><?php echo $schmatter["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($schmatter["doc_date_created"]));?></td>
																<td><?php echo date("m/d/Y", strtotime($schmatter["doc_date_modified"]));?></td>
															</tr><?php
														
														}
													}			
													
										// --->		//check for clients
													if(mysql_num_rows($schclients) > 0){
														echo "<tr><td colspan='5' class='active small'>Search results found from <b>client names</b> <sup><span class='badge small'>".mysql_num_rows($schclients)."</span></sup></td></tr>";
														while($schclient = mysql_fetch_assoc($schclients)){
															if($schclient["doc_type"] == "application/msword" || $schclient["doc_type"] == "application/vnd.ms-word" || $schclient["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($schclient["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($schclient["doc_type"] == "application/vnd.ms-excel" || $schclient["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($schclient["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$schclient["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($schclient["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															$dmtid = $schclient["doc_matter_tailored_id"];
															list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
															
															
															if ($schclient["doc_size"] >= 1073741824){
																$bytes = number_format($schclient["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($schclient["doc_size"] >= 1048576){
																$bytes = number_format($schclient["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($schclient["doc_size"] >= 1024){ 
																$bytes = number_format($schclient["doc_size"] / 1024, 2) . ' KB';
															}elseif ($schclient["doc_size"] > 1){
																$bytes = $schclient["doc_size"] . ' bytes';
															}elseif ($schclient["doc_size"] == 1){
																$bytes = $schclient["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schclient["doc_parent_id"]."'");
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$schclient["doc_size"]." bytes)</b><br/>Owner: <b>Me</b><br/>Modified: <b>".date("F d, Y", strtotime($schclient["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($schclient["doc_date_created"]))."</b><br/>Version ".$schclient["doc_version"]."<br/><br>Tags:<br/>";
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {
																			echo "";
																		} ;?>">
																		<?php echo $schclient["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-id="<?php echo $schclient["doc_parent_id"];?>" data-name="<?php echo $schclient["doc_name"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schclient["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schclient["doc_name"];?>" data-target="#docshare"> Share </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $schclient["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $schclient["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																		<?php $basename = pathinfo($schclient["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($schclient["doc_path"], PATHINFO_EXTENSION);
																		$baseunver = explode("__VERSION", $basename); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $schclient['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																		<a href="#" class="documentmodal" data-id="<?php echo $schclient["doc_id"]; ?>" data-todo="<?php echo $schclient["doc_name"];?>" data-toggle="modal" data-target="#docdelete"> Delete </a>&nbsp;
																		<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $schclient["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																		<?php list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
																	    <a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a>
																	</small>
																	<div id="doctags<?php echo $schclient["doc_parent_id"];?>" class="collapse"><small>
																	<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$schclient["doc_parent_id"]."'");
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {echo "";}?>
																		<form method="post" action="tags.php">
																			<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																			<input type="hidden" name="docid" value="<?php echo $schclient["doc_parent_id"];?>">
																			<input type="hidden" name="action" value="add">
																			<input type="hidden" name="view" value="users">
																			<input type="hidden" name="docu" value="own">
																			<input type="hidden" name="page" value="<?php echo $page;?>">
																			<input type="submit" style="display:none;" value="Submit">
																		</form>
																	</small>
																</div>
																</td>
																
																<td><?php $matter = mysql_query("SELECT cm.*, cc.*, d.* FROM case_matter cm, case_client cc, designation d WHERE cm.cm_id=cc.cc_matter_id AND d.des_id=cc.cc_location AND cm.cm_name='$doccase'");
																	if(mysql_num_rows($matter) > 0){
																		while($cm = mysql_fetch_array($matter)){ 
																			echo "<p class='description'><span class='black-link'>";
																				echo "<a href='#' title='Details' data-toggle='popover' data-trigger='focus' data-html='true' data-content='";
																					$caseheader = explode("-", $cm['cm_name']);
																					$headr = $caseheader[0];
																					$numbr = $caseheader[1];
																					if($headr == "CV"){
																						echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else if($headr == "CR"){
																						echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																					} else {echo "";}
																					
																					echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																					echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																					
																					if($cm["cm_status"] == 'New'){
																						echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																					} else if($cm["cm_status"] == 'Pending'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																						} else{
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																						}
																					} else if($cm["cm_status"] == 'Dismissed'){
																						if($cm["cm_date_pending"] == "0000-00-00"){
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						} else {
																							echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																						}
																					}
																					
																					echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																		echo "'>";
																		} 
																	}
																	 
																	echo $doccase;?></span></td>
																<td><?php echo $schclient["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($schclient["doc_date_created"]));?></td>
																<td><?php echo date("m/d/Y", strtotime($schclient["doc_date_modified"]));?></td>
															</tr><?php
														
														}
													}			
													
													echo "</tbody></table>";
													echo "<br><label class='small text-primary'>Click on the page number <label class='label label-warning'>1</label> at the bottom to redisplay previous table.</label></div></div>";
												} ?>
												
												  
												<table class="table" style="<?php if(isset($_POST["btnsearch"])){echo "display:none;";}?>">
													<thead>
														<tr class="active">
															<th>Name</th>
															<th>Matter</th>
															<th>Category</th>
															<th>Uploaded Date</th>
															<th>Last Edit</th>
														</tr>
													</thead>
													<tbody>
														<br/><?php
													while($doc = mysql_fetch_array($docs)){ 
														if($doc["doc_type"] == "application/msword" || $doc["doc_type"] == "application/vnd.ms-word" || $doc["doc_type"] == "application/vnd.open"){
															$icon = "fa fa-file-word-o";
															$type = "Word Document";
														} else if($doc["doc_type"] == "application/pdf"){
															$icon = "fa fa-file-pdf-o";
															$type = "PDF File";
														} else if($doc["doc_type"] == "application/vnd.ms-excel" || $doc["doc_type"] == "application/vnd.ms-e"){
															$icon = "fa fa-file-excel-o";
															$type = "Excel Worksheet";
														} else if(strpos($doc["doc_type"], "image") !== false){
															$icon = "fa fa-file-image-o";
															$images = explode("/",$doc["doc_type"]);
															$type = strtoupper($images[1])." File";
														} else if($doc["doc_type"] == "text/plain"){
															$icon = "fa fa-file-text-o";
															$type = "Text Document";
														} else { $icon = "fa fa-file-o";} 
														
														$dmtid = $doc["doc_matter_tailored_id"];
														list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
														
														
														if ($doc["doc_size"] >= 1073741824){
															$bytes = number_format($doc["doc_size"] / 1073741824, 2) . ' GB';
														}
														elseif ($doc["doc_size"] >= 1048576){
															$bytes = number_format($doc["doc_size"] / 1048576, 2) . ' MB';
														}
														elseif ($doc["doc_size"] >= 1024){
															$bytes = number_format($doc["doc_size"] / 1024, 2) . ' KB';
														}elseif ($doc["doc_size"] > 1){
															$bytes = $doc["doc_size"] . ' bytes';
														}elseif ($doc["doc_size"] == 1){
															$bytes = $doc["doc_size"] . ' byte';
														}else{
															$bytes = '0 bytes';
														} ?>
												
														<tr>
															<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
															    <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																	$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$doc["doc_parent_id"]."'");
																	echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$doc["doc_size"]." bytes)</b><br/>Owner: <b>Me</b><br/>Modified: <b>".date("F d, Y", strtotime($doc["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($doc["doc_date_created"]))."</b><br/>Version ".$doc["doc_version"]."<br/><br>Tags:<br/>";
																	if(mysql_num_rows($tags) > 0){
																		while($tag = mysql_fetch_assoc($tags)){
																			echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																		}
																	} else {
																		echo "";
																	} ;?>">
																	<?php echo $doc["doc_name"];?></a></span></p>
																<small><a href="#" id="detailsmodal" data-id="<?php echo $doc["doc_parent_id"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																	<a href="#" class="documentmodal" data-id="<?php echo $doc["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $doc["doc_name"];?>" data-target="#docshare"> Share </a>&nbsp; 
																	<a href="#" class="documentmodal" data-id="<?php echo $doc["doc_id"]; ?>" data-toggle="modal" data-todo="<?php echo $doc["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																	<?php $basename = pathinfo($doc["doc_path"], PATHINFO_FILENAME);
																	$extension = pathinfo($doc["doc_path"], PATHINFO_EXTENSION);
																	$baseunver = explode("__VERSION", $basename); ?>
																	<a href="#" id="renamedoc" data-id="<?php echo $doc['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																	<a href="#" class="documentmodal" data-id="<?php echo $doc["doc_id"]; ?>" data-todo="<?php echo $doc["doc_name"];?>" data-toggle="modal" data-target="#docdelete"> Delete </a>&nbsp;
																	<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $doc["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																	<?php list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
																	<a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a>
																</small>
																<div id="doctags<?php echo $doc["doc_parent_id"];?>" class="collapse"><small>
																<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$doc["doc_parent_id"]."'");
																	if(mysql_num_rows($tags) > 0){
																		while($tag = mysql_fetch_assoc($tags)){
																			echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																		}
																	} else {echo "";}?>
																	<form method="post" action="tags.php">
																		<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																		<input type="hidden" name="docid" value="<?php echo $doc["doc_parent_id"];?>">
																		<input type="hidden" name="action" value="add">
																		<input type="hidden" name="view" value="users">
																		<input type="hidden" name="docu" value="own">
																		<input type="hidden" name="page" value="<?php echo $page;?>">
																		<input type="submit" style="display:none;" value="Submit">
																	</form>
																</small>
															</div>
															</td>
															
															<td><?php $matter = mysql_query("SELECT cm.*, cc.*, d.* FROM case_matter cm, case_client cc, designation d WHERE cm.cm_id=cc.cc_matter_id AND d.des_id=cc.cc_location AND cm.cm_name='$doccase'");
																if(mysql_num_rows($matter) > 0){
																	while($cm = mysql_fetch_array($matter)){ 
																		echo "<p class='description'><span class='black-link'>";
																			echo "<a href='#' title='Details' data-toggle='popover' data-trigger='focus' data-html='true' data-content='";
																				$caseheader = explode("-", $cm['cm_name']);
																				$headr = $caseheader[0];
																				$numbr = $caseheader[1];
																				if($headr == "CV"){
																					echo "<b>Civil Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																				} else if($headr == "CR"){
																					echo "<b>Criminal Case</b> No. <b>" .$numbr ."</b><br/><br/>";
																				} else {echo "";}
																				
																				echo "Description: <b>" .$cm["cm_description"] ."</b><br/><br/>";
																				echo "Status: <b>".$cm["cm_status"] ."</b><br/><br/>";
																				
																				if($cm["cm_status"] == 'New'){
																					echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/><br/>";
																				} else if($cm["cm_status"] == 'Pending'){
																					if($cm["cm_date_pending"] == "0000-00-00"){
																						echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <br/><br/>";
																					} else{
																						echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/><br/>";
																					}
																				} else if($cm["cm_status"] == 'Dismissed'){
																					if($cm["cm_date_pending"] == "0000-00-00"){
																						echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																					} else {
																						echo "Open Date: <b>" .date("F d, Y", strtotime($cm['cm_date_created'])) ."</b><br/>Pending Date: <b>" .date("F d, Y", strtotime($cm['cm_date_pending'])) ."</b><br/> Close Date: <b>" .date("F d, Y", strtotime($cm['cm_date_dismissed'])) ."</b><br/><br/>";
																					}
																				}
																				
																				echo "Responsible Attorney: <b>" .$cm["cm_resp_atty"] ."</b><br/> Originating Attorney: <b>" .$cm["cm_orig_atty"] ."</b><br/>";
																	echo "'>";
																	} 
																}
																 
																echo $doccase;?></span></td>
															<td><?php echo $doc["doc_cat"];?></td>
															<td><?php echo date("m/d/Y", strtotime($doc["doc_date_created"]));?></td>
															<td><?php echo date("m/d/Y", strtotime($doc["doc_date_modified"]));?></td>
														</tr><?php
													} ?>
													
														<input type="hidden" name="createzip" value="createzip">
													
														</tbody>
													</table>
													
											</div></div><?php
										} ?>
										
										<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
											<ul class="pagination pagination-sm">
												<?php for($i = 1; $i<=$total; $i++){
													if($page == $i){
														echo "<li class='active'>";
													} else {
														echo "<li>";
													}
													echo "<a href='home.php?docu=own&page=$i#mydocs'>$i</a></li>";
												} ?>
												
											</ul>
										</div>
										
									</div>
									
									<!--Shared Documents-->
									<div id="shareddocs" class="tab-pane fade <?php if($utype=="Staff"){echo "in active";} ?>">
									    <?php $limit = 10;
										if(isset($_GET["page"]) && isset($_GET["docu"])){
											if($_GET["docu"] == "shared"){
												$page  = $_GET["page"]; 
											} else {
												$page = 1;
											}
										} else{ $page = 1; } 
										
										
										$countdocs = mysql_query("SELECT d.* FROM document d, document_share s WHERE(d.doc_sharing_opt=2 OR s.ds_doc_shared_user='$uname')AND NOT doc_author='$uname' AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) AND s.ds_doc_id=d.doc_id GROUP BY d.doc_name"); 
										$start = ($page-1) * $limit; 
									    $count = mysql_num_rows($countdocs);
									    $total = ceil($count/ $limit); 

										$docs = mysql_query("SELECT d.* FROM document d, document_share s WHERE(d.doc_sharing_opt=2 OR s.ds_doc_shared_user='$uname')AND NOT doc_author='$uname' AND s.ds_doc_id=d.doc_id AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) GROUP BY d.doc_name ORDER BY d.doc_date_modified DESC, d.doc_version DESC LIMIT $start, $limit");
										if(mysql_num_rows($docs) <= 0){?>
											<div align="center" style="font-size:24px; color:black;">
												<br/><br/><br/>
												<span class="glyphicon glyphicon-file" style="color:#f1f1f1; font-size:70px;"></span><br/>
												No current documents available.<br/>
												<small><small><small>No documents has been shared with you.</small></small></small>
											</div>	<?php
										} else{ ?>
											<div class="col-sm-12"><div style="height:300px;overflow-y:auto; overflow-x:hidden;">
	                                            <div class="row" style="float:right;">
													<form method="post" action="home.php#shareddocs">
													  <div class="input-group col-sm-4 col-sm-offset-7">
														<input type="text" name="txtsearchshared" class="form-control input-sm" style="margin:0 !important;" placeholder="Search file by name, category or tags" required>
														<div class="input-group-btn">
														  <button class="btn btn-primary btn-sm" name ="btnsearchshared" type="submit">
															<i class="glyphicon glyphicon-search"></i>
														  </button>
														</div>
													  </div>
													</form>
													<br/>
												</div><?php 
												
												if(isset($_POST["btnsearchshared"])){
													echo "<br><br><div class='panel panel-default' style='overflow-y:auto;'><div class='panel-body'>";
													$txtsearchshared = $_POST["txtsearchshared"];
													
													$sharedschnames = mysql_query("SELECT d.* FROM document d, document_share s WHERE NOT doc_author='$uname' AND (d.doc_sharing_opt=2 OR s.ds_doc_shared_user='$uname') AND s.ds_doc_id=d.doc_id AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) AND d.doc_name LIKE '%$txtsearchshared%' GROUP BY doc_name ORDER BY d.doc_date_modified DESC, d.doc_version DESC");
													$sharedschcategories = mysql_query("SELECT d.* FROM document d, document_share s WHERE NOT doc_author='$uname' AND (d.doc_sharing_opt=2 OR s.ds_doc_shared_user='$uname') AND s.ds_doc_id=d.doc_id AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) AND d.doc_cat LIKE '%$txtsearchshared%' GROUP BY d.doc_name ORDER BY d.doc_date_modified DESC, d.doc_version DESC");
													$sharedschtags = mysql_query("SELECT d.* FROM document d, document_tags t, document_share s WHERE NOT doc_author='$uname' AND t.tag_doc_id=d.doc_id AND (d.doc_sharing_opt=2 OR s.ds_doc_shared_user='$uname') AND s.ds_doc_id=d.doc_id AND d.doc_version=(SELECT MAX(x.doc_version) FROM document x WHERE x.doc_parent_id=d.doc_parent_id) AND t.tag_name LIKE '%$txtsearchshared%' GROUP BY d.doc_name ORDER BY d.doc_date_modified DESC, d.doc_version DESC");
													
													
													//count total results
													$sharedschresults = mysql_num_rows($sharedschnames) + mysql_num_rows($sharedschcategories) + mysql_num_rows($sharedschtags); 
													if($sharedschresults > 1){
														echo "<small>".$sharedschresults." results found for <b>".$txtsearchshared."</b>.</small><br>";
													} else {
														echo "<small>".$sharedschresults." result found for <b>".$txtsearchshared."</b>.</small><br>";
													}
													
													echo "<table class='table table-condensed'>";
													echo "<thead><tr class='active'><th>Description</th><th>Owner</th><th>Category</th><th>Uploaded Date</th><th>Last Edit</th></thead>";
													echo "<tbody>";
													
									////////////	//check for filename
													if(mysql_num_rows($sharedschnames) > 0){
														echo "<tr><td colspan='5' class='active small'>Search results found from <b>filename</b> <sup><span class='badge small'>".mysql_num_rows($sharedschnames)."</span></sup></td></tr>";
														while($sharedschname = mysql_fetch_assoc($sharedschnames)){
															if($sharedschname["doc_type"] == "application/msword" || $sharedschname["doc_type"] == "application/vnd.ms-word" || $sharedschname["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($sharedschname["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($sharedschname["doc_type"] == "application/vnd.ms-excel" || $sharedschname["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($sharedschname["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$sharedschname["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($sharedschname["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															$dmtid = $sharedschname["doc_matter_tailored_id"];
															list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
															
															
															if ($sharedschname["doc_size"] >= 1073741824){
																$bytes = number_format($sharedschname["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($sharedschname["doc_size"] >= 1048576){
																$bytes = number_format($sharedschname["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($sharedschname["doc_size"] >= 1024){ 
																$bytes = number_format($sharedschname["doc_size"] / 1024, 2) . ' KB';
															}elseif ($sharedschname["doc_size"] > 1){
																$bytes = $sharedschname["doc_size"] . ' bytes';
															}elseif ($sharedschname["doc_size"] == 1){
																$bytes = $sharedschname["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$sharedschname["doc_parent_id"]."'");
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$sharedschname["doc_size"]." bytes)</b><br/>Modified: <b>".date("F d, Y", strtotime($sharedschname["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($sharedschname["doc_date_created"]))."</b><br>Version ".$sharedschname["doc_version"]."<br/><br>Tags:<br/>";
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {
																			echo "";
																		} ;?>">
																		<?php echo $sharedschname["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-id="<?php echo $sharedschname["doc_parent_id"];?>" data-name="<?php echo $sharedschname["doc_name"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $sharedschname["doc_id"]; ?>" data-toggle="modal" data-todo="<?php echo $sharedschname["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																		<?php $basename = pathinfo($sharedschname["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($sharedschname["doc_path"], PATHINFO_EXTENSION); 
																		$baseunver = explode("__VERSION", $basename); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $sharedschname['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																		<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $sharedschname["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																		<?php list($fldradid) = mysql_fetch_row(mysql_query("SELECT admin_id FROM admin WHERE admin_uname='".$sharedschname["doc_author"]."'"));
																			list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='".$sharedschname["doc_author"]."'"));
																			if(empty($flradid)){?>
																				<a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a><?php
																			} else { ?>
																				<a href="download.php?folder=docs/<?php echo $fldradid."ad";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a><?php
																			} ?>
																	</small>
																	<div id="doctags<?php echo $sharedschname["doc_parent_id"];?>" class="collapse"><small>
																	<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$sharedschname["doc_parent_id"]."'");
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {echo "";}?>
																		<form method="post" action="tags.php">
																			<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																			<input type="hidden" name="docid" value="<?php echo $sharedschname["doc_parent_id"];?>">
																			<input type="hidden" name="action" value="add">
																			<input type="hidden" name="view" value="users">
																			<input type="hidden" name="docu" value="own">
																			<input type="hidden" name="page" value="<?php echo $page;?>">
																			<input type="submit" style="display:none;" value="Submit">
																		</form>
																	</small>
																</div>
																</td>
																
																<td><?php $getadminowner = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND a.admin_uname='".$sharedschname["doc_author"]."'");
																$getowner = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='".$sharedschname["doc_author"]."'");
																if(mysql_num_rows($getowner) > 0 || mysql_num_rows($getadminowner) > 0){
																	if(empty($getadminowner)){
																		list($ownerfname, $ownerlname) = mysql_fetch_row($getowner);
																		echo $ownerfname." ".$ownerlname;
																	} else {
																		list($ownerfname, $ownerlname) = mysql_fetch_row($getadminowner);
																		echo $ownerfname." ".$ownerlname;
																	}
																} else {
																	echo "<span class='text-danger'>Can't display owner name</span>"; }?>
																</td>
																<td><?php echo $sharedschname["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($sharedschname["doc_date_created"]));?></td>
																<td><?php echo date("m/d/Y", strtotime($sharedschname["doc_date_modified"]));?></td>
															</tr><?php
														
														}
													}

									////////////	//check for category
													if(mysql_num_rows($sharedschcategories) > 0){
														echo "<tr><td colspan='5' class='active small'>Search results found from <b>category</b> <sup><span class='badge small'>".mysql_num_rows($sharedschcategories)."</span></sup></td></tr>";
														while($sharedschcategory = mysql_fetch_assoc($sharedschcategories)){
															if($sharedschcategory["doc_type"] == "application/msword" || $sharedschcategory["doc_type"] == "application/vnd.ms-word" || $sharedschcategory["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($sharedschcategory["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($sharedschcategory["doc_type"] == "application/vnd.ms-excel" || $sharedschcategory["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($sharedschcategory["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$sharedschcategory["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($sharedschcategory["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															$dmtid = $sharedschcategory["doc_matter_tailored_id"];
															list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
															
															
															if ($sharedschcategory["doc_size"] >= 1073741824){
																$bytes = number_format($sharedschcategory["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($sharedschcategory["doc_size"] >= 1048576){
																$bytes = number_format($sharedschcategory["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($sharedschcategory["doc_size"] >= 1024){ 
																$bytes = number_format($sharedschcategory["doc_size"] / 1024, 2) . ' KB';
															}elseif ($sharedschcategory["doc_size"] > 1){
																$bytes = $sharedschcategory["doc_size"] . ' bytes';
															}elseif ($sharedschcategory["doc_size"] == 1){
																$bytes = $sharedschcategory["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$sharedschcategory["doc_parent_id"]."'");
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$sharedschcategory["doc_size"]." bytes)</b><br/>Modified: <b>".date("F d, Y", strtotime($sharedschcategory["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($sharedschcategory["doc_date_created"]))."</b><br/>Version ".$sharedschcategory["doc_version"]."<br/><br>Tags:<br/>";
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {
																			echo "";
																		} ;?>">
																		<?php echo $sharedschcategory["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-id="<?php echo $sharedschcategory["doc_parent_id"];?>" data-name="<?php echo $sharedschcategory["doc_name"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $sharedschcategory["doc_id"]; ?>" data-toggle="modal" data-todo="<?php echo $sharedschcategory["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																		<?php $basename = pathinfo($sharedschcategory["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($sharedschcategory["doc_path"], PATHINFO_EXTENSION); 
																		$baseunver = explode("__VERSION", $basename); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $sharedschcategory['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																		<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $sharedschcategory["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																		<?php list($fldradid) = mysql_fetch_row(mysql_query("SELECT admin_id FROM admin WHERE admin_uname='".$sharedschcategory["doc_author"]."'"));
																			list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='".$sharedschcategory["doc_author"]."'"));
																			if(empty($fldradid)){?>
																				<a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $sharedschcategory["doc_name"];?>"> <b>Download</b> </a><?php
																			} else { ?>
																				<a href="download.php?folder=docs/<?php echo $fldradid."ad";?>&filename=<?php echo $sharedschcategory["doc_name"];?>"> <b>Download</b> </a><?php
																			} ?>
																	</small>
																	<div id="doctags<?php echo $sharedschcategory["doc_parent_id"];?>" class="collapse"><small>
																	<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$sharedschcategory["doc_parent_id"]."'");
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {echo "";}?>
																		<form method="post" action="tags.php">
																			<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																			<input type="hidden" name="docid" value="<?php echo $sharedschcategory["doc_parent_id"];?>">
																			<input type="hidden" name="action" value="add">
																			<input type="hidden" name="view" value="users">
																			<input type="hidden" name="docu" value="own">
																			<input type="hidden" name="page" value="<?php echo $page;?>">
																			<input type="submit" style="display:none;" value="Submit">
																		</form>
																	</small>
																</div>
																</td>
																
																<td><?php $getadminowner = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND a.admin_uname='".$sharedschcategory["doc_author"]."'");
																$getowner = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='".$sharedschcategory["doc_author"]."'");
																if(mysql_num_rows($getowner) > 0 || mysql_num_rows($getadminowner) > 0){
																	if(empty($getadminowner)){
																		list($ownerfname, $ownerlname) = mysql_fetch_row($getowner);
																		echo $ownerfname." ".$ownerlname;
																	} else {
																		list($ownerfname, $ownerlname) = mysql_fetch_row($getadminowner);
																		echo $ownerfname." ".$ownerlname;
																	}
																} else {
																	echo "<span class='text-danger'>Can't display owner name</span>"; }?>
																</td>
																<td><?php echo $sharedschcategory["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($sharedschcategory["doc_date_created"]));?></td>
																<td><?php echo date("m/d/Y", strtotime($sharedschcategory["doc_date_modified"]));?></td>
															</tr><?php
														
														}
													}
													
									////////////	//check for tags
													if(mysql_num_rows($sharedschtags) > 0){
														echo "<tr><td colspan='5' class='active small'>Search results found from <b>tags</b> <sup><span class='badge small'>".mysql_num_rows($sharedschtags)."</span></sup></td></tr>";
														while($sharedschtag = mysql_fetch_assoc($sharedschtags)){
															if($sharedschtag["doc_type"] == "application/msword" || $sharedschtag["doc_type"] == "application/vnd.ms-word" || $sharedschtag["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($sharedschtag["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($sharedschtag["doc_type"] == "application/vnd.ms-excel" || $sharedschtag["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($sharedschtag["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$sharedschtag["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($sharedschtag["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															$dmtid = $sharedschtag["doc_matter_tailored_id"];
															list($doccase) = mysql_fetch_row(mysql_query("SELECT cm_name FROM case_matter WHERE cm_id='$dmtid'"));
															
															
															if ($sharedschtag["doc_size"] >= 1073741824){
																$bytes = number_format($sharedschtag["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($sharedschtag["doc_size"] >= 1048576){
																$bytes = number_format($sharedschtag["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($sharedschtag["doc_size"] >= 1024){ 
																$bytes = number_format($sharedschtag["doc_size"] / 1024, 2) . ' KB';
															}elseif ($sharedschtag["doc_size"] > 1){
																$bytes = $sharedschtag["doc_size"] . ' bytes';
															}elseif ($sharedschtag["doc_size"] == 1){
																$bytes = $sharedschtag["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$sharedschtag["doc_parent_id"]."'");
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$sharedschtag["doc_size"]." bytes)</b><br/>Modified: <b>".date("F d, Y", strtotime($sharedschtag["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($sharedschtag["doc_date_created"]))."</b><br/>Version ".$sharedschtag["doc_version"]."<br/><br>Tags:<br/>";
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {
																			echo "";
																		} ;?>">
																		<?php echo $sharedschtag["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-id="<?php echo $sharedschtag["doc_parent_id"];?>" data-name="<?php echo $sharedschtag["doc_name"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $sharedschtag["doc_id"]; ?>" data-toggle="modal" data-todo="<?php echo $sharedschtag["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																		<?php $basename = pathinfo($sharedschtag["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($sharedschtag["doc_path"], PATHINFO_EXTENSION); 
																		$baseunver = explode("__VERSION", $basename); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $sharedschtag['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																		<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $sharedschtag["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																		<?php  list($fldradid) = mysql_fetch_row(mysql_query("SELECT admin_id FROM admin WHERE admin_uname='".$sharedschtag["doc_author"]."'"));
																			list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='".$sharedschtag["doc_author"]."'"));
																			if(empty($fldradid)){?>
																				<a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a><?php
																			} else { ?>
																				<a href="download.php?folder=docs/<?php echo $fldradid."ad";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a><?php
																			} ?>
																	</small>
																	<div id="doctags<?php echo $sharedschtag["doc_parent_id"];?>" class="collapse"><small>
																	<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$sharedschtag["doc_parent_id"]."'");
																		if(mysql_num_rows($tags) > 0){
																			while($tag = mysql_fetch_assoc($tags)){
																				echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																			}
																		} else {echo "";}?>
																		<form method="post" action="tags.php">
																			<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																			<input type="hidden" name="docid" value="<?php echo $sharedschtag["doc_id"];?>">
																			<input type="hidden" name="action" value="add">
																			<input type="hidden" name="view" value="users">
																			<input type="hidden" name="docu" value="own">
																			<input type="hidden" name="page" value="<?php echo $page;?>">
																			<input type="submit" style="display:none;" value="Submit">
																		</form>
																	</small>
																</div>
																</td>
																
																<td><?php $getadminowner = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND a.admin_uname='".$sharedschtag["doc_author"]."'");
																$getowner = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='".$sharedschtag["doc_author"]."'");
																if(mysql_num_rows($getowner) > 0 || mysql_num_rows($getadminowner) > 0){
																	if(empty($getadminowner)){
																		list($ownerfname, $ownerlname) = mysql_fetch_row($getowner);
																		echo $ownerfname." ".$ownerlname;
																	} else {
																		list($ownerfname, $ownerlname) = mysql_fetch_row($getadminowner);
																		echo $ownerfname." ".$ownerlname;
																	}
																} else {
																	echo "<span class='text-danger'>Can't display owner name</span>"; }?>
																</td>
																<td><?php echo $sharedschtag["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($sharedschtag["doc_date_created"]));?></td>
																<td><?php echo date("m/d/Y", strtotime($sharedschtag["doc_date_modified"]));?></td>
															</tr><?php
														
														}
													}
													
													
													
													echo "</tbody></table>";
													echo "<br><label class='small text-primary'>Click on the page number <label class='label label-warning'>1</label> at the bottom to redisplay previous table.</label></div></div>";
												
												} ?>
												
												<table class="table" style="<?php if(isset($_POST["btnsearchshared"])){echo "display:none;";}?>">
													<thead>
														<tr class="active">
															<th>Description</th>
															<th>Owner</th>
															<th>Category</th>
															<th>Uploaded Date</th>
															<th>Last Edit</th>
														</tr>
													</thead>
													<tbody>
														<br/><?php
													while($doc = mysql_fetch_array($docs)){ 
														if($doc["doc_type"] == "application/msword" || $doc["doc_type"] == "application/vnd.ms-word" || $doc["doc_type"] == "application/vnd.open"){
															$icon = "fa fa-file-word-o";
															$type = "Word Document";
														} else if($doc["doc_type"] == "application/pdf"){
															$icon = "fa fa-file-pdf-o";
															$type = "PDF File";
														} else if($doc["doc_type"] == "application/vnd.ms-excel" || $doc["doc_type"] == "application/vnd.ms-e"){
															$icon = "fa fa-file-excel-o";
															$type = "Excel Worksheet";
														} else if(strpos($doc["doc_type"], "image") !== false){
															$icon = "fa fa-file-image-o";
															$images = explode("/",$doc["doc_type"]);
															$type = strtoupper($images[1])." File";
														} else if($doc["doc_type"] == "text/plain"){
															$icon = "fa fa-file-text-o";
															$type = "Text Document";
														} else { $icon = "fa fa-file-o";} 
														
														if ($doc["doc_size"] >= 1073741824){
															$bytes = number_format($doc["doc_size"] / 1073741824, 2) . ' GB';
														}
														elseif ($doc["doc_size"] >= 1048576){
															$bytes = number_format($doc["doc_size"] / 1048576, 2) . ' MB';
														}
														elseif ($doc["doc_size"] >= 1024){
															$bytes = number_format($doc["doc_size"] / 1024, 2) . ' KB';
														}elseif ($doc["doc_size"] > 1){
															$bytes = $doc["doc_size"] . ' bytes';
														}elseif ($doc["doc_size"] == 1){
															$bytes = $doc["doc_size"] . ' byte';
														}else{
															$bytes = '0 bytes';
														} ?>
												
														<tr>
															<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
															    <span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																	$tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$doc["doc_parent_id"]."'");
																	echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$doc["doc_size"]." bytes)</b><br/>Modified: <b>".date("F d, Y", strtotime($doc["doc_date_modified"]))."</b><br/>Created: <b>".date("F d, Y", strtotime($doc["doc_date_created"]))."</b><br/>Version ".$doc["doc_version"]."<br/><br>Tags:<br/>";
																	if(mysql_num_rows($tags) > 0){
																		while($tag = mysql_fetch_assoc($tags)){
																			echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																		}
																	} else {
																		echo "";
																	} ;?>">
																	<?php echo $doc["doc_name"];?></a></span></p>
																<small><a href="#" id="detailsmodal" data-id="<?php echo $doc["doc_parent_id"];?>" data-name="<?php echo $doc["doc_name"];?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																	<a href="#" class="documentmodal" data-id="<?php echo $doc["doc_parent_id"]; ?>" data-toggle="modal" data-todo="<?php echo $doc["doc_cat"];?>" data-target="#docedit"> Edit Properties </a>&nbsp;
																	<?php $basename = pathinfo($doc["doc_path"], PATHINFO_FILENAME);
																	$extension = pathinfo($doc["doc_path"], PATHINFO_EXTENSION); 
																	$baseunver = explode("__VERSION", $basename); ?>
																	<a href="#" id="renamedoc" data-id="<?php echo $doc['doc_id'];?>" data-basename="<?php echo $baseunver[0];?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#docrename"> Rename </a>&nbsp;
																	<a href="#" class="documentmodal" data-id="<?php echo $doc["doc_id"]; ?>" data-todo="<?php echo $doc["doc_name"];?>" data-toggle="modal" data-target="#docdelete"> Delete </a>&nbsp;
																	<a href="#" data-toggle="collapse" data-target="#doctags<?php echo $doc["doc_parent_id"];?>"> Add Tags </a>&nbsp;
																	<?php list($fldradid) = mysql_fetch_row(mysql_query("SELECT admin_id FROM admin WHERE admin_uname='".$doc["doc_author"]."'"));
																	list($fldrid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='".$doc["doc_author"]."'"));
																	if(empty($fldradid)){?>
																		<a href="download.php?folder=docs/<?php echo $fldrid."usr";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a><?php
																	} else { ?>
																		<a href="download.php?folder=docs/<?php echo $fldradid."ad";?>&filename=<?php echo $basename.'.'.$extension;?>"> <b>Download</b> </a><?php
																	} ?>
																	
																</small>
																<div id="doctags<?php echo $doc["doc_parent_id"];?>" class="collapse"><small>
																<?php $tags = mysql_query("SELECT * FROM document_tags WHERE tag_doc_id='".$doc["doc_parent_id"]."'");
																	if(mysql_num_rows($tags) > 0){
																		while($tag = mysql_fetch_assoc($tags)){
																			echo "<span class='label label-default'>".$tag["tag_name"]."</span> ";
																		}
																	} else {echo "";}?>
																	<form method="post" action="tags.php">
																		<span class="small" style="display:inline-block;"><input type="text" class="input-sm" name="tag" style="border-color:#ccc;width:90px;" placeholder="New Tag"></span>
																		<input type="hidden" name="docid" value="<?php echo $doc["doc_parent_id"];?>">
																		<input type="hidden" name="action" value="add">
																		<input type="hidden" name="view" value="users">
																		<input type="hidden" name="docu" value="own">
																		<input type="hidden" name="page" value="<?php echo $page;?>">
																		<input type="submit" style="display:none;" value="Submit">
																	</form>
																</small>
															</div>
															</td>
															
															
															<td><?php $getadminowner = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND a.admin_uname='".$doc["doc_author"]."'");
															$getowner = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='".$doc["doc_author"]."'");
															if(mysql_num_rows($getowner) > 0 || mysql_num_rows($getadminowner) > 0){
																if(empty($getadminowner)){
																	list($ownerfname, $ownerlname) = mysql_fetch_row($getowner);
																    echo $ownerfname." ".$ownerlname;
																} else {
																	list($ownerfname, $ownerlname) = mysql_fetch_row($getadminowner);
																    echo $ownerfname." ".$ownerlname;
																}
															} else {
																echo "<span class='text-danger'>Can't display owner name</span>"; }?>
															</td>
															<td><?php echo $doc["doc_cat"];?></td>
															<td><?php echo date("m/d/Y", strtotime($doc["doc_date_created"]));?></td>
															<td><?php echo date("m/d/Y", strtotime($doc["doc_date_modified"]));?></td>
														</tr><?php
													} ?>
													
														
														</tbody>
													</table>
												
											</div></div>	
												
											
											<?php
										} ?>
										
										<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
											<ul class="pagination pagination-sm">
												<?php for($i = 1; $i<=$total; $i++){
													if($page == $i){
														echo "<li class='active'>";
													} else {
														echo "<li>";
													}
													echo "<a href='home.php?docu=shared&page=$i#shareddocs'>$i</a></li>";
												} ?>
												
											</ul>
										</div>  
										
									</div>
								
									
 								</div>
							</div>
						</div>
						

						<!--Templates-->		
						<div id="documents_template" class="tab-pane fade">
							<div class="container">
								<ul class="nav nav-pills">
									<li class="active"><a data-toggle="pill" href="#template_uploaded">Uploaded Templates</a></li>
									<li><a data-toggle="pill" href="#template_system">System Templates</a></li>
								</ul>
								
								<div class="tab-content">
									
									<!--Uploaded Templates-->
									<div id="template_uploaded" class="tab-pane fade in active">
										<?php $limit = 10;
											if(isset($_GET["page"]) && isset($_GET["docu"])){
												if($_GET["docu"] == "template"){
													$page  = $_GET["page"]; 
												} else {
													$page = 1;
												}
											} else{ $page = 1; } 
											
											$counttemp = mysql_query("SELECT * FROM document_template WHERE NOT doc_uploader='System'") or die(mysql_error());
											$start = ($page-1) * $limit; 
											$count = mysql_num_rows($counttemp);
											$total = ceil($count/ $limit); 
												
											$templates = mysql_query("SELECT * FROM document_template WHERE NOT doc_uploader='System' ORDER BY doc_date_uploaded DESC LIMIT $start, $limit");
											
											if(mysql_num_rows($templates) > 0){ ?>
											<div class="col-sm-12"><div style="height:300px;overflow-y:auto; overflow-x:hidden;">
													<div class="row" style="float:right;">
														<form method="post" action="admin_home.php#template_uploaded">
														  <div class="input-group col-sm-4 col-sm-offset-7">
															<input type="text" name="txtsearchtemp" class="form-control input-sm" style="margin:0 !important;" placeholder="Search template by name or category" required>
															<div class="input-group-btn">
															  <button class="btn btn-primary btn-sm" name ="btnsearchtemp" type="submit">
																<i class="glyphicon glyphicon-search"></i>
															  </button>
															</div>
														  </div>
														</form>
														<br/>
													</div>
												<?php 
												if(isset($_POST["btnsearchtemp"])){
													echo "<br><br><div class='panel panel-default' style='overflow-y:auto;'><div class='panel-body'>";
													$txtsearchtemp = $_POST["txtsearchtemp"];
													
													$tempschnames = mysql_query("SELECT * FROM document_template WHERE NOT doc_uploader='System' AND doc_name LIKE '%$txtsearchtemp%'");
													$tempschcategories = mysql_query("SELECT * FROM document_template WHERE NOT doc_uploader='System' AND doc_cat LIKE '%$txtsearchtemp%'");
													
													
													//count total results
													$tempschresults = mysql_num_rows($tempschnames) + mysql_num_rows($tempschcategories); 
													if($tempschresults > 1){
														echo "<small>".$tempschresults." results found for <b>".$txtsearchtemp."</b>.</small><br>";
													} else {
														echo "<small>".$tempschresults." result found for <b>".$txtsearchtemp."</b>.</small><br>";
													}
													
													echo "<table class='table table-condensed'>";
													echo "<thead><tr class='active'><th>Name</th><th>Category</th><th>Uploaded Date</th><th>Uploader</th></thead>";
													echo "<tbody>";
													
													if(mysql_num_rows($tempschnames) > 0){
														echo "<tr><td colspan='4' class='active small'>Search results found from <b>filename</b> <sup><span class='badge small'>".mysql_num_rows($tempschnames)."</span></sup></td></tr>";
														while($tempschname = mysql_fetch_assoc($tempschnames)){
															if($tempschname["doc_type"] == "application/msword" || $tempschname["doc_type"] == "application/vnd.ms-word" || $tempschname["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($tempschname["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($tempschname["doc_type"] == "application/vnd.ms-excel" || $tempschname["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($tempschname["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$tempschname["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($tempschname["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															
															if ($tempschname["doc_size"] >= 1073741824){
																$bytes = number_format($tempschname["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($tempschname["doc_size"] >= 1048576){
																$bytes = number_format($tempschname["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($tempschname["doc_size"] >= 1024){ 
																$bytes = number_format($tempschname["doc_size"] / 1024, 2) . ' KB';
															}elseif ($tempschname["doc_size"] > 1){
																$bytes = $tempschname["doc_size"] . ' bytes';
															}elseif ($tempschname["doc_size"] == 1){
																$bytes = $tempschname["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$tempschname["doc_size"]." bytes)</b><br/>";?>">
																		<?php echo $tempschname["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-create="<?php echo $tempschname["doc_date_created"];?>" data-modify="<?php echo $tempschname["doc_date_modified"];?>" data-size="<?php echo $tempschname["doc_size"];?>" data-doctype="<?php echo $type;?>" data-id="<?php echo $tempschname["doc_id"];?>" data-name="<?php echo $tempschname["doc_name"];?>" data-icon="<?php echo $icon.' fa-lg';?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $tempschname["doc_id"]; ?>" data-toggle="modal" data-todo="<?php echo $tempschname["doc_cat"];?>" data-target="#doceditcat"> Edit Category</a>&nbsp;
																		<?php $basename = pathinfo($tempschname["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($tempschname["doc_path"], PATHINFO_EXTENSION); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $tempschname['doc_id'];?>" data-basename="<?php echo $basename;?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#doctemprename"> Rename </a>&nbsp;
																		<a href="#" class="documentmodal" data-id="<?php echo $tempschname["doc_id"]; ?>" data-todo="<?php echo $tempschname["doc_name"];?>" data-toggle="modal" data-target="#docdeletetemp"> Delete </a>&nbsp;
																		<a href="download.php?folder=templates/&filename=<?php echo $tempschname["doc_name"];?>"> <b>Download</b> </a>
																	</small>
																</td>
																<td><?php echo $tempschname["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($tempschname["doc_date_uploaded"]));?></td>
																<?php $adminauth = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE a.admin_id=p.admin_id AND a.admin_uname='".$tempschname["doc_uploader"]."'") or die(mysql_error());
																$userauth = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE u.user_id=p.user_id AND u.user_uname='".$tempschname["doc_uploader"]."'") or die(mysql_error()); 
																if(mysql_num_rows($adminauth) > 0){
																	list($authf, $authl) = mysql_fetch_row($adminauth);
																} else if(mysql_num_rows($userauth) > 0){
																	list($authf, $authl) = mysql_fetch_row($userauth);
																} else {
																	$authf = "<span class='text-danger'>Can't display name</span>";
																	$authl = "";
																}
																?>
																<td><?php echo $authf." ".$authl;?></td>
															</tr>
															<?php
														
														}
													}
													
													if(mysql_num_rows($tempschcategories) > 0){
														echo "<tr><td colspan='4' class='active small'>Search results found from <b>category</b> <sup><span class='badge small'>".mysql_num_rows($tempschcategories)."</span></sup></td></tr>";
														while($tempschcategory = mysql_fetch_assoc($tempschcategories)){
															if($tempschcategory["doc_type"] == "application/msword" || $tempschcategory["doc_type"] == "application/vnd.ms-word" || $tempschcategory["doc_type"] == "application/vnd.open"){
																$icon = "fa fa-file-word-o";
																$type = "Word Document";
															} else if($tempschcategory["doc_type"] == "application/pdf"){
																$icon = "fa fa-file-pdf-o";
																$type = "PDF File";
															} else if($tempschcategory["doc_type"] == "application/vnd.ms-excel" || $tempschcategory["doc_type"] == "application/vnd.ms-e"){
																$icon = "fa fa-file-excel-o";
																$type = "Excel Worksheet";
															} else if(strpos($tempschcategory["doc_type"], "image") !== false){
																$icon = "fa fa-file-image-o";
																$images = explode("/",$tempschcategory["doc_type"]);
																$type = strtoupper($images[1])." File";
															} else if($tempschcategory["doc_type"] == "text/plain"){
																$icon = "fa fa-file-text-o";
																$type = "Text Document";
															} else { $icon = "fa fa-file-o";} 
															
															
															if ($tempschcategory["doc_size"] >= 1073741824){
																$bytes = number_format($tempschcategory["doc_size"] / 1073741824, 2) . ' GB';
															}
															elseif ($tempschcategory["doc_size"] >= 1048576){
																$bytes = number_format($tempschcategory["doc_size"] / 1048576, 2) . ' MB';
															}
															elseif ($tempschcategory["doc_size"] >= 1024){ 
																$bytes = number_format($tempschcategory["doc_size"] / 1024, 2) . ' KB';
															}elseif ($tempschcategory["doc_size"] > 1){
																$bytes = $tempschcategory["doc_size"] . ' bytes';
															}elseif ($tempschcategory["doc_size"] == 1){
																$bytes = $tempschcategory["doc_size"] . ' byte';
															}else{
																$bytes = '0 bytes';
															} ?>
															<tr>
																<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																	<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																		echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$tempschcategory["doc_size"]." bytes)</b><br/>";?>">
																		<?php echo $tempschcategory["doc_name"];?></a></span></p>
																	<small><a href="#" id="detailsmodal" data-create="<?php echo $tempschcategory["doc_date_created"];?>" data-modify="<?php echo $tempschcategory["doc_date_modified"];?>" data-size="<?php echo $tempschcategory["doc_size"];?>" data-doctype="<?php echo $type;?>" data-id="<?php echo $tempschcategory["doc_id"];?>" data-name="<?php echo $tempschcategory["doc_name"];?>" data-icon="<?php echo $icon.' fa-lg';?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																		<a href="#" class="documentmodal" data-id="<?php echo $tempschcategory["doc_id"]; ?>" data-toggle="modal" data-todo="<?php echo $tempschcategory["doc_cat"];?>" data-target="#doceditcat"> Edit Category</a>&nbsp;
																		<?php $basename = pathinfo($tempschcategory["doc_path"], PATHINFO_FILENAME);
																		$extension = pathinfo($tempschcategory["doc_path"], PATHINFO_EXTENSION); ?>
																		<a href="#" id="renamedoc" data-id="<?php echo $tempschcategory['doc_id'];?>" data-basename="<?php echo $basename;?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#doctemprename"> Rename </a>&nbsp;
																		<a href="#" class="documentmodal" data-id="<?php echo $tempschcategory["doc_id"]; ?>" data-todo="<?php echo $tempschcategory["doc_name"];?>" data-toggle="modal" data-target="#docdeletetemp"> Delete </a>&nbsp;
																		<a href="download.php?folder=templates/&filename=<?php echo $tempschcategory["doc_name"];?>"> <b>Download</b> </a>
																	</small>
																</td>
																<td><?php echo $tempschcategory["doc_cat"];?></td>
																<td><?php echo date("m/d/Y", strtotime($tempschcategory["doc_date_uploaded"]));?></td>
																<?php $adminauth = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE a.admin_id=p.admin_id AND a.admin_uname='".$tempschcategory["doc_uploader"]."'") or die(mysql_error());
																$userauth = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE u.user_id=p.user_id AND u.user_uname='".$tempschcategory["doc_uploader"]."'") or die(mysql_error()); 
																if(mysql_num_rows($adminauth) > 0){
																	list($authf, $authl) = mysql_fetch_row($adminauth);
																} else if(mysql_num_rows($userauth) > 0){
																	list($authf, $authl) = mysql_fetch_row($userauth);
																} else {
																	$authf = "<span class='text-danger'>Can't display name</span>";
																	$authl = "";
																}
																?>
																<td><?php echo $authf." ".$authl;?></td>
															</tr>
															<?php
														
														}
													}
													
													echo "</tbody></table>";
													echo "<br><label class='small text-primary'>Click on the page number <label class='label label-warning'>1</label> at the bottom to redisplay previous table.</label></div></div>";
												
												} ?>
												
												<table class="table" style="<?php if(isset($_POST["btnsearchtemp"])){echo "display:none;";}?>">
													<thead>
														<tr class="active">
															<th>Name</th>
															<th>Category</th>
															<th>Uploaded Date</th>
															<th>Uploader</th>
														</tr>
													</thead>
													<tbody>
														<br/><?php
													while($template = mysql_fetch_array($templates)){ 
														if($template["doc_type"] == "application/msword" || $template["doc_type"] == "application/vnd.ms-word" || $template["doc_type"] == "application/vnd.open"){
															$icon = "fa fa-file-word-o";
															$type = "Word Document";
														} else if($template["doc_type"] == "application/pdf"){
															$icon = "fa fa-file-pdf-o";
															$type = "PDF File";
														} else if($template["doc_type"] == "application/vnd.ms-excel" || $template["doc_type"] == "application/vnd.ms-e"){
															$icon = "fa fa-file-excel-o";
															$type = "Excel Worksheet";
														} else if(strpos($template["doc_type"], "image") !== false){
															$icon = "fa fa-file-image-o";
															$images = explode("/",$template["doc_type"]);
															$type = strtoupper($images[1])." File";
														} else if($template["doc_type"] == "text/plain"){
															$icon = "fa fa-file-text-o";
															$type = "Text Document";
														} else { $icon = "fa fa-file-o";} 
														
														
														if ($template["doc_size"] >= 1073741824){
															$bytes = number_format($template["doc_size"] / 1073741824, 2) . ' GB';
														}
														elseif ($template["doc_size"] >= 1048576){
															$bytes = number_format($template["doc_size"] / 1048576, 2) . ' MB';
														}
														elseif ($template["doc_size"] >= 1024){
															$bytes = number_format($template["doc_size"] / 1024, 2) . ' KB';
														}elseif ($template["doc_size"] > 1){
															$bytes = $template["doc_size"] . ' bytes';
														}elseif ($template["doc_size"] == 1){
															$bytes = $template["doc_size"] . ' byte';
														}else{
															$bytes = '0 bytes';
														} ?>
												
														<tr>
															<td><p><i class="<?php echo $icon; ?> fa-lg"></i>
																<span class="black-link"><a href="#" title="Details" data-toggle="popover" data-trigger="focus" data-html="true" data-content="<?php 
																	echo "Type: <b>$type</b><br/>Size: <b>$bytes (".$template["doc_size"]." bytes)</b><br/>";?>">
																	<?php echo $template["doc_name"];?></a></span></p>
																<small><a href="#" id="detailsmodal" data-create="<?php echo $template["doc_date_created"];?>" data-modify="<?php echo $template["doc_date_modified"];?>" data-size="<?php echo $template["doc_size"];?>" data-doctype="<?php echo $type;?>" data-id="<?php echo $template["doc_id"];?>" data-name="<?php echo $template["doc_name"];?>" data-icon="<?php echo $icon.' fa-lg';?>" data-toggle="modal" data-target="#docdetails"> View Activity Details </a>&nbsp; 
																	<a href="#" class="documentmodal" data-id="<?php echo $template["doc_id"]; ?>" data-toggle="modal" data-todo="<?php echo $template["doc_cat"];?>" data-target="#doceditcat"> Edit Category</a>&nbsp;
																	<?php $basename = pathinfo($template["doc_path"], PATHINFO_FILENAME);
																	$extension = pathinfo($template["doc_path"], PATHINFO_EXTENSION); ?>
																	<a href="#" id="renamedoc" data-id="<?php echo $template['doc_id'];?>" data-basename="<?php echo $basename;?>" data-ext="<?php echo $extension;?>" data-toggle="modal" data-target="#doctemprename"> Rename </a>&nbsp;
																	<a href="#" class="documentmodal" data-id="<?php echo $template["doc_id"]; ?>" data-todo="<?php echo $template["doc_name"];?>" data-toggle="modal" data-target="#docdeletetemp"> Delete </a>&nbsp;
																	<a href="download.php?folder=templates/&filename=<?php echo $template["doc_name"];?>"> <b>Download</b> </a>
																</small>
															</td>
															<td><?php echo $template["doc_cat"];?></td>
															<td><?php echo date("m/d/Y", strtotime($template["doc_date_uploaded"]));?></td>
															<?php $adminauth = mysql_query("SELECT p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE a.admin_id=p.admin_id AND a.admin_uname='".$template["doc_uploader"]."'") or die(mysql_error());
															$userauth = mysql_query("SELECT p.user_fname, p.user_lname FROM user_profile p, user u WHERE u.user_id=p.user_id AND u.user_uname='".$template["doc_uploader"]."'") or die(mysql_error()); 
															if(mysql_num_rows($adminauth) > 0){
																list($authf, $authl) = mysql_fetch_row($adminauth);
															} else if(mysql_num_rows($userauth) > 0){
																list($authf, $authl) = mysql_fetch_row($userauth);
															} else {
																$authf = "<span class='text-danger'>Can't display name</span>";
																$authl = "";
															}
															?>
															<td><?php echo $authf." ".$authl;?></td>
														</tr><?php
													} ?>
													
														</tbody>
												</table>
											</div></div>
										    
											<div align="center" style="position:absolute; bottom:7%;left:0; right:0; margin:auto;">
												<ul class="pagination pagination-sm">
													<?php for($i = 1; $i<=$total; $i++){
														if($page == $i){
															echo "<li class='active'>";
														} else {
															echo "<li>";
														}
														echo "<a href='home.php?docu=template&page=$i#template_uploaded'>$i</a></li>";
													} ?>
													
												</ul>
											</div>
											<?php
										    } else {?>
											<div align="center" style="font-size:24px; color:black;">
												<br/><br/>
												<span class="glyphicon glyphicon-file" style="color:#f1f1f1; font-size:70px;"></span><br/>
												You haven't created any document templates.<br/>
												<small><small><small>Use the button in the top right to create a new document template.</small></small></small>
											</div><?php
									    	} ?>
										
									</div>
									
									<!--System Templates-->
									<div id="template_system" class="tab-pane fade">
									  <div class="col-sm-12"><div style="height:300px;overflow-y:auto; overflow-x:hidden;">
									    <?php $templates = mysql_query("SELECT * FROM document_template WHERE doc_uploader='System' ORDER BY doc_name ASC") or die(mysql_error()); ?><br/>
										<input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for templates..">
										<table id="myTable" class="table table-condensed">
											<thead>
												<tr class="active">
													<th>Name</th>
													<th>Size</th>
													<th>Category</th>
												</tr>
											</thead>
											<tbody>
												<br/><?php
											while($template = mysql_fetch_array($templates)){ 
												if ($template["doc_size"] >= 1073741824){
													$bytes = number_format($template["doc_size"] / 1073741824, 2) . ' GB';
												}
												elseif ($template["doc_size"] >= 1048576){
													$bytes = number_format($template["doc_size"] / 1048576, 2) . ' MB';
												}
												elseif ($template["doc_size"] >= 1024){
													$bytes = number_format($template["doc_size"] / 1024, 2) . ' KB';
												}elseif ($template["doc_size"] > 1){
													$bytes = $template["doc_size"] . ' bytes';
												}elseif ($template["doc_size"] == 1){
													$bytes = $template["doc_size"] . ' byte';
												}else{
													$bytes = '0 bytes';
												} ?>
										
												<tr>
													<td><h5><?php echo $template["doc_name"];?>&nbsp;&nbsp;
														<small><a href="download.php?folder=templates/system_templates/&filename=<?php echo $template["doc_name"];?>"> <b>Download</b> </a></small></h5>
													</td>
													
													<td><?php echo $bytes; ?></td>
													<td><?php echo $template["doc_cat"];?></td>
												</tr><?php
											} ?>
											
											</tbody>
										</table>
									  </div></div>
									</div>
								</div>	
								
							</div>
						</div>
						
						
						
					  </div>
					</div>
					
					
				</div>
<!--ACCOUNTS-->					
				<div id="myaccount" name="myaccount" class="tab-pane fade">
					<div class="container main-container" style="height:450px; overflow-y:auto;">
					    
						<?php //user id and type
						list($acctid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'"));
						list($accttype) = mysql_fetch_row(mysql_query("SELECT user_type FROM user WHERE user_uname='$uname'"));
						//other info
						list($acctfname) = mysql_fetch_row(mysql_query("SELECT user_fname FROM user_profile WHERE user_id='$acctid'"));
						list($acctlname) = mysql_fetch_row(mysql_query("SELECT user_lname FROM user_profile WHERE user_id='$acctid'"));
						list($acctpos) = mysql_fetch_row(mysql_query("SELECT user_pos FROM user_profile WHERE user_id='$acctid'"));
						list($acctemail) = mysql_fetch_row(mysql_query("SELECT user_email FROM user_profile WHERE user_id='$acctid'"));
						list($acctphone) = mysql_fetch_row(mysql_query("SELECT user_contact FROM user_profile WHERE user_id='$acctid'"));
						list($acctdp) = mysql_fetch_row(mysql_query("SELECT user_dp FROM user_profile WHERE user_id='$acctid'"));
						//assigned area 
						$aa = mysql_query("SELECT des_name AS area FROM designation WHERE des_resp_atty_id='$acctid'");
						
						?>

						<br/>
						<h4>My Account  <small style="float:right;"><small>Just press <code>ENTER</code> when an underline becomes visible to save any changes</small></small></h4>
						<form method="post" action="updateprofile.php">
							<input type="hidden" name="accid" value="<?php echo $acctid;?>">
							<table class="table table-condensed" border="0">
								<tr> 
									<!--picture-->
									<td rowspan="3" align="center">
									<?php list($dp) = mysql_fetch_row(mysql_query("SELECT dis_path FROM display_picture WHERE dis_name='$acctdp'"));?>
									<img class="circular-square" src="<?php 
												if($dp==null){
													echo 'image/png/default.png';} 
												else{ echo $dp;} ?>" height="100px" width="100px"><br />
												<a data-toggle="modal" data-target="#editpic" class="btn btn-default btn-xs" style="border:none;">Edit Picture</a></td>
									 <!--name and username-->
									 <th><font size="4"> 
									  <input type="text" id="accfname" name="accfname" value="<?php if($acctfname != null){echo $acctfname;} 
											else{echo 'User';}?>" placeholder="First Name" style="width:auto; border:none; background-color:white !important;" disabled="disabled"> 
										<input type="text" id="acclname" name="acclname" value="<?php if($acctlname != null){echo $acctlname;} 
											else{echo $acctid;}?>" placeholder="Last Name" style="width:auto; border:none; background-color:white !important;" disabled="disabled">
										<button id="editnameinfo" type="button" class="btn btn-warning btn-sm" style="float:right;">Edit</button></font><br /></th>
								</tr>
								<tr><td><input type="text" id="accuname" name="accuname" value="<?php echo $uname;?>" placeholder="Username" 
								    style="padding-top:2px; padding-bottom:2px; border:none; background-color:white !important;" disabled="disabled"> <br /></td></tr>
								<tr><td>&nbsp;</td></tr>
							</table>  
							
							<!--User Basic Info-->
							<table class="table table-condensed">
								<thead>
									<tr><th><font size="3">Basic Information</font></th>
										<th><button id="editbasicinfo" type="button" class="btn btn-warning btn-sm" style="float:right;">Edit</button></font><br /></th>
									</tr>
								</thead>
							    <tbody>
								    <tr><td class="text-muted" width="200px"><label>Position</label></td>
									    <td><input type="text" id="accpos" name="accpos" value="<?php if($acctpos != null){echo $acctpos;} 
											else{echo 'Not specified yet';}?>" placeholder="Office Position" style="padding-top:4px; padding-bottom:4px; border:none; background-color:white !important;" disabled="disabled"></td>
									</tr>
									<tr><td class="text-muted" width="200px"><label>Assigned Locations</label></td>
									    <td><input type="text" id="assignarea" name="assignarea" value="<?php if(mysql_num_rows($aa) <= 0){echo 'No assigned location yet';
										} else{
											while($assarea = mysql_fetch_array($aa)){echo $assarea['area'] .', ';}
										}?>" placeholder="In-charge Areas/Location" style="padding-top:4px; padding-bottom:4px; border:none; background-color:white !important;" disabled="disabled"></td>
									</tr>
									<tr><td class="text-muted" width="200px"><label>Permissions</label></td>
									    <td style="font-weight:bold;"><input type="text" id="utype" name="utype" value="<?php if($accttype == 'Staff'){echo 'Non-Attorney';} 
											else{echo $accttype;}?>" placeholder="Office Position" style="padding-top:4px; padding-bottom:4px; border:none; background-color:white !important;" disabled></td>
									</tr>
									<tr><td></td><td></td></tr>
								</tbody>
							</table>
							
							<!--User Contact Info-->
							<table class="table table-condensed">
								<thead>
									<tr><th><font size="3">Contact Information</font></th>
										<th><button id="editcontactinfo" type="button" class="btn btn-warning btn-sm" style="float:right;">Edit</button></font><br /></th>
									</tr>
								</thead>
							    <tbody>
								    <tr><td class="text-muted" width="200px"><label>Contact No.</label></td>
									    <td><input type="text" id="accphone" name="accphone" value="<?php if($acctphone == null){echo 'Not specified yet';} 
											else{echo $acctphone;}?>" placeholder="Contact Number" 
											style="padding-top:4px; padding-bottom:4px; border:none; background-color:white !important;" disabled="disabled"></td>
									</tr>
									<tr><td class="text-muted" width="200px"><label>Email Address</label></td>
									    <td><input type="text" id="accemail" name="accemail" value="<?php if($acctemail != null){echo $acctemail;} 
											else{echo 'Not specified yet';}?>" placeholder="Email Address" 
											style="padding-top:4px; padding-bottom:4px; border:none; background-color:white !important;" disabled="disabled"></td>
									</tr>
									<tr><td></td><td></td></tr>
								</tbody>
							</table>
							<input type="hidden" name="utype" value="Users">
							<button type="submit" style="display:none;">Submit</button>	
						</form>

							
					</div>
					
				</div>

<!--SETTINGS-->					
				<div id="settings" name="settings" class="tab-pane fade">
					<div class="container main-container">
					  <ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#settings_security">Security</a></li>
					  </ul>

					  <div class="tab-content" style="height:420px; overflow-y:auto; overflow-x:hidden;">
						
						<!--Security-->		
						<div id="settings_security" class="tab-pane fade in active">
							<br/>
							<!--password-->	
							<div class="panel-group">
								<div class="panel panel-default">
								  <div class="panel-heading">
									<h4 class="panel-title">
									  <a data-toggle="collapse" href="#collapse1">Update Password</a>
									</h4>
								  </div>
								  
								  <div id="collapse1" class="panel-collapse collapse">
									<div class="panel-body">
										<div class="form-horizontal">
											<form name="updatepw" onsubmit="return validatepassword()" method="post" action="updatepw.php">
												<input type="hidden" name="user" value="<?php echo $uname;?>">
												<input type="hidden" name="page" value="user">
												<small><p class="text-muted"><b>Tips on creating a strong password</b></p>
												<ul class="text-muted">
													<li>Contains characters, numbers, and symbolic characters</li>
													<li>Avoid using repeated patterns or only characters</li>
													<li>Is longer than 6 characters</li>
												</ul></small><br/>
												
												<div class="form-group">
												  <div class="col-sm-4">          
													<label for="oldpassword">Old Password</label><br/>
													<input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Old password" required>
													<span id="checkoldpw" class="small"></span>
												  </div>
												</div>
												<div class="form-group">
												  <div class="col-sm-4">        
													<label for="newpassword">New Password</label><br/>
													<input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="New password" required>
												  </div>
												</div>
												<div class="form-group">
												  <div class="col-sm-4">          
													<label for="confirmpassword">Confirm Password</label><br/>
													<input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm new password" required>
													<span id="checkconfirm" class="small"></span>
												  </div>
												</div>
												<div class="form-group">        
												  <div class="col-sm-4">
													<button type="submit" class="btn btn-primary">Submit</button>
													<button id="clearpass" class="btn btn-default">Cancel</button>
												  </div>
												</div>
				
											</form>
										</div>
									</div>
								  </div>
								</div>
							</div>
							
							<!--security question-->	
							<div class="panel-group">
								<div class="panel panel-default">
								  <div class="panel-heading">
									<h4 class="panel-title">
									  <a data-toggle="collapse" href="#collapse2">Security Question</a>
									</h4>
								  </div>
								  <div id="collapse2" class="panel-collapse collapse">
									<div class="panel-body">
									    <div class="form-horizontal">
										    
												<?php $userid = mysql_query("SELECT user_id AS id FROM user WHERE user_uname='$uname'");
												list($uid) = mysql_fetch_row($userid);
												$id = $uid;
												$security = mysql_query("SELECT * FROM security_questions WHERE sq_user_id=$id");
												list($count) = mysql_fetch_row(mysql_query("SELECT count(*) AS count FROM security_questions WHERE sq_user_id=$id"));
												
												if($count > 0) {
													while ($sec = mysql_fetch_array($security)) { ?>
														<div class="form-group">
														  <label class="control-label col-sm-2" for="ques">Question</label>
														  <div class="col-sm-10">
															<p class="form-control-static"><?php echo $sec['sq_question'];?></p>
														  </div>
														</div>
														<div class="form-group">
														  <label class="control-label col-sm-2" for="ques">Answer</label>
														  <div class="col-sm-10">
															<p class="form-control-static"><?php echo $sec['sq_answer'];?></p>
														  </div>
														</div>

													    <hr/>
												<!-- Update Security Question-->	
														<button type="button" class="btn btn-warning" data-toggle="collapse" data-target="#updatesecurity">Update Security Question</button><br/>
														<div id="updatesecurity" class="collapse">
															<form method="post" action="updatesecurity.php">

																<div class="form-group">
																  <div class="col-sm-5">  
																	<label>Question</label><br/> 
																	<input type="text" class="form-control" id="upquest" name="upquest" placeholder="Your question" required>
																  </div>
																</div>
																<div class="form-group">
																  <div class="col-sm-5">   
																	<label >Answer</label><br/> 
																	<input type="text" class="form-control" id="upans" name="upans" placeholder="Answer" required>
																  </div>
																</div>
																<div class="form-group">        
																  <div class="col-sm-5">
																	<input type="hidden" name="uptype" value="user">
																	<input type="hidden" name="upuname" value="<?php echo $uname;?>">
																	<button type="submit" class="btn btn-primary">Submit</button>
																  </div>
																</div>
															</form>
															
														</div><?php
													} 
												} else{ ?>
											    <!-- Add Security Question-->	
												<form method="post" action="addsecurity.php">
											        <small><p class="text-muted"><b>Tips on creating security question</b></p>
														<ul class="text-muted">
															<li>Enter a question that only you can know</li>
															<li>Use only 1 word for your answer for easy recalling</li>
															<li>Answers are case-sensitive so make sure to check whether your answer should be in <code>upper case</code> or <code>lower case</code></li>
														</ul></small><br/>
													
													<div class="form-group">
													  <div class="col-sm-5">  
														<label for="q">Question</label><br/> 
														<input type="text" class="form-control" id="q" name="question" placeholder="Your question" required>
													  </div>
													</div>
													<div class="form-group">
													  <div class="col-sm-5">   
													    <label for="a">Answer</label><br/> 
														<input type="text" class="form-control" id="a" name="answer" placeholder="Answer" required>
													  </div>
													</div>
													<div class="form-group">        
													  <div class="col-sm-5">
													    <input type="hidden" name="type" value="user">
														<input type="hidden" name="username" value="<?php echo $uname;?>">
														<button type="submit" class="btn btn-primary">Submit</button>
													  </div>
													</div>
												</form>
												<?php	
												}?>
											    
										</div>
									</div>
								  </div>
								</div>
							</div>
							
						</div>

					  </div>	
					</div>
				</div>
					
									
				
			</div>
		</div>
			
			
<!-------------------------------------------------------------------------------------------------------------------------------------------------------!>					
<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++--!>	
<!-------------------------------------------------------------------------------------------------------------------------------------------------------!>
				

<!--MODALS->	
	<!--Accounts: Edit Picture-->	  
		<div class="modal fade" id="editpic" role="dialog">
			<div class="modal-dialog">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Edit Picture</h4>
				</div>
				<div class="modal-body">
				  <p>Choose one of the images below.</p>
				  <form id="updatedp" method="POST" action="update_picture.php">
				    <div class="panel panel-default">
						<div class="panel-body" align="center">
							<label>
								<input type="radio" name="dp" value="2" checked />
								<img src="image/png/man.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="3" />
								<img src="image/png/man-1.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="4" />
								<img src="image/png/man-2.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="5" />
								<img src="image/png/man-3.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="6" />
								<img src="image/png/man-4.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="7" />
								<img src="image/png/man-5.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="8" />
								<img src="image/png/woman.png" width="70px">
							</label>
							<br/>
							<label>
								<input type="radio" name="dp" value="9" />
								<img src="image/png/woman-1.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="10" />
								<img src="image/png/woman-2.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="11" />
								<img src="image/png/woman-3.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="12" />
								<img src="image/png/woman-4.png" width="70px">
							</label>
							<label>
								<input type="radio" name="dp" value="13" />
								<img src="image/png/woman-5.png" width="70px">
							</label>
						</div>
					</div>
					<input type="hidden" name="uname" value="<?php echo $uname;?>">
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  <button type="submit" class="btn btn-primary">Submit</button>
				  </form>
				</div>
			  </div>
			</div>
		</div>
	
	<!--Upload Document-->	  	
		<div class="modal fade" id="uploaddocu" role="dialog">
			<div class="modal-dialog">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Upload</h4>
				</div>
				<form method="POST" action="uploadfile.php" enctype="multipart/form-data">
					<div class="modal-body">
					  <blockquote class="small text-muted">Available files for upload are .DOC, .DOCX, .PDF, .XLS, .JPG, .JPEG, .PNG, .TIFF, and .GIF only.</blockquote>
					  
					  <label class="small control-label">Upload File As</label>
					  <div class="form-group">
						  <?php if($utype=="Attorney"){ ?>
							  <span class="radio-inline"><input type="radio" id="regdoc" name="doctype" value="mydocs" checked>My own document</span>
						      <span class="radio-inline"><input type="radio" id="tempdoc" name="doctype" value="sharedocs">Document template</span><?php
						  } else{ ?>
							  <span class="radio-inline"><input type="radio" id="tempdoc" name="doctype" value="sharedocs" checked>Document template</span><?php
						  } ?>
					  </div>
					  
					  <label class="small control-label">Select File</label>
					  <input id="fileupload" name="fileupload" type="file" multiple class="file-loading" accept="application/pdf, application/vnd.ms-word, application/vnd.ms-excel, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/*" required>
					  <input type="hidden" name="utype" value="<?php echo $utype; ?>">
					  <input type="hidden" name="uname" value="<?php echo $uname;?>">
					  <div id="errorBlock" class="help-block"></div><br>
					  
					  <div class="row">
						  <div class="col-sm-6">
							  <label class="small control-label">Document Category</label>
							  <select class="form-control" name="doccat" required>
								<?php $dcateg = mysql_query("SELECT * FROM document_category ORDER BY cat_name ASC"); 
								if(mysql_num_rows($dcateg) > 0){
									while($dcat = mysql_fetch_array($dcateg)){
										echo "<option value='".$dcat["cat_id"]."'>".$dcat["cat_name"]."</option>";
									}
								} else {
									echo "<option value=''></option>";
								}?>
							  </select>
						  </div>
						  <div class="col-sm-6">
							  <label class="small control-label">Tailored Matter <small>(Optional)</small></label>
							  <input list="casenames" id="tailoredmatter" name="docmat" class="form-control" placeholder="Matter Number" style="margin-top:6px;" <?php if($utype=="Staff"){echo "disabled";} ?>></span>
							  <datalist id="casenames">
								<?php $getusername = mysql_query("SELECT p.user_id, p.user_fname, p.user_lname  FROM user_profile p, user u WHERE p.user_id=u.user_id AND u.user_uname='$uname'");
								if(mysql_num_rows($getusername) > 0){
									list($usrid, $usrfname, $usrlname) = mysql_fetch_row($getusername);
									$usrfllname = $usrfname." ".$usrlname; ?>
								    <input type="hidden" name="uid" value="<?php echo $uid;?>"><?php
								} else {
									$usrfname = "User ".$usrid;
									$usrfllname = $usrfname;
								}
								$getcasename = mysql_query("SELECT cm_name FROM case_matter WHERE cm_resp_atty='$usrfllname'");
								if(mysql_num_rows($getcasename) > 0){
									while($casename = mysql_fetch_array($getcasename)){
										echo "<option value='" .$casename["cm_name"]."'>";
									}
								} else{ ?>
									<option value=""><?php
								}?>
						      </datalist> 
							  
						  </div>
					  </div>
					  
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  <button type="submit" class="btn btn-primary">Upload</button>
					</div>
				</form>	
			  </div>
			  
			</div>
		</div>
		
		<!-- Edit Client Info-->
		<div class="modal fade" id="editclientinfo" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Edit Client Information</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="updateclient.php">
						<label class="small idtext"></label><br/>
						<input type="text" name="clientfname" class="form-control" style="width:47%; display:inline-block;" placeholder="First Name">
						<input type="text" name="clientlname" class="form-control" style="width:50%; display:inline-block;" placeholder="Last Name">
						<br/><br/>
					    <label class="small">Contact Number</label><br/>
					    <input type="text" class="form-control"name="clientcontact" placeholder="Contact Number">
						<br/>
						<label class="small">Address</label><br/>
					    <input type="text" class="form-control"name="clientaddress" placeholder="House/Building/Street Number Barangay">
						<select name="clientlocation" class="form-control">
						    <option value="" disabled selected>City/Municipality</option>
							<?php $loc = mysql_query("SELECT des_id AS id, des_name AS name FROM designation");
							if(mysql_num_rows($loc) > 0){
								while($l = mysql_fetch_array($loc)){
									echo "<option value='" .$l["id"] ."'>" .$l["name"] ."</option>";
								}
							} else{
								echo "<option value=''></option>";
							}?>
						</select>
						<input type="hidden" name="val" class="idval">
				</div>
				<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Edit</button>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<!-- Delete Client Info-->
		<div class="modal fade" id="delclientinfo" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Delete Client Information</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="delclient.php">
						<label>Are you sure you want to delete the client and its associating information?</label>
						Client information cannot be reverted back once deleted.
						<input type="hidden" name="val" class="idval"><br/><br/>
						<div align="right"><button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="submit" class="btn btn-primary">Yes</button></div>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<!-- Change Case Status-->
		<div class="modal fade" id="editstatusmatter" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Change Case Status</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="updatematter.php">
						<label class="idtext"></label><br/><br/>
							<p align="left" class="text-muted"><small><b>Case Status</b></small></p>
							<select name="casestatus" class="form-control">
								<option value="" disabled selected>Select status</option>
								<option value="Pending">Pending</option>
								<option value="Dismissed">Dismissed</option>
							</select><br/>
							
							<p align="left" class="text-muted"><small><b>Pending or Dismissed Date</b></small></p>
							<input type="text" name="statusdate" class="form-control datepick"  data-provide="datepicker" placeholder="mm/dd/yyyy">
							
							<input type="hidden" name="utype" value="Users">
						    <input type="hidden" name="casename" class="idval">
							<input type="hidden" name="action" value="editstatus">
				</div>
				<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Change</button>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<!-- Edit Matter-->
		<div class="modal fade" id="editmatter" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Edit Matter Information</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="updatematter.php">
						<label class="idtext"></label><br/><br/>
							<p align="left" class="text-muted"><small><b>New Matter Name</b></small></p>
							<input type="text" name="mattername" class="form-control" placeholder="New matter name"><br/>
							
							<p align="left" class="text-muted"><small><b>New Description</b></small></p>
							<textarea name="casedesc" class="form-control" placeholder="New matter description"></textarea><br/>
							
							<p align="left" class="text-muted"><small><b>New Responsible Attorney</b></small></p>
							<select class="form-control" name="caseatty">
								<option value="" disabled selected>Select Attorney</option>
								<?php $atty = mysql_query("SELECT u.user_id AS id, p.user_fname AS fname, p.user_lname AS lname FROM user u, user_profile p WHERE u.user_type='Attorney' AND u.user_id=p.user_id");
								$adminatty = mysql_query("SELECT a.admin_id AS id, p.admin_fname AS fname, p.admin_lname AS lname FROM admin a, admin_profile p WHERE a.user_type='Administrator' AND a.admin_id=p.admin_id");
								while($respadatty = mysql_fetch_array($adminatty)){?>
									<option value="<?php echo $respadatty['fname'] ." " .$respadatty['lname'];?>"><?php echo $respadatty['fname'] ." " .$respadatty['lname'];?></option> <?php
								
								}
								while($respatty = mysql_fetch_array($atty)){?>
									<option value="<?php echo $respatty['fname'] ." " .$respatty['lname'];?>"><?php echo $respatty['fname'] ." " .$respatty['lname'];?></option> <?php
								
								}?>
							</select>
							
						    <input type="hidden" name="utype" value="Users">
							<input type="hidden" name="casename" class="idval">
							<input type="hidden" name="action" value="editmatter">
				</div>
				<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Change</button>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<!-- Delete Matter-->
		<div class="modal fade" id="delmatter" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Delete Matter</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="updatematter.php">
					    Delete matter <b><span class="idtext"></span></b>?
						Matter information cannot be reverted back once deleted.
						
						<input type="hidden" name="utype" value="Users">
						<input type="hidden" name="action" value="delmatter">
						<input type="hidden" name="casename" class="idval"><br/><br/>
						<div align="right"><button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="submit" class="btn btn-primary">Yes</button></div>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<!-- Add Notes-->
		<div class="modal fade" id="addnotes" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Matter Notes</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="addnotes.php">
						Add additional information or notes for matter <label class="idtext"></label><br/><br/>
							<p align="left" class="text-warning"><small><b><span class="glyphicon glyphicon-warning-sign"></span> 
							 If you save any changes on this part the existing notes will be overwritten.</b></small></p>
							<textarea name="notes" placeholder="Place other details of your matter here" class="todoval form-control">
							</textarea>
							<br/>
							<input type="hidden" name="utype" value="Users">
						    <input type="hidden" name="casename" class="idval">
							<input type="hidden" name="view" value="case_all">
				</div>
				<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update</button>
					</form>	
				</div>
			  </div>
			</div>
		</div>		
		
		<!-- Delete Document-->
		<div class="modal fade" id="docdelete" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Delete File</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="delete.php">
					    Delete <b><span class="todotext"></span></b>?<br/>
						Deleting this only deletes the current version<br><br>
						<input type="checkbox" name="delallver" value="deleteall"> Delete all versions 
						
						<input type="hidden" name="utype" value="<?php echo $utype;?>">
						<input type="hidden" name="docid" class="idval"><br/><br/>
						<div align="right"><button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="submit" class="btn btn-primary">Yes</button></div>
					</form>	
				</div>
			  </div>
			</div>
		</div>
        
		<!-- Delete Document Template -->
		<div class="modal fade" id="docdeletetemp" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Delete File</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="deletetemp.php">
					    Delete <b><span class="todotext"></span></b>?
						
						<input type="hidden" name="utype" value="<?php echo $utype;?>">
						<input type="hidden" name="docid" class="idval"><br/><br/>
						<div align="right"><button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="submit" class="btn btn-primary">Yes</button></div>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<!-- Rename Document-->
		<div class="modal fade" id="docrename" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Rename File</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="rename.php">
						<small>Renaming file removes it from versions list if any, and saves as a new file</small><br/><br/>
						<label class="small text-muted">New name</label><br/>
						<div class="input-group">
						    <input type="text" style="margin:0 !important;" class="form-control todoname" name="rename" placeholder="File Name">	
						    <span class="input-group-addon" style="padding: 4px 12px; !important">.<span class="todoext"></span></span>
						</div>
						
						<?php list($uid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
						<input type="hidden" name="uid" value="<?php echo $uid; ?>">
						<input type="hidden" name="ext" class="passext">
						<input type="hidden" name="utype" value="<?php echo $utype;?>">
						<input type="hidden" name="docid" class="todoid">
				</div>
				<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update</button>
					</form>	
				</div>
			  </div>
			</div>
		</div>	
		
		<!-- Rename Document Template-->
		<div class="modal fade" id="doctemprename" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Rename File</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="renametemp.php">
						<label class="small text-muted">Rename</label><br/>
						<div class="input-group">
						    <input type="text" style="margin:0 !important;" class="form-control todoname" name="renametemp" placeholder="File Name">	
						    <span class="input-group-addon" style="padding: 4px 12px; !important">.<span class="todoext"></span></span>
						</div>
						
						<?php list($uid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
						<input type="hidden" name="uid" value="<?php echo $uid; ?>">
						<input type="hidden" name="ext" class="passext">
						<input type="hidden" name="utype" value="<?php echo $utype;?>">
						<input type="hidden" name="docid" class="todoid">
				</div>
				<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update</button>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<!--Edit Document-->
		<div class="modal fade" id="docedit" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Edit File Info</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="updatefile.php">
						<label class="small text-muted">New Tailored Matter</label><br/>
						<input list="casenames" name="docmat" class="form-control" placeholder="Matter Number" style="margin-top:6px;"></span>
							  <datalist id="casenames">
								<?php $getadusername = mysql_query("SELECT admin_fname, admin_lname  FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND a.admin_uname='$uname'");
								$getusername = mysql_query("SELECT user_fname, user_lname  FROM user_profile p, user u WHERE p.admin_id=u.user_id AND u.user_uname='$uname'");
								if(mysql_num_rows($getusername) > 0 || mysql_num_rows($getadusername) > 0){
									list($usradfname, $usradlname) = mysql_fetch_row($getadusername);
									list($usrfname, $usrlname) = mysql_fetch_row($getusername);
									$usradfllname = $usradfname." ".$usradlname;
									$usrfllname = $usrfname." ".$usrlname;
								} else {
									$usrfname = "User";
									$usrfllname = $usrfname;
								}
								$getcasename = mysql_query("SELECT cm_name FROM case_matter WHERE cm_resp_atty='$usrfllname' OR cm_resp_atty='$usradfllname'");
								if(mysql_num_rows($getcasename) > 0){
									while($casename = mysql_fetch_array($getcasename)){
										echo "<option value='" .$casename["cm_name"]."'>";
									}
								} else{ ?>
									<option value=""><?php
								}?>
						      </datalist> 
						
						<br/>
						<label class="small text-muted">New Category</label><br/>
						<select id="editdoccat" class="form-control" name="doccat">
							<option value="" class="todotext"></option>
							<?php $dcateg = mysql_query("SELECT * FROM document_category"); 
							if(mysql_num_rows($dcateg) > 0){
								while($dcat = mysql_fetch_array($dcateg)){
									echo "<option value='".$dcat["cat_id"]."'>".$dcat["cat_name"]."</option>";
								}
							} else {
								echo "<option value=''></option>";
							}?>
						</select>
						
						<?php list($userid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
						<input type="hidden" name="uid" value="<?php echo $userid;?>">
						<input type="hidden" name="utype" value="<?php echo $utype;?>">
						<input type="hidden" name="docid" class="idval">
				</div>
				<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update</button>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<!--Edit Document Template Category-->
		<div class="modal fade" id="doceditcat" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Edit Template Category</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="updatefile.php">
						<label class="small text-muted">New Category</label><br/>
						<select id="editdoccat" class="form-control" name="doccat">
							<option value="" class="todotext"></option>
							<?php $dcateg = mysql_query("SELECT * FROM document_category"); 
							if(mysql_num_rows($dcateg) > 0){
								while($dcat = mysql_fetch_array($dcateg)){
									echo "<option value='".$dcat["cat_id"]."'>".$dcat["cat_name"]."</option>";
								}
							} else {
								echo "<option value=''></option>";
							}?>
						</select>
						
						<?php list($userid) = mysql_fetch_row(mysql_query("SELECT user_id FROM user WHERE user_uname='$uname'")); ?>
						<input type="hidden" name="uid" value="<?php echo $userid;?>">
						<input type="hidden" name="utype" value="<?php echo $utype;?>">
						<input type="hidden" name="doctype" value="templates">
						<input type="hidden" name="docid" class="idval">
				</div>
				<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update</button>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<!-- View Document Activities-->
		<div class="modal fade" id="docdetails" role="dialog">
			<div class="modal-dialog">
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title"><b><span class="docuname"></span></b> - Activity Details</h4>
				</div>
				<div class="modal-body" style="height:350px; overflow-y:auto;">
				    <div class="container col-sm-12">
						<div class="fetched-data"></div>
					</div>
				</div>
				<div class="modal-footer">
				    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			  </div>
			</div>
		</div>
		
		<!--Share Document-->
		<div class="modal fade" id="docshare" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">File Sharing</h4>
				</div>
				<div class="modal-body">
				    <form method="post" action="sharefile.php">
						<h5><span class="todotext"></span></h5>
						<label class="small text-muted">Share to</label><br/>
						<input list="usernames" name="shareduser" class="form-control" placeholder="User" style="margin-top:6px;"></span>
							  <datalist id="usernames">
								<?php $admins = mysql_query("SELECT a.admin_id, p.admin_fname, p.admin_lname FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id");
								$users = mysql_query("SELECT u.user_id, p.user_fname, p.user_lname FROM user_profile p, user u WHERE p.user_id=u.user_id AND NOT u.user_uname='$uname'");
								if(mysql_num_rows($users) > 0 || mysql_num_rows($admins) > 0){
									while($admin = mysql_fetch_array($admins)){
										echo "<option value='" .$admin["admin_lname"].", ".$admin["admin_fname"]."'>";
									}
									while($user = mysql_fetch_array($users)){
										echo "<option value='" .$user["user_lname"].", ".$user["user_fname"]."'>";
									}
							
								} else{ ?>
									<option value=""><?php
								}?>
						      </datalist> <br/>
							  <input type="checkbox" name="shareall" value="all"> Share to <b>everyone</b> instead
						
						<br/>
						
						<input type="hidden" name="utype" value="<?php echo $utype;?>">
						<input type="hidden" name="owner" value="<?php echo $uname;?>">
						<input type="hidden" name="docid" class="idval">
				</div>
				<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Share</button>
					</form>	
				</div>
			  </div>
			</div>
		</div>
		
		<script>
		$(document).ready(function(){
			$(".nav-tabs a").click(function(){
				$(this).tab('show');
				
			});
		});
		
		$(document).ready(function(){
			$('[data-toggle="popover"]').popover(); 
		});
		
	
		function openspectab(){
			var url = document.location.toString();
			
			//pills
			if(url.match('#case')){
				$('.nav-tabs a[href="#matters"]').tab('show');
				$('.nav-tabs a[href="#matters_matter"]').tab('show');
				$('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
			} else if(url.match('#client')){
				$('.nav-tabs a[href="#matters"]').tab('show');
				$('.nav-tabs a[href="#matters_client"]').tab('show');
				$('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
			} else if(url.match('#mydocs') || url.match('#shareddocs')){
				$('.nav-tabs a[href="#documents"]').tab('show');
				$('.nav-tabs a[href="#documents_list"]').tab('show');
				$('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
			} else if(url.match('#template')){
				$('.nav-tabs a[href="#documents"]').tab('show');
				$('.nav-tabs a[href="#documents_template"]').tab('show');
				$('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
			}
			
			//tabs
			else if(url.match('#matters')){
				$('.nav-tabs a[href="#matters"]').tab('show');
			} else if(url.match('#documents')){
				$('.nav-tabs a[href="#documents"]').tab('show');
			} 
			
			else if (url.match('#')) {
				$('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
			}
		}
		
		var url = document.location.toString();
		if (url.match('#')) {
			$('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
		} 

		// Change hash for page-reload
		$('.nav-tabs a').on('shown.bs.tab', function (e) {
			window.location.hash = e.target.hash;
		});
		
		$('.nav-pills a').on('shown.bs.tab', function (e) {
			window.location.hash = e.target.hash;
		});
		
		
		$('#clearpass').click(function(){
			var form = document.getElementById("updatepw");
            form.reset();
		});
		
		$('#confirmpassword').keyup(function () {
			if ($(this).val() == $('#newpassword').val()) {
				$('#checkconfirm').html('Passwords matched').css('color', 'green');
			} else
				$('#checkconfirm').html('Passwords do not matched').css('color', 'red');
			
		});


        $('.datepick').datepicker();
		
		function resizeinput(){
			$(this).attr('size', $(this).val().length);
		}
		
		$('#accfname').keyup(resizeinput).each(resizeinput);
		$('#acclname').keyup(resizeinput).each(resizeinput);
		
		//edit myaccount name info
		$('#editnameinfo').click(function(){
			$('#accfname').prop('disabled', false);
			$('#accfname').css('border-bottom', '2px solid #f0ad4e');
			
			$('#acclname').prop('disabled', false);
			$('#acclname').css('border-bottom', '2px solid #f0ad4e');
			
			$('#accuname').prop('disabled', false);
			$('#accuname').css('border-bottom', '2px solid #f0ad4e');
			
		});
		
		//edit myaccount basic info
		$('#editbasicinfo').click(function(){
			$('#accpos').prop('disabled', false);
			$('#accpos').css('border-bottom', '2px solid #f0ad4e');	
		});

		//edit myaccount contact info
		$('#editcontactinfo').click(function(){
			$('#accphone').prop('disabled', false);
			$('#accphone').css('border-bottom', '2px solid #f0ad4e');

			$('#accemail').prop('disabled', false);
			$('#accemail').css('border-bottom', '2px solid #f0ad4e');
			
		});
		
		//pass id to modal
		$(document).on("click", ".passidtomodal", function(){
			 var id = $(this).data('id');
			 $(".modal-body .idval").val(id);
			 $(".modal-body .idtext").text(id);
			 
			 var todo = $(this).data('todo');
			 $(".modal-body .todoval").val(todo);
		});
		
		//pass id and name to document modal
		$(document).on("click", ".documentmodal", function(){
			 var id = $(this).data('id');
			 $(".modal-body .idval").val(id);
			 
			 var todo = $(this).data('todo');
			 $(".modal-body .todotext").text(todo);
			 $(".modal-body .todoval").val(todo);
		});
		
		// rename document modal
		$(document).on("click", "#renamedoc", function(){
			 var id = $(this).data('id');
			 var fln = $(this).data('basename');
			 var ext = $(this).data('ext');
			 
			 $(".modal-body .todoid").val(id);
			  $(".modal-body .todoname").val(fln);
			 $(".modal-body .todoext").text(ext);
			 $(".modal-body .passext").val(ext);
		});
		
		// document details/activity modal
		$(document).on("click", "#detailsmodal", function(){
			 var docuname = $(this).data('name');
			 var docuicon = $(this).data('icon');
			 var docutype = $(this).data('doctype');
			 var docusize = $(this).data('size');
			 var docauthor = $(this).data('author');
			 var docudate = $(this).data('create');
			 
			 $(".modal-header .docuname").text(docuname);
			 $(".modal-body .docuname").text(docuname);
			 $(".modal-body .docuicon").text(docuicon);
			 $(".modal-body .docutype").text(docutype);
			 $(".modal-body .docusize").text(docusize);
			 $(".modal-body .docauthor").text(docauthor);
			 $(".modal-body .docudate").text(docudate);
		});
		
		
		$(document).ready(function() {
			$("#fileupload").fileinput({
				showPreview: false,
				allowedFileExtensions: ["doc", "docx", "pdf", "tiff", "xls", "jpg", "jpeg", "png", "gif"],
				elErrorContainer: "#errorBlock"
			});
		});
		
		$('.evttime').timepicker({ 
			timeFormat: 'h:mm p',
			startTime: '07:00',
			minTime: '07:00',
			maxTime: '17:00',
			dynamic: false,
			dropdown: true,
			scrollbar: true,
			showTodayButton: true
		});
		
		// document details/activity modal
		$(document).on("click", "#detailsmodal", function(){
			 var docuname = $(this).data('name');
			 var docid = $(this).data('id');
			 
			 $(".modal-header .docuname").text(docuname);
				$.ajax({
					type: 'post',
					url: 'activity.php', 
					data:  'docid='+ docid +'&uname=' +'<?php echo $uname; ?>', 
					dataType: 'html',
					success: function(data){
					$('.modal-body .fetched-data').html(data);
					}
				});
		});

		$(document).on("click", "#tempdoc", function () {
			document.getElementById("tailoredmatter").disabled = true;
		});
		
		$(document).on("click", "#regdoc", function () {
			document.getElementById("tailoredmatter").disabled = false;
		});
		
		function myFunction() {
		  // Declare variables 
		  var input, filter, table, tr, td, i;
		  input = document.getElementById("myInput");
		  filter = input.value.toUpperCase();
		  table = document.getElementById("myTable");
		  tr = table.getElementsByTagName("tr");

		  // Loop through all table rows, and hide those who don't match the search query
		  for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			if (td) {
			  if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			  } else {
				tr[i].style.display = "none";
			  }
			} 
		  }
		}
		</script>
     
</body>
</html>
