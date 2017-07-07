<?php 
if(!isset($_GET["u"])){
	header("Location: index.php");
} else {
	$user = $_GET["u"];
}

include("database.php"); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Reset Password - PAO Document Management System</title>
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
			<h4><span class="glyphicon glyphicon-lock"></span> Reset Password</h4>
			<?php echo 'Please enter a new password for your <b>' .$user .'</b> account.';?>
			<form id="form" method="POST" action="reset.php">
			    <input type="password" name="password" id="password" placeholder="New password"><br />
				<input type="password" name="confirmpw" id="confirmpw" placeholder="Retype password"> <span id="message"></span><br />
			    <input type="hidden" name="user" value="<?php echo $user;?>">
				<a href="forgotpassword.php" class="btn btn-default">Back</a>
			    <button type="submit" class="btn btn-primary">Reset Password</button>
			</form>
		</div>
		
		<div class="image-display"  style="top:4%;">
			<img src="image/header.png" height="190px"/>
		</div>
		
		<script>
		    $('#confirmpw').on('keyup', function () {
				if ($(this).val() == $('#password').val()) {
					$('#message').html('matched').css('color', 'green');
				} else $('#message').html('not matching').css('color', 'red');
			});
		</script>
	</body>

</html>