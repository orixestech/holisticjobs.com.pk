<?php
include("admin/includes/conn.php"); include("site_theme_functions.php"); include("admin/admin_theme_functions.php");
$DfaultJOBQuery = " JobLastDateApply >= '".date("Y-m-d")."' and JobStatus = 'Publish' ";
$DfaultJOBQuery = " JobStatus = 'Publish' ";

//print_r($_REQUEST);
if ($_SERVER['HTTP_HOST'] != 'localhost'){
	if ($_SERVER['HTTP_HOST'] != 'www.holisticjobs.com.pk'){
		echo "<script language='javascript'>window.location = '".$path."';</script>"; exit;
	}
}

$SessionID = $_SESSION["sessid"]; 
if( $_GET['logout'] == 'true'){
	@session_destroy();
	echo "<script language='javascript'>window.location = '".$path."';</script>"; exit;
} 

if(!$_REQUEST['page']) {
	$_REQUEST['page']='index';
}

if(substr($_REQUEST["page"],0,7)=='author/') { if($_REQUEST["page"]!='author/list') $_REQUEST["page"] = 'author/detail'; }

if($_REQUEST["page"]=='user/logout') {
	mysql_query(" delete from `cart` WHERE `cart_sessionid` = '".$SessionID."' ");
	unset($_SESSION); session_destroy(); session_regenerate_id(true); 
	echo "<script language='javascript'>window.location = '".$path."';</script>"; exit;
}

if($SETTING['site_online']=='off'){
	echo "<script language='javascript'>window.location = '".$path."offline/';</script>";exit;
}

if($_SESSION["UserLogged"] == 1){
	$stmt = query(" SELECT * FROM `members` WHERE `id` = '".$SessionUserID."' ");
	$USER = fetch($stmt);
	
	$stmt = query(" SELECT * FROM `member_setting` WHERE `member_id` = '".$SessionUserID."' ");
	while($rslt = fetch($stmt)){
		$USERSETTING[$rslt['option_name']] = $rslt['option_value'];
	}
	
	$_SESSION["UserFullName"] = $USER["firstname"] . "&nbsp;" . $USER["lastname"];
}

if ($_SERVER['HTTP_HOST'] != 'localhost')
{
	if($_SESSION['country']['id']=='' ){
		$ip = $_SERVER['REMOTE_ADDR'];
		//$ip = '134.67.44.22';
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
		$_SESSION['country']['code'] = $details->country . ' - ' . $details->region; // -> "Mountain View"
	}
	
	if(!strstr($_SERVER["SERVER_NAME"],'www')){
	  header("HTTP/1.1 301 Moved Permanently");
	  $siteUrl = "Location: http://www." . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	  $siteUrl = str_replace('www.www.','www.',$siteUrl);
	  header($siteUrl);
	}
	
	/*if($_SERVER["HTTPS"] != "on") {
		header("HTTP/1.1 301 Moved Permanently");
		$siteUrl = "Location: https://www." . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		$siteUrl = str_replace('www.www.','www.',$siteUrl);
		header($siteUrl);
	}*/
}
else
{


}

$sql = " SELECT * FROM `contents` WHERE `page_physical_name` = '".str_replace("/","-",$_REQUEST['page'])."' ";
if(total($sql)>0){
	$stmt=query($sql);
	$CONTENT = fetch($stmt);
} else {
	$sql = " SELECT * FROM `contents` WHERE `page_physical_name` = 'page-not-found' ";
	$stmt=query($sql);
	$CONTENT = fetch($stmt);
}


if($_REQUEST["page"]=='category')
{
	$cattitle = GetData('title','category','id',$_REQUEST["cid"]);
	$CONTENT['page_title'] = $cattitle . " | " . $site_name;
}

if($_REQUEST["page"]=='job')
{
	$itemtitle = GetData('JobTitle','jobs','UID',$_REQUEST["jobid"]);
	$CONTENT['page_title'] = $itemtitle . " job at " . $site_name;
	$JobDescription = GetData('JobDescription','jobs','UID',$_REQUEST["jobid"]);
	$JobDescription = str_replace('"',"",$JobDescription);
	$JobDescription = str_replace("'","",$JobDescription);
	$CONTENT['description'] = substr(strip_tags($JobDescription),0,300);
	$keyword = explode(" ",$itemtitle);
	foreach($keyword as $val){
		if( strlen($val)>3 ) $arr[] = $val;
	}
	
	$CONTENT['keywords'] = implode(", ",$arr);
}

