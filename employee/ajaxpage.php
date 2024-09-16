<?php
include("../admin/includes/conn.php"); 
include("../admin/admin_theme_functions.php");
///////// Front Side Functions
include("../site_theme_functions.php");

$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];
//print_r($_REQUEST);

if($_REQUEST["action"]=='JobApplicationStatus'){
	$JOBID = GetData('JobID','jobs_apply','UID',$_REQUEST["UID"]);
	query(" UPDATE `jobs_apply` SET ApplicationStatus = '".$_REQUEST["Status"]."' WHERE `UID` = '".$_REQUEST["UID"]."' ");
	$num = mysql_affected_rows();
	if($num){?>

<thead>
  <tr>
    <th>Applicant Details</th>
    <th width="13%">Date</th>
    <th width="9%">Resume</th>
    <th width="10%">Status</th>
  </tr>
</thead>
<tbody>
  <?php
	$stmt = query(" SELECT * FROM jobs_apply WHERE ApplicationStatus = 'Ignore' and  `JobID` = '".$JOBID."' ");
	while( $application = fetch($stmt) ){ 
	  $EmpStmt = query(" SELECT * FROM employee WHERE `UID` = '".$application['EmployeeID']."' ");
	  $Employee = fetch($EmpStmt);?>
  <tr id="row-<?=$application['UID']?>">
    <td><? #print_r($Employee);?>
      Name:
      <?=optionVal($Employee['EmployeeTitle'])?>
      &nbsp;
      <?=$Employee['EmployeeName']?>
      <br />
      City:
      <?=optionVal($Employee['EmployeeCity'])?></td>
    <td><?=date("d M, Y \n h:i A", strtotime($application['SystemDate']))?></td>
    <td><a href="#" role="button" class="btn btn-success btn-xs green">Resume </a></td>
    <td><div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-xs btn-<?=($application['ApplicationStatus']=='Featured')?'success':'primary'?> dropdown-toggle">
        <?=$application['ApplicationStatus']?>
        <i class="icon-angle-down icon-on-right"></i> </button>
        <ul class="dropdown-menu">
          <li><a href="#EmailContent" data-toggle="modal" data-uid="<?=$rslt["UID"]?>" title="">Shortlist</a></li>
        </ul>
      </div>
      <?=($application['ApplicationStatus']=='Shortlist')?'<br /><br /><a href="#EmailContent" role="button" data-toggle="modal" class="btn btn-primary btn-sm ">Schedule Interview</a>':''?></td>
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
		<p class="mainText">'.$_REQUEST['EmailContent'].'</p>
	</td></tr></table> ';
	$subject = "Shortlist for Job :: Holistic Jobs";
	$data = array();
	$data['From'] = 'info@holisticjobs.com.pk';
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $_REQUEST['EmployeeEmail'] => $_REQUEST['EmployeeName'] );
	$body = SendMail($data, $subject, $message, $show=false);
	
	query("UPDATE `jobs_apply` SET `ApplicationStatus` = 'Shortlisted' WHERE `jobs_apply`.`UID` = '".$_REQUEST['applicationID']."'; ");
	
	echo Alert('success', 'Shortlisted Request has been send...!');
}

if($_REQUEST["action"]=='SchedulingInterview'){
	
	$_REQUEST['EmailContent'] = str_replace('&lt;|DATE|&gt;',date("l jS F, Y", strtotime($_REQUEST['InterviewDate'])),$_REQUEST['EmailContent']);
	$_REQUEST['EmailContent'] = str_replace('&lt;|VENUE|&gt;',$_REQUEST['InterviewVenue'],$_REQUEST['EmailContent']);
	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">'.$_REQUEST['EmailContent'].'</p>
	</td></tr></table> ';
	$subject = "Interview Scheduled for ShortListed Application :: Holistic Jobs";
	
	$data = array();
	$data['From'] = 'info@holisticjobs.com.pk';
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $_REQUEST['EmployeeEmail'] => $_REQUEST['EmployeeName'] );
	$body = SendMail($data, $subject, $message, $show=false);
	
	query("UPDATE `jobs_apply` SET InterviewDate = '".date("Y-m-d", strtotime($_REQUEST['InterviewDate']))."', InterviewVenue = '".$_REQUEST['InterviewVenue']."', `ApplicationStatus` = 'Interview-Scheduled' WHERE `jobs_apply`.`UID` = '".$_REQUEST['applicationID']."'; ");
	
	echo Alert('success', 'Interview Scheduled for ShortListed Application has been send...!');
}

