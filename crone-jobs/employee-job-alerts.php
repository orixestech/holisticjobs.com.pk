<?php
include("../admin/includes/conn.php");
include("../admin/admin_theme_functions.php");
///////// Front Side Functions
include("../site_theme_functions.php");

$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];
//print_r($_REQUEST);

/* ---------- Start ------------------------ Employee Portal Jobs Alerts Module  ------------------------------------------ */

$stmt = query(" SELECT * FROM `jobs_alerts` WHERE AlertStatus = 1 ORDER BY `jobs_alerts`.`UID` DESC");
$JOBSAlerts = array();
while($alert = fetch($stmt)){
	
	if($alert['AlertCompany']>0){
		$whereSQL .= " and `JobEmployerID` = '".$alert['AlertCompany']."'";
	}
	
	if($alert['AlertDesignation']>0){
		$whereSQL .= " and `JobDesignation` = '".$alert['AlertDesignation']."'";
	}

	if($alert['AlertArea']>0){
		$City = optionVal($alert['AlertArea']);
		$whereSQL .= " and UID in ( SELECT JobID FROM `jobs_extra` WHERE `InfoType` = 'JobCity' and `InfoTypeValue` = '".$City."' )";
	}
	
	$sql = "SELECT UID FROM `jobs` WHERE JobLastDateApply >= '".date("Y-m-d")."' and JobStatus = 'Publish' and UID not in ( SELECT `LogJobID` FROM `jobs_alerts_log` WHERE `LogEmployeeID` = '".$alert['AlertEmployeeUID']."' ) " . $whereSQL;
	$stmt1 = query($sql);
	while($job = fetch( $stmt1 )){
		$JOBSAlerts[$alert['AlertEmployeeUID']][] = $job['UID']; 
	}
	$printSQL .= $sql; 
	
}

foreach($JOBSAlerts as $EMP=>$jobs){

	$stmt = query("SELECT UID, `EmployeeTitle`,`EmployeeName`,`EmployeeEmail` FROM `employee` WHERE `UID` = '".$EMP."' ");
	$employee = fetch($stmt);
	
	/* -----------------------------    EMAIL CONTENT    -----------------------*/
	$subject = "Jobs Alert :: Holistic Jobs";
	$data = array();
	$data['From'] = 'info@holisticjobs.com.pk';
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $employee['EmployeeEmail'] => optionVal($employee['EmployeeTitle']) . " &nbsp;" . $employee['EmployeeName'] );
	$sql = "SELECT * FROM `jobs` WHERE `UID` in ( ".implode(", ",$jobs)." )";
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">
		<strong>Dear '.optionVal($employee['EmployeeTitle']) . "&nbsp;" . $employee['EmployeeName'].', </strong><br ><br >
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '.( (total($sql) > 1)?'Please check below jobs that are relevant to your area of interest.':'Please check below job that is relevant to your area of interest.' ).'<br><br>';
	$stmt = query($sql);
	while($job = fetch($stmt)){
		$message .='<p class="mainText">
		<strong>Job Title : </strong>'.$job['JobTitle'].'<br >
		<strong>Company : </strong>'.GetEmployer('EmployerCompany', $job['JobEmployerID']).'<br >
		<strong>Deadline : </strong>'.date("d M, Y", strtotime($job['JobLastDateApply'])).'<br >
		<strong>To view the vacancy details, click the <a href="'.JobLink($job['UID']).'">URL</a> </strong><br >
		<hr></p>';
		$alertlog = "INSERT INTO `jobs_alerts_log` (`UID`, `SystemDate`, `LogEmployeeID`, `LogJobID`) VALUES (NULL, NOW(), '".$employee['UID']."', '".$job['UID']."'); ";
		query($alertlog);
	}
	
	$message .= '
		<br><br><p class="mainText"><strong>Note:</strong> To change your area, designation or company of interest, edit the job alert section in your portal.<br>
		To login click <a href="'.$path.'page/sign-in">here</a>. </p>

		<br>
		<p class="mainText">Regards,<br><strong>Team '.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. </p><br >
		</p>
		<p class="mainText">Note: This is an auto generated email. Please do not reply to this email.<br>
		If you have received this message in error, please notify feedback@holisticjobs.com.pk immediately.</p>		<br >

	</td></tr></table> ';
	$body = SendMail($data, $subject, $message, $show=false);
	echo optionVal($employee['EmployeeTitle']) . " &nbsp;" . $employee['EmployeeName'] . " : " . $employee['EmployeeEmail'] . "<br>" ;
	
}

echo "
All alerts are Process Successfully...!";






/* ---------- END -------------------------- Employee Portal Jobs Alerts Module  ------------------------------------------ */