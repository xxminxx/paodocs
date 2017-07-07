<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>PAO Document Management System</title>
		<link rel="ICON" type="image/png" href="image/icon.png" />
		
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css"/>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<style>
           body {position: relative; font-family: Segoe UI, Arial;}
           .section {padding:50px;height:500px;}
		   .navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover {background-color: transparent;}
		   .popover {border: #476268;}
           .popover-content {background-color: #476268; color: #FB90B7;padding: 25px; font-size:11px; }
           .arrow {border-bottom-color:  #476268 !important;}
		</style>
	</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid" style="padding-bottom:20px;">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>                        
      </button>
      <ul class="nav navbar-nav">
        <li style="position: absolute; left: 50%; margin-left: -50px !important; display: block;">
		    <a class="navbar-brand" href="#pao"><img src="image/paoheader.png" height="50px;"></a></li>
      </ul>
    </div>
    <div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="checkstatus.php">Check Case Status</a></li>
          <li><a href="login.php">Access Account</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>    

<div id="top" class="container-fluid section">
  <div class="row">
    <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
    <div class="col-sm-5">
	    <br/><br/><br/>
		<h1><b><big>paoDocs</big><br/>
		<small><strong><small>DOCUMENT MANAGEMENT SYSTEM</small></strong></small>
		</b></h1><br/><br/>
		<p class="lead">Organize and keep track of your documents digitally.</p>		
	</div>
    <div class="col-sm-7">
	    <center><img src="image/monitoring.png" height="300px"/></center>
	</div>
  </div> 
</div>

<div id="pao" class="container-fluid section">
    <br/><br/><br/><br/><br/><br/><br/><br/>
    <div class="col-sm-7">
	    <center><img title="This is not the official PAO logo." src="image/paologo.png" height="300px"/></center>
	</div>
    <div class="col-sm-5">
	    <br/>
		<h1><b>Public Attorney's Office</h1>
		<p class="lead">Public Attorney's Office (<b>PAO</b>) is a government office that provide indigent litigants free access to courts, judicial and quasi-jidicial agencies by rendering legal services, counselling and assistance.<br/>
			<div class="row">
				<div class="col-sm-2" align="center"><img src="image/courthouse.png" height="50px"></div>
				<div class="col-sm-10"><small><span class="text-uppercase">Trece Martires City District Office</span><br/>
					New Government Center Building, Provincial Capitol Compound<br/>
					Trece Martires City, Cavite</small></div>
			</div><br/>
		    <button type="button" class="btn btn-lg btn-link" data-toggle="collapse" data-target="#availpao"><small><i class="fa fa-plus"></i></small> How to avail PAO services</button>
		</p>	
		<br/><br/><br/>
	</div>
  </div>
</div>

<div id="availpao" class="collapse container-fluid section" style="background-color:#dff0d8;">
    <br/><br/>
	<div class="col-sm-2"></div>
	<div class="col-sm-8">
	    <h2> Provide RECORDS of case like the following:</h2>
		<ul>
			<li><span class="lead">Certificate from the Barangay that you belong to an indigent family</span><br/>
			    Sertipiko mula sa barangay na kayo ay nabibilang sa <i>indigent family</i>
			</li>
			<li><span class="lead">Copy of case information</span><br/>
			   Kopya ng <i>Information </i>ng kaso
			</li>
			<li><span class="lead">Copy of the Statement of Complainant and Its Witness if any</span><br/>
			    Kopya ng Salaysay ng Nagrereklamo at kanyang testigo kung mayroon</i>
			</li>
			<li><span class="lead">Create a detailed narrative about the filed complaint/case against you</span><br/>
			   Gumawa ng detalyadong salaysay tungkol sa isinasampang reklamo/kaso laban sa iyo.</i>
			</li>
		</ul>
	</div>
	<br/><br/>
  </div>


<div id="client" class="container-fluid section">
  <br/><br/><br/><br/><br/><br/><br/>
    <div class="col-sm-7">
	    <br/><br/><br/><br/>
		<h1>Client in Waiting?</h1>
        <p class="lead">Be updated on your case. You can check your case status here.</p>
		<center><button class="btn btn-default btn-lg" onclick="location.href='checkstatus.php'" target="_blank">View Case Status</button></center>
	</div>
    <div class="col-sm-5">
	    <center><img src="image/jury.png" height="300px"/></center>
	</div>
  
</div>
<div id="lawyers" class="container-fluid section">
  <br/><br/><br/><br/><br/>
    <div class="col-sm-7">
	    <center><img src="image/lawyer.png" height="300px"/></center>
	</div>
    <div class="col-sm-5">
	    <br/><br/><br/>
		<h1><b>Lawyer or Member of the Office?</h1>
		<p class="lead">Access your account and documents here.<br/>
		    <center><button class="btn btn-default btn-lg" onclick="location.href='login.php'" target="_blank">Sign In</button></center>
		
		</p>	
		<br/><br/><br/><br/>
	</div>
</div>


<footer>
	<div style="float:right;"><small><span class="label" style="background-color#eeeeee; color:#e52b50; cursor:pointer;">
	    <a data-toggle="popover" data-html="true" data-content="<label class='text-uppercase text-justify'><span class='glyphicon glyphicon-book'></span><span class='glyphicon glyphicon-grain'></span> paoDocs</label><br><p class='text-justify'>Developed by <a href='https://plus.google.com/u/0/105445660294174349226' target='_blank' style='color:#f5e1da;'>Ciara Gale Bautista</a> and <a href='https://plus.google.com/u/0/116627375231551433297' target='_blank' style='color:#f5e1da;'>Shermin Joy Mamolo</a> under <a href='http://cvsu.edu.ph/' target='_blank' style='color:#f5e1da;'>Cavite State University - Main Campus</a><br/> &copy; 2016-<?php echo date("Y");?> All rights reserved.</p>" style="color:#e52b50; text-decoration:none; pointer:cursor !important;">
		MADE WITH <span class="glyphicon glyphicon-heart"></span> BY CIARA&SHERMIN </a></span></small></div>
</footer>
<script>
$(document).ready(function () {
	var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
	if (window.location.hash && isChrome) {
		setTimeout(function () {
			var hash = window.location.hash;
			window.location.hash = "";
			window.location.hash = hash;
		}, 300);
	}
});
	
$(document).ready(function(){
  $("#availpao").on("hide.bs.collapse", function(){
    $(".btn-link").html('<small><i class="fa fa-plus"></i></small> How to avail PAO services');
  });
  $("#availpao").on("show.bs.collapse", function(){
    $(".btn-link").html('<small><i class="fa fa-close"></i></small> Close');
  });
});
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({trigger:"click", placement:"top"});   
});
</script>
	</body>
</html>	