if($_REQUEST["action"]=='LoadEmployeeEducationForm'){
	$UID = $_REQUEST["uid"];
	$formtype = 'add';
	$EDU = '';
	if($UID==0){
		$head = 'Add Education';
		//$EDU['EducationTo'] = $EDU['EducationFrom'] = date("Y-m-d"); 
	} else {
		$head = 'Update Education';
		$stmt = query("SELECT * FROM `employee_education` WHERE `UID` = '".$UID."' ");
		$EDU = fetch($stmt);
		$formtype = 'update';
	} ?>
<h5 class="accordion-header">
  <?=$head?>
  <small class="red"> (Please enter highest education first)</small> </h5>
<input type="hidden" id="EducationFormType" value="<?=$formtype?>" />
<input type="hidden" id="EducationFormUID" value="<?=$UID?>" />
<div class="form-group">
  <label class="col-sm-4 col-md-4 col-xs-6 control-label no-padding-right" for="form-field-1">Institution:</label>
  <div class="col-sm-7 col-md-7 col-xs-8">
    <input type="text" id="EducationInstitute" name="Institute" placeholder"education institute" class="col-xs-12 col-sm-12" value="<?=$EDU['EducationInstitute']?>" />
  </div>
</div>
<div class="form-group">
  <label class="col-sm-4 col-md-4 col-xs-7 control-label no-padding-right" for="form-field-1">Qualification:<i class="icon-asterisk"></i></label>
  <div class="col-sm-7 col-md-7 col-xs-8">
    <select id="EducationQualification" name="Qualification" class="col-xs-12 col-sm-12 selectstyle validate[required]" onchange="if(this.value=='other') $('#EducationQualification_Other').removeClass('hide'); else $('#EducationQualification_Other').addClass('hide');" >
      <?=formListOpt('qualification', $EDU['EducationQualification'])?>
      <option value="other">Any Other</option>
    </select>
  </div>
</div>
<div class="form-group hide" id="EducationQualification_Other">
  <label class="col-sm-4 col-md-4 col-xs-5 control-label no-padding-right" for="form-field-1"></label>
  <div class="col-sm-7 col-md-7 col-xs-8">
    <input type="text" id="OtherQualification" name="OtherQualification" placeholder="Enter Other Option" class="col-xs-12 col-sm-12 validate[required]" value="<?=$PAGE['OtherQualification']?>" />
  </div>
</div>
<div class="form-group">
  <label class="col-sm-4 col-md-4 col-xs-4 control-label no-padding-right" for="form-field-1">From:</label>
  <div class="col-sm-3 col-md-3 col-xs-5">
    <div class="input-group">
      <input class="date-picker form-control validate[custom[date]]" value="<?=( ( empty($EDU['EducationFrom']) ) ? '' : date("Y-m-d", strtotime($EDU['EducationFrom'])) )?>"  data-date-format="yyyy-mm-dd" id="EducationFrom" name="EducationFrom" type="text" />
    </div>
  </div>
  <label for="form-field-1" class="col-sm-1 col-md-2 col-xs-1 pull-left control-label center no-padding-right" style="width: 41px; padding-left: 0px;">To:</label>
  <div class="col-sm-3 col-md-3 col-xs-5">
    <div class="input-group">
      <input class="date-picker form-control validate[custom[date]]" value="<?=( ( empty($EDU['EducationTo']) ) ? '' : date("Y-m-d", strtotime($EDU['EducationTo'])) )?>"  data-date-format="yyyy-mm-dd"  id="EducationTo" name="EducationTo" type="text" />
    </div>
  </div>
</div>
<div class="col-md-3"></div>
<div class="col-md-9"><a href="javascript:void(0);" onclick="ProcessEducationForm()" role="button" class="btn btn-success">Submit</a> </div>
<div class="col-md-3"></div>
<div class="clearfix col-md-8" id="AjaxResult"><br />
</div>
<?php
}

if($_REQUEST["action"]=='LoadEmployeeEducationData'){ ?>
<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th>College or University</th>
      <th>Qualification</th>
      <th>From - To</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
		  $stmt = query("SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$_SESSION['EmployeeUID']."' ORDER BY `employee_education`.`EducationTo` DESC");
		  while($education = fetch($stmt)){ ?>
    <tr>
      <td><?=$education['EducationInstitute']?></td>
      <td><?=optionVal($education['EducationQualification'])?></td>
      <td><?=( ( $education['EducationFrom'] == "0000-00-00" ) ? '' : date("M, Y", strtotime($education['EducationFrom'])) . " - " . date("M, Y", strtotime($education['EducationTo'])) )?></td>
      <td><?php
			  $deleteurl = "profile.php?delete=education&uid=".$education['UID'];
			  $data = array(
						  array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="LoadEmployeeEducationForm('.$education['UID'].', \'EducationHistorySection\')" role="button" class="green" title="Edit Experience"'),
						  array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\''.$deleteurl.'\')" class="red ConfirmDelete"  title="Delete" ')
						  );
			  echo TableActions($data); 
			  ?></td>
    </tr>
    <?php
		  }?>
  </tbody>
</table>
<?php
}

if($_REQUEST["action"]=='EmployeeEducationSubmit'){
	$Education = array();
	$Education['EducationEmployeeID'] = $_SESSION['EmployeeUID'];
	$Education['EducationInstitute'] = $_REQUEST["Institute"];
	$Education['EducationQualification'] = $_REQUEST["Qualification"];
	$Education['EducationFrom'] = $_REQUEST["EducationFrom"];
	$Education['EducationTo'] = $_REQUEST["EducationTo"];
	
	if($Education['EducationQualification'] == 'other' ){
		/*
		$otherdata = explode(",",$_REQUEST['OtherQualification']);		
		foreach($otherdata as $value){
			
			
		}
		*/
		
		$type = GetData("TypeId","typedata","TypeFieldName","qualification");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherQualification'] )."', '".$_REQUEST['OtherQualification']."', '0');";
		$stmt = mysql_query($sql);
		$Education['EducationQualification'] = mysql_insert_id();
		AdminEmailForDropDown();
	}
	
	if($_REQUEST["FormType"]=='update'){
		$result = FormData('employee_education', 'update', $Education, " UID = '".$_REQUEST["uid"]."' ", $view=false );
		echo Alert('success', "Education Updated Successfully");
	}
	if($_REQUEST["FormType"]=='add'){
		$result = FormData('employee_education', 'insert', $Education, "", $view=false );
		echo Alert('success', "Education Added Successfully");
	}
	
	
}

