<?php
include("includes/conn.php"); 
include("admin_theme_functions.php");
if ($_SERVER['HTTP_HOST'] != 'localhost:8081'){
//	if ($_SERVER['HTTP_HOST'] != 'www.holisticjobs.com.pk'){
//		echo "<script language='javascript'>window.location = '".$path."admin/';</script>"; exit;
//	}
}

$_SESSION['AdminLogged'] = $_SESSION['Admin'] = $FormMessge='';

if(isset($_POST['form']) && $_POST['form'] == 'login'){
	if(isset($_POST['Username']) && isset($_POST['Password']) && $_POST['Password']!=''){
	  $SQL = " SELECT * FROM `users` WHERE `user_access` = 'admin' and `user_status` = 1 and `user_email` = '".$_POST['Username']."' and `user_pass` = '".PassWord($_POST['Password'],'hide')."' ";
		$user = mysql_query($SQL);
		$row = mysql_num_rows($user);
		if($row){
			$rslt = mysql_fetch_array($user);
			$_SESSION['User'] = $rslt;
			$_SESSION['AdminUserLogged'] = 1;
			$FormMessge = Alert('success', 'Login Successfully...');
			//echo '<meta http-equiv="refresh" content="1;URL=\'index.php\'" />';
			/*header("Location: index.php"); exit; */
			echo "<script language='javascript'>window.location = 'index.php';</script>";exit;
		} else {
			$FormMessge = Alert('error', 'Invalid Username or Password');
		}
		
	}
}

echo PassWord('htbDj+MpQdEycGnwzUqsjak+1DE0xjI16P+2XGUQurs=','show');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Login Page - <?=$SETTING['sitename']?></title>
<meta name="description" content="User login page" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- basic styles -->

<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="assets/css/font-awesome.min.css" />

<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

<!-- page specific plugin styles -->

<!-- fonts -->

<link rel="stylesheet" href="assets/css/ace-fonts.css" />

<!-- ace styles -->

<link rel="stylesheet" href="assets/css/ace.min.css" />
<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

<!--[if lte IE 8]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

<!-- inline styles related to this page -->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!--[if lt IE 9]>
		<script src="assets/js/html5shiv.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>

<body class="login-layout">
<div class="main-container" style="margin-top:10px;">
  <div class="main-content">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="login-container">
          <div class="center">
            <img src="<?=$path?>images/logo.png" width="100%" alt="Holistic Jobs">          </div>
          <div class="space-6"></div>
          <div class="position-relative">
            <div id="login-box" class="login-box visible widget-box no-border">
              <div class="widget-body">
                <div class="widget-main">
				<h2 class="header blue lighter bigger"> Admin Portal</h2>
                  <h4 class="header blue lighter bigger"> <i class="icon-coffee green"></i> Please Enter Your Information </h4>
                  <div class="space-6"></div>
                  <?=$FormMessge?>
                  <form method="post" action=""><input type="hidden" name="form" value="login">
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
                
                <div class="toolbar clearfix hide">
                  <div> <a href="#" onClick="show_box('forgot-box'); return false;" class="forgot-password-link"> <i class="icon-arrow-left"></i> I forgot my password </a> </div>
                  <div> <a href="#" onClick="show_box('signup-box'); return false;" class="user-signup-link"> I want to register <i class="icon-arrow-right"></i> </a> </div>
                </div>
              </div>
              <!-- /widget-body --> 
            </div>
            <!-- /login-box -->
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
			window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]--> 

<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]--> 

<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
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
	?>
</script>
</body>
</html>
