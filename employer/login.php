<?php
include("../admin/includes/conn.php");

include("../admin/admin_theme_functions.php");

/////////////////////////////////////
///////// Front Side Functions
include("../site_theme_functions.php");
$employer_path = $path . "employer/";
if ($_SERVER['HTTP_HOST'] != 'localhost'){
	if ($_SERVER['HTTP_HOST'] != 'www.holisticjobs.com.pk'){
		echo "<script language='javascript'>window.location = '".$employer_path."';</script>"; exit;
	}
}
/////////////////////////////////////


@$_SESSION['EmployerUserLogged'] = @$_SESSION['Employer'] = $FormMessge='';

if(isset($_POST['form']) && $_POST['form'] == 'login'){
	if(isset($_POST['Username']) && isset($_POST['Password'])){
		$SQL = " SELECT * FROM `employer` WHERE  EmployerStatus = 'Active' and EmployerEmail = '".$_POST['Username']."' and EmployerPassword = '".PassWord($_POST['Password'],'hide')."' ";
		$user = mysql_query($SQL);
		$row = mysql_num_rows($user);
		if($row){
			$rslt = mysql_fetch_array($user);
			$_SESSION['Employer'] = $rslt;
			$_SESSION['EmployerLogged'] = 1;
			$FormMessge = Alert('success', 'Login Successfully...');
			echo '<meta http-equiv="refresh" content="1;URL=\'index.php\'" />';
			/*header("Location: index.php"); exit; */
			/*echo "<script language='javascript'>window.location = 'index.php';</script>";exit;*/
		} else {
			$FormMessge = Alert('error', 'Invalid Username or Password');
		}
	}
}

if(isset($_POST['form']) && $_POST['form'] == 'forgetpass'){
	$SQL = " SELECT * FROM `employer` WHERE EmployerEmail = '".$_POST['user_email']."' limit 1 ";
	$user = mysql_query($SQL);
	$row = mysql_num_rows($user);
	if($row){
		$rslt = mysql_fetch_array($user);
		$unique_key = substr(md5(rand(0, 1000000)), 10, 5);
		$query = "UPDATE `employer` SET EmployerPassword = '".PassWord($unique_key,'hide')."' WHERE `employer`.`UID` = '".$rslt['UID']."'; ";
		query($query);
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear '.$rslt['EmployerCompany'].'!, </strong><br ><br >
			A new password has been created for you:.<br >
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
		$data['addAddress'] = array($rslt['EmployerEmail']=>$rslt['EmployerCompany']);
		$body = SendMail($data, $subject, $message, $show=false);
		
		$forgetpassMessge = Alert('success', 'New Password has been updated, Please check your Email.');
	} else {
		$forgetpassMessge = Alert('error', 'Email not found in our System.!');
	}
	
	
	
}






?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Login Page -
<?=$SETTING['sitename']?>
</title>
<meta name="description" content="User login page" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- basic styles -->
<link href="<?=$admin_path?>assets/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?=$admin_path?>assets/css/font-awesome.min.css" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?=$admin_path?>assets/css/font-awesome-ie7.min.css" />
<![endif]-->
<!-- page specific plugin styles -->
<!-- fonts -->
<link rel="stylesheet" href="<?=$admin_path?>assets/css/ace-fonts.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?=$admin_path?>assets/css/ace.min.css" />
<link rel="stylesheet" href="<?=$admin_path?>assets/css/ace-rtl.min.css" />
<!--[if lte IE 8]>
	<link rel="stylesheet" href="<?=$admin_path?>assets/css/ace-ie.min.css" />
<![endif]-->
<!-- inline styles related to this page -->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="<?=$admin_path?>assets/js/html5shiv.js"></script>
    <script src="<?=$admin_path?>assets/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="login-layout">
