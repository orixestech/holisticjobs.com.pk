<?php include('header.php');

if($_POST &&  $_POST['current_pass']!='' && $_POST['new_pass']!='' && $_POST['retype_new_pass']!=''){
	$sql = " SELECT * FROM `employee` WHERE `UID` = '".$_SESSION['Employee']['UID']."' and `EmployeePassword` = '".PassWord($_POST['current_pass'],'hide')."' ";
	$valid = total($sql);
	if($valid == 1){
		
		if($_POST['new_pass'] == $_POST['retype_new_pass'] && $_POST['new_pass']!=''){
			$logout = 0;
			if($_POST['new_pass']!='') {
				$logout = 1;
				$rslt = fetch( query( $sql ) );
				$users['EmployeePassword'] = PassWord($_POST['new_pass'],'hide');
				$run = FormData('employee', 'update', $users, " `UID` = '".$_SESSION['Employee']['UID']."' ", $view=false );
				
				$message = '
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="borderColor2" style="padding:10px;" align="left">
					<p class="mainText">
					<strong>Dear '.$rslt['EmployeeName'].'! </strong><br ><br >
					Your password has been changed successfully.<br >
					<strong>Password: </strong> '.$_POST['new_pass'].' <br ><br >
					<br><br>
					Thank you for using Holistic Jobs!<br>
					<strong>Team '.$site_name.'</strong><br>
					3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
					</p>
				</td></tr></table> ';
				$subject = "Holistic Jobs Password Change";
				$data = array();
				$data['From'] = 'info@holisticjobs.com.pk';
				$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
				$data['addAddress'] = array($rslt['EmployeeEmail']=>$rslt['EmployeeName']);
				$body = SendMail($data, $subject, $message, $show=false);
				
				$FormMessge = Alert('success', 'New Password successfully changed, Please relogin...!');
				echo "<script language='javascript'>setTimeout(function(){ window.location = 'index.php?logout=true'; }, 2000); </script>";
			}
		} else {
			$FormMessge = Alert('error', 'New password does not match with re-type new password...!');
		}
	} else {
		$FormMessge = Alert('error', 'Invalid current password...!'); 	
	}
} 

if($_POST &&  $_POST['EmployeeEmail']!=''){
	$sql = " SELECT * FROM `employee` WHERE `UID` = '".$_SESSION['Employee']['UID']."' and `EmployeeEmail` = '".$_POST['EmployeeEmail']."' ";
	$valid = total($sql);
	if($valid == 0){
		$sql = " SELECT * FROM `employee` WHERE `EmployeeEmail` = '".$_POST['EmployeeEmail']."' ";
		$valid = total($sql);
		if($valid!=0){
			$FormMessge = Alert('error', 'Duplicate Account Email address, Please try another...!'); 	
		} else {
			if(filter_var($_POST['EmployeeEmail'], FILTER_VALIDATE_EMAIL) && $valid == 0){
				$logout = 1;
				$sql = " SELECT * FROM `employee` WHERE `UID` = '".$_SESSION['Employee']['UID']."' ";
				$rslt = fetch( query( $sql ) );
				
				$updateSQL = "update employee set EmployeeEmail = '".$_POST['EmployeeEmail']."' where `UID` = '".$_SESSION['Employee']['UID']."' ";
				
				$actions = array(
							'type'=>'UpdateAccountEmailSQL',
							'newemail'=>$_POST['EmployeeEmail'],
							'userid'=>$_SESSION['Employee']['UID']
							//,'sql'=>$updateSQL
						   );
				$verifyCode = base64_encode(json_encode($actions));
				$verifyCodeURL = $path . "employee/verify.php?code=" . $verifyCode;
				
				$message = '
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td class="borderColor2" style="padding:10px;" align="left">
					<p class="mainText">
					<strong>Dear '.$rslt['EmployeeName'].'! </strong><br ><br >
					Your Email will be changed after verifying below link.<br >
					<strong>Old Email: </strong> '.$rslt['EmployeeEmail'].' <br >
					<strong>New Email: </strong> '.$_POST['EmployeeEmail'].' <br ><br >
					<strong>Verify Code: </strong> <a href="'.$verifyCodeURL.'"><code style="width:400px;">'.$verifyCode.'</code></a> <br ><br >
					<br><br>
					Thank you for using Holistic Jobs!<br>
					<strong>Team '.$site_name.'</strong><br>
					3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
					</p>
				</td></tr></table> ';
				$subject = "Holistic Jobs Account Email Change";
				$data = array();
				$data['From'] = 'info@holisticjobs.com.pk';
				$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
				$data['addAddress'] = array($rslt['EmployeeEmail']=>$rslt['EmployeeName']);
				$body = SendMail($data, $subject, $message, $show=false);
				
				$FormMessge = Alert('success', 'New Email request has been sent to your Email Address, Please Verify ...!');
				/*echo "<script language='javascript'>setTimeout(function(){ window.location = 'index.php?logout=true'; }, 2000); </script>";*/
				
					 
			} else {
				$FormMessge = Alert('error', 'Invalid Email Address password...!'); 	
			}
		}
	}
} 

$stmt = " SELECT * FROM employee WHERE UID = '".$_SESSION['Employee']['UID']."' ";
$employee = fetch( query( $stmt ) );

?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="#">Home</a> </li>
      <li class="active">Account Settings</li>
    </ul>
    <!-- .breadcrumb -->
	<div id="SubscriptionExpireStatus" class="pull-right"><?=$SubscriptionExpireStatus?></div> 
    <!-- #nav-search --> 
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Account Setting <small> <i class="icon-double-angle-right"></i> Change your Account Details </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" action="" method="post">
      <div class="row">
        <div class="col-xs-12"> 
          <!-- PAGE CONTENT BEGINS -->
          <h4 class="header green">Change Details</h4>
          <div class="row">
		  <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Account E-Mail </label>
              <div class="col-sm-9">
                <input type="text" id="EmployeeEmail" name="EmployeeEmail" placeholder="Employer Login Email" class="col-xs-10 col-sm-5"  required value="<?=$employee['EmployeeEmail']?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Current Password </label>
              <div class="col-sm-9">
                <input type="password" id="current_pass" name="current_pass" placeholder="Your Current Password" class="col-xs-10 col-sm-5" value="" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> New Password </label>
              <div class="col-sm-9">
                <input type="password" id="new_pass" name="new_pass" placeholder="New Password" class="col-xs-10 col-sm-5" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Re-type New Password </label>
              <div class="col-sm-9">
                <input type="password" id="retype_new_pass" name="retype_new_pass" placeholder="Re-type New Password" class="col-xs-10 col-sm-5" />
              </div>
            </div>
          </div>
          <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
              <button type="submit" class="btn btn-info"> <i class="icon-ok bigger-110"></i> Submit </button>
              <button type="reset" class="btn"> <i class="icon-undo bigger-110"></i> Reset </button>
            </div>
          </div>
          <!-- PAGE CONTENT ENDS --> 
        </div>
        <!-- /.col --> 
      </div>
      <!-- /.row -->
    </form>
  </div>
  <!-- /.page-content --> 
</div>
<!-- /.main-content -->
<?php include('footer.php');?>
