<?php
include("../admin/includes/conn.php");
include("../admin/admin_theme_functions.php");
include("../site_theme_functions.php");

$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];
//print_r($_REQUEST);

if($_REQUEST["action"]=='JobApplicationStatus'){
	$JOBID = GetData('JobID','jobs_apply','UID',$_REQUEST["UID"]);
	$EmployeeID = GetData('EmployeeID','jobs_apply','UID',$_REQUEST["UID"]);
	mysql_query(" UPDATE `jobs_apply` SET ApplicationStatus = '".$_REQUEST["Status"]."' WHERE `UID` = '".$_REQUEST["UID"]."' ");
	
	if( $_REQUEST["Status"] == "Ignored"){
		$EmployeeName = GetData('EmployeeName','employee','UID',$EmployeeID);
		$EmployeeEmail = GetData('EmployeeEmail','employee','UID',$EmployeeID);
		$JobTitle = GetData('JobTitle','jobs','UID',$JOBID);
		$JobEmployerID = GetData('JobEmployerID','jobs','UID',$JOBID);
		$EmployerCompany = GetData('EmployerCompany','employer','UID',$JobEmployerID);
		
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<strong>Dear '.$EmployeeName.',</strong><br ><br >
				<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; We regret to inform you that you have been rejected for the job of "'.$JobTitle.'" by "'.$EmployerCompany.'".<br />
				There are many more job opportunities on Holistic Jobs that may match your interest.<br /><br />
				To visit, click here:<br />
				<a href="http://www.holisticjobs.com.pk/jobs/list">http://www.holisticjobs.com.pk/jobs/list</a></p>
				<p>We wish you all the best for your job hunt.</p><br /><br />
				<p>Regards,<br />
				<strong>Team Holistic Jobs</strong>	</p>
		</td></tr></table> ';
		$subject = "Application Status :: Holistic Jobs";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $EmployeeEmail => $EmployeeName );
		$body = SendMail($data, $subject, $message, $show=false);
	}
	
	$JobTitle = GetData('JobTitle','jobs','UID',$JOBID);
	$JobEmployerID = GetData('JobEmployerID','jobs','UID',$JOBID);
	$EmployerCompany = GetData('EmployerCompany','employer','UID',$JobEmployerID);
	
	Track(ucfirst('Your application for the job of "'.$JobTitle.'" at "'.$EmployerCompany.'" has been ignored'), 'employee', $EmployeeID);
	
	$num = mysql_affected_rows();
	if($num){?>
        <thead>
          <tr>
            <th>Applicant Details</th>
            <th width="13%">Date</th>
            <th width="9%">Resume</th>
            <th width="10%" class="hide">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
              $stmt = mysql_query(" SELECT * FROM jobs_apply WHERE ApplicationStatus = 'Ignored' and  `JobID` = '".$JOBID."' ");
              while( $application = mysql_fetch_array($stmt) ){ 
                $EmpStmt = mysql_query(" SELECT * FROM employee WHERE `UID` = '".$application['EmployeeID']."' ");
                $Employee = mysql_fetch_array($EmpStmt);?>
                <tr id="row-<?=$application['UID']?>">
                  <td><? #print_r($Employee);?>
            Name:
            <?=optionVal($Employee['EmployeeTitle'])?>
            &nbsp;
            <?=$Employee['EmployeeName']?>
            <br />
            City:
            <?=$application['City']?></td>
                  <td><?=date("d M, Y \n h:i A", strtotime($application['SystemDate']))?></td>
                  <td><a href="#" role="button" class="btn btn-success btn-xs green">Resume </a></td>
                  <td class="hide"><div class="btn-group">
                      <button data-toggle="dropdown" class="btn btn-xs btn-<?=($application['ApplicationStatus']=='Featured')?'success':'primary'?> dropdown-toggle">
                      <?=($application['ApplicationStatus']=='New')?'Action Awaited':$application['ApplicationStatus']?>
                      <i class="icon-angle-down icon-on-right"></i> </button>
                      <ul class="dropdown-menu">
                        <li><a href="#EmailContent" data-toggle="modal" data-uid="<?=$rslt["UID"]?>" title="">Shortlist</a></li>
                      </ul>
                    </div>
                    <?=($application['ApplicationStatus']=='Shortlisted')?'<br /><br /><a href="#EmailContent" role="button" data-toggle="modal" class="btn btn-primary btn-sm ">Schedule Interview</a>':''?></td>
                </tr>
                <?php
              } ?>
        </tbody>
<?php
    }
}

