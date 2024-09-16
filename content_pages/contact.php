<?php 
if($_POST){
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">
		<strong>Dear Admin, </strong><br ><br >
		Someone wants to contact you, Details are below.<br >
		<strong>Name : </strong>'.$_REQUEST['name'].'<br >
		<strong>Email : </strong>'.$_REQUEST['email'].'<br >
		<strong>Number : </strong>'.$_REQUEST['number'].'<br >
		<strong>Message : </strong>'.$_REQUEST['comment'].'<br >
		<br>
		Regards,<br><strong>Team '.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
		</p>
	</td></tr></table> ';
	$subject = "Holistic Jobs Contact Us";
	$body = SendMail("malik.shaheryar@hotmail.com", $subject, $message, $show=true);
	//echo $body; exit;
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
    $mail->ContentType = 'text/html';
	//$mail->isSMTP();										// Set mailer to use SMTP
	$mail->SMTPDebug  = 2;
	$mail->Host = 'mail.holisticjobs.com.pk';  				// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'developer@holisticjobs.com.pk';                 // SMTP username
	$mail->Password = 'developer@147';                           // SMTP password
	$mail->Port = 26;                                    // TCP port to connect to
	
	$mail->From = $site_email;
	$mail->FromName = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$mail->addAddress($site_email, 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd');
	//$mail->addAddress("developer@holisticsolutions.com.pk", 'Holistic Solutions IT Team');
	$mail->addReplyTo($site_email, 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd');
	$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = $subject;
	$mail->Body    = $body;
	$mail->AltBody = $body;
	$mail->send(); unset($mail);
	
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">
		<strong>Dear '.$_REQUEST['name'].', </strong><br ><br >
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Thank you for contacting us, our team will get back to you soon.<br>
		<br>
		Regards,<br><strong>Team '.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
		</p>
	</td></tr></table> ';
	$body = SendMail($_REQUEST['email'], $subject, $message, $show=true);
	//echo $body; exit;
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
    $mail->ContentType = 'text/html';
	//$mail->isSMTP();										// Set mailer to use SMTP
	$mail->SMTPDebug  = 2;
	$mail->Host = 'mail.holisticjobs.com.pk';  						// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'developer@holisticjobs.com.pk';                 // SMTP username
	$mail->Password = 'developer@147';                           // SMTP password
	$mail->Port = 26;                                    // TCP port to connect to
	
	$mail->From = $site_email;
	$mail->FromName = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$mail->addAddress($_REQUEST['email'], $_REQUEST['name']);
	//$mail->addAddress("developer@holisticsolutions.com.pk", 'Holistic Solutions IT Team');
	$mail->addReplyTo($site_email, 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd');
	$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = $subject;
	$mail->Body    = $body;
	$mail->AltBody = $body;
	if($mail->send()){
		$formMESSAGE = '<div class="success"><div class="message-box-wrap"> Your message has been <strong>Received Successfully!</strong> Thank you!</div></div>
			<input type="hidden" id="reload" value="1" /> <input type="hidden" id="reloadurl" value="'.$path.'" />';
		echo "<script language='javascript'>alert('Your message has been Received Successfully! Thank you!'); window.location = '".$path."';</script>";exit;
	} else {
		$formMESSAGE = '<div class="error"><div class="message-box-wrap"> Something Wrong, Please try again!</strong> Thank you!</div></div>
			<input type="hidden" id="reload" value="1" /> <input type="hidden" id="reloadurl" value="'.$path.'page/contact" />';
		echo "<script language='javascript'>alert('Something Wrong, Please try again!'); window.location = '".$path."page/contact';</script>";exit;
	}
	unset($mail);
}?>

<div id="titlebar" class="single">
  <div class="container">
    <div class="sixteen columns">
      <h2>Contact</h2>
      <nav id="breadcrumbs">
        <ul>
          <li>You are here:</li>
          <li><a href="<?=$path?>">Home</a></li>
          <li>Contact</li>
        </ul>
      </nav>
    </div>
  </div>
</div>
<!-- Content
================================================== -->
<!-- Container -->
<div class="container">
  <?=($_GET["action"]=='postjob')?'<h1 style="color: rgb(255, 0, 0); text-align: center;" class="margin-bottom-20">Employer portal is under construction. For job posting, Contact us </h1>':''?>
  <div class="eight columns">
    <!-- Information -->
    <h3 class="margin-bottom-10">Information</h3>
    <div class="widget-box">
      <ul class="contact-informations">
        <li>
          <?=$SETTING['address']?>
        </li>
      </ul>
      <ul class="contact-informations second"><?php
	  	$phone = explode(",",$SETTING['phone']);
		foreach($phone as $ph){ ?>
			<li><i class="fa fa-phone"></i><p><?=$ph?></p></li><?
		} ?>
        <li><i class="fa fa-envelope"></i>
          <p>
            <?=$SETTING['admin_email']?>
          </p>
        </li>
        <li><i class="fa fa-globe"></i>
          <p>
            <?=$SETTING['siteurl']?>
          </p>
        </li>
      </ul>
    </div>
    <h3 class=" margin-top-30">Contact Form</h3>
    <!-- Contact Form -->
    <section id="contact" class="padding-right">
      <!-- Success Message -->
      <mark id="message"></mark>
      <!-- Form -->
      <form method="post" name="contactus" id="contactus">
        <fieldset>
        <div>
          <label>Name:<i class="icon-asterisk"></i></label>
          <input name="name" type="text" id="name" class="validate[required,custom[onlyLetterSp]]" />
        </div>
        <div>
          <label >Email:<i class="icon-asterisk"></i></label>
          <input name="email" type="email" id="email" class="validate[required,custom[email]]"/>
        </div>
        <div>
          <label>Contact No:<i class="icon-asterisk"></i></label>
          <input name="number" type="text" id="number" class="validate[required,custom[onlyNumberSp]]" />
        </div>
        <div>
          <label>Message:<i class="icon-asterisk"></i></label>
          <textarea name="comment" cols="40" rows="3" id="comment" spellcheck="true"></textarea>
        </div>
        </fieldset>
        <div id="result"></div>
        <input type="submit" class="submit" id="submit" value="Send Message" />
        <div class="clearfix"></div>
        <div class="margin-bottom-40"></div>
      </form>
    </section>
    <!-- Contact Form / End -->
  </div>
  <div class="seven columns">
    <!-- Social -->
    
    <h3 class="margin-bottom-10">Our Office</h3>
    <!-- Google Maps -->
    <section class="google-map-container">
      <div class="google-map google-map-full">
        <?=$SETTING['sitegooglemap']?>
      </div>
    </section>
    <!-- Google Maps / End -->
	
	<div class="widget margin-top-30">
      <h3 class="margin-bottom-5">Social Media</h3>
      <ul class="social-icons">
        <li><a class="facebook" href="<?=$SETTING['facebook']?>"><i class="icon-facebook"></i></a></li>
        <li><a class="linkedin" href="<?=$SETTING['linkedin']?>"><i class="icon-linkedin"></i></a></li>
      </ul>
      <div class="clearfix"></div>
      <div class="margin-bottom-50"></div>
    </div>
  </div>
</div>
<!-- Container / End -->
<!-- Container -->
<div class="container">
  <div class="seven columns"> </div>
  <!-- Container / End -->
  <!-- Sidebar
================================================== -->
</div>
<!-- Container / End -->
<script>
$(document).ready(function(){
	$("form#contactus").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.contactus.submit();
			} else {
				//alert("The form status is: " +status+", it will never submit");
			}
	  }
	});

	$("#reset").click(function(){
		$(".formError").hide();
	});
	
});


</script>
