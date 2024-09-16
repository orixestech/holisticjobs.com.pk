<?php 
@session_start();
$timeout = 3600;
ini_set('session.gc_maxlifetime',$timeout); 
ini_set("session.cookie_lifetime", $timeout); 
ini_set('session.gc_probability',100);
ini_set('session.gc_divisor',1); 
error_reporting("E_ALL & ~E_DEPRECATED");
ini_set("display_errors", 1);
date_default_timezone_set("Asia/Karachi");

ini_set("post_max_size", '100M');
ini_set("upload_max_filesize", '100M');


if ($_SERVER['HTTP_HOST'] != 'localhost')
{
	$hostname_dbconn = "localhost";
	$rating_dbname   = "holistic_jobsdb";
	$username_dbconn = "holistic_maindb";
	$password_dbconn = "@holistic_maindb@";
}
else
{
	$hostname_dbconn = "localhost";
	$rating_dbname = "holistic_jobsdb";
	$username_dbconn = "root";
	$password_dbconn = "";
}

$conn = @mysql_connect($hostname_dbconn, $username_dbconn, $password_dbconn) or die(mysql_error());
mysql_select_db($rating_dbname,$conn) or die(mysql_error()); 

$conn2 = mysqli_connect($hostname_dbconn, $username_dbconn, $password_dbconn, $rating_dbname);

if (!$conn2) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

mysql_query("SET time_zone = '+5:00';");

session_start();
include("settings.inc.php");
include("site_functions.php");

$_SESSION["sessid"] = session_id();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
#$site_offline=1;

?>