<?php
define('CATEGORY_SEPERATOR', '--');
define('CATEGORY_IMAGE_DIRECTORY', '../images/category_images/');
define('BANNER_IMAGE_DIRECTORY', '../images/banners/');
define('CATEGORY_IMAGE_ALLOWED_FILESIZE', 5000000);	//5MB
define('MEMBER_IMAGE_DIRECTORY', '../uploads/');
define('MEMBER_IMAGE_ALLOWED_FILESIZE', 5000000);	//5MB
define('TEMPLATE_DIRECTORY', '../images/templates/');
define('TEMPLATE_ALLOWED_FILESIZE', 5000000);	//5MB
global $path;
$site_name     = "SELECT * FROM `admin_setting` WHERE 1";
$exe_site_name = mysql_query($site_name) or die($site_name);

while($row_site_name = mysql_fetch_array($exe_site_name)){
	$SETTING[$row_site_name['option_name']] = $row_site_name['option_value'];
}

if ($_SERVER['HTTP_HOST'] == 'localhost'){
	$path = 'http://localhost/holisticjobs.com.pk/';
}
else{
	$path = $SETTING['siteurl'];
}

$admin_path = $path."admin/";
if ($_SERVER['HTTP_HOST'] != 'localhost'){
	$root_path = $_SERVER['DOCUMENT_ROOT'] . "/";
} else {
	$root_path = $_SERVER['DOCUMENT_ROOT'] . "/holisticjobs.com.pk/";
}



$_SESSION["site_path"] = $path;
$_SESSION["admin_path"] = $admin_path;
$_SESSION["root_path"] = $root_path;

$site_name = $_SESSION["site_name"] = $SETTING['sitename'];
$site_email = $_SESSION["site_email"] =  $SETTING['admin_email'];
$site_ccemail = $_SESSION["site_ccemail"] =  $SETTING['cc_email'];

$social_media = array("twitter"=>$row_site_name['twitter'], "facebook"=>$row_site_name['facebook'], "youtube"=>$row_site_name['youtube'], "linkedin"=>$row_site_name['linkedin'], "friendfeed"=>$row_site_name['friendfeed'], "facebook_fan"=>$row_site_name['facebook_fan']);
# print_r($social_media);

function redirectToHTTPS()
{
	//echo "<pre>"; print_r($_SERVER); echo "</pre>";
	if($_SERVER['HTTPS']!="on")//check if page is at HTTP then move it to HTTPS
	{
		if (isset($_SERVER['REQUEST_URI']))
			$redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		else
			$redirect= "https://".$_SERVER['HTTP_HOST'].request_uri();
		//echo $redirect;
		
		header("Location:$redirect");
	}
}

function sessionX(){ 
	global $path;
    $logLength = 6800; # time in seconds :: 1800 = 30 minutes 
    $ctime = strtotime("now"); # Create a time from a string 
    # If no session time is created, create one 
    /*if(!isset($_SESSION['sessionX'])){  
        # create session time 
        $_SESSION['sessionX'] = $ctime;  
    }else{ 
        # Check if they have exceded the time limit of inactivity 
        if(((strtotime("now") - $_SESSION['sessionX']) > $logLength) && $_SESSION['AdminUserLogged'] == 1){ 
            # If exceded the time, log the user out 
            session_unset();
			session_destroy();
            # Redirect to login page to log back in 
            header("Location: ".$_SESSION["site_path"]); 
            exit; 
        }else{ 
            # If they have not exceded the time limit of inactivity, keep them logged in 
            $_SESSION['sessionX'] = $ctime; 
        } 
    } */
} 
sessionX();
?>