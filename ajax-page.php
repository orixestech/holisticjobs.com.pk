<?php include("admin/includes/conn.php"); include("site_theme_functions.php"); $SessionID = $_SESSION["sessid"]; // print_r( $_REQUEST );
$SessionUserID = 999;
if( $_SESSION["UserLogged"] == 1 && $_SESSION["UserID"] > 0 ){
	$SessionUserID = $_SESSION["UserID"];
} 

if($_REQUEST['type']=='Subscribe'){
	$data = array();
	$data['email_type'] = 'Subscribe';
	$data['email_address'] = $_REQUEST['email'];
	$insert_id = FormData('email_bank', 'insert', $data, "", $view=false );
	echo '<div class="alert alert-success">Thanks for Subscribe</div>';
}

if($_REQUEST['type']=='EmailDuplicateCheck'){
	$error = 1;
	$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
	
	if (preg_match($pattern, $_REQUEST['email']) === 1) {
		if($_REQUEST['usertype'] == 'employer'){
			$total = total("SELECT `UID` FROM `employer` WHERE `EmployerEmail` = '".strtolower($_REQUEST['email'])."'");
			if($total==0){
				$msg = "";
				$error = 0;
			} else {
				$msg = "Account for this email has already been created.";
				$error = 1;
			}
		}
		
		if($_REQUEST['usertype'] == 'employee'){
			$total = total("SELECT `UID` FROM `employee` WHERE `EmployeeEmail` = '".strtolower($_REQUEST['email'])."'");
			if($total==0){
				$msg = "";
				$error = 0;
			} else {
				$msg = "Account for this email has already been created.";
				$error = 1;
			}
		}
	} else {
		$msg = "Invalid Email Address";
		$error = 1;
	}
	
	if($error==1){
		echo '<div class="notification error closeable" style="margin-bottom:0px;padding:13px;"><p><span>Error!</span> '.$msg.'</p><a href="#" class="close"></a></div>';
	} else {
		echo '<div class="closeable" style="margin-bottom:0px;padding:13px;"><p><span></span> '.$msg.'</p><a href="#" class="close"></a></div>';		
	}
	echo '<input type="hidden" id="error" value="'.$error.'">';
}
























?>
