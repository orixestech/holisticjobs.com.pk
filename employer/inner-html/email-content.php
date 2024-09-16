<?php
include("../../admin/includes/conn.php");
include("../../admin_theme_functions.php");

/////////////////////////////////////
///////// Front Side Functions
include("../../site_theme_functions.php");
///////////////////////////////////// 

//print_r($_REQUEST);

$uid = explode("|",$_REQUEST['uid']);
$type = $uid['0'];
$uid = $uid['1'];

$stmt = query(" SELECT * FROM jobs_apply WHERE UID = '".$uid."' ");
$APPLICATION = fetch($stmt);

$stmt = query(" SELECT * FROM `jobs` WHERE `UID` = '".$APPLICATION['JobID']."' ");
$JOBS = fetch($stmt);

$stmt = query(" SELECT * FROM employee WHERE `UID` = '".$APPLICATION['EmployeeID']."' ");
$EMPLOYEE = fetch($stmt);

$EMPLOYER = GetData('EmployerCompany','employer','UID',$JOBS['JobEmployerID']);

if($type=='Shortlisted'){
	$EMAILHTML = '<strong>Dear '.$EMPLOYEE['EmployeeName'].'!</strong><br >
	<p>&nbsp;</p>
	<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; We are hereby pleased to inform you that you have been shortlisted for the interview at <strong>'.$EMPLOYER.'</strong> for the post of <strong>'.$JOBS['JobTitle'].'</strong>.
Login to your account and visit "Applied Jobs" section to accept or reject this interview. Once you accept it then you will be contacted with the details of the interview soon.</p>
	<p>All the best for your job hunt!</p>';
	$EMAILHEAD = 'Applicant Details for Shortlisting.';
	$HEAD = 'Shortlisting Form';
	$formAction = 'ShortListCandidate';
}

if($type=='ScheduleInterview'){
	$EMAILHTML = '
	<strong>Dear '.$EMPLOYEE['EmployeeName'].'!</strong><br />
	<p>&nbsp;</p>
	<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; We are hereby pleased to inform you that you have been shortlisted for the interview at <strong>'.$EMPLOYER.'</strong> for the post of <strong>'.$JOBS['JobTitle'].'</strong>. The interview is scheduled on: <br /></p>
	<p>&nbsp;</p>
	<p><strong>Date:</strong> ||DATE|| </p>
	<p><strong>Venue:</strong> ||VENUE|| </p>
	<p><strong>City: </strong> ||CITY|| </p>
	<p>&nbsp;</p>
	<p>All the best for your interview.</p>
	';
	$EMAILHEAD = 'Applicant Details for Scheduling Interview.';
	$HEAD = 'Scheduling Interview Form';
	$formAction = 'SchedulingInterview';
}

if($type=='InvitationShortlisted'){
	$Employee = GetData('EmployeeUID','jobs_invitations','UID',$uid);
	
	$stmt = query(" SELECT * FROM employee WHERE `UID` = '".$Employee."' ");
	$EMPLOYEE = fetch($stmt);
	
	$Designation = optionVal( GetData('Designation','jobs_invitations','UID',$uid) );
	
	$Employer = GetData('EmployerUID','jobs_invitations','UID',$uid);
	$EmployerName = GetData('EmployerCompany','employer','UID',$Employer);

	$EMAILHTML = '
	<strong>Dear '.$EMPLOYEE['EmployeeName'].'!</strong><br />
	<p>&nbsp;</p>
	<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; We are hereby pleased to inform you that you have been shortlisted for the interview at <strong>'.$EmployerName.'</strong> for the post of <strong>'.$Designation.'</strong>. 
	Login to your account and visit "My Invitations" section to accept or reject this interview. Once you accept it then you will be contacted with the details of the interview soon.</p>
	<p>All the best for your job hunt!</p>';
	$EMAILHEAD = 'Applicant Details for Invitation Shortlisting.';
	$HEAD = 'Invitation Shortlisting Form';
	$formAction = 'InvitationShortlistCandidate';
}

if($type=='InvitationScheduleInterview'){
	$Employee = GetData('EmployeeUID','jobs_invitations','UID',$uid);
	
	$Designation = optionVal( GetData('Designation','jobs_invitations','UID',$uid) );
	
	$Employer = GetData('EmployerUID','jobs_invitations','UID',$uid);
	$EmployerName = GetData('EmployerCompany','employer','UID',$Employer);
	
	$stmt = query(" SELECT * FROM employee WHERE `UID` = '".$Employee."' ");
	$EMPLOYEE = fetch($stmt);

	$EMAILHTML = '
	<strong>Dear '.$EMPLOYEE['EmployeeName'].'!</strong><br />
	<p>&nbsp;</p>
	<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; We are hereby pleased to inform you that you have been shortlisted for the interview at <strong>'.$EmployerName.'</strong>  for the post of <strong>'.$Designation.'</strong>. The interview is scheduled on:<br /></p>
	<p>&nbsp;</p>
	<p><strong>Date:</strong> ||DATE||</p>
	<p><strong>Venue:</strong> ||VENUE||</p>
	<p><strong>City:</strong> ||CITY||</p>
	<p>&nbsp;</p>
	<p>All the best for you interview.</p>';
	$EMAILHEAD = 'Applicant Details for Invitation Scheduling Interview.';
	$HEAD = 'Invitation Scheduling Interview Form';
	$formAction = 'InvitationScheduleInterview';
}