if($_REQUEST["page"]=='employer-profile')
{
	$itemtitle = GetData('EmployerCompany','employer','UID',$_REQUEST["empid"]);
	$CONTENT['page_title'] = $itemtitle . " at " . $site_name;
	$JobDescription = GetData('EmployerAboutContent','employer','UID',$_REQUEST["empid"]);
	$JobDescription = str_replace('"',"",$JobDescription);
	$JobDescription = str_replace("'","",$JobDescription);
	$CONTENT['description'] = substr(strip_tags($JobDescription),0,300);
	$keyword = explode(" ",$itemtitle);
	foreach($keyword as $val){
		if( strlen($val)>3 ) $arr[] = $val;
	}
	$MetaImage = GetData('EmployerCover','employer','UID',$_REQUEST["empid"]);
	
	$CONTENT['keywords'] = implode(", ",$arr);
	$METATags = '<meta property="og:url"                content="'.EmployerProfileLink($_SESSION['EmployerUID']).'" />
		<meta property="og:type"               content="article" />
		<meta property="og:title"              content="'.$CONTENT['page_title'].'" />
		<meta property="og:description"        content="'.$CONTENT['description'].'" />
		<meta property="og:image"              content="'.$path.'uploads/'.$MetaImage.'" />';
}

if($_REQUEST["page"]=='employee-profile')
{
	$InvitationAccept = GetEmployerAccessToEmployee($_SESSION['Employer']['UID'], $_GET["empid"], 'invitation-accept');
	if($InvitationAccept){
		$itemtitle = GetData('EmployeeName','employee','UID',$_REQUEST["empid"]);
		$EmployeeObjective = GetData('EmployeeObjective','employee','UID',$_REQUEST["empid"]);
		$EmployeeObjective = str_replace('"',"",$EmployeeObjective);
		$EmployeeObjective = str_replace("'","",$EmployeeObjective);
		$CONTENT['description'] = substr(strip_tags($EmployeeObjective),0,300);
		$keyword = explode(" ",$itemtitle);
		foreach($keyword as $val){
			if( strlen($val)>3 ) $arr[] = $val;
		}
		$MetaImage = GetData('EmployeeLogo','employee','UID',$_REQUEST["empid"]);
		
		$CONTENT['keywords'] = implode(", ",$arr);
		$METATags = '<meta property="og:url"                content="'.EmployeeProfileLink($_SESSION['EmployeeUID']).'" />
			<meta property="og:type"               content="article" />
			<meta property="og:title"              content="'.$CONTENT['page_title'].'" />
			<meta property="og:description"        content="'.$CONTENT['description'].'" />
		<meta property="og:image"              content="'.$path.'uploads/'.$MetaImage.'" />';
	} else {
		$itemtitle = EmpCode("Employee Code : ", $_REQUEST["empid"]);
	}

	$CONTENT['page_title'] = $itemtitle . " at " . $site_name;
} ?>




<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<!-- Basic Page Needs
================================================== -->
<meta charset="utf-8">
<title>
<?=$CONTENT['page_title']?>
</title>
<link rel="shortcut icon" type="image/png" href="<?=$path?>images/holisticjobs.ico"/>
<meta name="author" content="Holistic IT Solutions. - www.holisticsolutions.com">
<meta name="web_author" content="Holistic IT Solutions. - www.holisticsolutions.com">
<meta name="publisher" content="http://www.holisticsolutions.com.pk/web-development.htm">
<meta name="copyright" content="Holistic IT Solutions. <?=$path?>">
<meta name="host" content="<?=$path?>">
<meta name="distribution" content="global" />
<meta name="revisit" content="1 day">
<meta name="Robots" content="index,follow">
<meta name="description" content="<?=$CONTENT['description']?>">
<meta name="keywords" content="holistic jobs,<?=$CONTENT['keywords']?>, jobs in pakistan, orixes tech">
<!-- Facebook Meta Tags
================================================== -->
<?=$METATags?>
<!-- Mobile Specific Metas
================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!-- CSS
================================================== -->
<link rel="stylesheet" href="<?=$path?>jquery-ui/jquery-ui.min.css" media="all">
<link rel="stylesheet" href="<?=$path?>jquery-ui/jquery-ui.theme.min.css" media="all">
<link rel="stylesheet" href="<?=$path?>css/style.css">
<link rel="stylesheet" href="<?=$path?>css/colors/red.css" id="colors">
<link rel="stylesheet" type="text/css" href="<?=$path?>formcss/validationEngine.jquery.css" />
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="<?=$path?>scripts/jquery-2.1.3.min.js"></script>
<script src="<?=$path?>jquery-ui/jquery-ui.min.js"></script>
<script src="<?=$path?>scripts/custom.js"></script>
<script type="text/javascript">