/*CertificateHistorySection Begin*/
if($_REQUEST["action"]=='LoadEmployeeCertificateForm'){
	$UID = $_REQUEST["uid"];
	$formtype = 'add';
	$EDU = '';
	if($UID==0){
		$head = 'Add Certificate';
		$EDU['CertificateDate'] = '';//date("Y-m-d"); 
	} else {
		$head = 'Update Certificate';
		$stmt = query("SELECT * FROM `employee_certificate` WHERE `UID` = '".$UID."' ");
		$EDU = fetch($stmt);
		$formtype = 'update';
	} ?>
<h5 class="accordion-header">
  <?=$head?>
</h5>
<input type="hidden" id="CertificateFormType" name="CertificateFormType" value="<?=$formtype?>" />
<input type="hidden" id="CertificateFormUID" name="CertificateFormUID" value="<?=$UID?>" />
<div class="form-group">
  <label class="col-sm-5 col-md-4 col-xs-6  control-label no-padding-right" for="form-field-1">Institution:<i class="icon-asterisk"></i></label>
  <div class="col-sm-7 col-md-7 col-xs-8">
    <input type="text" id="CertificateInstitute" name="Institute" placeholder="Certificate Institute" class="col-xs-12 col-sm-12  validate[required] " value="<?=$EDU['CertificateInstitute']?>" />
  </div>
</div>
<div class="form-group">
  <label class="col-sm-5 col-md-4 col-xs-6 control-label no-padding-right" for="form-field-1">Certificate Title:<i class="icon-asterisk"></i></label>
  <div class="col-sm-7 col-md-7 col-xs-8">
    <input type="text" id="CertificateQualification" name="Qualification"  placeholder="Certificate Title" class="col-xs-12 col-sm-12  validate[required] " value="<?=$EDU['CertificateQualification']?>" />
  </div>
</div>
<div class="form-group">
  <label class="col-sm-5 col-md-4 col-xs-8 control-label no-padding-right" for="form-field-1">Certificate Date:</label>
  <div class="col-sm-7 col-md-7 col-xs-7">
    <div class="input-group">
      <input class="date-picker form-control validate[custom[date]]" value="<?=($EDU['CertificateDate']!='')?date("Y-m-d", strtotime($EDU['CertificateDate'])):''?>"  data-date-format="yyyy-mm-dd" id="CertificateDate" name="CertificateDate" type="text" />
    </div>
  </div>
</div>
<div class="col-md-3"></div>
<div class="col-md-9"><a href="javascript:void(0);" onclick="ProcessCertificateForm()" role="button" class="btn btn-success">Submit</a> </div>
<div class="col-md-3"></div>
<div class="clearfix col-md-8" id="AjaxResult"><br />
</div>
<?php
}

if($_REQUEST["action"]=='LoadEmployeeCertificateData'){ ?>
<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th>Institution</th>
      <th>Certificate Title</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php $sql = "SELECT * FROM `employee_certificate` WHERE `EmployeeID` = '".$_SESSION['EmployeeUID']."' ORDER BY CertificateDate DESC";
		  $stmt = query($sql);
		  while($certificate = fetch($stmt)){ ?>
    <tr>
      <td><?=$certificate['CertificateInstitute']?></td>
      <td><?=$certificate['CertificateQualification']?></td>
      <td><?=(($certificate['CertificateDate']!='0000-00-00')?date("M, Y", strtotime($certificate['CertificateDate'])):'N/A')?></td>
      <td><?php
			  $deleteurl = "profile.php?delete=certificate&uid=".$certificate['UID'];
			  $data = array(
						  array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="LoadEmployeeCertificateForm('.$certificate['UID'].', \'CertificateHistorySection\')" role="button" class="green" title="Edit Experience"'),
						  array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\''.$deleteurl.'\')" class="red ConfirmDelete"  title="Delete" ')
						  );
			  echo TableActions($data); 
			  ?></td>
    </tr>
    <?php
		  }?>
  </tbody>
</table>
<?php
}

if($_REQUEST["action"]=='EmployeeCertificateSubmit'){
	$Certificate = array();
	$Certificate['EmployeeID'] = $_SESSION['EmployeeUID'];
	$Certificate['CertificateInstitute'] = $_REQUEST["Institute"];
	$Certificate['CertificateQualification'] = $_REQUEST["Qualification"];
	$Certificate['CertificateDate'] = ( $_REQUEST["CertificateDate"]=='' ) ? '0000-00-00' : $_REQUEST["CertificateDate"] ;
	
	if($_REQUEST["FormType"]=='update'){
		$result = FormData('employee_certificate', 'update', $Certificate, " UID = '".$_REQUEST["uid"]."' ", $view=false );
		echo Alert('success', "Certificate Updated Successfully");
	}
	if($_REQUEST["FormType"]=='add'){
		$result = FormData('employee_certificate', 'insert', $Certificate, "", $view=false );
		echo Alert('success', "Certificate Added Successfully");
	}
	
	
}
/*CertificateHistorySection end*/

