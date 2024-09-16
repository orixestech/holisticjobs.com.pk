<?php @session_start();

# FileName="Connection_php_mysql.htm"

# Type="MYSQL"

# HTTP="true"

if ($_SERVER['HTTP_HOST'] != 'localhost')
{
	$hostname_dbconn = "localhost";
	$rating_dbname   = "ibuildso_devmarket";
	$username_dbconn = "ibuildso_kamran";
	$password_dbconn = "kamran123";
}
else
{
	$hostname_dbconn = "localhost";
	$rating_dbname = "devmarket";
	$username_dbconn = "root";
	$password_dbconn = "mysql";
}
#echo $hostname_dbconn;
$conn = mysql_connect($hostname_dbconn, $username_dbconn, $password_dbconn) or die(mysql_error());

mysql_select_db($rating_dbname,$conn) or die(mysql_error()); 

error_reporting("E_ERROR");

session_start();
$_SESSION["sessid"] = session_id();

include("settings.inc.php");

include("site_functions.php");

#$site_offline=1;
?>