if($_REQUEST["action"]=='ShortListCandidate'){
	
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">'.$_REQUEST['EmailContent'].'</p><br />
	</td></tr></table> ';
	$subject = "Shortlisted for Job:: Holistic Jobs";
	$data = array();
	$data['From'] = 'info@holisticjobs.com.pk';
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $_REQUEST['EmployeeEmail'] => $_REQUEST['EmployeeName'] );
	$body = SendMail($data, $subject, $message, $show=false);
	
	query("UPDATE `jobs_apply` SET `ApplicationStatus` = 'Shortlisted' WHERE `jobs_apply`.`UID` = '".$_REQUEST['applicationID']."'; ");
	
	echo Alert('success', 'Shortlisting email has been successfully sent...!');
	
	$JOBID = GetData('JobID','jobs_apply','UID',$_REQUEST["applicationID"]);
	$EmployeeID = GetData('EmployeeID','jobs_apply','UID',$_REQUEST["applicationID"]);
	$JobTitle = GetData('JobTitle','jobs','UID',$JOBID);
	$JobEmployerID = GetData('JobEmployerID','jobs','UID',$JOBID);
	$EmployerCompany = GetData('EmployerCompany','employer','UID',$JobEmployerID);
	
	Track(ucfirst('Your Application for the job of "'.$JobTitle.'" at "'.$EmployerCompany.'" has been shortlisted for interview. Please approve <a href="'.$path.'employee/applied-jobs.php">here</a>.'), 'employee', $EmployeeID);
}

if($_REQUEST["action"]=='SchedulingInterview'){
	
	$_REQUEST['EmailContent'] = str_replace('||DATE||',date("l jS F, Y", strtotime($_REQUEST['InterviewDate'])),$_REQUEST['EmailContent']);
	$_REQUEST['EmailContent'] = str_replace('||VENUE||',$_REQUEST['InterviewVenue'],$_REQUEST['EmailContent']);
	$_REQUEST['EmailContent'] = str_replace('||CITY||',optionVal($_REQUEST['InterviewCity']),$_REQUEST['EmailContent']);
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">'.$_REQUEST['EmailContent'].'</p>
	</td></tr></table> ';
	$subject = "Interview Scheduled :: Holistic Jobs";
	
	$data = array();
	$data['From'] = 'info@holisticjobs.com.pk';
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $_REQUEST['EmployeeEmail'] => $_REQUEST['EmployeeName'] );
	$body = SendMail($data, $subject, $message, $show=false);
	
	query("UPDATE `jobs_apply` SET InterviewDate = '".date("Y-m-d", strtotime($_REQUEST['InterviewDate']))."', InterviewVenue = '".$_REQUEST['InterviewVenue']."', InterviewCity = '".$_REQUEST['InterviewCity']."', `ApplicationStatus` = 'Interview-Schedule' WHERE `jobs_apply`.`UID` = '".$_REQUEST['applicationID']."'; ");
	
	echo Alert('success', 'Interview scheduling email has been successfully sent to the applicant.');
	
	$JOBID = GetData('JobID','jobs_apply','UID',$_REQUEST["applicationID"]);
	$EmployeeID = GetData('EmployeeID','jobs_apply','UID',$_REQUEST["applicationID"]);
	$JobTitle = GetData('JobTitle','jobs','UID',$JOBID);
	$JobEmployerID = GetData('JobEmployerID','jobs','UID',$JOBID);
	$EmployerCompany = GetData('EmployerCompany','employer','UID',$JobEmployerID);
	
	Track(ucfirst('Your Interview for the job of "'.$JobTitle.'" at "'.$EmployerCompany.'" has been scheduled on "'.$_REQUEST['InterviewDate'].'" at "'.$_REQUEST['InterviewVenue'].'","'.optionVal($_REQUEST['InterviewCity']).'"'), 'employee', $EmployeeID);
}