if($_REQUEST["action"]=='LoadHelpDesk'){ $cid = $_REQUEST['cid'];
	if($cid>0){?>
<div class="widget-header header-color-blue2">
  <h4 class="lighter smaller">
    <?=GetData('title','category','id',$cid)?>
    Questions</h4>
</div>
<div class="widget-body">
  <div class="widget-main padding-8">
    <div id="help-desk" class="panel-group accordion-style1 accordion-style1">
      <?php
					$stmt = query( " SELECT * FROM `user_manual` WHERE `ManualCategory` in ( SELECT `id` FROM `category` WHERE `id` = '".$cid."' ) " );
					while( $rslt = fetch($stmt) ){ ?>
      <div class="panel panel-default">
        <div class="panel-heading"> <a href="#help-<?=$rslt['UID']?>" data-parent="#help-desk" data-toggle="collapse" class="accordion-toggle collapsed"> <i class="icon-chevron-right smaller-80" data-icon-hide="icon-chevron-down align-top" data-icon-show="icon-chevron-right"></i> &nbsp;
          <?=$rslt['ManualQuestion']?>
          </a> </div>
        <div class="panel-collapse collapse" id="help-<?=$rslt['UID']?>">
          <div class="panel-body ">
            <?=$rslt['ManualAnswer']?>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php
	}
}


if($_REQUEST["action"]=='LoadEmployeeExperienceData'){ ?>
<h4>Experience History <?=GetEmployeeExpInYear( $_SESSION['EmployeeUID'] )?></h4>
<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th>Company</th>
      <th>Designation</th>
      <th>Salary</th>
      <th>From</th>
      <th>To</th>
      <th>Experience</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php $sql = "SELECT * FROM `employee_experience` WHERE `ExperienceEmployeeID` = '".$_SESSION['EmployeeUID']."' ORDER BY UID ";
		  $stmt = query($sql);
		  while($experience = fetch($stmt)){
		  $experience['ExperienceFrom'] = ( $experience['ExperienceFrom'] == '0000-00-00' ) ? '' : $experience['ExperienceFrom'] ;
		  if( $experience['ExperienceTo'] == "1971-01-01" ){
		  	$ExperienceTo = 'Present';
		  } else 
		  if( $experience['ExperienceTo'] == "0000-00-00" ){
		  	$ExperienceTo = '';
		  } else {
		  	$ExperienceTo = $experience['ExperienceTo'];
		  }
		  
		  
		  //$experience['ExperienceTo'] = ( $experience['ExperienceFrom'] == '0000-00-00' && $experience['ExperienceTo'] == '0000-00-00' ) ? '' : ( $experience['ExperienceFrom'] != '0000-00-00' && $experience['ExperienceTo'] == '0000-00-00' ) ? 'Present' : $experience['ExperienceTo'];
		  
		 //
		  ?>
    <tr>
      <td><?=$experience['ExperienceEmployer']?></td>
      <td><?=optionVal($experience['ExperienceDesignation'])?></td>
      <td><?=( ( $experience['ExperienceSallery'] > 0)? $experience['ExperienceSallery']:'' )?></td>
      <td><?=$experience['ExperienceFrom']?></td>
      <td><?=$ExperienceTo?></td>
      <td><?=GetEmployeeExpInYearByJobID($experience['UID'])?></td>
      <td><?php
			  $deleteurl = "profile.php?delete=experience&uid=".$experience['UID'];
			  $data = array(
						  array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="LoadEmployeeExperienceForm('.$experience['UID'].', \'ExperienceHistorySection\')" role="button" class="green" title="Edit Experience"'),
						  array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ProcessExperienceDelete('.$experience['UID'].')" class="red ConfirmDelete"  title="Delete" ')
						  );
			  echo TableActions($data); 
			  ?></td>
    </tr>
    <?php
		  }?>
  </tbody>
</table>
<?php
}

if($_REQUEST["action"]=='LoadEmployeeExperienceForm'){
	$UID = $_REQUEST["uid"];
	$formtype = 'add';
	$EDU = '';
	if($UID==0){
		$head = 'Add Experience';
		$PAGE['ExperienceTo'] = $PAGE['ExperienceFrom'] = '0000-00-00'; 
	} else {
		$head = 'Update Experience';
		$stmt = query("SELECT * FROM `employee_experience` WHERE `UID` = '".$UID."' ");
		$PAGE = fetch($stmt);
		$formtype = 'update';
		
	} ?>
<h5 class="accordion-header">
  <?=$head?>
  <small class="red"> (Please enter the current experience first)</small> </h5>
<input type="hidden" id="ExperienceFormType" name="FormType" value="<?=$formtype?>" />
<input type="hidden" id="ExperienceFormUID" name="FormUID" value="<?=$UID?>" />
<div class="form-group">
  <label for="form-field-1" class="col-sm-3 control-label no-padding-right">Company:<i class="icon-asterisk"></i></label>
  <div class="col-sm-9">
    <input type="text" value="<?=$PAGE['ExperienceEmployer']?>" class="col-xs-5 col-sm-5 validate[required]" placeholder"your employer" name="ExperienceEmployer" id="ExperienceEmployer">
  </div>
</div>
<div class="form-group">
  <label for="form-field-1" class="col-sm-3 control-label no-padding-right">Designation:<i class="icon-asterisk"></i></label>
  <div class="col-sm-9">
    <select name="ExperienceDesignation" id="ExperienceDesignation" class="col-xs-5 col-sm-5 selectstyle validate[required]" onchange="if(this.value=='other') $('#ExperienceDesignation_Other').removeClass('hide'); else $('#ExperienceDesignation_Other').addClass('hide');">
      <?=formListOpt('designation', $PAGE['ExperienceDesignation'])?>
      <option value="other">Any Other</option>
    </select>
  </div>
</div>
<div class="form-group hide" id="ExperienceDesignation_Other">
  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
  <div class="col-sm-9">
    <input type="text" id="OtherDesignation" name="OtherDesignation" placeholder="Enter Other Option" class="col-xs-5 col-sm-5 validate[required]" value="<?=$PAGE['OtherDesignation']?>" />
  </div>
</div>
<div class="space-4"></div>
<div class="form-group">
  <label for="form-field-1" class="col-sm-3 control-label no-padding-right">From:</label>
  <div class="col-sm-9">
    <input class="col-xs-5 col-sm-5 date-picker validate[custom[date]]" type="text" value="<?=( ( $PAGE['ExperienceFrom']=='0000-00-00' ) ? '' : date("Y-m-d", strtotime($PAGE['ExperienceFrom'])) ) ?>"  data-date-format="yyyy-mm-dd" id="ExperienceFrom" name="ExperienceFrom" />
    <span class="col-xs-2 col-sm-2" style="float:none">
    <label>
      <input class="ace" type="checkbox"  id="CurrentJob_checkbox" name="CurrentJob_checkbox" onchange="if ($(this).is(':checked')) $('#ExperienceTo_check').addClass('hide'); else $('#ExperienceTo_check').removeClass('hide');"  />
      <span class="lbl"> Present</span> </label>
    </span> </div>
	<?=( ( $PAGE['ExperienceTo']=='1971-01-01' ) ? '<script> $("#ExperienceHistorySection #CurrentJob_checkbox").prop("checked", true); $("#ExperienceTo_check").addClass("hide");</script>' : '' )?>
	
</div>
<div class="form-group" id="ExperienceTo_check">
  <label for="form-field-1" class="col-sm-3 control-label no-padding-right">To:</label>
  <div class="col-sm-9">
    <input class="col-xs-5 col-sm-5 date-picker validate[custom[date]]" type="text" value="<?=( ( $PAGE['ExperienceTo']=='0000-00-00' || $PAGE['ExperienceTo']=='1971-01-01' ) ? '' : date("Y-m-d", strtotime($PAGE['ExperienceTo'])) ) ?>"  data-date-format="yyyy-mm-dd" id="ExperienceTo" name="ExperienceTo" />
  </div>
</div>
<div class="space-4"></div>
<div class="form-group">
  <label for="form-field-1" class="col-sm-3 control-label no-padding-right">Salary:</label>
  <div class="col-sm-9">
    <select class="col-xs-5 col-sm-5 selectstyle" id="ExperienceSallery" name="ExperienceSallery">
      <?=SalleryDropdown($PAGE['ExperienceSallery'])?>
    </select>
  </div>
</div>
<div class="space-4"></div>
<div class="space-4"></div>
<div class="col-md-3"></div>
<div class="col-md-9"><a href="javascript:void(0);" onclick="ProcessExperienceForm()" role="button" class="btn btn-success">Submit</a> </div>
<div class="col-md-3"></div>
<div class="clearfix col-md-4" id="AjaxResult"><br />
</div>
<?php
}

if($_REQUEST["action"]=='EmployeeExperienceSubmit'){  
	//print_r($_REQUEST);
	$Experience = array();
	$Experience['ExperienceEmployeeID'] = $_SESSION['EmployeeUID'];
	$Experience['ExperienceEmployer'] = $_REQUEST["ExperienceEmployer"];
	$Experience['ExperienceDesignation'] = $_REQUEST["ExperienceDesignation"];
	$Experience['ExperienceSallery'] = $_REQUEST["ExperienceSallery"];
	$Experience['ExperienceYear'] = $_REQUEST["ExperienceYear"];
	$Experience['ExperienceFrom'] = $_REQUEST["ExperienceFrom"];
	$Experience['ExperienceTo'] = ( $_REQUEST["CurrentJob_checkbox"] == 'on' ) ? '1971-01-01' : $_REQUEST["ExperienceTo"];
	
	if($Experience['ExperienceYear'] == 'other' ){
		$type = GetData("TypeId","typedata","TypeFieldName","experience");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherExperience'] )."', '".$_REQUEST['OtherExperience']."', '0');";
		$stmt = mysql_query($sql);
		$Experience['ExperienceYear'] = mysql_insert_id();
		AdminEmailForDropDown();
	}
	
	if($Experience['ExperienceDesignation'] == 'other' ){
		$type = GetData("TypeId","typedata","TypeFieldName","designation");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherDesignation'] )."', '".$_REQUEST['OtherDesignation']."', '0');";
		$stmt = mysql_query($sql);
		$Experience['ExperienceDesignation'] = mysql_insert_id();
		AdminEmailForDropDown();
	}

	($Experience['ExperienceSallery']=='other') ? $Experience['ExperienceSallery'] = $_REQUEST['OtherSallery'] : '' ;
	
	
	if($_REQUEST["FormType"]=='update'){
		$result = FormData('employee_experience', 'update', $Experience, " UID = '".$_REQUEST["uid"]."' ", $view=false );
		echo Alert('success', "Experience Updated Successfully");
	}
	if($_REQUEST["FormType"]=='add'){
		$result = FormData('employee_experience', 'insert', $Experience, "", $view=false );
		echo Alert('success', "Experience Added Successfully");
	}	
} 

