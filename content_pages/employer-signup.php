<?php
			
if($_POST){
	//print_r($_POST);
	$target_dir = MEMBER_IMAGE_DIRECTORY;
	$nowDate = date("Y-m-d h:i:s");
	
	$sql = "SELECT * FROM `employer` WHERE `EmployerEmail` = '".$_POST["EmployerEmail"]."' limit 1";
	$stmt = total ( $sql );
	if($stmt==1){
		$formMESSAGE = '<div class="notification error closeable"> <p><span>Error!</span> Email already Exist, Please contact Admin and request for login details...! Thank you!</p> </div>';
	} else {
		$employer =  $_POST;
		$employer['EmployerAboutContent'] = nl2br($_POST["EmployerAboutContent"]);
		$unique_key = substr(md5(rand(0, 1000000)), 10, 5);
		$employer['EmployerPassword'] = PassWord($unique_key,'hide');
		$employer['EmployerSubscriptionExpire'] = date("Y-m-d", strtotime("+365 days"));
		$employer['EmployerSubscription'] = 1;
		if($_FILES["image"]["name"])
		{
			$uploadOk = 1;
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'employer'. "_" . rand(0, 100000); $path_parts['filename'];
			//echo "fileName = " . $fileName . "<br />";
			
			$fileType = $path_parts['extension'];
			//echo "fileType = " . $fileType . "<br />";
			$target_file = $target_dir . $fileName . "." . $fileType;
			
			// Check if file already exists
			while(file_exists($target_file)) {
				$fileName = $fileName . "_" . rand(0, 100000);
				$target_file = $target_dir . $fileName . "." . $fileType;
			}
			echo "target_file = " . $target_file . "<br />";
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))
				{
					$employer['EmployerLogo'] = mysql_real_escape_string($fileName.".".$fileType);
				}
				else
				{
					//$FormMessge = Alert('error', '');
					$formMESSAGE = '<div class="error"><div class="message-box-wrap"> Sorry, <strong> there was an error uploading your file.</strong> Thank you!</div></div>';
				}
			}
		}
		
		$run = FormData('employer', 'insert', $employer, "  ", $view=false );
		
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear '.$_POST['EmployerCompany'].', </strong><br ><br >
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Welcome to Holistic Jobs. You are now part of an innovative job portal and a few steps away from finding best matched employees for your jobs.<br ><br>
			<strong>Your credentials are:</strong><br>
			<strong>Login Email: </strong> '.$_POST['EmployerEmail'].' <br >
			<strong>Password: </strong> '.$unique_key.' <br ><br >
			<a href="'.$path.'page/employer-login">Click here to start using Holistic Jobs</a>
			<br><br>
			Regards,<br><strong>Team '.$site_name.'</strong><br ><br >
			</p>
		</td></tr></table> ';
		$subject = "Holistic Jobs Registration";
		
		/*$pdf = array();
		$pdf['EmpID'] = $run;
		$pdf['PlanID'] = $_POST['EmployerSubscription'];
		$filename = CreatePDF($pdf);*/
		
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $_POST['EmployerEmail'] => $_POST['EmployerCompany'] );
		//$data['addAttachment'] = array( $_SESSION["root_path"].'invoices/'.$filename );
		$body = SendMail($data, $subject, $message, $show=false);
		
		$formMESSAGE = '<div class="notification success closeable"> <p><span>Success!</span> An auto generated password has been sent to the email address you entered. Kindly check your email and come back to Log in with Holistic Jobs.</p> </div>';
		echo "<script language='javascript'>setTimeout('window.location = '".$path."page/sign-in';', 1500); </script>";
	}	
}?>

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
            <input name="EmployerEmail" type="email" id="EmployerEmail" class="validate[required,custom[email]]" onblur="EmailDuplicateCheck(this, 'employer', this.value, 'EmailDuplicateResult')"/>
          </div>
          <div id="EmailDuplicateResult" class="six columns" style="margin-top: 35px; margin-bottom: 0px;"></div>
          
          <div class="clearfix"></div>
          <h3>Basic Details</h3>
          <div class="six columns">
            <label>Company Name: <span>*</span></label>
            <input name="EmployerCompany" type="text" id="EmployerCompany" class="validate[required,custom[onlyLetterSp]]" />
          </div>
          <div class="six columns">
            <label>LandLine No.: <span>*</span></label>
            <input name="EmployerLandLine" type="text" id="EmployerLandLine" class="validate[required,custom[onlyNumberSp]]" />
          </div>
          <div class="six columns">
            <label>Address: <span>*</span></label>
            <input name="EmployerAddress" type="text" id="EmployerAddress" class="validate[required]" />
          </div>
          <div class="six columns">
            <label >City: <span>*</span></label>
            <select data-placeholder="Choose City" class="chosen-select" name="EmployerCity" id="EmployerCity" >
              <?=formListOpt('city',0)?>
            </select>
          </div>
          <div class="clearfix"></div>
          <h3>Contact Person Details</h3>
          <div class="six columns">
            <label>Name: <span>*</span></label>
            <input name="EmployerContactName" type="text" id="EmployerContactName" class="validate[required,custom[onlyLetterSp]]" />
          </div>
          <div class="six columns">
            <label >Email: <span>*</span></label>
            <input name="EmployerContactEmail" type="email" id="EmployerContactEmail" class="validate[required,custom[email]]"/>
          </div>
          <div class="six columns">
            <label>Contact No.:<span>*</span></label>
            <input name="EmployerContactMobile" type="text" id="EmployerContactMobile" class="validate[required,custom[onlyNumberSp]]" />
          </div>
          <div class="six columns">
            <label>Designation: <span>*</span></label>
            <select name="EmployerContactDesig" id="EmployerContactDesig" class="chosen-select" >
			  <?=formListOpt('designation', 0)?>
            </select>
          </div>
          <div class="clearfix"></div>
          <div class="six columns">
            <label >Subscription Plan: <span>*</span></label>
            <select data-placeholder="Choose Your Plan" class="chosen-select" name="EmployerSubscription" id="EmployerSubscription" >
              <?php
			$stmt = query("SELECT * FROM `subscriptions` where UID = '".$_REQUEST['plan']."' ORDER by PlanFee");
			while($rslt=fetch($stmt)){?>
              <option value="<?=$rslt['UID']?>" <?=($_REQUEST['plan']==$rslt['UID'])?'selected':''?>> 
              <?=$rslt['PlanTitle']?>
              for
              <?=($rslt['PlanFee']==0)?'Free':"Rs. ".$rslt['PlanFee']?>
              </option>
              <?php
            }?>
            </select>
          </div>
          <div class="six columns" style="display:none">
            <div class="form upload-file">
              <label>Upload Logo</label>
              <label class="upload-btn">
                <input type="file" name="image" id="image"  />
                <i class="fa fa-upload"></i> Browse </label>
              <span class="fake-input hide">No file selected</span> </div>
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