if($_REQUEST["action"]=='LoadHelpDesk'){ $cid = $_REQUEST['cid'];
	if($cid>0){?>
		<div class="widget-header header-color-blue2">
		  <h4 class="lighter smaller"><?=GetData('title','category','id',$cid)?> Questions</h4>
		</div>
		<div class="widget-body">
		  <div class="widget-main padding-8">
			<div id="help-desk" class="panel-group accordion-style1 accordion-style1"><?php
			$stmt = query( " SELECT * FROM `user_manual` WHERE `ManualCategory` in ( SELECT `id` FROM `category` WHERE `id` = '".$cid."' ) " );
			while( $rslt = fetch($stmt) ){ ?>
				<div class="panel panel-default">
				  <div class="panel-heading"> <a href="#help-<?=$rslt['UID']?>" data-parent="#help-desk" data-toggle="collapse" class="accordion-toggle collapsed"> <i class="icon-chevron-right smaller-80" data-icon-hide="icon-chevron-down align-top" data-icon-show="icon-chevron-right"></i> &nbsp; <?=$rslt['ManualQuestion']?> </a> </div>
				  <div class="panel-collapse collapse" id="help-<?=$rslt['UID']?>">
					<div class="panel-body "> <?=$rslt['ManualAnswer']?> </div>
				  </div>
				</div>
			<?php } ?>
			</div>
		  </div>
		</div><?php
	}
}

if($_REQUEST["action"]=='EmployeeInvitation'){
	$EmployerCompany = GetData('EmployerCompany','employer','UID',$_SESSION['EmployerUID']);
	
	$_REQUEST['EmailContent'] = str_replace('||COMPNAME||', $EmployerCompany,$_REQUEST['EmailContent']);
	
	if( $_REQUEST['InviteTitle']=='Open'){
		$_REQUEST['JobCity'] = $_REQUEST['AllCity'];
		$_REQUEST['EmailContent'] = str_replace('||CITY||', optionVal($_REQUEST['AllCity']),$_REQUEST['EmailContent']);
		$_REQUEST['EmailContent'] = str_replace('||DESIGNATION||', optionVal($_REQUEST['JobDesignation']),$_REQUEST['EmailContent']);
		$_REQUEST['JobTitle'] = 0;
	} else {
		$_REQUEST['EmailContent'] = str_replace('||CITY||', optionVal($_REQUEST['JobCity']),$_REQUEST['EmailContent']);
		
		$JobTitle = GetData('JobTitle', 'jobs', 'UID', $_REQUEST["JobTitle"]);
		$_REQUEST['JobDesignation'] = GetData('JobDesignation', 'jobs', 'UID', $_REQUEST["JobTitle"]);
		$_REQUEST['EmailContent'] = str_replace('||DESIGNATION||', $JobTitle,$_REQUEST['EmailContent']);
	}
	
	$EmployeeName = GetData('EmployeeName','employee','UID',$_REQUEST["EmployeeUID"]);
	$EmployeeEmail = GetData('EmployeeEmail','employee','UID',$_REQUEST["EmployeeUID"]);
	
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">'.$_REQUEST['EmailContent'].'</p>
		<p><br />Regards</p><br /><p><strong>Team Holistic Jobs </strong></p>
	</td></tr></table> ';
	$subject = $_REQUEST['email_head'] . " :: Holistic Jobs";
	$data = array();
	$data['From'] = 'info@holisticjobs.com.pk';
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $EmployeeEmail => $EmployeeName );
	$body = SendMail($data, $subject, $message, $show=false);
	//print_r($data);
	
	$sql = " INSERT INTO `jobs_invitations` (`UID`, `SystemDate`, `EmployerUID`, `EmployeeUID`, `JobUID`, `Designation`, `City`, `InvitationStatus`) VALUES (NULL, 	CURRENT_TIMESTAMP , '".$_SESSION['EmployerUID']."', '".$_REQUEST['EmployeeUID']."', '".$_REQUEST['JobTitle']."', '".$_REQUEST['JobDesignation']."', '".$_REQUEST['JobCity']."', 'Approval Awaited'); ";
	
	query($sql);
	echo Alert('success', 'Invitation email has been successfully sent...!');
	Track(ucfirst('New Invitation Received, Please check your Invitations.'), 'employee', $_REQUEST['EmployeeUID']);
	
}

