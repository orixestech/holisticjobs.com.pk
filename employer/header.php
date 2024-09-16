<?php
include("../admin/includes/conn.php");
include("../admin/admin_theme_functions.php");



/////////////////////////////////////
///////// Front Side Functions
include("../site_theme_functions.php");
$employer_path = $path . "employer/"; //print_r($_SESSION);
/////////////////////////////////////

$_SESSION['EmployerAccess'] = $_SESSION['Employer']['user_access'];
$_SESSION['EmployerUID'] = $_SESSION['Employer']['UID'];

if( !isset($_SESSION['Employer']['UID']) ){
	echo '<meta http-equiv="refresh" content="0;URL=\'login.php\'" />';exit;
}

#print_r($_SESSION['Admin']);


///// Subscription Access Check
$access = CheckSubAccess('homepage-exposure', 'employer');
if($access['access']) {
	//// Your code .....
}

$page = end( explode("/",$_SERVER['SCRIPT_FILENAME']) );

include("access_check.php");

$SubscriptionExpireStatus = '';SubscriptionStatus('string', $_SESSION['Employer']['EmployerSubscriptionExpire'] );


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Dashboard -
<?=$SETTING['sitename']?>
</title>
<meta name="description" content="overview &amp; stats" />
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
<link rel="stylesheet" href="<?=$admin_path?>assets/css/ace-skins.min.css" />
<link href="<?=$path?>css/paging.css" rel="stylesheet">
<link rel="stylesheet" href="<?=$admin_path?>assets/css/chosen.css" />
<link rel="stylesheet" href="<?=$admin_path?>assets/css/datepicker.css" />
<link rel="stylesheet" href="<?=$admin_path?>assets/css/bootstrap-timepicker.css" />
<link rel="stylesheet" href="style_employer.css" />
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

<link rel="stylesheet" href="<?=$path?>admin/assets/redactor/redactor.css" />
<script src="<?=$path?>admin/assets/redactor/redactor.js"></script>

