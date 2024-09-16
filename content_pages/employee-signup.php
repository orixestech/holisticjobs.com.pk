<?php

if($_POST){

	//print_r($_REQUEST);
	$error = 1;
	$sql = "SELECT * FROM `employee` WHERE `EmployeeEmail` = '".$_POST["EmployeeEmail"]."' limit 1";
	$stmt = total ( $sql );
	if($stmt==1){
		$formMESSAGE = '<div class="notification error closeable"> <p><span>Error!</span> Email already Exist, Please contact Admin and request for login details...! Thank you!</p> </div>';
	} else {
		$unique_key = substr(md5(rand(0, 1000000)), 10, 5);
		$employee['EmployeeName'] = $_REQUEST['EmployeeName'];
		$employee['EmployeeEmail'] = $_REQUEST['EmployeeEmail'];
//		$employee['EmployeeCity'] = $_REQUEST['EmployeeCity'];
//		$employee['EmployeeDOB'] = $_REQUEST['EmployeeDOB'];
		$employee['EmployeeMobile'] = $_REQUEST['EmployeeMobile'];
//		$employee['EmployeeGender'] = $_REQUEST['EmployeeGender'];
		$employee['EmployeeSubscription'] = $_REQUEST['EmployeeSubscription'];
		$employee['EmployeePassword'] = PassWord($unique_key,"hide");
		$stmt = query("SELECT * FROM `subscriptions` WHERE `UID` = '".$_REQUEST['EmployeeSubscription']."' ");
		$plan = fetch($stmt);
		$ExpireDate = date("Y-m-d");
		$NewExpireDate = date("Y-m-d", strtotime("+".$plan['PlanDays']." days", strtotime($ExpireDate)) );
		$employee['EmployeeSubscriptionExpire'] = $NewExpireDate;
		
		$run = FormData('employee', 'insert', $employee, "  ", $view=false );
		if($run>0) { $error = 0; } 	
		
		$message = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td class="borderColor2" style="padding:10px;" align="left">
				<p class="mainText">
				<strong>Dear '.$_REQUEST['EmployeeName'].', </strong><br ><br >
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Welcome to Holistic Jobs. You are now part of an innovative job portal and a few steps away from finding best jobs.<br ><br>			
				<strong>Your credentials are:</strong><br>
				<strong>Login Email: </strong> '.$_REQUEST['EmployeeEmail'].' <br >
				<strong>Password: </strong> '.$unique_key.'<br ><br >
				<a href="'.$path.'page/employee-login">Click here to start using Holistic Jobs</a>
				<br><br>
				Regards,<br><strong>Team '.$site_name.'</strong><br ><br >
				</p>
			</td></tr></table> ';
		$subject = "Holistic Jobs Employee Sign up";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array($_REQUEST['EmployeeEmail']=>$_REQUEST['EmployeeName']);
		if(SendMail($data, $subject, $message, $show=false)){ $error = 0; }
		
		if($error == 0){
			$formMESSAGE = '<div style="margin-bottom:0px;padding:13px;" class="notification success  closeable"><p><span>Success!</span> Employee registered successfully, Check your password in email and come back to login.</p><a class="close" href="#"></a></div>';
			?><script>setTimeout('window.location = "<?=$path?>page/employee-login"', 4500);</script><?
			
		} else {
			$formMESSAGE = '<div style="margin-bottom:0px;padding:13px;" class="notification error closeable"><p><span>Success!</span> Please try again...!</p><a class="close" href="#"></a></div>';
		}
	}
	

}
?>
<div id="titlebar" class="single">
  <div class="container">
    <div class="sixteen columns">
      <h2>
        <?=$CONTENT['content_title']?>
      </h2>
      <nav id="breadcrumbs">
        <ul>
          <li>You are here:</li>
          <li><a href="<?=$path?>">Home</a></li>
          <li>
            <?=$CONTENT['content_title']?>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>
<!-- Content
================================================== --> 
<!-- Container -->
<div class="container">
  <div class="sixteen columns">
    <p class="margin-reset">
      <?=$CONTENT['content_desc']?>
    </p>
    <div class="clearfix"></div>
    <div class="margin-bottom-40"></div>
  </div>
  <section id="contact" class="padding-right container">
    <div class="sixteen columns">
      <?=$formMESSAGE?>
      <form method="post" name="signupform" id="signupform" enctype="multipart/form-data">
        <fieldset>
          <h3 class="clearfix">Login Details</h3>
          <div class="six columns">
            <label >Email: <span>*</span></label>
            <input name="EmployeeEmail" type="email" id="EmployeeEmail" class="validate[required,custom[email]]" onblur="EmailDuplicateCheck(this, 'employee', this.value, 'EmailDuplicateResult')"/>
          </div>
          <div id="EmailDuplicateResult" class="six columns" style="margin-top: 35px; margin-bottom: 0px;"></div>
          
          <div class="clearfix"></div>
          <h3>Basic Details</h3>
          <div class="six columns">
            <label>Employee Name: <span>*</span></label>
            <input name="EmployeeName" type="text" id="EmployeeName" class="validate[required,custom[onlyLetterSp]]" />
          </div>
		  <div class="six columns hide">
            <label>Date of Birth:</label>
            <input name="EmployeeDOB" type="text" id="EmployeeDOB" class="validate[custom[date],future[1950-01-01],past[2005-01-01]] date-picker" value="<?=date("Y-m-d", strtotime("-22 years"))?>" />
          </div>
          <div class="six columns">
            <label >City: <span>*</span></label>
            <select data-placeholder="Choose City" class="validate[required]" name="EmployeeCity" id="EmployeeCity" >
              <?=formListOpt('city',0)?>
            </select>
          </div>
          
          <div class="six columns">
            <label >Subscription Plan: <span>*</span> <small class="pull-right"><a href="<?=$path?>page/employee-subscription" target="_blank">Click here to view the services you get</a></small></label>
            <select data-placeholder="Choose Your Plan" class="chosen-select" name="EmployeeSubscription" id="EmployeeSubscription" >
              <?php
			$stmt = query("SELECT * FROM `subscriptions` where UID = '".$_REQUEST['plan']."' ORDER by PlanFee");
			while($rslt=fetch($stmt)){?>
              <option value="<?=$rslt['UID']?>" <?=($_REQUEST['plan']==$rslt['UID'])?'selected':''?>>
              <?=$rslt['PlanTitle']?>
              <!-- for <?=($rslt['PlanFee']==0)?'Free':"Rs. ".$rslt['PlanFee']?>-->
              </option>
              <?php
            }?>
            </select>
          </div>
		  <div class="six columns">
            <label>Mobile Number: <span>*</span></label>
            <input type="text" id="EmployeeMobile" name="EmployeeMobile" placeholder="Enter Mobile" value="<?=$PAGE['EmployeeMobile']?>" class="validate[required,custom[onlyNumberSp]]" /> 
          </div>
        </fieldset>
        <div id="result"></div>
        <input type="submit" class="submit" id="submit" value="Sign Up" />
        <div class="clearfix"></div>
        <div class="margin-bottom-40"></div>
      </form>
    </div>
  </section>
  <!-- Contact Form / End --> 
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
<link rel="stylesheet" type="text/css" href="<?=$path?>formcss/validationEngine.jquery.css" />
<script src="<?=$path?>formcss/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?=$path?>formcss/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script> 
<script>
$(document).ready(function(){
	$("form#signupform").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.signupform.submit();
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
