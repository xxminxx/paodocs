<!DOCTYPE html>

<?php
   include('database.php');
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>Login - paoDocs</title>
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
			
			<div align="center" style="color:red; font-size:14px;">
			   <?php if (isset($_GET["msg"])) {
				   $msg = $_GET["msg"];
				   echo $msg;
			   } ?>
			</div>
			
			<div align="center" style="color:green; font-size:14px;">
			   <?php if (isset($_GET["ok"])) {
				   $ok = $_GET["ok"];
				   echo $ok;
			   } ?>
			</div>
			
			<form method="POST" action="login_confirm.php">
				<h4><span class="glyphicon glyphicon-log-in"></span> Log in</h4>
				<input type="text" name="uname" placeholder="Username" style="<?php if (isset($_GET['changebdr']) && $_GET['changebdr'] == 'username') { ?> border-color: red; <?php }?>" required>
				<input type="password" name="pword" placeholder="Password" style="<?php if (isset($_GET['changebdr']) && $_GET['changebdr'] == 'password') { ?> border-color: red; <?php }?>" required>
				<input type="checkbox" name="session" value="ON"> Keep me signed in 
				<span style="float: right;"><a href="forgotpassword.php" style="cursor:pointer;">Forgot Password?</a></span><br /><br />
				<button type="submit" class="btn-sign-in-confirm">Confirm</button> <br /><br />
			</form>
			<div align="right">Don't have account? <a href="signup.php" style="cursor:pointer;">Sign Up</a> <br /></div>
			
		</div>
		
		<div class="image-display" style="top:4%">
			<img src="image/header.png" height="190px" />
		</div>
		
	</body>

</html>