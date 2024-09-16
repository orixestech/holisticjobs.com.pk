<?php 
$accessLevel = array(
				'user'=> array('view_users.php', 'add_users.php', 'edit_users.php'),
);

$pageNotCheck = array('access_denied','index.php', 'welcome');

if(!in_array($page, $accessLevel[$_SESSION['User']['user_access']]) && $_SESSION['UserAccess']!='admin' && !in_array($page,$pageNotCheck)){
	//echo "<meta http-equiv='refresh' content='0;url=access_denied'>";exit;
}
	
?>