?>

<form role="form" id="EmailContentForm">
  <input type="hidden" name="applicationID" value="<?=$uid?>" />
  <div class="modal-header no-padding">
    <div class="table-header">
      <?=$HEAD?>
    </div>
  </div>
  <div class="slim-scroll" data-height="500">
  <div class="modal-body form-horizontal">
    <h4>
      <?=$EMAILHEAD?>
    </h4>
    <div id="Ajax-Result"><i class="fa fa-search fa-1"></i></div>
    <div class="form-group">
      <label class="col-sm-2"> <strong>Name : </strong> </label>
      <div class="row">
        <div class="col-xs-9 col-sm-9">
          <div class="input-group">
            <label> <?=$EMPLOYEE['EmployeeName']?> </label>
            <input type="hidden" name="EmployeeName" id="EmployeeName" value="<?=$EMPLOYEE['EmployeeName']?>" readonly="readonly">
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2"> <strong>Email :</strong> </label>
      <div class="row">
        <div class="col-xs-9 col-sm-9">
          <div class="input-group">
          	<label> <?=$EMPLOYEE['EmployeeEmail']?> </label>
            <input type="hidden" name="EmployeeEmail" id="EmployeeEmail" value="<?=$EMPLOYEE['EmployeeEmail']?>" readonly="readonly">
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2"> <strong>Mobile Number :</strong> </label>
      <div class="row">
        <div class="col-xs-9 col-sm-9">
          <div class="input-group">
            <label> <?=$EMPLOYEE['EmployeeMobile']?> </label>
            <input type="hidden" name="EmployeeMobile" id="EmployeeMobile" value="<?=$EMPLOYEE['EmployeeMobile']?>" readonly="readonly">
          </div>
        </div>
      </div>
    </div>
    
    <?php if($formAction=='SchedulingInterview' || $formAction=='InvitationScheduleInterview'){ ?>
    <div class="form-group">
      <label class="col-sm-2"> <strong>Date:<i class="icon-asterisk"></i></strong> </label>
      <div class="row">
        <div class="col-xs-9 col-sm-9">
          <div class="input-group">
            <input class="form-control validate[custom[date]]" name="InterviewDate" id="InterviewDate" value="<?=date("Y-m-d")?>">
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2"> <strong>Venue:<i class="icon-asterisk"></i></strong> </label>
      <div class="row">
        <div class="col-xs-9 col-sm-9">
          <div class="input-group">
            <input class="form-control validate[required]" name="InterviewVenue" id="InterviewVenue" value="" >
          </div>
        </div>
      </div>
    </div>
	<div class="form-group">
      <label class="col-sm-2"> <strong>City: <i class="icon-asterisk"></i></strong> </label>
      <div class="row">
        <div class="col-xs-9 col-sm-9">
          <div class="input-group">
			<select name="InterviewCity" id="InterviewCity" class="form-control  col-xs-2 col-sm-2 selectstyle" >
			  <?=formListOpt('city',0)?>
			</select>
          </div>
        </div>
      </div>
    </div>
    <?php }?>
    
    <div class="form-group">
      <label class="col-sm-2"> <strong>Email Content :</strong>
      <?php if($formAction=='SchedulingInterview' || $formAction=='InvitationScheduleInterview'){ ?>
      		<br /><i class="red">Do not remove below code from your message.<br />||DATE||<br />||VENUE||<br />||CITY||<br /> this will auto change with your submission.</i>
      <?php }?>
      </label>
      <div class="row">
        <div class="col-xs-9 col-sm-9">
          <div class="input-group">
            <textarea id="EmailContent" name="EmailContent" class="ContentEditor col-sm-12"  style="height: 250px; width:350px"><?=$EMAILHTML?>
</textarea>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12"> </div>
    </div>
    <div class="space-4"></div>
  </div>
  </div>
  <div class="modal-footer no-margin-top">
    <button type="button" onclick="ShortListCandidate();" class="btn btn-primary"> Submit </button>
  </div>
</form>
<script type="text/javascript">
	$('.date-picker').datepicker({autoclose:true}).next().on(ace.click_event, function(){
		$(this).prev().focus();
	});

	$('.ContentEditor').redactor({ autoresize: false });
	$("form#EmailContentForm").validationEngine('validate');
	
	function ShortListCandidate(){
		//alert("Form Ready to Process..."); return false;
		var validate = $("form#EmailContentForm").validationEngine('validate');
		var valid = $("#EmailContentForm .formError").length;
		if (valid != 0){ return false; }
		
		var form_data = $('form#EmailContentForm').serialize();
		form_data = "action=<?=$formAction?>&type=<?=$type?>&" + form_data;
		//alert(form_data);
		$.ajax({
			cache: false, 
			type: "POST",
			url: "<?=$path?>employer/ajaxpage.php",
			data: form_data,
			dataType : 'html',
			async: false,
			success: function(data){
				$("form#EmailContentForm #Ajax-Result").html(data);
				setTimeout(function(){ window.location.reload(true); }, 3500);
				return false;
			},
			error: function(){
				ALERT("Error with your request, Please try again...!", 'error');
			}
		});
	}
	
	$("#reset").click(function(){
		$(".formError").hide();
	});
	
</script>