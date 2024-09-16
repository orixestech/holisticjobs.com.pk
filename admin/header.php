<?php
include("includes/conn.php");

include("admin_theme_functions.php");

/////////////////////////////////////
///////// Front Side Functions
include("../site_theme_functions.php");
/////////////////////////////////////

$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];

if($_SESSION['AdminUserLogged'] != 1){
	echo '<meta http-equiv="refresh" content="0;URL=\'login.php\'" />';exit;
}

#print_r($_SESSION['Admin']);

$page = end( explode("/",$_SERVER['SCRIPT_FILENAME']) );

include("access_check.php");

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
<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
<link href="<?=$path?>css/paging.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/chosen.css" />
<link rel="stylesheet" href="assets/css/datepicker.css" />
<link rel="stylesheet" href="assets/css/bootstrap-timepicker.css" />
<!--[if lte IE 8]>

		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />

		<![endif]-->
<!-- inline styles related to this page -->
<!-- ace settings handler -->
<script src="assets/js/ace-extra.min.js"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>

		<script src="assets/js/html5shiv.js"></script>

		<script src="assets/js/respond.min.js"></script>

		<![endif]-->

<?php if ($_SERVER['HTTP_HOST'] != 'localhost'){?>
		<?php /*?>
		<script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=612883548793753";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
		<?php */?>
<?php } ?>
</head>
<body>
<div id="fb-root"></div>
<div class="navbar navbar-default" id="navbar">
  <script type="text/javascript"> try{ace.settings.check('navbar' , 'fixed')}catch(e){} </script>
  <div class="navbar-container" id="navbar-container">
    <div class="navbar-header pull-left"> <a href="<?=$path?>" class="navbar-brand"> Admin Panel
      <?=$SETTING['sitename']?>
      </a>
      <!-- /.brand -->
    </div>
    <!-- /.navbar-header -->
    <div class="navbar-header pull-right " role="navigation">
      <ul class="nav ace-nav">
        <?php
      	$stmt = mysql_query(" SELECT * FROM `admin_log` WHERE `logstatus` = 0 order by `logdatetime` desc ");
		$row = mysql_num_rows( $stmt );
		?>
        <li class="purple"> <a data-toggle="dropdown" class="dropdown-toggle" href="#"> <i class="icon-bell-alt icon-animated-bell"></i> <span class="badge badge-important">
          <?=$row?>
          </span> </a>
          <ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
            <li class="dropdown-header"> <i class="icon-warning-sign"></i>
              <?=$row?>
              Notifications </li>
            <?php
			if($row){ 
				/*while( $rslt = mysql_fetch_array($stmt) ){
					?><li> <a href="#">
				  <div class="clearfix"> <span class="pull-left"> <i class="btn btn-xs no-hover btn-pink icon-comment"></i> <?=$rslt['lognotes']?> </span></div>
				  </a> </li><?
				}*/
			} ?>
            <li> <a href="track-view.php"> See all notifications <i class="icon-arrow-right"></i> </a> </li>
          </ul>
        </li>
        <li class="light-blue"> <a data-toggle="dropdown" href="#" class="dropdown-toggle"> <span class="user-info"> <small>Welcome,</small>
          <?=$_SESSION['User']['display_name']?>
          </span> <i class="icon-caret-down"></i> </a>
          <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
            <li> <a href="profile.php"> <i class="icon-user"></i> Profile </a> </li>
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
<script type="text/javascript">

				try{ace.settings.check('main-container' , 'fixed')}catch(e){}

			</script>