if($_REQUEST["action"]=='JobAlertForm'){
	$Alert = array();
	$Alert['AlertEmployeeUID'] = $_SESSION['EmployeeUID'];
	$Alert['AlertTitle'] = $_REQUEST["AlertTitle"];
	$Alert['AlertStatus'] = 1;
	$Alert['AlertCompany'] = $_REQUEST["AlertCompany"];
	$Alert['AlertArea'] = $_REQUEST["AlertArea"];
	$Alert['AlertDesignation'] = $_REQUEST["AlertDesignation"];
	
	if($_REQUEST["FormType"]=='update'){
		$result = FormData('jobs_alerts', 'update', $Alert, " UID = '".$_REQUEST["uid"]."' ", $view=false );
		echo Alert('success', "Alert Updated Successfully");
	}
	if($_REQUEST["FormType"]=='add'){
		$result = FormData('jobs_alerts', 'insert', $Alert, "", $view=false );
		echo Alert('success', "Alert Added Successfully");
	}
}

if($_REQUEST["action"]=='InterviewApproval'){  
	$data = array();
	$data['InterviewApproval'] = $_REQUEST["val"];	
	$result = FormData('jobs_apply', 'update', $data, " UID = '".$_REQUEST["uid"]."' ", $view=false );
	
	$employerid = GetData('JobEmployerID','jobs','UID',GetData('JobID','jobs_apply','UID',$_REQUEST["uid"]));
	$JobTitle = GetData('JobTitle','jobs','UID',GetData('JobID','jobs_apply','UID',$_REQUEST["uid"]));
	
	$EmployeeName = GetData('EmployeeName','employee','UID',GetData('EmployeeID','jobs_apply','UID',$_REQUEST["uid"])); 
	
	if($_REQUEST["val"]==1){
		echo '<strong>Interview accepted successfully</strong>';
		Track(ucfirst('<strong>'.$EmployeeName.'</strong> has accepted your interview offer for the job of <strong>'.$JobTitle.'</strong>. You can now schedule the interview.'), 'employer', $employerid);
	} else if($_REQUEST["val"]==2){
		echo '<strong>Interview rejected successfully</strong>';
		Track(ucfirst('<strong>'.$EmployeeName.'</strong> has rejected your interview offer for the job of <strong>'.$JobTitle.'</strong>.'), 'employer', $employerid);
	}
} 
if($_REQUEST["action"]=='RemoveLogo'){
	query("UPDATE `employee` SET `EmployeeLogo` = 'no-image.png' WHERE `employee`.`UID` = '".$_REQUEST["uid"]."' ; ");
	
}
if($_REQUEST["action"]=='EmployeeProfileSubmit'){   
	
	$contents = $_REQUEST;
	$target_dir = MEMBER_IMAGE_DIRECTORY;
	$nowDate = date("Y-m-d h:i:s");
	//$contents['EmployeePassword'] = PassWord($_POST['EmployeePassword'],'hide');
	$_GET["mode"]='';
	

	$FormMessge = '';
	if($_FILES["image"]["name"])
	{
		$uploadOk = 1;
		$target_file = $target_dir . basename($_FILES["image"]["name"]);
		//echo "target_file = " . $target_file . "<br />";
		
		$path_parts = pathinfo($target_file);
		//echo "<pre>";print_r($path_parts);echo "</pre>";
		
		$fileName = 'employee'. "_" . rand(0, 100000); $path_parts['filename'];
		//echo "fileName = " . $fileName . "<br />";
		
		$fileType = $path_parts['extension'];
		//echo "fileType = " . $fileType . "<br />";
		$target_file = $target_dir . $fileName . "." . $fileType;
		
		// Check if file already exists
		while(file_exists($target_file)) {
			$fileName = $fileName . "_" . rand(0, 100000);
			$target_file = $target_dir . $fileName . "." . $fileType;
		}
		//echo "target_file = " . $target_file . "<br />";
		
		// Allow certain file formats
		if(strtolower($fileType) != "jpg" && strtolower($fileType) != "png") {
			$FormMessge = Alert('error', 'Sorry, only jpg & jpeg files are allowed.');
			$uploadOk = 0;
		}
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 1)
		{
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))
			{
				$contents['EmployeeLogo'] = mysql_real_escape_string($fileName.".".$fileType);
				//Delete old file.
				$existing_image = GetData('EmployeeLogo','employee','UID',$_SESSION['EmployeeUID']);
				if($existing_image!='no-image.png')
					@unlink($target_dir.$existing_image);
			}
			else 
			{
				$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
			}
		}
	}
	
	if($contents['EmployeeCity'] == 'other' && $contents['OtherCity'] != '' ){
		$type = GetData("TypeId","typedata","TypeFieldName","city");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $contents['OtherCity'] )."', '".$contents['OtherCity']."', '0');";
		$stmt = mysql_query($sql);
		$contents['EmployeeCity'] = mysql_insert_id();
		AdminEmailForDropDown();
	}
	
	if($contents['EmployeeMotherLanguage'] == 'other' && $contents['OtherLanguage'] != '' ){
		$type = GetData("TypeId","typedata","TypeFieldName","mother-language");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $contents['OtherLanguage'] )."', '".$contents['OtherLanguage']."', '0');";
		$stmt = mysql_query($sql);
		$contents['EmployeeMotherLanguage'] = mysql_insert_id();
		AdminEmailForDropDown();
	}
	
	if($contents['EmployeeTotalExperience'] == 'other' && $contents['OtherTotalExperience'] != '' ){ 
		$type = GetData("TypeId","typedata","TypeFieldName","experience");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $contents['OtherTotalExperience'] )."', '".$contents['OtherTotalExperience']."', '0');";
		$stmt = mysql_query($sql);
		$contents['EmployeeTotalExperience'] = mysql_insert_id();
		AdminEmailForDropDown();
	}	 
	
	$options = array('EmployeeInterests','EmployeeSoftSkills','EmployeeSkills','EmployeeObjective'); 
	foreach($options as $opt){ 
		if($_REQUEST['form']=='EmployeeProfileSkills'){
			$sql = "DELETE FROM `employee_extra` WHERE InfoType = '".$opt."' and EmployeeID = '".$_SESSION['EmployeeUID']."' ";
			query($sql);
			//echo $opt . 'Extra Clear...!<br />';
		}

		foreach($_REQUEST[$opt] as $val){
			$JobCity = array();
			$JobCity['EmployeeID'] = $_SESSION['EmployeeUID'];
			$JobCity['InfoType'] = $opt;
			$JobCity['InfoTypeValue'] = ($opt=='EmployeeGender')?$val:optionVal($val);
			FormData('employee_extra', 'insert', $JobCity, "", $view=false );
		
		}
	}

	
	if( isset($_REQUEST['OtherEmployeeSkills']) && $_REQUEST['OtherEmployeeSkills'] !=''  ){ 
		$type = GetData("TypeId","typedata","TypeFieldName","skills");
		echo $sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherEmployeeSkills'] )."', '".$_REQUEST['OtherEmployeeSkills']."', '0');";
		$stmt = mysql_query($sql);
		AdminEmailForDropDown();
		$JobCity = array();
		$JobCity['EmployeeID'] = $_SESSION['EmployeeUID'];
		$JobCity['InfoType'] = 'EmployeeSkills';
		$JobCity['InfoTypeValue'] = $_REQUEST['OtherEmployeeSkills'];
		FormData('employee_extra', 'insert', $JobCity, "", $view=false );
	}
	
	if( isset($_REQUEST['OtherEmployeeSoftSkills']) && $_REQUEST['OtherEmployeeSoftSkills'] !='' ){ 
		$type = GetData("TypeId","typedata","TypeFieldName","soft-skills");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherEmployeeSoftSkills'] )."', '".$_REQUEST['OtherEmployeeSoftSkills']."', '0');";
		$stmt = mysql_query($sql);
		AdminEmailForDropDown();
		$JobCity = array();
		$JobCity['EmployeeID'] = $_SESSION['EmployeeUID'];
		$JobCity['InfoType'] = 'EmployeeSoftSkills';
		$JobCity['InfoTypeValue'] = $_REQUEST['OtherEmployeeSoftSkills'];
		FormData('employee_extra', 'insert', $JobCity, "", $view=false );
	}

	if( isset($_REQUEST['OtherEmployeeInterests']) && $_REQUEST['OtherEmployeeInterests'] !='' ){ 
		$type = GetData("TypeId","typedata","TypeFieldName","interests");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherEmployeeInterests'] )."', '".$_REQUEST['OtherEmployeeInterests']."', '0');";
		$stmt = mysql_query($sql);
		AdminEmailForDropDown();
		$JobCity = array();
		$JobCity['EmployeeID'] = $_SESSION['EmployeeUID'];
		$JobCity['InfoType'] = 'EmployeeInterests';
		$JobCity['InfoTypeValue'] = $_REQUEST['OtherEmployeeInterests'];
		FormData('employee_extra', 'insert', $JobCity, "", $view=false );
	}

	query(" DELETE FROM `employee_extra` WHERE `InfoTypeValue` = '' ");

	$run = FormData('employee', 'update', $contents, " UID = '".$_SESSION['EmployeeUID']."' ", $view=false );
	echo $FormMessge = Alert('success', 'Setting Temporarily Save, Please confirm these changes on Last Tab...!'); 
}