if($_REQUEST["action"]=='JobInvitationStatus'){
	query("UPDATE `jobs_invitations` SET InvitationStatus = 'Ignored' WHERE `jobs_invitations`.`UID` = '".$_REQUEST['UID']."' ; ");
	
	$Employee = GetData('EmployeeUID','jobs_invitations','UID',$_REQUEST['UID']);
	
	$EmployeeName = GetData('EmployeeName','employee','UID',$Employee);
	$EmployeeEmail = GetData('EmployeeEmail','employee','UID',$Employee);
	
	$Designation = GetData('Designation','jobs_invitations','UID',$_REQUEST['UID']);
	
	$JOBID = GetData('JobUID','jobs_invitations','UID',$_REQUEST['UID']);
	( $JOBID > 0 ) ? $JobTitle = GetData('JobTitle','jobs','UID',$JOBID) : $JobTitle = "Open Invitation" ;
	
	$JobEmployerID = GetData('EmployerUID','jobs_invitations','UID',$_REQUEST['UID']);
	$EmployerCompany = GetData('EmployerCompany','employer','UID',$JobEmployerID);

	
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<strong>Dear '.$EmployeeName.',</strong><br /><br />
			<p>We regret to inform you that you have not been selected for the job of <strong>"'.optionVal($Designation).'"</strong> by <strong>"'.$EmployerCompany.'"</strong>.<br />
			There are many more job opportunities on Holistic Jobs that may match your interest.<br />
			To visit, <a href="http://www.holisticjobs.com.pk/jobs/list">click here:</a><br />
			We wish you all the best for your job hunt.<br /></p><br />
			<p>Regards,<br />
			<strong>Team Holistic Jobs</strong>
		</p>
	</td></tr></table> ';
	$subject = "Invitation Status :: Holistic Jobs";
	$data = array();
	$data['From'] = 'info@holisticjobs.com.pk';
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $EmployeeEmail => $EmployeeName );
	$body = SendMail($data, $subject, $message, $show=false);
	Track(ucfirst('New Invitation Received, Please check your Invitations.'), 'employee', $Employee);
	
}

if($_REQUEST["action"]=='InvitationShortlistCandidate'){
	
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">'.$_REQUEST['EmailContent'].'</p>
	</td></tr></table> ';
	$subject = "Shortlisted for Job:: Holistic Jobs";
	$data = array();
	$data['From'] = 'info@holisticjobs.com.pk';
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $_REQUEST['EmployeeEmail'] => $_REQUEST['EmployeeName'] );
	$body = SendMail($data, $subject, $message, $show=false);
	
	query("UPDATE `jobs_invitations` SET InvitationStatus = 'Shortlisted' WHERE `jobs_invitations`.`UID` = '".$_REQUEST['applicationID']."' ; ");

	$Designation = GetData('Designation','jobs_invitations','UID',$_REQUEST['applicationID']);
	$EmployeeUID = GetData('EmployeeUID','jobs_invitations','UID',$_REQUEST['applicationID']);
	$EmployerUID = GetData('EmployerUID','jobs_invitations','UID',$_REQUEST['applicationID']);
	$EmployerCompany = GetData('EmployerCompany','employer','UID',$EmployerUID);
	
	echo Alert('success', 'Invitation Shortlisting email has been successfully sent...!');
	Track(ucfirst('You have been shortlisted for the job of "'.optionVal($Designation).'" at "'.$EmployerCompany.'", Please check your Invitations.'), 'employee', $EmployeeUID);
}

