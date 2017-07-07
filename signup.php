<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>Register - paoDocs</title>
		<link rel="ICON" type="image/png" href="image/icon.png" />
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/index.css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	
	<body>
		<br /><br /><br /><br />
		
		<div id="container" style="width:450px; padding-top: 29px; padding-bottom: 29px;">			
			<br/>
			<h4><span class="glyphicon glyphicon-user"></span> Register</h4>
			
			<div style="color:red; font-size:14px;">
			   <?php if (isset($_GET["msg"])) {
				   $msg = $_GET["msg"];
				   echo $msg;
			   } ?>
			</div>
			
			<form method="POST" action="register.php">
			    
				<input type="text" placeholder="First Name" name="fname" style="width:49%; <?php if (isset($_GET['changebdr']) && $_GET['changebdr'] == 'name'){echo 'border-color: red;';}?>" required>
				<input type="text" placeholder="Last Name" name="lname" style="width:49%; <?php if (isset($_GET['changebdr']) && $_GET['changebdr'] == 'name'){echo 'border-color: red;';}?>" required>
				<input type="text" placeholder="Username" name="uname" required>
				<input type="password" placeholder="Password" name="pword" style="<?php if (isset($_GET['changebdr']) && $_GET['changebdr'] == 'pword'){echo 'border-color: red;';}?>" required>
				<input type="password" placeholder="Re-enter Password" name="repword" style="<?php if (isset($_GET['changebdr']) && $_GET['changebdr'] == 'pword'){echo 'border-color: red;';}?>" required>
				<label for="name"></label>
				<select name="utype" data-toggle="select" style="width:100%; <?php if (isset($_GET['changebdr']) && $_GET['changebdr'] == 'usertype') {echo 'border-color: red;';}?>">
				   <option value="null" disabled selected>User Type</option>
				   <option value="atty">Attorney</option>
				   <option value="staff">Non-Attorney</option>
				</select>
				<button type="submit" class="btn-sign-in-confirm">Sign up</button>
			</form>
		</div>
		
		<div class="image-display" style="top:30px;">
			<img src="image/header.png" height="100px" />
		</div>
	</body>
</html>	