<link rel="stylesheet" type="text/css" href="<?=$path?>formcss/validationEngine.jquery.css" />
<script src="<?=$path?>formcss/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$path?>formcss/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

    if("ontouchend" in document) document.write("<script src='<?=$admin_path?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
	$("#breadcrumbs form.form-search").hide();
	
	function ConfirmDelete(url){
		url = '<?=$path?>employer/' + url;
		if(confirm("Do you want to delete.?")){
			window.location.href = url;
		} else {
			return false;			
		}
	}
	
	function ajaxreq(phpurl, phpdata, divid) {
		//alert(phpurl+phpdata);
		$.ajax({
			cache: false,
			type: "POST",
			url: "<?php echo $path; ?>employer/" + phpurl,
			beforeSend: function () {
				$("#" + divid).html('Loading....');
				$("#" + divid).fadeIn('fast');
			},
			dataType: "html",
			data: phpdata,
			success: function (data) {
				$("#" + divid).html(data);
				$("#" + divid).fadeIn('slow');
				if ($("#" + divid + ' #reload').val() == 1) {
					if ($("#" + divid + ' #reloadurl').val() != '') {
						var reloadURL = $("#" + divid + ' #reloadurl').val();
						setTimeout('window.location = "' + reloadURL + '"', 2200);
					} else {
						setTimeout("window.location = location.href;", 2200);
					}
				}
				
			},
			error: function () {
				ALERT("Something Wrong with Server...!", 'Error');
			}
		});
	}
	
	
</script>
<script src="<?=$admin_path?>assets/js/bootstrap.min.js"></script>
<script src="<?=$admin_path?>assets/js/typeahead-bs2.min.js"></script>
<!-- ace scripts -->
<script src="<?=$admin_path?>assets/js/ace-elements.min.js"></script>
<script src="<?=$admin_path?>assets/js/ace.min.js"></script>
<script src="<?=$path?>employer/script.js"></script>
<?php include("admin_theme_scripts.php");?>
<!--[if lte IE 8]>

		  <link rel="stylesheet" href="<?=$admin_path?>assets/css/ace-ie.min.css" />

		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="<?=$admin_path?>assets/js/ace-extra.min.js"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>

		<script src="<?=$admin_path?>assets/js/html5shiv.js"></script>

		<script src="<?=$admin_path?>assets/js/respond.min.js"></script>

		<![endif]-->
<!--<script>(function(d, s, id) {

	  var js, fjs = d.getElementsByTagName(s)[0];

	  if (d.getElementById(id)) return;

	  js = d.createElement(s); js.id = id;

	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=613462515432251&version=v2.0";

	  fjs.parentNode.insertBefore(js, fjs);

	}(document, 'script', 'facebook-jssdk'));</script>-->
</head>
<body>

<div id="fb-root"></div>
<div class="navbar navbar-default" id="navbar"> 
  <script type="text/javascript"> try{ace.settings.check('navbar' , 'fixed')}catch(e){} </script>
  <div class="navbar-container" id="navbar-container">
    <div class="navbar-header pull-left navbar-brand">Employer Portal
      <?=$SETTING['sitename']?>
      <!-- /.brand --> 
    </div>
                                        
    <!-- /.navbar-header -->
    <div class="navbar-header pull-right " role="navigation">
      <ul class="nav ace-nav">
       <?php
      	$stmt = mysql_query(" SELECT * FROM `admin_log` WHERE `logstatus` = 0 and `logref` = 'employer' and `logrefid` = '".$_SESSION['EmployerUID']."'  order by `logdatetime` desc limit 5");
		$row = mysql_num_rows( $stmt ) + 0;
		?>
        <li class="purple"> <a href="notification.php"> <i class="icon-bell-alt icon-animated-bell"></i> <span class="badge badge-important">
          <?=$row?>
          </span> </a>
        </li>
        <li class="light-blue"> <a data-toggle="dropdown" href="#" class="dropdown-toggle"> <span class="user-info"> <small>Welcome,</small>
          <?=$_SESSION['Employer']['EmployerCompany']?>
          </span> <i class="icon-caret-down"></i> </a>
          <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
            <li> <a href="index.php?logout=true"> <i class="icon-off"></i> Logout </a> </li>
          </ul>
        </li>
      </ul>
      <!-- /.ace-nav --> 
    </div>
    <!-- /.navbar-header --> 
  </div>
  <!-- /.container --> 
</div>
<div class="main-container" id="main-container">
<script type="text/javascript"> try{ace.settings.check('main-container' , 'fixed')}catch(e){} </script>
<div class="main-container-inner">
<a class="menu-toggler" id="menu-toggler" href="#"> <span class="menu-text"></span> </a>
<div class="sidebar" id="sidebar"> 
  <script type="text/javascript"> try{ace.settings.check('sidebar' , 'fixed')}catch(e){} </script>
  
  <ul class="nav nav-list">
    <li class="active"> <a href="index.php"> <i class="icon-dashboard"></i> <span class="menu-text"> Dashboard </span> </a> </li>
    
	<?php $class=''; $opt = array('profile.php','pages-view.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="profile.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text">Corporate Profile </span> </a> </li>
    
	<?php $class=' class="active open" '; $opt = array('post-job.php','published-jobs.php','unpublished-jobs.php','expired-jobs.php','employee-comparison.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> My Jobs </span> <b class="arrow icon-angle-down"></b> </a>
      <ul class="submenu">
        <?php $class=''; $opt = array('post-job.php',''); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
        <li<?=$class?>> <a href="post-job.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Post a Job </span> </a> </li>
        <?php $class=''; $opt = array('published-jobs.php',''); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
        <li<?=$class?>> <a href="published-jobs.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Published Jobs </span> </a> </li>
        <?php $class=''; $opt = array('unpublished-jobs.php',''); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
        <li<?=$class?>> <a href="unpublished-jobs.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Unpublished Jobs </span> </a> </li>
        <?php $class=''; $opt = array('expired-jobs.php',''); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
        <li<?=$class?>> <a href="expired-jobs.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Expired Jobs </span> </a> </li>        
		

      </ul>
    </li>
    <?php $class=''; $opt = array('subscription-details.php','subscription-view.php','payment-history.php','update-subscription.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Subscription </span> <b class="arrow icon-angle-down"></b> </a>
      <ul class="submenu">
        <li<?=($page=='subscription-details.php')?' class="active" ':''?>> <a href="subscription-details.php"> <i class="icon-double-angle-right"></i> View Details </a> </li>
        <!--<li<?=($page=='subscription-view.php')?' class="active" ':''?>> <a href="#"> <i class="icon-double-angle-right"></i> View Details </a> </li>
        <li<?=($page=='payment-history.php')?' class="active" ':''?>> <a href="payment-history.php"> <i class="icon-double-angle-right"></i> Payment History </a> </li>-->
      </ul>
    </li>
	    
	<?php $class=''; $opt = array('user-manual-view.php',''); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="user-manual-view.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text">User Manual</span> </a> </li>
		
	<?php $class=''; $opt = array('pool-of-cv.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="pool-of-cv.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Pool of CVs </span> </a> </li>

	<?php $class=''; $opt = array('invitations.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="invitations.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Invitations </span> </a> </li>

    <?php $class=''; $opt = array('account-settings.php',''); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="account-settings.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Account Settings </span> </a> </li>
    </li>
  </ul>
  <!-- /.nav-list -->
  <div class="sidebar-collapse" id="sidebar-collapse"> <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i> </div>
  <script type="text/javascript"> try{ace.settings.check('sidebar' , 'collapsed')}catch(e){} </script> 
</div>