<div class="main-container" style="margin-top:10px;">
  <div class="main-content">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="login-container">
          <div class="center"> <img src="<?=$path?>images/logo.png" width="100%" alt="TSHOP"> </div>
          <div class="space-6"></div>
          <div class="position-relative">
            <div id="login-box" class="login-box visible widget-box no-border">
              <div class="widget-body">
                <div class="widget-main">
				<h2 class="header blue lighter bigger"> Employer Portal</h2>
                  <h4 class="header blue lighter bigger"> <i class="icon-coffee green"></i> Please Enter Your Information </h4>
                  <div class="space-6"></div>
                  <?=$FormMessge?>
                  <form method="post" action="">
                    <input type="hidden" name="form" value="login">
                    <fieldset>
                    <label class="block clearfix"> <span class="block input-icon input-icon-right">
                    <input type="text" class="form-control" placeholder="Username" name="Username" />
                    <i class="icon-user"></i> </span> </label>
                    <label class="block clearfix"> <span class="block input-icon input-icon-right">
                    <input type="password" class="form-control" placeholder="Password" name="Password" />
                    <i class="icon-lock"></i> </span> </label>
                    <div class="space"></div>
                    <div class="clearfix">
                      <button type="submit" class="width-35 pull-right btn btn-sm btn-primary"> <i class="icon-key"></i> Login </button>
                    </div>
                    <div class="space-4"></div>
                    </fieldset>
                  </form>
                </div>
                <!-- /widget-main -->
                <div class="toolbar clearfix">
                  <div> <a href="#" onClick="show_box('forgot-box'); return false;" class="forgot-password-link"> <i class="icon-arrow-left"></i>Forgot Password </a> </div>
                  <div class="hide"> <a href="#" onClick="show_box('signup-box'); return false;" class="user-signup-link">Varify Account<i class="icon-arrow-right"></i> </a> </div>
                </div>
              </div>
              <!-- /widget-body -->
            </div>
            <div id="forgot-box" class="forgot-box widget-box no-border">
              <div class="widget-body">
                <div class="widget-main">
                  <h4 class="header red lighter bigger"> <i class="icon-key"></i> Retrieve Password </h4>
                  <div class="space-6"></div>
                  <p> Enter your email to receive instructions </p>
                  <form method="post" action="">
                  <?=$forgetpassMessge?>
                    <input type="hidden" name="form" value="forgetpass">
                    <fieldset>
                    <label class="block clearfix"> <span class="block input-icon input-icon-right">
                    <input type="email" id="user_email" name="user_email" class="form-control" placeholder="Email" />
                    <i class="icon-envelope"></i> </span> </label>
                    <div class="clearfix">
                      <button type="submit" class="width-35 pull-right btn btn-sm btn-danger"> <i class="icon-lightbulb"></i> Send Me! </button>
                    </div>
                    </fieldset>
                  </form>
                </div>
                <!-- /widget-main -->
                <div class="toolbar center"> <a href="#" onClick="show_box('login-box'); return false;" class="back-to-login-link"> Back to login <i class="icon-arrow-right"></i> </a> </div>
              </div>
              <!-- /widget-body -->
            </div>
			<div id="signup-box" class="signup-box widget-box no-border">
              <div class="widget-body">
                <div class="widget-main">
                  <h4 class="header green lighter bigger"> <i class="icon-group blue"></i>Varify Account</h4>
                  <div class="space-6"></div>
                  <p> Enter your Security Code: </p>
                  <form method="post" action="">
                    <input type="hidden" name="form" value="varifyaccount">
                    <fieldset>
                    <label class="block clearfix"> <span class="block input-icon input-icon-right">
                    <input type="text" class="form-control" placeholder="Security Code" />
                    <div class="space-24"></div>
                    <div class="clearfix">
                      <button type="reset" class="width-36 pull-left btn btn-sm"> <i class="icon-refresh"></i>Send Again</button>
                      <button type="button" class="width-55 pull-right btn btn-sm btn-success"> Varify<i class="icon-arrow-right icon-on-right"></i> </button>
                    </div>
                    </fieldset>
                  </form>
                </div>
                <div class="toolbar center"> <a href="#" onClick="show_box('login-box'); return false;" class="back-to-login-link"> <i class="icon-arrow-left"></i> Back to login </a> </div>
              </div>
              <!-- /widget-body -->
            </div>
          </div>
          <!-- /position-relative -->
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
</div>
<!-- /.main-container -->
<!-- basic scripts -->
<!--[if !IE]> -->
<script type="text/javascript">
	window.jQuery || document.write("<script src='<?=$admin_path?>assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>
<!-- <![endif]-->
<!--[if IE]>
    <script type="text/javascript">
     window.jQuery || document.write("<script src='<?=$admin_path?>assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
    </script>
<![endif]-->
<script type="text/javascript">
	if("ontouchend" in document) document.write("<script src='<?=$admin_path?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<!-- inline scripts related to this page -->
<script type="text/javascript">
	function show_box(id) {
	 jQuery('.widget-box.visible').removeClass('visible');
	 jQuery('#'+id).addClass('visible');
	}
	<?php
	#$URLTYPE ='reset';
	if($_GET["type"]){
		?>jQuery('.widget-box.visible').removeClass('visible');show_box('<?=$_GET["type"]?>');<?	
	}
	
	if(isset($_POST['form']) && $_POST['form'] == 'forgetpass'){ 
		?>show_box('forgot-box');<? 
	}?>
</script>
</body>
</html>
