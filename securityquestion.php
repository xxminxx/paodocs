<?php
$question = $_GET["question"];
$user = $_GET["user"];
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Security Question - paoDocs</title>
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
			<h4><span class="glyphicon glyphicon-eye-close"></span> Security Question</h4>
			<strong><?php echo $question;?></strong> <br />
			
			<div style="color:red; font-size:14px;">
			   <?php if (isset($_GET["answeris"])) {
				   $msg = $_GET["answeris"];
				   echo $msg;
			   } ?>
			</div>
			
			<form method="POST" action="verifysecurity.php">
			    <input type="text" name="answer" placeholder="Answer" <?php if(isset($_GET['answeris'])){echo "style='border-color:red;'";}?>><br />
				<input type="hidden" name="user" value="<?php echo $user;?>">
				<input type="hidden" name="question" value="<?php echo $question;?>">
			    <button type="button" href="forgotpassword.php" class="btn btn-default">Back</button>
			    <button type="submit" class="btn btn-primary">Answer</button>
			</form>
		</div>
		
		<div class="image-display"  style="top:4%;">
			<img src="image/header.png" height="190px"/>
		</div>
		
	</body>

</html>