<div class="main-container-inner">
<a class="menu-toggler" id="menu-toggler" href="#"> <span class="menu-text"></span> </a>
<div class="sidebar" id="sidebar">
  <script type="text/javascript">

						try{ace.settings.check('sidebar' , 'fixed')}catch(e){}

					</script>
  <!--<div class="sidebar-shortcuts" id="sidebar-shortcuts">

    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">

      <button class="btn btn-success"> <i class="icon-signal"></i> </button>

      <button class="btn btn-info"> <i class="icon-pencil"></i> </button>

      <button class="btn btn-warning"> <i class="icon-group"></i> </button>

      <button class="btn btn-danger"> <i class="icon-cogs"></i> </button>

    </div>

    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini"> <span class="btn btn-success"></span> <span class="btn btn-info"></span> <span class="btn btn-warning"></span> <span class="btn btn-danger"></span> </div>

  </div>-->
  <!-- #sidebar-shortcuts -->
  <ul class="nav nav-list">
    <li class="active"> <a href="index.php"> <i class="icon-dashboard"></i> <span class="menu-text"> Dashboard </span> </a> </li>
    
	<?php $class=''; $opt = array('page.php','pages-view.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Pages </span> <b class="arrow icon-angle-down"></b> </a>
      <ul class="submenu">
        <li<?=($page=='page.php')?' class="active" ':''?>> <a href="page.php"> <i class="icon-double-angle-right"></i> Add New </a> </li>
        <li<?=($page=='pages-view.php')?' class="active" ':''?>> <a href="pages-view.php"> <i class="icon-double-angle-right"></i> View All </a> </li>
      </ul>
    </li>
    
	<?php $class=''; $opt = array('careers-view.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="careers-view.php" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Careers </span> </a> </li>
    
	<?php $class=''; $opt = array('jobs-view.php','job.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Jobs </span> <b class="arrow icon-angle-down"></b> </a>
      <ul class="submenu">
        <li<?=($page=='job.php')?' class="active" ':''?>> <a href="job.php"> <i class="icon-double-angle-right"></i> Add New </a> </li>
        <li<?=($page=='jobs-view.php')?' class="active" ':''?>> <a href="jobs-view.php"> <i class="icon-double-angle-right"></i> View All </a> </li>
      </ul>
    </li>
    
	<?php $class=''; $opt = array('employer-view.php','employer.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Corporate </span> <b class="arrow icon-angle-down"></b> </a>
      <ul class="submenu">
        <li<?=($page=='employer.php')?' class="active" ':''?>> <a href="employer.php"> <i class="icon-double-angle-right"></i> Add New </a> </li>
        <li<?=($page=='employer-view.php')?' class="active" ':''?>> <a href="employer-view.php"> <i class="icon-double-angle-right"></i> View All </a> </li>
      </ul>
    </li>
    
	<?php $class=''; $opt = array('employee-view.php','employee.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Individual </span> <b class="arrow icon-angle-down"></b> </a>
      <ul class="submenu">
        <li<?=($page=='employee.php')?' class="active" ':''?>> <a href="employee.php"> <i class="icon-double-angle-right"></i> Add New </a> </li>
        <li<?=($page=='employee-view.php')?' class="active" ':''?>> <a href="employee-view.php"> <i class="icon-double-angle-right"></i> View All </a> </li>
      </ul>
    </li>
	
	<?php $class=''; $opt = array('user-manual-employee-view.php','user-manual-employee.php','user-manual-employer-category.php','user-manual-employer-category.php','user-manual-employer.php','user-manual-employer-view.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> User Manual</span> <b class="arrow icon-angle-down"></b> </a>
      <ul class="submenu">
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Employer</span> <b class="arrow icon-angle-down"></b> </a>
	   <ul class="submenu">
	           <li<?=($page=='user-manual-employer.php')?' class="active" ':''?>> <a href="user-manual-employer.php"> <i class="icon-double-angle-right"></i> Add New </a> </li>
        <li<?=($page=='user-manual-employer-view.php')?' class="active" ':''?>> <a href="user-manual-employer-view.php"> <i class="icon-double-angle-right"></i> View All </a> </li>
		        <li<?=($page=='user-manual-employer-category.php')?' class="active" ':''?>> <a href="user-manual-employer-category.php"> <i class="icon-double-angle-right"></i> Categories</a> </li>
</ul></li>
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Employee</span> <b class="arrow icon-angle-down"></b> </a>
<ul class="submenu">
	           <li<?=($page=='user-manual-employee.php')?' class="active" ':''?>> <a href="user-manual-employee.php"> <i class="icon-double-angle-right"></i> Add New </a> </li>
        <li<?=($page=='user-manual-employee-view.php')?' class="active" ':''?>> <a href="user-manual-employee-view.php"> <i class="icon-double-angle-right"></i> View All </a> </li>

		<li<?=($page=='user-manual-employee-category.php')?' class="active" ':''?>> <a href="user-manual-employee-category.php"> <i class="icon-double-angle-right"></i> Categories</a> </li>
</ul></li>
      </ul>
    </li>
   
	<?php $class=''; $opt = array('employee-subscription-view.php', 'employer-subscription-view.php','employer-access-type.php','employee-access-type.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Subscription </span> <b class="arrow icon-angle-down"></b></a>
	      <ul class="submenu">
	 <li<?=($page=='employer-subscription-view.php')?' class="active" ':''?>> <a href="employer-subscription-view.php"> <i class="icon-double-angle-right"></i> Employer Subscription </a> </li>
     <li<?=($page=='employer-access-type.php')?' class="active" ':''?>> <a href="employer-access-type.php"> <i class="icon-double-angle-right"></i>Access Type (Employer)</a> </li>
	  <li<?=($page=='employee-subscription-view.php')?' class="active" ':''?>> <a href="employee-subscription-view.php"> <i class="icon-double-angle-right"></i>Employee Subscription </a> </li> 
      <li<?=($page=='employee-access-type.php')?' class="active" ':''?>> <a href="employee-access-type.php"> <i class="icon-double-angle-right"></i>Access Type (Employee)</a> </li>
	  </ul>
	  </li>
   
    <?php $class=''; $opt = array('invoices-view.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
	<li<?=$class?>> <a href="invoices-view.php"> <i class="icon-desktop"></i> <span class="menu-text"> Invoices </span> </a> </li>

	<?php $class=''; $opt = array('career-factor.php','navigation-list.php',''); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>
	    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Career Navigation </span> <b class="arrow icon-angle-down"></b> </a>
		<ul class="submenu">
		        <li<?=($page=='career-factor.php')?' class="active" ':''?>> <a href="career-factor.php"> <i class="icon-double-angle-right"></i> Factors </a> </li>
<li<?=($page=='navigation-list.php')?' class="active" ':''?>> <a href="navigation-list.php"> <i class="icon-double-angle-right"></i> List </a> </li>
		</ul></li>
	<?php $class=''; $opt = array('dropdowns-view.php','banner-view.php','category-view.php', 'setting.php', 'track-view.php',  'database_backup.php'); if(in_array($page,$opt)){ $class= ' class="active open" '; }?>

    <li<?=$class?>> <a href="#" class="dropdown-toggle"> <i class="icon-desktop"></i> <span class="menu-text"> Settings </span> <b class="arrow icon-angle-down"></b> </a>
      <ul class="submenu">
        <li <?=($page=='setting.php')?' class="active" ':''?>> <a href="setting.php"> <i class="icon-cog"></i> Admin Settings </a> </li>
        <li<?=($page=='banner-view.php')?' class="active" ':''?>> <a href="banner-view.php"> <i class="icon-double-angle-right"></i> Banners </a> </li>
       <li <?=($page=='database_backup.php')?' class="active" ':''?>> <a href="database_backup.php"> <i class="icon-download-alt"></i> Database Backup </a> </li>
        <li<?=($page=='dropdowns-view.php')?' class="active" ':''?>> <a href="dropdowns-view.php"> <i class="icon-double-angle-right"></i> Dropdowns </a> </li>
		<li<?=($page=='category-view.php?type=Jobs')?' class="active" ':''?>> <a href="category-view.php?type=Jobs"> <i class="icon-double-angle-right"></i> Jobs Categories </a> </li>
        <li <?=($page=='track-view.php')?' class="active" ':''?>> <a href="track-view.php"> <i class="icon-eye-open"></i> System Log </a> </li>
      </ul>
    </li>
  </ul>
  <!-- /.nav-list -->
  <div class="sidebar-collapse" id="sidebar-collapse"> <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i> </div>
  <script type="text/javascript"> try{ace.settings.check('sidebar' , 'collapsed')}catch(e){} </script>
</div>
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
	$("#breadcrumbs form.form-search").hide();
	
	function ConfirmDelete(url){
		url = '<?=$path?>admin/' + url;
		if(confirm("Do you want to delete.?")){
			window.location.href = url;
		} else {
			return false;			
		}
	}
	
</script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/typeahead-bs2.min.js"></script>
<!-- ace scripts -->
<script src="assets/js/ace-elements.min.js"></script>
<script src="assets/js/ace.min.js"></script>

<script src="script.js"></script>

<link rel="stylesheet" type="text/css" href="<?=$path?>formcss/validationEngine.jquery.css" />
<script src="<?=$path?>formcss/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?=$path?>formcss/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script> 


<?php include("admin_theme_scripts.php");?>
