<?php include("database.php");
?>

<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">
	<title>Client Logbook - paoDocs</title>
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
	#container {width:95%; height:540px; padding-top: 29px; padding-bottom: 29px; text-align:justify;}
	table.table-fixedheader>tbody {
		display: block;
	}

	table.table-fixedheader>tbody {
		overflow-y: auto;
		overflow-x: hidden;
		height: 300px; /*placeholder:  override as needed*/
	}

	table.table-fixedheader>thead>tr>th, table.table-fixedheader>tbody>tr>td {
		float: left;
		 /*plachoder:  override as needed*/
	}
	</style>
</head>
	
<body >
    <br /><br />
    <div align="center" id="container">			
		<div style="float:left;"><h3>PUBLIC ASSISTANCE DESK <small>(Logbook)</small></h3></div>
		<div style="float:right;"><img src="image/logo.png" height="75px"/></div>
		<br/><br/><br/>
		
		<form method="POST" action="addclientlog.php">           
		  <br/>
		  <h4 class="text-info"><span class="glyphicon glyphicon-calendar"></span> <?php
				    list($date) = mysql_fetch_row(mysql_query("SELECT curdate()"));
					echo date_format(date_create($date), "F d, Y (m/d/Y)");?></h4>
		  <table class="table table-striped table-responsive table-fixedheader" style="color:black;">
			<thead>
			  <tr>
				<th class="col-xs-1">First Name</th>
				<th class="col-xs-1">Last Name</th>
				<th class="col-xs-4" colspan="2">Address</th>
				<th class="col-xs-2">Contact No.</th>
				<th class="col-xs-3">Purpose</th>
				<th class="col-xs-1">Time In</th>
			  </tr>
			</thead>
			
			<tbody >
			  <?php $logs = mysql_query("SELECT * FROM client_log WHERE log_date=curdate()");
				if(mysql_num_rows($logs) > 0){
					while($log = mysql_fetch_array($logs)){?>
						<tr><td class="col-xs-1"><?php echo $log["log_client_fname"];?></td>
						    <td class="col-xs-1"><?php echo $log["log_client_lname"];?></td>
							<td class="col-xs-4"colspan="2"><?php echo $log["log_address"] .", " .$log["log_location"];?></td>
							<td class="col-xs-2"><?php echo $log["log_client_contact"];?></td>
							<td class="col-xs-3"><?php echo $log["log_purpose"];?></td>
							<td class="col-xs-1"><?php echo date("h:i a", strtotime($log["log_timein"]));?></td></tr><?php
					}
				}?>
			    <tr><td class="col-xs-1"><input type="text" name="fname" placeholder="First Name" class="form-control" required></td>
				    <td class="col-xs-1"><input type="text" name="lname" placeholder="Last Name" class="form-control" required></td>
					<td class="col-xs-3"><textarea name="address" placeholder="Address" class="form-control textarea" rows="1" cols="40" required></textarea></td>
					<td class="col-xs-1"><select name="location" class="form-control select" required>
						     <?php $designation = mysql_query("SELECT * FROM designation");
							 while($des = mysql_fetch_array($designation)){?>
								 <option value="<?php echo $des["des_id"];?>"><?php echo $des["des_name"];?></option><?php
							 }?>
						</select></td>
					<td class="col-xs-2"><input type="text" name="contact" onkeypress="return isNumber(event)" placeholder="Contact Number" class="form-control" required></td>
					<td class="col-xs-3"><input type="text" name="purpose" placeholder="Purpose" class="form-control" required></td>
					<td class="col-xs-1"><input type="text" id="timein" name="timein" placeholder="HH:MM am/pm" class="form-control" required>
					    <button type="submit" id="submit" class="btn btn-primary btn-xs" style="float:right;">Submit</button></td>
					
					<script type="text/javascript">
						$('#timein').timepicker({ 
						    timeFormat: 'h:mm p',
							startTime: '08:00',
							minTime: '08:00',
							maxTime: '17:00',
							dynamic: false,
							dropdown: true,
							scrollbar: true,
							showTodayButton: true
						});
						
						$(document).ready(function() {
							var date = new Date();
							var hr = date.getHours();
							var min = date.getMinutes();
							var ampm = hr >= 12 ? 'PM' : 'AM';
							hr = hr % 12;
							hr = hr ? hr : 12;
							min = min < 10 ? '0'+min : min;
							
							var time = hr + ':' + min + ' ' + ampm;
							$('#timein').val(time);
						});
						
						function isNumber(evt) {
							evt = (evt) ? evt : window.event;
							var charCode = (evt.which) ? evt.which : evt.keyCode;
							if (charCode > 31 && (charCode < 48 || charCode > 57)) {
								return false;
							}
							return true;
						}
						
					</script>
					
				</tr>
				
			</tbody>
		  </table>
			
		</form>
	</div>
	
</body>
</html>