<?php include("database.php");
$type = $_POST["rpttype"];

$uname = $_POST["username"];
list($fname, $lname, $pos) = mysql_fetch_row(mysql_query("SELECT p.admin_fname, p.admin_lname, p.admin_pos FROM admin a, admin_profile p WHERE p.admin_id=a.admin_id AND a.admin_uname='$uname'"));
$name = "Atty. ".$fname." ".$lname;
?>

<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">
	<title>Reports - paoDocs</title>
	<link rel="ICON" type="image/png" href="image/icon.png" />
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/index.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.min.css" />
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.timepicker.min.js"></script>
	
	<style>
	input[type=text] {border-radius:0;box-shadow: 0px 0px 0px;outline: none;border-color: transparent; padding:0; background-color: transparent;border-bottom: 2px solid #31708f;}
	input[type=text]:focus {box-shadow: 0px 0px 0px;outline: none;border-color: transparent;border-bottom: 2px solid #6EB6FF;}
	.select {border-radius:0;box-shadow: 0px 0px 0px;outline: none; padding:0; border-color: transparent;background-color: transparent !important;border-bottom: 2px solid #31708f;}
	.select:focus{box-shadow: 0px 0px 0px;outline: none;border-color: transparent;border-bottom: 2px solid #6EB6FF;}
	.textarea {box-shadow: 0px 0px 0px;outline:none !important;border-radius:0;border-color: transparent;background-color: transparent;border-bottom: 2px solid #31708f;}
	.textarea:focus{box-shadow: 0px 0px 0px;outline: none;border-color: transparent;border-bottom: 2px solid #6EB6FF;}
	#container {width:95%; height:540px; padding-top: 29px; padding-bottom: 29px; text-align:justify; overflow-y:auto;}

	</style>
</head>
	
<body >
    <br /><br />
    <div align="center" id="container">			
		<?php //CLIENT ACTIVITY REPORT (by SPEC DATE)
		if($type == "dailyclientact"){
			$date = $_POST["datedailyreport"];
			$rows = mysql_query("SELECT * FROM client_log WHERE log_date=DATE_FORMAT(STR_TO_DATE('$date', '%m/%d/%Y'), '%Y-%m-%d')");?>
			
			<div style="float:left;"><h3>Client Activity Report <small>(for <?php echo $date;?>)</small></h3></div>
			<div style="float:right;"><img src="image/logo.png" height="75px"/></div>
			<br/><br/><br/>
			       
			<br/>
			
			<!-- table to display -->
			<table class="table table-responsive form-horizontal" style="color:black;">
				<thead>
				  <tr class="active">
					<th class="col-xs-3">Name</th>
					<th class="col-xs-2">Purpose</th>
					<th class="col-xs-4">Address</th>
					<th class="col-xs-2">Contact Number</th>
					<th class="col-xs-1">Log Time</th>
				  </tr>
				</thead>
				<tbody >
			  <?php 
				if(mysql_num_rows($rows) > 0){
					while($row = mysql_fetch_array($rows)){?>
						<tr><td class="col-xs-3"><?php echo $row["log_client_fname"]." " .$row["log_client_lname"];?></td>
						    <td class="col-xs-2"><?php echo $row["log_purpose"];?></td>
							<td class="col-xs-4"><?php echo $row["log_address"] .", " .$row["log_location"];?></td>
							<td class="col-xs-2"><?php echo $row["log_client_contact"]; ?></td>
							<td class="col-xs-1"><?php echo date("h:i:s a", strtotime($row["log_timein"]));?></td></tr><?php
					}?>
					<tr><td colspan="5">
					<form method="POST" action="generatecsv.php" target="_blank"> 
						<input type="hidden" name="rpttype" value="dailyclientact">
						<input type="hidden" name="datedailyreport" value="<?php echo $date;?>">
						<input type="hidden" name="filename" value="client_activity_<?php echo date("m_d_Y", strtotime($date));?>">
						<button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-download-alt"></span> Export .CSV file</button>
						<button type="button" class="btn btn-default btn-sm" onclick="PrintDiv();" ><span class="glyphicon glyphicon-print"></span> Print</button>
					</form>
					
					</td></tr><?php
				} else{?>
					<tr><td>No results.</td></tr><?php
				}?>
			    	
			</tbody>
			</table>
			
			<!-- table to print -->
			<div id="divToPrint" style="display:none;"><?php
				$prows = mysql_query("SELECT * FROM client_log WHERE log_date=DATE_FORMAT(STR_TO_DATE('$date', '%m/%d/%Y'), '%Y-%m-%d')");?>
				
				<!--header-->
				<table border="0" style="margin-left:20%;">
					<tr>
						<td rowspan="2"><img src="image/newlogo.jpg" height="96px"/></td>
						<td align="center" style="font-size:10px;">Republika ng Pilipinas<br/>Kagawaran ng Katarungan<br/>Tanggapan ng Manananggol Pambayan<br/>
							<big><b>PUBLIC ATTORNEY'S OFFICE</b></big><br/>New Government Center Building, Provincial Capitol Compound<br/>
							Trece Martires City Cavite<br/>Telephone No. 929-9436; Email: pao_executive@yahoo.com
						</td>
					</tr>
				</table><br/><br/>
				
				<!--content-->
				<h3 align="center">Client Activity Report <small>(for <?php echo $date;?>)</small></h3>
				
				<table border="1" style="border-collapse:collapse;" align="center">
					<thead>
					  <tr>
						<th>Name</th>
						<th>Purpose</th>
						<th>Address</th>
						<th>Contact Number</th>
						<th>Log Time</th>
					  </tr>
					</thead>
					<tbody >
				  <?php 
					if(mysql_num_rows($prows) > 0){
						while($prow = mysql_fetch_array($prows)){?>
							<tr><td><?php echo $prow["log_client_fname"]." " .$prow["log_client_lname"];?></td>
								<td><?php echo $prow["log_purpose"];?></td>
								<td><?php echo $prow["log_address"] .", " .$prow["log_location"];?></td>
								<td><?php echo $prow["log_client_contact"]; ?></td>
								<td><?php echo date("h:i:s a", strtotime($prow["log_timein"]));?></td></tr><?php
						}?>
						<?php
					} else{ echo "";}?>
						
				</tbody>
				</table>
				
				<!--footer-->
				<br/><br/><br/><br/><br/>
				Prepared by:<br/><br/><br/>
				<b><?php echo strtoupper($name); ?></b><br/>
				<?php echo $pos; ?>
			</div>
			
			<?php
			
			
		//CLIENT ACTIVITY REPORT (by SPEC MONTH)
		} else if($type == "monthlyclientact"){
			if(isset($_POST["yearmonthlyreport"])){
				$year = $_POST["yearmonthlyreport"];
			} else {$year = date("Y");}
			$month = $_POST["monthmonthlyreport"];
			$date = $year ."-". $month;
			$rows = mysql_query("SELECT * FROM client_log WHERE log_date BETWEEN '".$date ."-01' AND '".$date ."-31' ORDER BY log_date"); ?>
			
			<div style="float:left;"><h3>Client Activity Report <small>(for <?php echo date_format(date_create($date .'-01'), "F Y");?>)</small></h3></div>
			<div style="float:right;"><img src="image/logo.png" height="75px"/></div>
			<br/><br/><br/>
						
			          
			<br/>
			<!-- table to display-->
			<table class="table table-responsive form-horizontal" style="color:black;">
				<thead>
				  <tr class="active">
					<th class="col-xs-2">Name</th>
					<th class="col-xs-3">Purpose</th>
					<th class="col-xs-3">Address</th>
					<th class="col-xs-2">Contact Number</th>
					<th class="col-xs-1">Log Time</th>
					<th class="col-xs-1">Date</th>
				  </tr>
				</thead>
				<tbody >
			  <?php 
				if(mysql_num_rows($rows) > 0){
					while($row = mysql_fetch_array($rows)){?>
						<tr><td class="col-xs-2"><?php echo $row["log_client_fname"]." " .$row["log_client_lname"];?></td>
						    <td class="col-xs-3"><?php echo $row["log_purpose"];?></td>
							<td class="col-xs-3"><?php echo $row["log_address"] .", " .$row["log_location"];?></td>
							<td class="col-xs-2"><?php echo $row["log_client_contact"]; ?></td>
							<td class="col-xs-1"><?php echo $row["log_timein"];?></td>
							<td class="col-xs-1"><?php echo $row["log_date"];?></td></tr><?php
					}?>
					<tr><td colspan="5">
					    <form method="POST" action="generatecsv.php" target="_blank"> 
							<input type="hidden" name="rpttype" value="monthlyclientact">
							<input type="hidden" name="datemonthlyreport" value="<?php echo $date;?>">
							<input type="hidden" name="filename" value="client_activity_<?php echo date("m_Y", strtotime($year .'/'.$month. '/01'));?>">
							<button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-download-alt"></span> Export .CSV file</button>
							<button type="button" class="btn btn-default btn-sm" onclick="PrintDiv();" ><span class="glyphicon glyphicon-print"></span> Print</button>
						</form>
					</td></tr><?php
				} else{?>
					<tr><td>No results.</td></tr><?php
				}?>
			    	
			</tbody>
			</table>
			
			<!-- table to print -->
			<div id="divToPrint" style="display:none;"><?php
				$prows = mysql_query("SELECT * FROM client_log WHERE log_date BETWEEN '".$date ."-01' AND '".$date ."-31' ORDER BY log_date");?>
				
				<!--header-->
				<table border="0" style="margin-left:20%;">
					<tr>
						<td rowspan="2"><img src="image/newlogo.jpg" height="96px"/></td>
						<td align="center" style="font-size:10px;">Republika ng Pilipinas<br/>Kagawaran ng Katarungan<br/>Tanggapan ng Manananggol Pambayan<br/>
							<big><b>PUBLIC ATTORNEY'S OFFICE</b></big><br/>New Government Center Building, Provincial Capitol Compound<br/>
							Trece Martires City Cavite<br/>Telephone No. 929-9436; Email: pao_executive@yahoo.com
						</td>
					</tr>
				</table><br/><br/>
				
				<!--content-->
				<h3 align="center">Client Activity Report <small>(for <?php echo date_format(date_create($date .'-01'), "F Y");?>)</small></h3>
				
				<table border="1" style="border-collapse:collapse;" align="center">
					<thead>
					  <tr>
						<th>Name</th>
						<th>Purpose</th>
						<th>Address</th>
						<th>Contact Number</th>
						<th>Log Time</th>
					  </tr>
					</thead>
					<tbody >
				  <?php 
					if(mysql_num_rows($prows) > 0){
						while($prow = mysql_fetch_array($prows)){?>
							<tr><td><?php echo $prow["log_client_fname"]." " .$prow["log_client_lname"];?></td>
								<td><?php echo $prow["log_purpose"];?></td>
								<td><?php echo $prow["log_address"] .", " .$prow["log_location"];?></td>
								<td><?php echo $prow["log_client_contact"]; ?></td>
								<td><?php echo date("h:i:s a", strtotime($prow["log_timein"]));?></td></tr><?php
						}?>
						<?php
					} else{ echo "";}?>
						
				</tbody>
				</table>
				
				<!--footer-->
				<br/><br/><br/><br/><br/>
				Prepared by:<br/><br/><br/>
				<b><?php echo strtoupper($name); ?></b><br/>
				<?php echo $pos; ?>
			</div>
			
			<?php
		
		
		//CLIENT ACTIVITY REPORT (by YEAR)	
		} else if($type == "annualclientact"){
			$year = $_POST["yearannualreport"];
			$rows = mysql_query("SELECT * FROM client_log WHERE log_date BETWEEN '".$year ."-01-01' AND '".$year ."-12-31' ORDER BY log_date"); ?>
			
			<div style="float:left;"><h3>Client Activity Report <small>(for <?php echo date_format(date_create($year), "Y");?>)</small></h3></div>
			<div style="float:right;"><img src="image/logo.png" height="75px"/></div>
			<br/><br/><br/>
						
			          
			<br/>
			<!-- table to display-->
			<table class="table table-responsive form-horizontal" style="color:black;">
				<thead>
				  <tr class="active">
					<th class="col-xs-2">Name</th>
					<th class="col-xs-3">Purpose</th>
					<th class="col-xs-3">Address</th>
					<th class="col-xs-2">Contact Number</th>
					<th class="col-xs-1">Log Time</th>
					<th class="col-xs-1">Date</th>
				  </tr>
				</thead>
				<tbody >
			  <?php 
				if(mysql_num_rows($rows) > 0){
					while($row = mysql_fetch_array($rows)){?>
						<tr><td class="col-xs-2"><?php echo $row["log_client_fname"]." " .$row["log_client_lname"];?></td>
						    <td class="col-xs-3"><?php echo $row["log_purpose"];?></td>
							<td class="col-xs-3"><?php echo $row["log_address"] .", " .$row["log_location"];?></td>
							<td class="col-xs-2"><?php echo $row["log_client_contact"]; ?></td>
							<td class="col-xs-1"><?php echo date("h:i:s a", strtotime($row["log_timein"]));?></td>
							<td class="col-xs-1"><?php echo date("m/d/Y", strtotime($row["log_date"]));?></td></tr><?php
					}?>
					<tr><td colspan="5">
					    <form method="POST" action="generatecsv.php" target="_blank"> 
							<input type="hidden" name="rpttype" value="annualclientact">
							<input type="hidden" name="yearannualreport" value="<?php echo $year;?>">
							<input type="hidden" name="filename" value="client_activity_<?php echo date("Y", strtotime($year));?>">
							<button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-download-alt"></span> Export .CSV file</button>
							<button type="button" class="btn btn-default btn-sm" onclick="PrintDiv();" ><span class="glyphicon glyphicon-print"></span> Print</button>
						</form>
					</td></tr><?php
				} else{?>
					<tr><td>No results.</td></tr><?php
				}?>
			    	
			</tbody>
			</table>
			
			<!-- table to print -->
			<div id="divToPrint" style="display:none;"><?php
				$prows = mysql_query("SELECT * FROM client_log WHERE log_date BETWEEN '".$year ."-01-01' AND '".$year ."-12-31' ORDER BY log_date"); ?>
				
				<!--header-->
				<table border="0" style="margin-left:20%;">
					<tr>
						<td rowspan="2"><img src="image/newlogo.jpg" height="96px"/></td>
						<td align="center" style="font-size:10px;">Republika ng Pilipinas<br/>Kagawaran ng Katarungan<br/>Tanggapan ng Manananggol Pambayan<br/>
							<big><b>PUBLIC ATTORNEY'S OFFICE</b></big><br/>New Government Center Building, Provincial Capitol Compound<br/>
							Trece Martires City Cavite<br/>Telephone No. 929-9436; Email: pao_executive@yahoo.com
						</td>
					</tr>
				</table><br/><br/>
				
				<!--content-->
				<h3 align="center">Client Activity Report <small>(for <?php echo date_format(date_create($year), "Y");?>)</small></h3>
				
				<table border="1" style="border-collapse:collapse;" align="center">
					<thead>
					  <tr>
						<th>Name</th>
						<th>Purpose</th>
						<th>Address</th>
						<th>Contact Number</th>
						<th>Log Time</th>
						<th>Date</th>
					  </tr>
					</thead>
					<tbody >
				  <?php 
					if(mysql_num_rows($prows) > 0){
						while($prow = mysql_fetch_assoc($prows)){ ?>
						<tr><td><?php echo $prow["log_client_fname"]." " .$prow["log_client_lname"];?></td>
						    <td><?php echo $prow["log_purpose"];?></td>
							<td><?php echo $prow["log_address"] .", " .$prow["log_location"];?></td>
							<td><?php echo $prow["log_client_contact"]; ?></td>
							<td><?php echo date("h:i:s a", strtotime($prow["log_timein"]));?></td>
							<td><?php echo date("m/d/Y", strtotime($prow["log_date"]));?></td></tr><?php
						}?>
						<?php
					} else{ echo "";}?>
						
				</tbody>
				</table>
				
				<!--footer-->
				<br/><br/><br/><br/><br/>
				Prepared by:<br/><br/><br/>
				<b><?php echo strtoupper($name); ?></b><br/>
				<?php echo $pos; ?>
			</div>
			
			<?php
			

		//MATTER REPORT
		} else if($type == "allmatreport"){
			$rows = mysql_query("SELECT * FROM case_matter"); ?>
			
			
			<div style="float:left;"><h3>Matters Report</h3></div>
			<div style="float:right;"><img src="image/logo.png" height="75px"/></div>
			<br/><br/><br/>
						
			<br/>
			<!-- table to display-->
			<table class="table table-responsive form-horizontal" style="color:black;">
				<thead>
				  <tr class="active">
					<th class="col-xs-2">Matter</th>
					<th class="col-xs-3">Description</th>
					<th class="col-xs-3">Status</th>
					<th class="col-xs-2">Responsible Attorney</th>
					<th class="col-xs-2">Originating Attorney</th>
				  </tr>
				</thead>
				<tbody >
			  <?php 
				if(mysql_num_rows($rows) > 0){
					while($row = mysql_fetch_array($rows)){?>
						<tr><td class="col-xs-2"><?php echo $row["cm_name"];?></td>
						    <td class="col-xs-3"><?php echo $row["cm_description"];?></td>
							<td class="col-xs-3"><?php if($row["cm_status"] == "New"){
								echo $row["cm_status"]. " (".date("m/d/Y", strtotime($row["cm_date_created"])) .")";
							} else if($row["cm_status"] == "Pending"){
								echo $row["cm_status"]. " (".date("m/d/Y", strtotime($row["cm_date_pending"])) .")";
							} else{
								echo $row["cm_status"]. " (".date("m/d/Y", strtotime($row["cm_date_dismissed"])) .")";
							}?></td>
							<td class="col-xs-2"><?php echo $row["cm_resp_atty"]; ?></td>
							<td class="col-xs-2"><?php echo $row["cm_orig_atty"];?></td></tr><?php
					}?>
					<tr><td colspan="5">
					    <form method="POST" action="generatecsv.php" target="_blank"> 
							<input type="hidden" name="rpttype" value="allmatreport">
							<input type="hidden" name="filename" value="matter_report_<?php echo date("m_d_Y", strtotime("now"));?>">
							<button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-download-alt"></span> Export .CSV file</button>
							<button type="button" class="btn btn-default btn-sm" onclick="PrintDiv();" ><span class="glyphicon glyphicon-print"></span> Print</button>
						</form>
					</td></tr><?php
				} else{?>
					<tr><td>No results.</td></tr><?php
				}?>
			    	
			</tbody>
			</table>
			
			<!-- table to print -->
			<div id="divToPrint" style="display:none;"><?php
				$prows = mysql_query("SELECT * FROM case_matter");  ?>
				
				<!--header-->
				<table border="0" style="margin-left:20%;">
					<tr>
						<td rowspan="2"><img src="image/newlogo.jpg" height="96px"/></td>
						<td align="center" style="font-size:10px;">Republika ng Pilipinas<br/>Kagawaran ng Katarungan<br/>Tanggapan ng Manananggol Pambayan<br/>
							<big><b>PUBLIC ATTORNEY'S OFFICE</b></big><br/>New Government Center Building, Provincial Capitol Compound<br/>
							Trece Martires City Cavite<br/>Telephone No. 929-9436; Email: pao_executive@yahoo.com
						</td>
					</tr>
				</table><br/><br/>
				
				<!--content-->
				<h3 align="center">Matters Report</h3>
				
				<table border="1" style="border-collapse:collapse;" align="center">
					<thead>
					  <tr>
						<th>Matter</th>
						<th>Description</th>
						<th>Status</th>
						<th>Responsible Attorney</th>
						<th>Originating Attorney</th>
					  </tr>
					</thead>
					<tbody >
				  <?php 
					if(mysql_num_rows($prows) > 0){
						while($prow = mysql_fetch_array($prows)){?>
							<tr><td><?php echo $prow["cm_name"];?></td>
								<td><?php echo $prow["cm_description"];?></td>
								<td><?php if($row["cm_status"] == "New"){
								echo $row["cm_status"]. " (".date("m/d/Y", strtotime($row["cm_date_created"])) .")";
								} else if($row["cm_status"] == "Pending"){
									echo $row["cm_status"]. " (".date("m/d/Y", strtotime($row["cm_date_pending"])) .")";
								} else{
									echo $row["cm_status"]. " (".date("m/d/Y", strtotime($row["cm_date_dismissed"])) .")";
								}?></td>
								<td><?php echo $prow["cm_resp_atty"]; ?></td>
								<td><?php echo $prow["cm_orig_atty"];?></td>
							</tr><?php
						}?>
						<?php
					} else{ echo "";}?>
						
				</tbody>
				</table>
				
				<!--footer-->
				<br/><br/><br/><br/><br/>
				Prepared by:<br/><br/><br/>
				<b><?php echo strtoupper($name); ?></b><br/>
				<?php echo $pos; ?>
			</div>
			
			<?php
			          
		
		//MATTERS BY STATUS
		} else if($type == "matbystatus"){
			$new = mysql_query("SELECT * FROM case_matter WHERE cm_status='New'"); 
			$pending = mysql_query("SELECT * FROM case_matter WHERE cm_status='Pending'"); 
			$dismissed = mysql_query("SELECT * FROM case_matter WHERE cm_status='Dismissed'"); ?>
			
			<div style="float:left;"><h3>Matters Report</h3></div>
			<div style="float:right;"><img src="image/logo.png" height="75px"/></div>
			<br/><br/><br/>
						
			<br/>
			<!--new-->
			<label class="lead">New</label>
			<table class="table table-responsive form-horizontal" style="color:black;">
				<thead>
				  <tr class="active">
					<th class="col-xs-2">Matter</th>
					<th class="col-xs-3">Description</th>
					<th class="col-xs-3">Date</th>
					<th class="col-xs-2">Responsible Attorney</th>
					<th class="col-xs-2">Originating Attorney</th>
				  </tr>
				</thead>
				<tbody >
			  <?php 
				if(mysql_num_rows($new) > 0){
					while($n = mysql_fetch_array($new)){?>
						<tr><td class="col-xs-2"><?php echo $n["cm_name"];?></td>
						    <td class="col-xs-3"><?php echo $n["cm_description"];?></td>
							<td class="col-xs-3"><?php echo date("m/d/Y", strtotime($n["cm_date_created"]));?></td>
							<td class="col-xs-2"><?php echo $n["cm_resp_atty"]; ?></td>
							<td class="col-xs-2"><?php echo $n["cm_orig_atty"];?></td></tr><?php
					}
				} else{?>
					<tr><td>No results.</td></tr><?php
				}?>
			    	
			    </tbody>
			</table>
			<br/>
			<!--pending-->
			<label class="lead">Pending</label>
			<table class="table table-responsive form-horizontal" style="color:black;">
				<thead>
				  <tr class="active">
					<th class="col-xs-2">Matter</th>
					<th class="col-xs-3">Description</th>
					<th class="col-xs-3">Date</th>
					<th class="col-xs-2">Responsible Attorney</th>
					<th class="col-xs-2">Originating Attorney</th>
				  </tr>
				</thead>
				<tbody >
			  <?php 
				if(mysql_num_rows($pending) > 0){
					while($p = mysql_fetch_array($pending)){?>
						<tr><td class="col-xs-2"><?php echo $p["cm_name"];?></td>
						    <td class="col-xs-3"><?php echo $p["cm_description"];?></td>
							<td class="col-xs-3"><?php echo date("m/d/Y", strtotime($p["cm_date_pending"]));?></td>
							<td class="col-xs-2"><?php echo $p["cm_resp_atty"]; ?></td>
							<td class="col-xs-2"><?php echo $p["cm_orig_atty"];?></td></tr><?php
					}
				} else{?>
					<tr><td>No results.</td></tr><?php
				}?>
			    	
			    </tbody>
			</table>
			<br/>
			<!--dismissed-->
			<label class="lead">Dismissed</label>
			<table class="table table-responsive form-horizontal" style="color:black;">
				<thead>
				  <tr class="active">
					<th class="col-xs-2">Matter</th>
					<th class="col-xs-3">Description</th>
					<th class="col-xs-3">Date</th>
					<th class="col-xs-2">Responsible Attorney</th>
					<th class="col-xs-2">Originating Attorney</th>
				  </tr>
				</thead>
				<tbody >
			  <?php 
				if(mysql_num_rows($dismissed) > 0){
					while($d = mysql_fetch_array($dismissed)){?>
						<tr><td class="col-xs-2"><?php echo $d["cm_name"];?></td>
						    <td class="col-xs-3"><?php echo $d["cm_description"];?></td>
							<td class="col-xs-3"><?php echo date("m/d/Y", strtotime($d["cm_date_dismissed"]));?></td>
							<td class="col-xs-2"><?php echo $d["cm_resp_atty"]; ?></td>
							<td class="col-xs-2"><?php echo $d["cm_orig_atty"];?></td></tr><?php
					}
				} else{?>
					<tr><td>No results.</td></tr><?php
				}?>
			    	
			    </tbody>
			</table>
			
			<form method="POST" action="generatecsv.php" target="_blank"> 
				<input type="hidden" name="rpttype" value="matbystatus">
				<input type="hidden" name="filename" value="matter_by_status_<?php echo date("m_d_Y", strtotime("now"));?>">
				<button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-download-alt"></span> Export .CSV file</button>
				<button type="button" class="btn btn-default btn-sm" onclick="PrintDiv();" ><span class="glyphicon glyphicon-print"></span> Print</button>
			</form>
			
			<!-- table to print -->
			<div id="divToPrint" style="display:none;"><?php
				$pnew = mysql_query("SELECT * FROM case_matter WHERE cm_status='New'"); 
		      	$ppending = mysql_query("SELECT * FROM case_matter WHERE cm_status='Pending'"); 
		    	$pdismissed = mysql_query("SELECT * FROM case_matter WHERE cm_status='Dismissed'");   ?>
				
				<!--header-->
				<table border="0" style="margin-left:20%;">
					<tr>
						<td rowspan="2"><img src="image/newlogo.jpg" height="96px"/></td>
						<td align="center" style="font-size:10px;">Republika ng Pilipinas<br/>Kagawaran ng Katarungan<br/>Tanggapan ng Manananggol Pambayan<br/>
							<big><b>PUBLIC ATTORNEY'S OFFICE</b></big><br/>New Government Center Building, Provincial Capitol Compound<br/>
							Trece Martires City Cavite<br/>Telephone No. 929-9436; Email: pao_executive@yahoo.com
						</td>
					</tr>
				</table><br/><br/>
				
				<!--content-->
				<h3 align="center">Matters Report</h3>
				
				<big>New</big>
				<table border="1" style="border-collapse:collapse;">
					<thead>
					  <tr>
						<th>Matter</th>
						<th>Description</th>
						<th>Date</th>
						<th>Responsible Attorney</th>
						<th>Originating Attorney</th>
					  </tr>
					</thead>
					<tbody >
				  <?php 
					if(mysql_num_rows($pnew) > 0){
						while($pn = mysql_fetch_array($pnew)){?>
							<tr><td><?php echo $pn["cm_name"];?></td>
								<td><?php echo $pn["cm_description"];?></td>
								<td><?php echo date("m/d/Y", strtotime($pn["cm_date_created"]));?></td>
								<td><?php echo $pn["cm_resp_atty"]; ?></td>
								<td><?php echo $pn["cm_orig_atty"];?></td></tr><?php
						}
					} else{ echo "";}?>
						
			    	</tbody>
				</table>
				<br>
				<big>Pending</big>
				<table border="1" style="border-collapse:collapse;" align="center">
					<thead>
					  <tr>
						<th>Matter</th>
						<th>Description</th>
						<th>Date</th>
						<th>Responsible Attorney</th>
						<th>Originating Attorney</th>
					  </tr>
					</thead>
					<tbody >
				  <?php 
					if(mysql_num_rows($ppending) > 0){
						while($pp = mysql_fetch_array($ppending)){?>
							<tr><td><?php echo $pp["cm_name"];?></td>
								<td><?php echo $pp["cm_description"];?></td>
								<td><?php echo date("m/d/Y", strtotime($pp["cm_date_pending"]));?></td>
								<td><?php echo $pp["cm_resp_atty"]; ?></td>
								<td><?php echo $pp["cm_orig_atty"];?></td></tr><?php
						}
					} else{ echo "";}?>
						
				    </tbody>
				</table>
				<br>
				<big>Dismissed</big>
				<table border="1" style="border-collapse:collapse;" align="center">
					<thead>
					  <tr>
						<th>Matter</th>
						<th>Description</th>
						<th>Date</th>
						<th>Responsible Attorney</th>
						<th>Originating Attorney</th>
					  </tr>
					</thead>
					<tbody >
				  <?php 
					if(mysql_num_rows($pdismissed) > 0){
						while($pd = mysql_fetch_array($pdismissed)){?>
							<tr><td><?php echo $pd["cm_name"];?></td>
								<td><?php echo $pd["cm_description"];?></td>
								<td><?php echo date("m/d/Y", strtotime($pd["cm_date_dismissed"]));?></td>
								<td><?php echo $pd["cm_resp_atty"]; ?></td>
								<td><?php echo $pd["cm_orig_atty"];?></td></tr><?php
						}
					} else{ echo "";}?>
						
				    </tbody>
				</table>
				
				<!--footer-->
				<br/><br/><br/><br/><br/>
				Prepared by:<br/><br/><br/>
				<b><?php echo strtoupper($name); ?></b><br/>
				<?php echo $pos; ?>
			</div>
			
			<?php
		
		
		//MATTERS BY RESPONSIBLE ATTORNEY
		} else if($type == "matbyatty"){ ?>
			<div style="float:left;"><h3>Matters Report</h3></div>
			<div style="float:right;"><img src="image/logo.png" height="75px"/></div>
			<br/><br/><br/><?php
			
			$respattys = mysql_query("SELECT cm_resp_atty AS atty FROM case_matter GROUP BY cm_resp_atty");

			while($atty = mysql_fetch_assoc($respattys)){
				$at = $atty["atty"];
				$rows = mysql_query("SELECT * FROM case_matter WHERE cm_resp_atty='$at'"); ?>

				<!--table for display-->
				<br/>
				<label class="lead"><?php echo $atty["atty"]; ?></label>
				<table class="table table-responsive form-horizontal" style="color:black;">
					<thead>
					  <tr class="active">
						<th class="col-xs-3">Matter</th>
						<th class="col-xs-4">Description</th>
						<th class="col-xs-3">Status</th>
						<th class="col-xs-2">Originating Attorney</th>
					  </tr>
					</thead>
					<tbody ><?php
					if(mysql_num_rows($rows) > 0){
						while($row = mysql_fetch_assoc($rows)){?>
						<tr><td class="col-xs-2"><?php echo $row["cm_name"];?></td>
							<td class="col-xs-3"><?php echo $row["cm_description"];?></td>
							<td class="col-xs-3"><?php if($row["cm_status"] == "New"){
								echo $row["cm_status"]. " (".$row["cm_date_created"] .")";
							} else if($row["cm_status"] == "Pending"){
								echo $row["cm_status"]. " (".$row["cm_date_pending"] .")";
							} else{
								echo $row["cm_status"]. " (".$row["cm_date_dismissed"] .")";
							}?></td>
							<td class="col-xs-2"><?php if($row["cm_resp_atty"] == $row["cm_orig_atty"]){
								echo "<center>-</center>";
							} else {
								echo $row["cm_orig_atty"];
							}?></td></tr><?php
					    }
					} ?>
					</tbody>
				</table><?php
					
			}?>
			
			
			<form method="POST" action="generatecsv.php" target="_blank"> 
				<input type="hidden" name="rpttype" value="matbyatty">
				<input type="hidden" name="filename" value="matter_report_<?php echo date("m_d_Y", strtotime("now"));?>">
				<button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-download-alt"></span> Export .CSV file</button>
				<button type="button" class="btn btn-default btn-sm" onclick="PrintDiv();" ><span class="glyphicon glyphicon-print"></span> Print</button>
			</form>
			
			<!-- table to print -->
			<div id="divToPrint" style="display:none;">
				<!--header-->
				<table border="0" style="margin-left:20%;">
					<tr>
						<td rowspan="2"><img src="image/newlogo.jpg" height="96px"/></td>
						<td align="center" style="font-size:10px;">Republika ng Pilipinas<br/>Kagawaran ng Katarungan<br/>Tanggapan ng Manananggol Pambayan<br/>
							<big><b>PUBLIC ATTORNEY'S OFFICE</b></big><br/>New Government Center Building, Provincial Capitol Compound<br/>
							Trece Martires City Cavite<br/>Telephone No. 929-9436; Email: pao_executive@yahoo.com
						</td>
					</tr>
				</table><br/><br/>
				
				<!--content-->
				<h3 align="center">Matters Report</h3><?php

				$respattys = mysql_query("SELECT cm_resp_atty AS atty FROM case_matter GROUP BY cm_resp_atty");
				while($atty = mysql_fetch_assoc($respattys)){
					$at = $atty["atty"];
					$rows = mysql_query("SELECT * FROM case_matter WHERE cm_resp_atty='$at'"); ?>

					<br/>
					<big><?php echo $atty["atty"]; ?></big>
					<table border="1" style="border-collapse:collapse;">
						<thead>
						  <tr>
							<th>Matter</th>
							<th>Description</th>
							<th>Status</th>
							<th>Originating Attorney</th>
						  </tr>
						</thead>
						<tbody ><?php
						if(mysql_num_rows($rows) > 0){
							while($row = mysql_fetch_assoc($rows)){?>
							<tr><td><?php echo $row["cm_name"];?></td>
								<td><?php echo $row["cm_description"];?></td>
								<td><?php if($row["cm_status"] == "New"){
									echo $row["cm_status"]. " (".$row["cm_date_created"] .")";
								} else if($row["cm_status"] == "Pending"){
									echo $row["cm_status"]. " (".$row["cm_date_pending"] .")";
								} else{
									echo $row["cm_status"]. " (".$row["cm_date_dismissed"] .")";
								}?></td>
								<td><?php if($row["cm_resp_atty"] == $row["cm_orig_atty"]){
									echo "<center>-</center>";
								} else {
									echo $row["cm_orig_atty"];
								}?></td></tr><?php
							}
						} ?>
						</tbody>
					</table><?php
						
				}
				
				 ?>
				
				<!--footer-->
				<br/><br/><br/><br/><br/>
				Prepared by:<br/><br/><br/>
				<b><?php echo strtoupper($name); ?></b><br/>
				<?php echo $pos; ?>
			</div><?php
			
			
		//CLIENT TRANSACTION HISTORY
		} else if($type == "clienttrans"){
			$name = explode(", ", $_POST["nameclient"]);
			$lname = $name[0];
			$fname = $name[1];
			$rows = mysql_query("SELECT * FROM case_client WHERE cc_fname='$fname' AND cc_lname='$lname'");
			$logs = mysql_query("SELECT * FROM client_log WHERE log_client_fname='$fname' AND log_client_lname='$lname'"); ?>
			
			<div style="float:left;"><h3>Client Report</h3></div>
			<div style="float:right;"><img src="image/logo.png" height="75px"/></div>
			<br/><br/><br/>
			
			<!-- div to display-->
			<div style="color:black;">
			    <br/>
				<p class="lead"><?php echo $fname ." ".$lname; ?>
				    <button type="button" class="btn btn-default btn-sm" onclick="PrintDiv();" style="float:right; padding:5px 40px;"><span class="glyphicon glyphicon-print"></span> Print</button>
				</p>
				<div class="panel panel-default col-xs-4" style="background-color:#f1f1f1;">
				<?php if(mysql_num_rows($rows) > 0){
					while($row = mysql_fetch_assoc($rows)){?>
						
							<div class="panel-body">
							Reference Number: <b><?php echo $row["cc_id"];?></b><br/>
							Address: <b><?php echo $row["cc_address"].", ".$row["cc_location"];?></b><br/>
							Contact No.: <b><?php echo $row["cc_contact"];?></b><br/><br/>
							
							<label>Case: </label><br/>
							<?php list($name, $des, $opend, $date, $status, $resp, $orig) = mysql_fetch_row(mysql_query("SELECT cm_name, cm_description, cm_date_created, CASE cm_status WHEN 'Pending' THEN cm_date_pending WHEN 'Dismissed' THEN cm_date_dismissed ELSE NULL END AS date, cm_status, cm_resp_atty, cm_orig_atty FROM case_matter WHERE cm_id='". $row["cc_matter_id"]."'") ); ?>
							<u><?php echo $name;?></u><br/>
							<?php echo $des ."<br/><br/> Open Date: ".date("m/d/Y", strtotime($opend))."<br/>" ;
							if($status == "Pending"){
								echo "Status: Pending(" .date("m/d/Y", strtotime($date)).")<br/>";
							} else if($status == "Dismissed"){
								echo "Status: Dismissed(" .date("m/d/Y", strtotime($date)).")<br/>";
							} else {
								echo "";
							}
							echo "<br/>Responsible Attorney: ".$resp."<br/>Originating Attorney: ".$orig;?>
							</div>
						
						
						
						<?php
				    } 
				} else {
					echo "<div class='panel-body'>No records yet.</div>";
				} ?>
				</div>
				
				<div class="col-xs-8">
				    <p>Logs</p>
					<table class="table table-responsive form-horizontal">
					    <thead><tr>
						    <th>Date</th>
							<th>Purpose</th>
							<th>Time in</th>
						</tr></thead>
						<tbody><?php
						    if(mysql_num_rows($logs) > 0){
								while($log = mysql_fetch_array($logs)){?>
									<tr><td><?php echo date("m/d/Y", strtotime($log["log_date"]));?></td>
							        <td><?php echo $log["log_purpose"];?></td>
							        <td><?php echo date("h:i:s a", strtotime($log["log_timein"]));?></td></tr><?php
								}
							} else {
								echo "<tr><td colspan='3'>No log records.</td></tr>";
							} ?>
						</tbody>
					</table>
				</div>
	
			</div>			
			
			<!-- div to print -->
			<div id="divToPrint" style="display:none;"><?php
			    $name = explode(", ", $_POST["nameclient"]);
				$lname = $name[0];
				$fname = $name[1];
				$rows = mysql_query("SELECT * FROM case_client WHERE cc_fname='$fname' AND cc_lname='$lname'");
				$logs = mysql_query("SELECT * FROM client_log WHERE log_client_fname='$fname' AND log_client_lname='$lname'"); ?>
				
				<!--header-->
				<table border="0" style="margin-left:20%;">
					<tr>
						<td rowspan="2"><img src="image/newlogo.jpg" height="96px"/></td>
						<td align="center" style="font-size:10px;">Republika ng Pilipinas<br/>Kagawaran ng Katarungan<br/>Tanggapan ng Manananggol Pambayan<br/>
							<big><b>PUBLIC ATTORNEY'S OFFICE</b></big><br/>New Government Center Building, Provincial Capitol Compound<br/>
							Trece Martires City Cavite<br/>Telephone No. 929-9436; Email: pao_executive@yahoo.com
						</td>
					</tr>
				</table><br/><br/>
				
				<!--content-->
				<fieldset>
				    <legend><h3><?php echo $fname ." ".$lname; ?></h3></legend>
					<?php if(mysql_num_rows($rows) > 0){
						while($row = mysql_fetch_assoc($rows)){?>
							
								<div class="panel-body">
								Reference Number: <b><?php echo $row["cc_id"];?></b><br/>
								Address: <b><?php echo $row["cc_address"].", ".$row["cc_location"];?></b><br/>
								Contact No.: <b><?php echo $row["cc_contact"];?></b><br/><br/>
								
								<label>Case: </label><br/>
								<?php list($name, $des, $opend, $date, $status, $resp, $orig) = mysql_fetch_row(mysql_query("SELECT cm_name, cm_description, cm_date_created, CASE cm_status WHEN 'Pending' THEN cm_date_pending WHEN 'Dismissed' THEN cm_date_dismissed ELSE NULL END AS date, cm_status, cm_resp_atty, cm_orig_atty FROM case_matter WHERE cm_id='". $row["cc_matter_id"]."'") ); ?>
								<u><?php echo $name;?></u><br/>
								<?php echo $des ."<br/><br/> Open Date: ".date("m/d/Y", strtotime($opend))."<br/>" ;
								if($status == "Pending"){
									echo "Status: Pending(" .date("m/d/Y", strtotime($date)).")<br/>";
								} else if($status == "Dismissed"){
									echo "Status: Dismissed(" .date("m/d/Y", strtotime($date)).")<br/>";
								} else {
									echo "";
								}
								echo "<br/>Responsible Attorney: ".$resp."<br/>Originating Attorney: ".$orig;?>
								</div>
							<?php
						} 
				    } else {
						echo "No records yet.";
					} ?>
				</fieldset>
				<br>
				<big>Logs</big>
				<table border="1" style="border-collapse:collapse;">
					<thead><tr>
						<th>Date</th>
						<th>Purpose</th>
						<th>Time in</th>
					</tr></thead>
					<tbody><?php
						if(mysql_num_rows($logs) > 0){
							while($log = mysql_fetch_array($logs)){?>
								<tr><td><?php echo date("m/d/Y", strtotime($log["log_date"]));?></td>
								<td><?php echo $log["log_purpose"];?></td>
								<td><?php echo date("h:i:s a", strtotime($log["log_timein"]));?></td></tr><?php
							}
						} else {
							echo "<tr><td colspan='3'>No log records.</td></tr>";
						} ?>
					</tbody>
				</table>
				
				<!--footer-->
				<br/><br/><br/><br/><br/>
				Prepared by:<br/><br/><br/>
				<b><?php echo strtoupper($name); ?></b><br/>
				<?php echo $pos; ?>
			</div>
			<br/>
			 <?php
		
		//CLIENT WITH PENDING CASES
		} else if($type == "ongoingcases"){
			$rows = mysql_query("SELECT c.*, m.* FROM case_client c, case_matter m WHERE c.cc_matter_id=m.cm_id AND m.cm_status='New' OR m.cm_status='Pending'");?>
			
			<div style="float:left;"><h3>Client Report <small>(Ongoing/Pending Cases as of <?php echo date("m/d/Y", strtotime("now"))?>)</small></h3></div>
			<div style="float:right;"><img src="image/logo.png" height="75px"/></div>
			<br/><br/><br/>
						
			          
			<br/>
			<table class="table table-responsive form-horizontal" style="color:black;">
				<thead>
				  <tr class="active">
					<th class="col-xs-3">Name</th>
					<th class="col-xs-2">Case Number</th>
					<th class="col-xs-3">Description</th>
					<th class="col-xs-2">Open Date</th>
					<th class="col-xs-2">Responsible Attorney</th>
				  </tr>
				</thead>
				<tbody >
			  <?php 
				if(mysql_num_rows($rows) > 0){
					while($row = mysql_fetch_array($rows)){?>
						<tr><td class="col-xs-3"><?php echo $row["cc_fname"]." " .$row["cc_lname"];?></td>
						    <td class="col-xs-2"><?php echo $row["cm_name"];?></td>
							<td class="col-xs-3"><?php echo $row["cm_description"];?></td>
							<td class="col-xs-2"><?php echo date("m/d/Y", strtotime($row["cm_date_created"])); ?></td>
							<td class="col-xs-2"><?php echo $row["cm_resp_atty"];?></td></tr><?php
					}?>
					<tr><td colspan="5">
					<form method="POST" action="generatecsv.php" target="_blank"> 
						<input type="hidden" name="rpttype" value="ongoingcases">
						<input type="hidden" name="filename" value="clients_with_ongoing_cases">
						<button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-download-alt"></span> Export .CSV file</button>
						<button type="button" class="btn btn-default btn-sm" onclick="PrintDiv();" ><span class="glyphicon glyphicon-print"></span> Print</button>
					</form>
					</td></tr><?php
				} else{?>
					<tr><td>No results.</td></tr><?php
				}?>
			    	
			</tbody>
			</table>
			
			<!-- table to print -->
			<div id="divToPrint" style="display:none;"><?php
				$prows = mysql_query("SELECT c.*, m.* FROM case_client c, case_matter m WHERE c.cc_matter_id=m.cm_id AND m.cm_status='New' OR m.cm_status='Pending'");?>
				
				<!--header-->
				<table border="0" style="margin-left:20%;">
					<tr>
						<td rowspan="2"><img src="image/newlogo.jpg" height="96px"/></td>
						<td align="center" style="font-size:10px;">Republika ng Pilipinas<br/>Kagawaran ng Katarungan<br/>Tanggapan ng Manananggol Pambayan<br/>
							<big><b>PUBLIC ATTORNEY'S OFFICE</b></big><br/>New Government Center Building, Provincial Capitol Compound<br/>
							Trece Martires City Cavite<br/>Telephone No. 929-9436; Email: pao_executive@yahoo.com
						</td>
					</tr>
				</table><br/><br/>
				
				<!--content-->
				<h3 align="center">Client Report <small>(Ongoing/Pending Cases as of <?php echo date("m/d/Y", strtotime("now"))?>)</small></h3>
				
				<table border="1" style="border-collapse:collapse;" align="center">
					<thead>
					  <tr>
						<th>Name</th>
				    	<th>Case Number</th>
				    	<th>Description</th>
				    	<th>Open Date</th>
				    	<th>Responsible Attorney</th>
					  </tr>
					</thead>
					<tbody >
				  <?php 
					if(mysql_num_rows($prows) > 0){
						while($prow = mysql_fetch_array($prows)){?>
						<tr><td><?php echo $prow["cc_fname"]." " .$prow["cc_lname"];?></td>
						    <td><?php echo $prow["cm_name"];?></td>
							<td><?php echo $prow["cm_description"];?></td>
							<td><?php echo date("m/d/Y", strtotime($prow["cm_date_created"])); ?></td>
							<td><?php echo $prow["cm_resp_atty"];?></td></tr><?php
						}?>
						<?php
					} else{ echo "";}?>
						
				</tbody>
				</table>
				
				<!--footer-->
				<br/><br/><br/><br/><br/>
				Prepared by:<br/><br/><br/>
				<b><?php echo strtoupper($name); ?></b><br/>
				<?php echo $pos; ?>
			</div>
			
			
			<?php
		}
		?>
	
	

	</div>
	
	<script type="text/javascript">     
		function PrintDiv(){    
		   var divToPrint = document.getElementById('divToPrint');
		   var popupWin = window.open('', '_blank');
		   popupWin.document.open();
		   popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
		   popupWin.document.close();
		}
	</script>
</body>
</html>