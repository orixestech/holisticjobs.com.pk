<?php session_cache_expire(20);
include("../admin/includes/conn.php"); include("../site_theme_functions.php");?>
<!DOCTYPE html>
<html class="full" lang="en">
<head>
<meta charset="utf-8">
<title><?=$site_name?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
<link href="assets/css/bootstrap.min.css" rel="stylesheet" >
<link href="assets/css/font-awesome.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>

<body>
<div id="preloader">
  <div id="status"> <img src="assets/img/preloader.gif" height="64" width="64" alt=""> </div>
</div>
<div class="coming-soon">
  <div class="container">
    <div class="row">
      <div class="span12">
        <div class="logo"> <img src="Holistic Jobs Logo.png" style="height:250px;" alt=""> </div>
        <h2 style="color: rgb(237, 50, 55); line-height: 140px; margin-top: 0px; letter-spacing: 20px;"><?=$site_name?></h2>
        <p><?=$SETTING['siteoffline-desc']?></p>
        <h3>Time left until launching</h3>
        <div class="counter">
          <div class="days-wrapper"> <span class="days"></span> <br>
            days </div>
          <div class="hours-wrapper"> <span class="hours"></span> <br>
            hours </div>
          <div class="minutes-wrapper"> <span class="minutes"></span> <br>
            minutes </div>
          <div class="seconds-wrapper"> <span class="seconds"></span> <br>
            seconds </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <!--<div class="row">
    <div class="span12 subscribe">
      <h3>Subscribe to get notified!</h3>
      <form class="form-inline">
        <input type="text" name="email" placeholder="Enter your email...">
        <button type="submit">Subscribe</button>
      </form>
    </div>
  </div>-->
  <div class="row">
    <div class="span12 social"> <a href="http://www.holisticsolutions.com.pk/"><i class="fa fa-globe fa-2x"></i> <font style="font-size:25px;">Holistic Solutions (Pvt.) Ltd.</font></a> <!--<a href="#"><i class="fa fa-facebook-square fa-2x"></i></a> <a href="#"><i class="fa fa-twitter-square fa-2x"></i></a> <a href="#"><i class="fa fa-google-plus-square fa-2x"></i></a> <a href="#"><i class="fa fa-pinterest-square fa-2x"></i></a> <a href="#"><i class="fa fa-linkedin-square fa-2x"></i></a>--> </div>
  </div>
  <div class="span12 row">
    <div class="copyright">Copyright &copy; 2015 Holistic Solutions (Pvt.) Ltd. Design & Development by <a href="http://www.holisticsolutions.com.pk/web-development.htm">Holistic IT Solutions</a></div>
  </div>
</div>

<!-- Javascript --> 
<script src="assets/js/jquery-1.10.2.min.js"></script> 
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/jquery.countdown.js"></script> 
<script>
jQuery(document).ready(function() {
	/*
		Preloader
	*/
  	$(window).load(function() {

   	// will first fade out the loading animation 
    	$("#status").fadeOut("slow"); 

    	// will fade out the whole DIV that covers the website. 
    	$("#preloader").delay(500).fadeOut("slow").remove();      

  	}) 

	/*
		Final Countdown Settings
	*/
	var finalDate = '<?=date("Y/m/d", strtotime("+3 day"))?>';

	$('div.counter').countdown(finalDate)
   	.on('update.countdown', function(event) {

   		$(this).html(event.strftime('<div class="days-wrapper"><span class="days">%D</span><br>days</div>' + 
   										 	 '<div class="hours-wrapper"><span class="hours">%H</span><br>hours</div>' + 
   										 	 '<div class="minutes-wrapper"><span class="minutes">%M</span><br>minutes</div>' +
   										 	 '<div class="seconds-wrapper"><span class="seconds">%S</span><br>seconds</div>'));
   });  

});

</script>
</body>
</html>
