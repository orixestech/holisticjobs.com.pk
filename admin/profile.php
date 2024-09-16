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
      <li class="active">Profile</li>
    </ul>
    <!-- .breadcrumb -->
    <!-- #nav-search -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Manage Profile <small> <i class="icon-double-angle-right"></i> Update your profile and account details </small> </h1>
    </div>
    <!-- /.page-header -->
	<?=$FormMessge?>
    <form class="form-horizontal" role="form" action="" method="post">
      <div class="row">
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <h4 class="header green">Account Details</h4>
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name </label>
              <div class="col-sm-9">
                <input type="text" id="display_name" name="display_name" placeholder="Display Name" value="<?=$rslt['display_name']?>" class="col-xs-10 col-sm-5" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email </label>
              <div class="col-sm-9">
                <input type="text" id="user_email" name="user_email" placeholder="Username" class="col-xs-10 col-sm-5" value="<?=$rslt['user_email']?>" />
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Password </label>
              <div class="col-sm-9">
                <input type="text" id="user_pass" name="user_pass" placeholder="Password" class="col-xs-10 col-sm-5" />
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
