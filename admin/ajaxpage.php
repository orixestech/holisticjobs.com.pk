<?php
include("includes/conn.php");
include("admin_theme_functions.php");
$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];
//print_r($_REQUEST);

if($_REQUEST["action"]=='delete'){
	$pageTitle = GetData('title','videos','`video_id`',$_REQUEST["vid"]);
	mysql_query(" DELETE FROM  `videos` WHERE video_id = '".$_REQUEST["vid"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Video [ '.$pageTitle.' ] Deleted...!');
		echo $message = Alert('success', 'Video [ '.$pageTitle.' ] Deleted...!');
	}
}

if($_REQUEST["action"]=='deleteDM'){
	$pageTitle = GetData('title','dailymotion','`id`',$_REQUEST["vid"]);
	mysql_query(" DELETE FROM  `dailymotion` WHERE `id` = '".$_REQUEST["vid"]."' ");
	$num = mysql_affected_rows();
	if($num){
		//Track('Video [ '.$pageTitle.' ] Deleted...!');
		echo $message = Alert('success', 'Daily Motion Video [ '.$pageTitle.' ] Deleted...!');
	}
}

if($_REQUEST["action"]=='AddSubscription'){
	$Subscription = $_REQUEST;
	$Subscription['PlanStatus'] = 'Normal';
	$transactions_id = FormData('subscriptions', 'insert', $Subscription, "", $view=false );
	echo $message = Alert('success', 'New Subscription Plan has been Added ...!');
}

if($_REQUEST["action"]=='EditSubscription'){
	$Subscription = $_REQUEST;
	$transactions_id = FormData('subscriptions', 'update', $Subscription, " UID = '".$_REQUEST["uid"]."' ", $view=false );
	echo $message = Alert('success', 'Subscription Plan has been Updated ...!');
}

if($_REQUEST["action"]=='ManageSubAccess'){
	
	query("DELETE FROM `subscription_access` WHERE AccessSubID = '".$_REQUEST["uid"]."' ");
	$stmt = query(" SELECT * FROM subscription_accesstype WHERE AccessTypeModule = '".$_REQUEST["Module"]."' ORDER BY AccessTypeTitle ");
	while($rslt = fetch($stmt)){
		if( $_REQUEST["AccessTypeKey".$rslt['UID']] ){
			$insert = array();
			$insert['AccessSubID'] = $_REQUEST["uid"];
			$insert['AccessTypeKey'] = $_REQUEST["AccessTypeKey".$rslt['UID']];
			$insert['AccessAllowed'] = $_REQUEST["Access".$rslt['UID']];
			$insert['AccessDays'] = $_REQUEST["AccessDays".$rslt['UID']];
			$insert['AccessModule'] = $_REQUEST["Module"];
			$SA_id = FormData('subscription_access', 'insert', $insert, "", $view=false );
		}
	}
	echo $message = Alert('success', 'Subscription Access has been Updated ...!');
}

if($_REQUEST["action"]=='SendLoginDetails'){
	if($_REQUEST['type']=='employer'){
		$stmt = query("SELECT * FROM `employer` WHERE `UID` = '".$_REQUEST['uid']."' ");
		$rslt = mysql_fetch_array($stmt);
		$unique_key = substr(md5(rand(0, 1000000)), 10, 5);
		$query = "UPDATE `employer` SET EmployerPassword = '".PassWord($unique_key,'hide')."' WHERE `employer`.`UID` = '".$rslt['UID']."'; ";
		query($query);
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear '.$rslt['EmployerCompany'].'! </strong><br ><br >
			A new password has been created for you:<br >
			<strong>Login Email: </strong> '.$rslt['EmployerEmail'].' <br >
			<strong>Password: </strong> '.$unique_key.' <br ><br >
			You will be able to change your password once you log in.
			<br><br>
			Regards,<br><strong>Team '.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
			</p>
		</td></tr></table> ';
		$subject = "Holistic Jobs Login Details";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array($rslt['EmployerEmail']=>$rslt['EmployerCompany']);
		$body = SendMail($data, $subject, $message, $show=false);
		
		echo '<font color="red" size="3">Password changed successfully and email sent to corporate</font>';
		
	}
	
	if($_REQUEST['type']=='employee'){
		$stmt = query("SELECT * FROM `employee` WHERE `UID` = '".$_REQUEST['uid']."' ");
		$rslt = mysql_fetch_array($stmt);
		$unique_key = substr(md5(rand(0, 1000000)), 10, 5);
		$query = "UPDATE `employee` SET EmployeePassword = '".PassWord($unique_key,'hide')."' WHERE `employee`.`UID` = '".$rslt['UID']."'; ";
		query($query);
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear '.$rslt['EmployeeName'].'! </strong><br ><br >
			A new password has been created for you:<br >
			<strong>Login Email: </strong> '.$rslt['EmployeeEmail'].' <br >
			<strong>Password: </strong> '.$unique_key.' <br ><br >
			You will be able to change your password once you log in.
			<br><br>
			Regards,<br><strong>Team '.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
			</p>
		</td></tr></table> ';
		$subject = "Holistic Jobs Login Details";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array($rslt['EmployeeEmail']=>$rslt['EmployeeName']);
		$body = SendMail($data, $subject, $message, $show=false);
		
		echo '<font color="red" size="3">Password changed successfully and email sent to employee</font>';
		
	}
}

if($_REQUEST["action"]=='EditAccessType'){
	$Subscription = $_REQUEST;
	$transactions_id = FormData('subscription_accesstype', 'update', $Subscription, " UID = '".$_REQUEST["uid"]."' ", $view=false );
	echo $message = Alert('success', 'Access Type has been Updated ...!');
}

if($_REQUEST["action"]=='changesubs'){
	$stmt = query("SELECT * FROM `subscriptions` WHERE `UID` = '".$_REQUEST['plan']."' ");
	$plan = fetch($stmt);
	if($plan['UID']>0){
		if($_REQUEST['module']=='employee'){
			$stmt = query("SELECT * FROM `employee` WHERE `UID` = '".$_REQUEST['uid']."' ");
			$employee = fetch($stmt);
			
			$ExpireDate = $employee['EmployeeSubscriptionExpire'];
			if($ExpireDate=='0000-00-00') { $ExpireDate = date("Y-m-d"); }
			$ExpireDate = date("Y-m-d");
			
			$NewExpireDate = date("Y-m-d", strtotime("+".$plan['PlanDays']." days", strtotime($ExpireDate)) );
			
			query("UPDATE `employee` SET `EmployeeSubscription` = '".$_REQUEST['plan']."', `EmployeeSubscriptionExpire` = '".$NewExpireDate."' WHERE `employee`.`UID` = '".$_REQUEST['uid']."' ; ");
			echo $message = Alert('success', 'Employee Subscription Update ...!');
			echo "<strong>New Expire Date will be ".date("d M, y", strtotime($NewExpireDate))."</strong>";
		}
	}
	
}

if($_REQUEST["action"]=='AddDiscount'){
	$transactions_id = FormData('subscription_discount', 'insert', $_REQUEST, "", $view=false );
	echo $message = Alert('success', 'Discount Successfully Inserted ...!');
}
