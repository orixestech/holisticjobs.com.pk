<?php
include("../admin/includes/conn.php");
include("../admin/admin_theme_functions.php");
///////// Front Side Functions
include("../site_theme_functions.php");

$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];
//print_r($_REQUEST);

/* ---------- Start ------------------------ Employee Before Exipire Code  ------------------------------------------ */
$date = date("Y-m-d");
$stmt = " SELECT employee.*, DATEDIFF( `EmployeeSubscriptionExpire`, '$date' ) as expiredays FROM `employee` having DATEDIFF( `EmployeeSubscriptionExpire`, '$date' ) < 14 ORDER BY UID";
$stmt = query($stmt);
while($employee = fetch($stmt)){
	$expiredays = $employee['expiredays'];
	$SubName = GetData('PlanTitle','subscriptions','UID',$employee['EmployeeSubscription']);
	
	if($expiredays == 7 || $expiredays == 2){
		
		$subject = "Subscription Expiry :: Holistic Jobs";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $employee['EmployeeEmail'] => optionVal($employee['EmployeeTitle']) . " &nbsp;" . $employee['EmployeeName'] );
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear '.optionVal($employee['EmployeeTitle']) . "&nbsp;" . $employee['EmployeeName'].', </strong><br ><br >
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Your subscription plan is going to expire within <strong>'.$expiredays.'</strong> days. <br /><br />
			The details for your expiring plan are:<br />
			Subscription Plan: <strong>'.$SubName.'</strong><br />
			Expiry Date: <strong>'.date("l, d M, Y", strtotime($employee['EmployeeSubscriptionExpire'])).'</strong><br /><br />
			Please renew your subscription after "'.date("l, d M, Y", strtotime($employee['EmployeeSubscriptionExpire'])).'" to keep enjoying Holistic Jobs services.<br><br>';
		$message .= '
			<br>
			<p class="mainText">Regards,<br><strong>Team '.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. </p><br >
			</p>
		</td></tr></table> ';
		
		$body = SendMail($data, $subject, $message, $show=false);
		if($body)
			echo $employee['EmployeeName'] . " | " . $employee['EmployeeEmail'] . " | Expire Date : " . $employee['EmployeeSubscriptionExpire'] . " | Expire Days : " . $employee['expiredays'] ." \n\n ";
		//echo optionVal($employee['EmployeeTitle']) . " &nbsp;" . $employee['EmployeeName'] . " : " . $employee['EmployeeEmail'] . "<br>" ;
		
	}
	
	if($expiredays == 0){
		
		$subject = "Subscription Expired :: Holistic Jobs";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $employee['EmployeeEmail'] => optionVal($employee['EmployeeTitle']) . " &nbsp;" . $employee['EmployeeName'] );
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear '.optionVal($employee['EmployeeTitle']) . "&nbsp;" . $employee['EmployeeName'].', </strong><br ><br >
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Your subscription plan has been <strong>Expired</strong>. <br /><br />
			The details for your expiring plan are:<br />
			Subscription Plan: <strong>'.$SubName.'</strong><br />
			Expiry Date: <strong>'.date("l, d M, Y", strtotime($employee['EmployeeSubscriptionExpire'])).'</strong><br /><br />
			Please renew your subscription  to keep enjoying Holistic Jobs services.<br><br>';
		$message .= '
			<br>
			<p class="mainText">Regards,<br><strong>Team '.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. </p><br >
			</p>
		</td></tr></table> ';
		
		$body = SendMail($data, $subject, $message, $show=false);
		if($body)
			echo $employee['EmployeeName'] . " | " . $employee['EmployeeEmail'] . " | Expire Date : " . $employee['EmployeeSubscriptionExpire'] . " | Expire Days : " . $employee['expiredays'] ." \n\n ";
		//echo optionVal($employee['EmployeeTitle']) . " &nbsp;" . $employee['EmployeeName'] . " : " . $employee['EmployeeEmail'] . "<br>" ;
		
	}

	
	
}
exit;
