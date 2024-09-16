<?php
include("../admin/includes/conn.php");
include("../admin/admin_theme_functions.php");
///////// Front Side Functions
include("../site_theme_functions.php");
/* ---------- Start ------------------------ Interview Reminder Code  ------------------------------------------ */
$date = date("Y-m-d"); //'2016-06-04'; 


$stmt = " SELECT * FROM `jobs_reminders` WHERE `ReminderDate` <= '".$date."' and `ReminderStatus` = 'Queue'";

echo "Total (".total($stmt).") Reminders Process for Date " . date("d M, Y", strtotime($date) );

$stmt = query($stmt);
while($reminder = fetch($stmt)){
	$module = $reminder['ReminderModule'];
	
	if($module == 'invitation' ){

		$sql = "
			SELECT
			    `jobs_invitations`.`InterviewDate`
			    , `jobs_invitations`.`InterviewVenue`
			    , `jobs_invitations`.`InterviewCity`
			    , `jobs_invitations`.`EmployerUID`
				, `jobs_invitations`.`EmployeeUID`
			    , `jobs_invitations`.`JobUID`
				, `jobs_invitations`.`Designation`
			    , `employee`.`EmployeeName`
			    , `employee`.`EmployeeEmail`
			    , `jobs_invitations`.`UID`
			FROM
			    `jobs_invitations`
			    INNER JOIN `employee` ON (`jobs_invitations`.`EmployeeUID` = `employee`.`UID`)
			WHERE (`jobs_invitations`.`InterviewDate` is not NULL and `jobs_invitations`.`UID` = '".$reminder['ReminderAppID']."' );
		";
		$rslt = fetch( query( $sql ) );
		$Employer = GetData('EmployerCompany','employer','UID', $rslt['EmployerUID'] );
		if($rslt['JobID']==0){
			$JOB = optionVal($rslt['Designation']);	
		} else {
			$JOB = GetData('JobTitle','jobs','UID',$rslt['JobID']);	
		}
		
		//print_r( $rslt );
		/* -----------------------------    EMAIL CONTENT    -----------------------*/
		$subject = "Interview Reminder :: Holistic Jobs";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $rslt['EmployeeEmail'] => $rslt['EmployeeName'] );
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear '.$rslt['EmployeeName'].', </strong><br ><br >
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; With reference to the email for interview at <strong>'.$Employer.'</strong> for the post of <strong>'.$JOB.'</strong>, it is a reminder of your interview that is scheduled on: <br /><br />
			Date: <strong>'.date("d M, Y", strtotime($rslt['InterviewDate']) ).'</strong><br />
			City: <strong>'.( ($rslt['InterviewCity']==0)? 'N/A' : optionVal($rslt['InterviewCity']) ).'</strong><br />
			Venue: <strong>'.$rslt['InterviewVenue'].'</strong><br /><br />
			All the best for your interview.<br /><br />
			';
		$message .= '
			<br>
			<p class="mainText">Regrads, <br><strong>Team '.$site_name.'</strong></p><br >
			</p>
		</td></tr></table> ';
		//print_r($data);
		$body = SendMail($data, $subject, $message, $show=false);
		query(" UPDATE `jobs_reminders` SET `ReminderStatus` = 'Email Sent' WHERE `jobs_reminders`.`UID` = '".$reminder['UID']."' ; ");
		Track(ucwords("You have a new reminder. Please check your email."), 'employee', $rslt['EmployeeUID']);
		
	}
	
	if($module == 'application' ){

		$sql = "
			SELECT
			    `jobs_apply`.`InterviewDate`
			    , `jobs_apply`.`InterviewVenue`
			    , `jobs_apply`.`InterviewCity`
				, `jobs_apply`.`JobID`
				, `jobs_apply`.`EmployeeID`
			    , `employee`.`EmployeeName`
			    , `employee`.`EmployeeEmail`
			    , `jobs_apply`.`UID`
			FROM
			    `jobs_apply`
			    INNER JOIN `employee` ON (`jobs_apply`.`EmployeeID` = `employee`.`UID`)
			WHERE (`jobs_apply`.`InterviewDate` is not NULL and `jobs_apply`.`UID` = '".$reminder['ReminderAppID']."' );
		";
		$rslt = fetch( query( $sql ) );
		$Employer = GetData('EmployerCompany','employer','UID', GetData('JobEmployerID','jobs','UID',$rslt['JobID']) );
		$JOB = GetData('JobTitle','jobs','UID',$rslt['JobID']);
		//print_r( $rslt );
		$subject = "Interview Reminder :: Holistic Jobs";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $rslt['EmployeeEmail'] => $rslt['EmployeeName'] );
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear '.$rslt['EmployeeName'].', </strong><br ><br >
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; With reference to the email for interview at <strong>'.$Employer.'</strong> for the post of <strong>'.$JOB.'</strong>, it is a reminder of your interview that is scheduled on: <br /><br />
			Date: <strong>'.date("d M, Y", strtotime($rslt['InterviewDate']) ).'</strong><br />
			City: <strong>'.( ($rslt['InterviewCity']==0)? 'N/A' : optionVal($rslt['InterviewCity']) ).'</strong><br />
			Venue: <strong>'.$rslt['InterviewVenue'].'</strong><br /><br />
			All the best for you interview.<br /><br />
			';
		$message .= '
			<br>
			<p class="mainText">Regards, <br><strong>Team '.$site_name.'</strong></p><br >
			</p>
		</td></tr></table> ';
		//print_r($data);
		$body = SendMail($data, $subject, $message, $show=false);
		query(" UPDATE `jobs_reminders` SET `ReminderStatus` = 'Email Sent' WHERE `jobs_reminders`.`UID` = '".$reminder['UID']."' ; ");
		Track(ucwords("You have a new reminder. Please check your email."), 'employee', $rslt['EmployeeID']);
		
	}
	
}
exit;
