<?php

$site_name     = "SELECT * FROM `admin_setting` WHERE 1";
$exe_site_name = mysql_query($site_name) or die($site_name);
while($row_site_name = mysql_fetch_array($exe_site_name)){
	$SETTING[$row_site_name['option_name']] = $row_site_name['option_value'];
}
$path = $SETTING['siteurl'];
$admin_path = $path."admin/";
$root_path = $_SERVER['DOCUMENT_ROOT'] . "/";

$social_media = array("twitter"=>$row_site_name['twitter'], "facebook"=>$row_site_name['facebook'], "youtube"=>$row_site_name['youtube'], "linkedin"=>$row_site_name['linkedin'], "friendfeed"=>$row_site_name['friendfeed'], "facebook_fan"=>$row_site_name['facebook_fan']);
# print_r($social_media);
error_reporting("E_ERROR");

function redirectToHTTPS()
{
//	echo "<pre>"; print_r($_SERVER); echo "</pre>";
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


/**
* Settings by Khalid.
*/
define('CATEGORY_SEPERATOR', '--');
define('CATEGORY_IMAGE_DIRECTORY', '../images/category_images/');
define('CATEGORY_IMAGE_ALLOWED_FILESIZE', 5000000);	//5MB

?>