<?php 

if($_REQUEST['FormtType']=='employee'){
	if(isset($_POST['username']) && isset($_POST['password'])){
		$SQL = " SELECT * FROM `employee` WHERE EmployeeEmail = '".$_POST['username']."' and EmployeePassword = '".PassWord($_POST['password'],'hide')."' ";
		$user = mysql_query($SQL);
		$row = mysql_num_rows($user);
		if($row){
			$rslt = mysql_fetch_array($user);
			$_SESSION['Employee'] = $rslt;
			$_SESSION['EmployeeLogged'] = 1;
			$_SESSION['EmployeeAccess'] = $_SESSION['Employee']['user_access'];
			$_SESSION['EmployeeUID'] = $_SESSION['Employee']['UID'];
			query("UPDATE `employee` SET `LastLoginDateTime` = '".date("Y-m-d h:i:s")."' WHERE `employee`.`UID` = '".$_SESSION['EmployeeUID']."'; ");
			
			( $_POST['HTTP_REFERER'] || $_POST['HTTP_REFERER']!= 'http://www.holisticjobs.com.pk/page/sign-in') ? $URL=$_POST['HTTP_REFERER'] : $URL=$path."employee/index.php";
			$URL=$path.'employee/index.php';
			
			$formMESSAGE = '<div class="notification success closeable"> <p><span>Employee Login Success!</span> You successfully login to your Employee Portal</p> </div>';
			echo "<script language='javascript'>setTimeout(\"window.location = '".$URL."';\", 2000);</script>";
			
		} else {
			$formMESSAGE = '<div class="notification error closeable"> <p><span>Employee Login Error!</span> Invalid Employee Login Details.</p> </div>';
		}
	}	
}

if($_REQUEST['FormtType']=='forgetpass'){
	//print_r($_REQUEST);
	$SQL = " SELECT * FROM `employee` WHERE EmployeeEmail = '".$_POST['user_email']."' limit 1 ";
	$user = mysql_query($SQL);
	$row = mysql_num_rows($user);
	if($row){
		$rslt = mysql_fetch_array($user);
		$unique_key = substr(md5(rand(0, 1000000)), 10, 5);
		$query = "UPDATE `employee` SET EmployeePassword = '".PassWord($unique_key,'hide')."' WHERE `employee`.`UID` = '".$rslt['UID']."'; ";
		query($query);
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear '.$rslt['EmployeeName'].'!, </strong><br ><br >
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; A new password has been created for you:.<br >
			<strong>Password: </strong> '.$unique_key.' <br ><br >
			You will be able to change your password once you log in.
			<br><br>
			Regards,<br><strong>Team '.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
			</p>
		</td></tr></table> ';
		$subject = "Holistic Jobs Forget Password";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array($rslt['EmployeeEmail']=>$rslt['EmployeeName']);
		$body = SendMail($data, $subject, $message, $show=false);
		
		$formMESSAGE = '<div class="notification success closeable"> <p><span>Forget Password Success!</span> New Password has been updated, Please check your Email.</p> </div>';
	} else {
		$formMESSAGE = '<div class="notification error closeable"> <p><span>Forget Password Error!</span> Email not found in our System.!</p> </div>';
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
  </div>
  <div class="clearfix"></div>
  <div class="margin-top-40"></div>
  <?=$formMESSAGE?>
  <div class="my-account">
    <div class="row">
        <div class="ten columns">
          <h3 class="margin-bottom-10 margin-top-10">Employee Login Details</h3>
          <form method="post" class="login  five columns" id="EmployeeSignin">
            <input type="hidden" name="FormtType" value="employee" />
            <input type="hidden" name="HTTP_REFERER" value="<?=$_SERVER['HTTP_REFERER']?>" />
            
            <p class="form-row form-row-wide">
              <label for="username">Email Address:</label>
              <input type="email" class="input-text validate[required, custom[email]]" name="username" id="username" value="" />
            </p>
            <p class="form-row form-row-wide">
              <label for="password">Password:</label>
              <input class="input-text validate[required]" type="password" name="password" id="password" />
            </p>
            <p class="form-row">
              <input type="submit" class="button" name="login" value="Login" />
            <div>
              <p class="lost_password"> <a href="#small-dialog" class="popup-with-zoom-anim ">Forgot your Employee Password?</a> </p>
            </div>
          </form>
        </div>
        <div class="five columns">
          <h3 class="margin-bottom-5">Don't Have an Account?</h3>
          <!-- Navigation -->
          <div class="clearfix"></div>
          <!-- Showbiz Container -->
          <div id="job-spotlight" class="showbiz-container">
            <div class="showbiz" data-left="#showbiz_left_1" data-right="#showbiz_right_1" data-play="#showbiz_play_1" >
              <div class="overflowholder">
                <div class="job-spotlight">
                  <p style="text-align:justify;">Register with Holistic Jobs to become part of a specialized pharmaceutical job portal and find the best jobs for you.</p>
                  <br />
                  <a href="<?=$path?>page/employee-subscription" class="button big margin-top-5">Sign Up</a> </div>
                <div class="divider margin-top-0 padding-reset"></div>
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
  </div>
  <div id="small-dialog" class="zoom-anim-dialog mfp-hide apply-popup">
    <div class="small-dialog-headline">
      <h2>Reset Your Password</h2>
    </div>
    <div class="small-dialog-content">
      <form method="post" class="login  five columns" name="ForgetPassword" id="ForgetPassword">
        <input type="hidden" name="FormtType" value="forgetpass" />
        <input type="hidden" name="type" value="employer" />
        <p class="form-row form-row-wide">
          <label for="user_email">Email Address:</label>
          <input type="text" class="input-text validate[required, custom[email]]" name="user_email" id="user_email" value="" />
        </p>
        <!--<p class="form-row form-row-wide">
                <label for="type">Account Type:</label>
                <select data-placeholder="Choose Type" class="chosen-select validate[required]" name="type" id="type" >
                  <option value="Employer">Employer</option>
                  <option value="Employee">Employee</option>
                </select>
              </p>-->
        <label class="rememberme" for="rememberme">
          <input type="checkbox" value="1" id="agree" name="agree" class="validate[required]">
          I Agree to generate new password.</label>
        <p class="form-row form-row-wide"></p>
        <p class="form-row">
          <input type="submit" class="button" name="login" value="Generate New Password" />
      </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
	$("form#EmployerSignin").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.EmployerSignin.submit();
			} else {
				//alert("The form status is: " +status+", it will never submit");
			}
	  }
	});
	
	$("form#EmployeeSignin").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.EmployeeSignin.submit();
			} else {
				//alert("The form status is: " +status+", it will never submit");
			}
	  }
	});
	
	$("form#ForgetPassword").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.ForgetPassword.submit();
			} else {
				//alert("The form status is: " +status+", it will never submit");
			}
	  }
	});
	
	$("form#EmployeeSignUp").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.EmployeeSignUp.submit();
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
