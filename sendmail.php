<?php $email = $_POST["email"];
include("database.php");

$admin = mysql_query("SELECT a.admin_uname, a.user_type, p.admin_fname, p.admin_lname, p.admin_email FROM admin_profile p, admin a WHERE p.admin_id=a.admin_id AND p.admin_email='$email'") or die(mysql_error());
$user = mysql_query("SELECT u.user_uname, u.user_type, p.user_fname, p.user_lname, p.user_email FROM user_profile p, user u WHERE p.user_id=u.user_id AND p.user_email='$email'") or die(mysql_error());

if(mysql_num_rows($admin) > 0){
	list($uname, $type, $fname, $lname, $email) = mysql_fetch_row($admin);
	$hon = "Atty. ";
} else if(mysql_num_rows($user) > 0){
	list($uname, $type, $fname, $lname, $email) = mysql_fetch_row($user);
	if($type == "Attorney"){
		$hon = "Atty. ";
	} else {
		$hon = "";
	}
} else {
	$error = urlencode("Email address is not registered in our system.");
	header("Location: forgotpasswordemail.php?msg=$error&changebdr=email&alert=red");
}


require 'mailer/PHPMailerAutoload.php';
require 'mailer/class.phpmailer.php';
require 'mailer/class.smtp.php';

$crendentials = array(
    'email'     => 'ldmspao@gmail.com',    //Your GMail adress
    'password'  => 'xxxxxxxx143'               //Your GMail password
    );

$smtp = array(

'host' => 'smtp.gmail.com',
'port' => 587,
'username' => $crendentials['email'],
'password' => $crendentials['password'],
'secure' => 'tsl' //SSL or TLS

);	
	
$to = $email;
$subject = 'Reset Password';
$content  = '
	<html>
	<head><style>
		button{color: #ffffff;width: 140px;font-size: 16px;border: 1px solid #f7a22f;border-radius: 3px;padding: 11px 17px;background-color: #f7a22f;outline-color: transparent; cursor: pointer;}
		body{color: #333;font-size: 16px;}</style>
	</head>
	<body>
	<img src="image/logo.png" style="width:70px;"><br><br>
	Hi '.$hon.$fname.',<br><br>
	Someone recently requested a password change for your PAO DMS account. If this was you, you can set a new password here:<br><br>
	<button onclick="location.href='.'"resetpassword.php?u='.$uname.'"'.'" target="_blank">Reset Password</button><br><br>
	If you don\'t want to change your password or didn\'t request this, just ignore and delete this message.<br><br>
	To keep your account secure, please don\'t forward this email to anyone. <br><br>
	</body>
	</html>';

$mailer = new PHPMailer();

//SMTP Configuration
$mailer->isSMTP();
$mailer->SMTPAuth   = true; //We need to authenticate
$mailer->Host       = $smtp['host'];
$mailer->Port       = $smtp['port'];
$mailer->Username   = $smtp['username'];
$mailer->Password   = $smtp['password'];
$mailer->SMTPSecure = $smtp['secure']; 

//Now, send mail :
//From - To :
$mailer->setFrom ($crendentials['email'], 'No reply');
$mailer->addAddress($to);  // Add a recipient

//Subject - Body :
$mailer->Subject        = $subject;
$mailer->Body           = $content;
$mailer->isHTML(true); //Mail body contains HTML tags

//Check if mail is sent :
if(!$mailer->send()) {
	$err = urlencode('Error sending mail : '.$mailer->ErrorInfo);
	header("Location: forgotpasswordemail.php?msg=$err&alert=red");
} else {
    $msg = urlencode('A mail has been sent to <b>'.$email.'</b>');
	header("Location: forgotpasswordemail.php?msg=$msg&alert=green");
}

?>