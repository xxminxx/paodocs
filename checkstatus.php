<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>Check Case Status - paoDocs</title>
		<link rel="ICON" type="image/png" href="image/icon.png" />
		
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/index.css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	
	<body>
		<br /><br /><br /><br /><br /><br /><br /><br />
		
		<div id="container">
			<br /><br /><br />

			<form method="POST" action="showcasestatus.php">
			    <div class="form-group <?php if(isset($_GET['errmsg'])){echo 'has-error has-feedback';}?>">
   				    <label>Client Reference Number</label><br/>
				    <input type="text" name="refnum" class="form-control" placeholder="Client Reference Number">  
					<?php if(isset($_GET['errmsg'])){echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>';}?>
				</div>	
			</form>
			
			<div style="color:#a94442; font-size:14px;">
			   <?php if (isset($_GET["errmsg"])) {
				   $msg = $_GET["errmsg"];
				   echo $msg;
			   } ?>
			</div>
			
			<?php if(isset($_GET['errmsg'])){
					echo '<br /><a href="index.php" class="btn btn-default">Back</a>';
				} ?>
			
		</div>
		
		<div class="image-display"  style="top:10%;">
			<img src="image/header.png" height="190px"/>
		</div>
		
	</body>

</html>