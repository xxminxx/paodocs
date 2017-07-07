<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>Forgot Password - paoDocs</title>
		<link rel="ICON" type="image/png" href="image/icon.png" />
		
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/index.css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	
	<body>
		<br /><br /><br /><br /><br /><br />
		
		<div id="container">
			<br /><br /><br />
			<h4><span class="glyphicon glyphicon-lock"></span> Forgot Password?</h4>
			<div style="<?php if(isset($_GET['div']) && $_GET['div']=='hide'){echo 'display:none;';}?>">Enter your username below, and answer our security question.</div> <br />
			
			<div style="color:red; font-size:14px;">
			   <?php if (isset($_GET["msg"])) {
				   $msg = $_GET["msg"];
				   echo $msg;
			   } ?>
			</div>
			
			<form method="POST" action="showsecurity.php">
			    <input type="text" name="user" placeholder="Username" style="<?php if (isset($_GET['changebdr']) && $_GET['changebdr'] == 'user'){echo 'border-color: red;';} 
				    if(isset($_GET['div']) && $_GET['div']=='hide'){echo 'visibility:hidden;';}?>" required><br />
			    <a href="login.php" class="btn btn-default">Back</a>
			    <button type="submit" class="btn btn-primary" style="<?php if(isset($_GET['div']) && $_GET['div']=='hide'){echo 'visibility:hidden;';}?>">Submit</button>
			</form>
		</div>
		
		<div class="image-display"  style="top:4%;">
			<img src="image/header.png" height="190px"/>
		</div>
		
	</body>

</html>