if($_REQUEST["action"]=='InvitationApproval'){  

	if($_REQUEST['val']==1){ 
		query("UPDATE `jobs_invitations` SET InvitationStatus = 'Invitation Accepted', `InvitationApproval` = '".$_REQUEST['val']."' WHERE `jobs_invitations`.`UID` = '".$_REQUEST['uid']."' ; ");
		
		$Designation = optionVal( GetData('Designation','jobs_invitations','UID',$_REQUEST['uid']) );
		
		$JobUID = GetData('JobUID','jobs_invitations','UID',$_REQUEST['uid']);
		if( $JobUID == 0 ){ $JobTitle = "Open Invitation"; }
		if( $JobUID > 0 ){ $JobTitle = GetData('JobTitle','jobs','UID',$JobUID); }
		
		$Employee = GetData('EmployeeUID','jobs_invitations','UID',$_REQUEST['uid']);
		$EmployeeName = GetData('EmployeeName','employee','UID',$Employee);
		
		$Employer = GetData('EmployerUID','jobs_invitations','UID',$_REQUEST['uid']);		
		$EmployerName = GetData('EmployerCompany','employer','UID',$Employer);
		$EmployerEmail = GetData('EmployerEmail','employer','UID',$Employer);
		$EmployerContactName = optionVal( GetData('EmployerContactTitle','employer','UID',$Employer) ) . "&nbsp;" . GetData('EmployerContactName','employer','UID',$Employer);
		
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<strong>Dear Employer,</strong><br /><br />
			<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; You showed interest in our portal member (Code # '.EmpCode('', $Employee).') for the vacancy of "'.$Designation.'" and he authorized us to show his identity.  Its "'.$EmployeeName.'" and he is interested in working with your company. <br />
			To view his complete profile, please Login <a href="'.$path.'page/employer-login">Here</a> and visit Invitation section.
			</p>
			<p><br />Regards,<br />
			<strong>Team Holistic Jobs </strong></p>
		</td></tr></table> ';
		$subject = "Invitation Status :: Holistic Jobs";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $EmployerEmail => $EmployerName );
		$body = SendMail($data, $subject, $message, $show=false);
		
		echo "Invitation Accepted";
	}
	
	if($_REQUEST['val']==2){
		query("UPDATE `jobs_invitations` SET InvitationStatus = 'Invitation Rejected', `InvitationApproval` = '".$_REQUEST['val']."' WHERE `jobs_invitations`.`UID` = '".$_REQUEST['uid']."' ; ");
		
		$Designation = optionVal( GetData('Designation','jobs_invitations','UID',$_REQUEST['uid']) );
		
		$JobUID = GetData('JobUID','jobs_invitations','UID',$_REQUEST['uid']);
		if( $JobUID == 0 ){ $JobTitle = "Open Invitation"; }
		if( $JobUID > 0 ){ $JobTitle = GetData('JobTitle','jobs','UID',$JobUID); }
		
		$Employee = GetData('EmployeeUID','jobs_invitations','UID',$_REQUEST['uid']);
		$EmployeeName = GetData('EmployeeName','employee','UID',$Employee);
		
		$Employer = GetData('EmployerUID','jobs_invitations','UID',$_REQUEST['uid']);
		
		$EmployerName = GetData('EmployerCompany','employer','UID',$Employer);
		$EmployerEmail = GetData('EmployerEmail','employer','UID',$Employer);
		$EmployerContactName = optionVal( GetData('EmployerContactTitle','employer','UID',$Employer) ) . "&nbsp;" . GetData('EmployerContactName','employer','UID',$Employer);
		
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<strong>Dear Employer,</strong><br /><br />
			<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; You showed interest in our portal member (Code # <strong>'.EmpCode('', $Employee).'</strong>) for the vacancy of <strong>"'.$Designation.'"</strong> and he didn\'t not authorized us to show his identity.<br />
			There are many more candidates on Holistic Jobs that may meet your requirements.<br />
			To view the candidates, please Login <a href="'.$path.'page/employer-login">Here</a>.<br />
			</p>
			<p><br />Regards,<br /><strong>Team Holistic Jobs </strong></p>
		</td></tr></table> ';
		$subject = "Invitation Status :: Holistic Jobs";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $EmployerEmail => $EmployerName );
		$body = SendMail($data, $subject, $message, $show=false);
		
		echo "Invitation Rejected";
	}
	
}

