<?php include('header.php');

if($_POST){
	//$users = $_POST;
	$logout = 0;
	if($_POST['user_pass']!='') {
		$logout = 1;
		$users['user_pass'] = PassWord($_POST['user_pass'],'hide');
	}
	
	$users['display_name'] = $_POST['display_name'];
	$users['user_email'] = $_POST['user_email'];
	$run = FormData('users', 'update', $users, " `ID` = '".$_SESSION['User']['ID']."' ", $view=false );
	$FormMessge = Alert('success', 'Account Details successfully Updated...!'); 
	if($logout == 1){
		echo "<script language='javascript'>alert('Profile Updated, Session Logout.!');window.location = 'index.php?logout=true';</script>";exit;
		echo '<meta http-equiv="refresh" content="0;URL=\'index.php?logout=true\'" />'; exit;	
	} else {
		echo "<script language='javascript'>alert('Profile Updated, Session Logout.!');window.location = 'index.php?logout=true';</script>";exit;
		echo '<meta http-equiv="refresh" content="0;URL=\'profile.php\'" />'; exit;
	}
}
$stmt = mysql_query(" SELECT * FROM `users` WHERE `ID` = '".$_SESSION['User']['ID']."' ");
$rslt = mysql_fetch_array($stmt); ?>


<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="#">Home</a> </li>
      <li class="active">Account</li>
    </ul>
    <!-- .breadcrumb -->
    <!-- #nav-search -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Manage Account <small> <i class="icon-double-angle-right"></i> Update your account details </small> </h1>
    </div>
    <!-- /.page-header -->
	<?=$FormMessge?>
    <form class="form-horizontal" role="form" action="" method="post">
      <div class="row">
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <h4 class="header green">Account Details</h4>
		  <div class="profile-user-info profile-user-info-striped">
												<div class="profile-info-row">
													<div class="profile-info-name"> Username </div>
													<div class="profile-info-value">
														<span class="editable" id="username"><?=$rslt['display_name']?> </span>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Email </div>

													<div class="profile-info-value">
														<span class="editable" id="username"><?=$rslt['user_email']?></span>
													</div>
												</div>
												
												<div class="profile-info-row">
													<div class="profile-info-name"> Password </div>

													<div class="profile-info-value">
														<span <button class="btn btn-info"><a href="reset-password.php"></a>Change Password </button>
</span>
													</div>
												</div>
												
											</div>
          
          <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
              <button type="submit" class="btn btn-info pull-right"> <i class="icon-ok bigger-110"></i> Save Changes </button>
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
