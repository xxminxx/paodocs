<?php
$refid = $_POST['refnum'];
include('database.php');
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>Check Case Status - paoDocs</title>
		<link rel="ICON" type="image/png" href="image/icon.png" />
		
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/index.css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<style> .main-container {background-color:white; height:470px; box-shadow: 10px 10px 5px #ccc; margin-top:4%; padding:auto;} </style>
	</head>
	
	<body>
		<div class="container main-container">
		<?php $sql = mysql_query("SELECT * FROM case_client WHERE cc_id='$refid'");
			if(mysql_num_rows($sql) > 0){ 
				while($id = mysql_fetch_array($sql)){?>
				<div class="container">
				  <br/><br/>
				  <p><label>Client Reference Number: &nbsp;</label><?php echo $refid;?></p>   
				  <p><label>Client Name: &nbsp;</label><?php echo $id['cc_fname'] ." " .$id['cc_lname'];?></p>    <br/>        
					
				  <div class="col-sm-12">
				  <table class="table">
					<thead>
					<?php $stat = mysql_fetch_row(mysql_query("SELECT cm.cm_status AS status FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cc_id='$refid'"))?>
					  <tr class="active">
						<th>Case Name</th>
						<th>Status</th>
						<th>Responsible Attorney</th>
						<th><?php if($stat == 'Dismissed'){echo 'Dismissed';} else if($stat == 'Pending'){echo 'Pending';} else{echo 'Open';}?> Date</th>
						<th>Other Details</th>
					  </tr>
					</thead>
					<tbody>
					<?php $matters = mysql_query("SELECT cm.* FROM case_matter cm, case_client cc WHERE cm.cm_id=cc.cc_matter_id AND cc_id='$refid'");
					while($matter = mysql_fetch_assoc($matters)){ ?>
						<tr>
							<td><u><?php echo $matter['cm_name'];?></u><br/><?php echo $matter['cm_description'];?></td>
							<td class="warning"><b><?php echo $matter['cm_status'];?></b></td>
							<td><?php echo $matter['cm_resp_atty'];?></td>
							<td><?php if($matter['cm_status'] == 'Dismissed'){echo $matter['cm_date_dismissed'];} else{echo $matter['cm_date_created'];}?></td>
							<td><?php echo $matter['cm_notes'];?></td>
						</tr> <?php
					} ?>
					</tbody>
				  </table>
				  </div>
				  <button type="button" class="btn btn-default" onclick="location.href='index.php'">Back</button>
				</div><?php
			    }
			} else {
				$msg = 'Wrong reference number';
				header("Location: checkstatus.php?errmsg=$msg");
			}?>
		
		</div>
	</body>
</html>