if($_REQUEST["action"]=='InvitationInterviewApproval'){

	if( $_REQUEST['val'] == 1 ){
		query("UPDATE `jobs_invitations` SET InvitationStatus = 'Interview Accepted', InterviewApproval = '".$_REQUEST['val']."' WHERE `jobs_invitations`.`UID` = '".$_REQUEST['uid']."' ; ");
		
		$Employer = GetData('EmployerUID','jobs_invitations','UID',$_REQUEST['uid']);
		
		$EmployerName = GetData('EmployerCompany','employer','UID',$Employer);
		$EmployerEmail = GetData('EmployerEmail','employer','UID',$Employer);
		
		$Employee = GetData('EmployeeUID','jobs_invitations','UID',$_REQUEST['uid']);
		$EmployeeName = GetData('EmployeeName','employee','UID',$Employee);
		
		$Designation = optionVal( GetData('Designation','jobs_invitations','UID',$_REQUEST['uid']) );
		
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<strong>Dear Employer,</strong><br /><br />
			<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; The shortlisted candidate <strong>"'.$EmployeeName.'"</strong> for the post of <strong>"'.$Designation.'"</strong> has accepted your offer and is interested in proceeding further for interview.<br /></p>
			<p><br />Regards,<br /><strong>Team Holistic Jobs </strong></p>
		</td></tr></table> ';
		$subject = "Interview Offer Status :: Holistic Jobs";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $EmployerEmail => $EmployerName );
		$body = SendMail($data, $subject, $message, $show=false);
		
		echo "<strong>Offer Accepted</strong>";
	}
	
	if( $_REQUEST['val'] == 2 ){
		query("UPDATE `jobs_invitations` SET InvitationStatus = 'Interview Rejected', InterviewApproval = '".$_REQUEST['val']."' WHERE `jobs_invitations`.`UID` = '".$_REQUEST['uid']."' ; ");
		
		$Employer = GetData('EmployerUID','jobs_invitations','UID',$_REQUEST['uid']);
		
		$EmployerName = GetData('EmployerCompany','employer','UID',$Employer);
		$EmployerEmail = GetData('EmployerEmail','employer','UID',$Employer);
		
		$Employee = GetData('EmployeeUID','jobs_invitations','UID',$_REQUEST['uid']);
		$EmployeeName = GetData('EmployeeName','employee','UID',$Employee);
		
		$Designation = optionVal( GetData('Designation','jobs_invitations','UID',$_REQUEST['uid']) );
		$City = optionVal( GetData('City','jobs_invitations','UID',$_REQUEST['uid']) );
		
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<strong>Dear Employer,</strong><br /><br />
			<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; The shortlisted portal member <strong>"'.$EmployeeName.'"</strong> for the post of <strong>"'.$Designation.'"</strong> in <strong>"'.$City.'"</strong> has refused to accept your interview offer.<br />There are many more candidates on Holistic Jobs that may meet your requirements.<br />
			To view the candidates, please Login <a href="'.$path.'page/employer-login">Here</a>.</p>
			<p><br />Regards,<br /><strong>Team Holistic Jobs </strong></p>
		</td></tr></table> ';
		$subject = " Interview Offer Status :: Holistic Jobs";
		$data = array();
		$data['From'] = 'info@holisticjobs.com.pk';
		$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$data['addAddress'] = array( $EmployerEmail => $EmployerName );
		$body = SendMail($data, $subject, $message, $show=false);
		
		echo "<strong>Offer Rejected</strong>";
	}
	
}

if($_REQUEST["action"]=='ExperienceDelete'){
	$sql = "DELETE FROM `employee_experience` WHERE `UID` = '".$_REQUEST['uid']."' ";
	@query($sql);
}





















