<?php include('header.php');
if($_GET['code']!=''){
	$ActionHead = "Invalid Code";
	$Code = json_decode(base64_decode($_GET['code']), true);
	
	if($Code['userid']>0){
		$SQL = " SELECT * FROM `employer` WHERE  UID = '".$Code['userid']."' ";
		$user = mysql_query($SQL);
		$row = mysql_num_rows($user);
		if($row){
			$rslt = mysql_fetch_array($user);
			$_SESSION['Employer'] = $rslt;
			$_SESSION['EmployerLogged'] = 1;
			switch ($Code['type']) {
				case 'UpdateAccountEmailSQL':
					$ActionHead = "Update Account Email";
					$updateSQL = "update employer set EmployerEmail = '".$Code['newemail']."' where `UID` = '".$Code['userid']."' ";
					query($updateSQL);
					$message = '
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr><td class="borderColor2" style="padding:10px;" align="left">
						<p class="mainText">
						<strong>Dear '.$rslt['EmployerCompany'].'! </strong><br ><br >
						Your Email Successfully Changed.<br >
						<strong>Old Email: </strong> '.$rslt['EmployerEmail'].' <br >
						<strong>New Email: </strong> '.$Code['newemail'].' <br ><br >
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
					$data['addAddress'] = array($Code['newemail']=>$rslt['EmployerCompany']);
					$body = SendMail($data, $subject, $message, $show=false);
					$FormMessge = Alert('success', 'Account email successfully Changed, Please relogin...!'); 	
					echo "<script language='javascript'>setTimeout(function(){ window.location = 'index.php?logout=true'; }, 2000); </script>";
					break;
			}
		} else {
			$FormMessge = Alert('error', 'Invalid Verify URL/Code');
		
		}
	}
	
	



} 

?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="#">Home</a> </li>
      <li class="active">Action Verify</li>
    </ul>
    <!-- .breadcrumb -->
	<div id="SubscriptionExpireStatus" class="pull-right"><?=$SubscriptionExpireStatus?></div> 
    <!-- #nav-search --> 
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Action Verify <small> <i class="icon-double-angle-right"></i> Verify your code to Perform Actions. </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" action="" method="get">
      <div class="row">
        <div class="col-xs-12"> 
          <!-- PAGE CONTENT BEGINS -->
          <h4 class="header green">Verification Action : <?=$ActionHead?></h4>
          <div class="row">
		  
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Verify Code </label>
              <div class="col-sm-9">
                <textarea id="verifycode" name="verifycode" placeholder="Your Verification Code" class="col-xs-10 col-sm-5" rows="5"></textarea>
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
