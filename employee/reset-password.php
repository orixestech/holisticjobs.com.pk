<?php include('header.php');

if($_POST){
	$valid = total(" SELECT * FROM `employer` WHERE `UID` = '".$_SESSION['Employer']['UID']."' and `EmployerPassword` = '".PassWord($_POST['current_pass'],'hide')."' ");
	if($valid == 1){
		
		if($_POST['new_pass'] == $_POST['retype_new_pass']){
			$logout = 0;
			if($_POST['new_pass']!='') {
				$logout = 1;
				$users['EmployerPassword'] = PassWord($_POST['new_pass'],'hide');
			}
			
			$run = FormData('employer', 'update', $users, " `UID` = '".$_SESSION['Employer']['UID']."' ", $view=false );
			$FormMessge = Alert('success', 'New Password successfully changed, Please relogin...!');
			echo "<script language='javascript'>window.location = 'index.php?logout=true';</script>";exit;
			
		} else {
			$FormMessge = Alert('error', 'New password does not match with re-type new password...!');
		}
	} else {
		$FormMessge = Alert('error', 'Invalid current password...!'); 	
	}
} ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="#">Home</a> </li>
      <li class="active">Password Reset</li>
    </ul>
    <!-- .breadcrumb --> 
    <!-- #nav-search --> 
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Password Reset <small> <i class="icon-double-angle-right"></i> Change your Password </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" action="" method="post">
      <div class="row">
        <div class="col-xs-12"> 
          <!-- PAGE CONTENT BEGINS -->
          <h4 class="header green">Change Password</h4>
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Current Password </label>
              <div class="col-sm-9">
                <input type="password" id="current_pass" name="current_pass" placeholder="Your Current Password" class="col-xs-10 col-sm-5" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> New Password </label>
              <div class="col-sm-9">
                <input type="text" id="new_pass" name="new_pass" placeholder="New Password" class="col-xs-10 col-sm-5" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Re-type New Password </label>
              <div class="col-sm-9">
                <input type="text" id="retype_new_pass" name="retype_new_pass" placeholder="Re-type New Password" class="col-xs-10 col-sm-5" />
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