if($_REQUEST["action"]=='InvitationScheduleInterview'){
	
	$_REQUEST['EmailContent'] = str_replace('||DATE||',date("l jS F, Y", strtotime($_REQUEST['InterviewDate'])),$_REQUEST['EmailContent']);
	$_REQUEST['EmailContent'] = str_replace('||VENUE||',$_REQUEST['InterviewVenue'],$_REQUEST['EmailContent']);
	$_REQUEST['EmailContent'] = str_replace('||CITY||',optionVal($_REQUEST['InterviewCity']),$_REQUEST['EmailContent']);
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">'.$_REQUEST['EmailContent'].'</p>
	</td></tr></table> ';
	$subject = "Intimation of Interview Scheduled :: Holistic Jobs";
	
	$data = array();
	$data['From'] = 'info@holisticjobs.com.pk';
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $_REQUEST['EmployeeEmail'] => $_REQUEST['EmployeeName'] );
	$body = SendMail($data, $subject, $message, $show=false);
	
	query("UPDATE `jobs_invitations` SET InvitationStatus = 'Interview Scheduled', InterviewDate = '".date("Y-m-d", strtotime($_REQUEST['InterviewDate']))."', InterviewVenue = '".$_REQUEST['InterviewVenue']."', InterviewCity = '".$_REQUEST['InterviewCity']."' WHERE `jobs_invitations`.`UID` = '".$_REQUEST['applicationID']."'; ");
	
	echo Alert('success', 'Interview scheduling email has been successfully sent.');

	$Designation = GetData('Designation','jobs_invitations','UID',$_REQUEST['applicationID']);
	$EmployeeUID = GetData('EmployeeUID','jobs_invitations','UID',$_REQUEST['applicationID']);
	$EmployerUID = GetData('EmployerUID','jobs_invitations','UID',$_REQUEST['applicationID']);
	$EmployerCompany = GetData('EmployerCompany','employer','UID',$EmployerUID);
	Track(ucfirst('Congratulations!. Your interview for the "'.optionVal($Designation).'" at "'.$EmployerCompany.'" have been Scheduled on "'.date("l jS F, Y", strtotime($_REQUEST['InterviewDate'])).'", Please check your Invitation Interviews.'), 'employee', $EmployeeUID);
}

if($_REQUEST["action"]=='AddReminder'){
	//print_r($_REQUEST);
	$reminder = array();
	$reminder['ReminderAppID'] = $_REQUEST["ModuleID"];
	$reminder['ReminderModule'] = $_REQUEST["Module"];
	$reminder['ReminderDate'] = date("Y-m-d", strtotime($_REQUEST["ReminderDate"]) );
	$reminder['ReminderSubject'] = $_REQUEST["ReminderSubject"];
	$reminder['ReminderEmail'] = $_REQUEST["ReminderEmail"];
	$reminder['ReminderStatus'] = 'Queue';
	
	$result = FormData('jobs_reminders', 'insert', $reminder, " ", $view=false );
	echo Alert('success', "Reminder Successfully Inserted.");
}

if($_REQUEST["action"]=='LoadReminder'){
	
	$stmt = query("SELECT * FROM `jobs_reminders` WHERE UID = '".$_REQUEST["UID"]."' ");
	$rslt = fetch($stmt);

	//$rslt['ReminderDate'] = date("m/d/Y", strtotime($rslt['ReminderDate']));
	echo json_encode($rslt);	
}

if($_REQUEST["action"]=='EditReminder'){
	//print_r($_REQUEST);
	$reminder = array();
	$reminder['ReminderDate'] = date("Y-m-d", strtotime($_REQUEST["ReminderDate"]) );
	$reminder['ReminderSubject'] = $_REQUEST["ReminderSubject"];
	$reminder['ReminderEmail'] = $_REQUEST["ReminderEmail"];
	
	$result = FormData('jobs_reminders', 'update', $reminder, " UID = '".$_REQUEST['UID']."' ", $view=false );
	echo Alert('success', "Reminder Successfully Updated.");	
}

if($_REQUEST["action"]=='LoadJobIDCity'){

	if($_REQUEST["jobid"] > 0){
		$JobCity = JobExtra($_REQUEST["jobid"], 'JobCity', 'array');
		foreach($JobCity as $val){
		  if($val=='Multiple Cities'){
			  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'city') order by OptionName");
				while( $rslt = mysql_fetch_array($qry) ){
					echo '<option value="'.$rslt["UID"].'">'.$rslt["OptionDesc"].'</option>';
				}
		  } else {
			  $UID = query(" SELECT `OptionId` FROM `optiondata` WHERE `OptionDesc` = '".$val."'  ");
			  $UID = fetch( $UID );
			  echo '<option value="'.$UID[0].'">'.$val.'</option>'; 
		  }
		}
	} else {
		echo formListOpt('city',0);
	}
	
}







?>