function ajaxreq(phpurl, phpdata, divid) {
	//alert(phpurl+phpdata);
	$.ajax({
		cache: false,
		type: "POST",
		url: "<?php echo $path; ?>" + phpurl,
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

function ajaxreqResponse(phpurl, phpdata) {
	var return_val = '';
	$.ajax({
		cache: false,
		type: "POST",
		async: false,
		url: "<?php echo $path; ?>" + phpurl,
		beforeSend: function () {
		},
		dataType: "html",
		data: phpdata,
		success: function (data) {
			return_val = data;
		},
		error: function () {
			ALERT("Something Wrong with Server...!", 'Error');
		}
	});
	return return_val;
}



function EmailDuplicateCheck(obj, type, uid, divid){
	var data = 'type=EmailDuplicateCheck&usertype=' + type + '&email=' + uid;
	var rslt = ajaxreqResponse('ajax-page.php', data);
	$("#" + divid).html(rslt);
	var error = $("#" + divid + " #error").val();
	if(error==1){
		//$(obj).val('');
	}
}

paceOptions = {
  elements: true
};

$(document).ready(function(){
	$('textarea[maxlength]').keyup(function(){  
		var limit = parseInt($(this).attr('maxlength'));  
		var text = $(this).val();  
		var chars = parseInt(text.length); 
		var remaining = $(this).attr('reminingid');
		$("#"+remaining).html( (limit - chars) + " letters remaining." ); 
		if(chars > limit){  
			var new_text = text.substr(0, limit);  
			$(this).val(new_text);  
		}
	});
	$("form").attr("autocomplete","off");
	$("input").attr("autocomplete","off");
	$("textarea").attr("autocomplete","off");
	$("select").attr("autocomplete","off"); 
	
	$('.modal').on('hidden.bs.modal', function () { alert("model close.");
		$(".modal-dialog .modal-content",this).html('<h4 class="modal-body"><i class="icon-spinner icon-spin green bigger-170"></i> Loading Please wait...</h4>');
	})
	
	blinker();
});

function blinker(){
	$("#btnBlink").hide();
	setTimeout("$('#btnBlink').show();", 500);
	setTimeout("blinker()",1500);
}

function blinkHighlight(){
	$(".highlight").css('color','#000');
	setTimeout(function(){
		$('.highlight').css('color','#F00');	
	}, 300);
	setTimeout("blinkHighlight()",1000);
}

</script>
<style>
div.blinkerbox {
	position: fixed;
	border: 2px red;
	left: 0;
	top: 120px;
	z-index: 9999;
	display:none;
	visibility:hidden;
}

/*font.highlight{ color: #F00; }*/
font.highlight{ 
	color: #F00;
    -webkit-animation: mymove 0.5s infinite;
    animation: mymove 0.5s infinite;
}

@-webkit-keyframes mymove {
    from {color: black;}
    to {color: #F00 ;}
}


@keyframes mymove {
    from {color: black;}
    to {color: #F00 ;}
}
</style>
</head>
<body>
<div class="blinkerbox"> <a href="<?=$path?>professional-poll.htm" target="_blank"> <img src="<?=$path?>images/Testphase-Background.png" width="40" style="position: fixed; left: 0px;"> <img id="btnBlink" src="<?=$path?>images/Testphase.png" width="50" style="position: fixed; left: 0px; padding: 0px;"></a></div>
<div id="wrapper">
  <div id="signup-dialog" class="zoom-anim-dialog mfp-hide apply-popup">
    <div class="small-dialog-headline">
      <h2>Sign Up options</h2>
    </div>
    <div class="small-dialog-content">
      <div class="eight columns"> <a href="<?=$path?>page/employer-subscription">
        <button class="send">Sign Up as Company</button>
        </a> </div>
      <div class="eight columns"> 
	  	<!--<a href="<?=$path?>page/employee-subscription">-->
	  	<a href="<?=$path?>page/employee-signup?plan=10">
        <button class="send">Sign Up as Applicant</button>
        </a> </div>
    </div>
  </div>
  <div id="login-dialog" class="zoom-anim-dialog mfp-hide apply-popup">
    <div class="small-dialog-headline">
      <h2>Login Options</h2>
    </div>
    <div class="small-dialog-content">
      <div class="eight columns"> <a href="<?=$path?>page/employer-login">
        <button class="send">Login as Company</button>
        </a> </div>
      <div class="eight columns"> <a href="<?=$path?>page/employee-login">
        <button class="send">Login as Applicant</button>
        </a> </div>
    </div>
  </div>
  <!-- Header
================================================== -->
  <header>
    <div class="container">
      <div class="sixteen columns">
        <div class="four columns">
          <!-- Logo -->
          <div id="logo">
            <h1><a href="<?=$path?>"><img src="<?=$path?>images/logo.png" alt="Holistic Jobs" style="width:100%;" /></a></h1>
          </div>
        </div>
        <div class="eleven columns" style="float:right">
          <!-- Menu -->
          <nav id="navigation" class="menu">
            <ul id="responsive">
              <li><a href="<?=$path?>" <?=($_REQUEST['page']=='home')?'id="current"':''?>>Home</a></li>
              <li><a href="<?=$path?>page/aboutus" <?=($_REQUEST['page']=='aboutus' || $_REQUEST['page']=='terms-of-services' || $_REQUEST['page']=='privacy-policy')?'id="current"':''?>>About Us</a>
                <!--<ul>
                <li><a href="<?=$path?>page/terms-of-services">Terms of Service</a></li>
                <li><a href="<?=$path?>page/privacy-policy">Privacy Policy</a></li>
              </ul>-->
              </li>
              <li><a href="<?=$path?>jobs/list" <?=($_REQUEST['page']=='jobs')?'id="current"':''?>>Jobs</a></li>
              <li><a href="<?=$path?>page/contact" <?=($_REQUEST['page']=='contact')?'id="current"':''?>>Contact</a></li>
            </ul>
            <?php if($_SESSION['EmployeeUID']>0){ ?>
            <ul class="float-right">
              <li><a href="<?=$path?>employee/index.php"><i class="fa fa-user"></i> My Account</a></li>
              <li><a href="<?=$path?>index.php?portal=employee&logout=true"><i class="fa fa-user"></i> Logout</a></li>
            </ul>
            <?php } else if($_SESSION['EmployerUID']>0){ ?>
            <ul class="float-right">
              <li><a href="<?=$path?>employer/index.php"><i class="fa fa-user"></i> My Account</a></li>
              <li><a href="<?=$path?>index.php?portal=employer&logout=true"><i class="fa fa-user"></i> Logout</a></li>
            </ul>
            <?php } else {?>
            <ul class="float-right">
              <li><a href="#login-dialog" class="popup-with-zoom-anim button"><i class="fa fa-lock"></i> Login</a></li>
              <li><a href="#signup-dialog" class="popup-with-zoom-anim button"><i class="fa fa-user"></i> Sign Up</a></li>
            </ul>
            <?php }?>
          </nav>
          <!-- Navigation -->
          <div id="mobile-navigation"> <a href="#menu" class="menu-trigger"><i class="fa fa-reorder"></i> Menu</a> </div>
        </div>
      </div>
    </div>
  </header>
  <div class="clearfix"></div>
  <div class="container">
    <marquee behavior="scroll" align="left">
    <div style="color: red; font-weight: bold; line-height: 30px; font-size:16px;"> 
		<span style="margin-right:30px;"><i class="fa fa-globe"></i> Attention Portal Members! We highly recommend you to update your profile to get a maximum profile score in order to attract the employers and receive job invitations.</span>
		<span style="margin-right:30px;"><i class="fa fa-globe"></i> Employer Portal Sign Up started!!!! Now employers can sign up and post jobs themselves</span>
		<span style="margin-right:30px;"><i class="fa fa-globe"></i> Dear Job Seekers! Sign up right now to apply for unlimited jobs</span>
	</div>
    </marquee>
  </div>
  <div class="clearfix"></div>
  <!-- Banner
================================================== -->
  <?php ($_REQUEST['page'] == 'index') ? include("home-banner.php") : '' ; ?>
  <?php // echo substr($_REQUEST['page'],0,5); exit;
	switch ($_REQUEST['page']) {
    case 'index':
        include("content_pages/homepage.php");
        break;
	case 'contact':
        include("content_pages/contact.php");
        break;
	case 'search':
        include("content_pages/search-item.php");
        break;
	case 'jobs':
        include("content_pages/jobs-list.php");
        break;
	/*case 'upload-cv':
        include("content_pages/upload-cv.php");
        break;*/
	case 'job':
        include("content_pages/job-view.php");
        break;
	case 'job-test':
        include("content_pages/job-view-test.php");
        break;
		
		
		
	case 'employer-profile':
        include("content_pages/employer-profile.php");
        break;
	case 'employee-profile':
        include("content_pages/employee-profile.php");
        break;
	case 'employer-subscription':
        include("content_pages/employer-subscription.php");
        break;
	case 'employee-subscription':
        include("content_pages/employee-subscription.php");
        break;
	
	
	case 'employer-signup':
        include("content_pages/employer-signup.php");
        break;
		
	case 'employee-signup':
        include("content_pages/employee-signup.php");
        break;

	case 'employer-login':
        include("content_pages/employer-login.php");
        break;
	case 'employee-login':
        include("content_pages/employee-login.php");
        break;
		
	case 'categories':
        include("content_pages/categories.php");
        break;

	
	
	
			






    default:
    	include("content_pages/innerpage.php");
}
?>
  <!-- Footer
================================================== -->
  <div class="margin-top-5"></div>
  <div id="footer">
    <!-- Main -->
    <div class="container">
      <div class="four columns">
        <h4>Follow Us</h4>
        <ul class="social-icons">
          <li><a class="facebook" href="<?=$SETTING['facebook']?>"><i class="icon-facebook"></i></a></li>
          <li><a class="linkedin" href="<?=$SETTING['linkedin']?>"><i class="icon-linkedin"></i></a></li>
        </ul>
      </div>
      <div class="six columns">
        <h4 style="padding: 0px 83px;">Company</h4>
        <div id="logo"> <a href="<?=$path?>"><img src="<?=$path?>images/logo-footer.png" alt="Holistic Jobs" /></a> </div>
      </div>
      <div class="six columns">
        <h4>Contact Us</h4>
        <ul class="contact-informations second">
          <li><i class="fa fa-phone"></i>
            <p>
              <?=$SETTING['phone']?>
            </p>
          </li>
          <li><i class="fa fa-envelope"></i>
            <p>
              <?=$SETTING['admin_email']?>
            </p>
          </li>
          <li><i class="fa fa-map-marker"></i>
            <p>
              <?=$SETTING['address']?>
            </p>
          </li>
        </ul>
      </div>
    </div>
    <!-- Bottom -->
    <div class="container">
      <div class="footer-bottom">
        <div class="sixteen columns">
          <div class="copyrights">&copy;  Copyright
            <?=date("Y")?>
            by <a href="http://www.holisticsolutions.com.pk/web-development.htm">Holistic IT Solutions</a>. All Rights Reserved.</div>
        </div>
      </div>
    </div>
  </div>
  <!-- Back To Top Button -->
  <div id="backtotop"><a href="#"></a></div>
</div>
<!-- Wrapper / End -->
<!-- Scripts
================================================== -->
<script src="<?=$path?>scripts/jquery.superfish.js"></script>
<script src="<?=$path?>scripts/jquery.themepunch.tools.min.js"></script>
<script src="<?=$path?>scripts/jquery.themepunch.revolution.min.js"></script>
<script src="<?=$path?>scripts/jquery.themepunch.showbizpro.min.js"></script>
<script src="<?=$path?>scripts/jquery.flexslider-min.js"></script>
<script src="<?=$path?>scripts/chosen.jquery.min.js"></script>
<script src="<?=$path?>scripts/jquery.magnific-popup.min.js"></script>
<script src="<?=$path?>scripts/waypoints.min.js"></script>
<script src="<?=$path?>scripts/jquery.counterup.min.js"></script>
<script src="<?=$path?>scripts/jquery.jpanelmenu.js"></script>
<script src="<?=$path?>scripts/stacktable.js"></script>
<script src="<?=$path?>formcss/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=$path?>formcss/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script>
(function($) {
    $.fn.hasScrollBar = function() {
        return this.get(0).scrollHeight > this.height();
    }
})(jQuery);

function UpdateFooter(){
	var h = window.innerHeight;
	var b = $("body").height();
	var pos = $('body').scrollTop();

	if ($(document).height() > $(window).height()) {
		// scrollbar
	} else {
		$("div#footer").css('margin-top',( h - b ) + "px");	
	}
}
UpdateFooter();

$(document).ready(function() {
	$( ".date-picker" ).datepicker( { showOtherMonths: true, selectOtherMonths: true, dateFormat: "yy-mm-dd", } );
	$( ".date-picker" ).on( "change", function() {
	  $( this ).datepicker( { showOtherMonths: true, selectOtherMonths: true, dateFormat: "yy-mm-dd" } );
	});
});
	
$('.upload-file ').each(function(){
	$('.upload-btn input[type="file"]',this).change(function() {
        var filename = $(this).val();
		//alert(filename);
        $('span.fake-input').html(filename);
    });
});

<?php if ($_SERVER['HTTP_HOST'] != 'localhost'){?>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-71071799-1', 'auto');
  ga('send', 'pageview');
<?php }?>
</script>
</body>
</html>
