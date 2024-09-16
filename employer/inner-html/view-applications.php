<?php
include("../../admin/includes/conn.php");
include("../../admin/admin_theme_functions.php");

/////////////////////////////////////
///////// Front Side Functions
include("../../site_theme_functions.php");
///////////////////////////////////// 

$stmt = mysql_query(" SELECT * FROM `jobs` WHERE `UID` = '".$_GET["uid"]."' ");
$PAGE = mysql_fetch_array($stmt);

?>
<div class="modal-header no-padding">
  <div class="table-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
    Job Applications (
    <?=$PAGE['JobTitle']?>
    )</div>
</div>
<div class="modal-body form-horizontal">
<div class="slim-scroll" data-height="500">
  <h3 style="margin-top:0;">Job Applications</h3>
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="JobApplicationsData">
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
      $stmt = mysql_query(" SELECT * FROM jobs_apply WHERE ApplicationStatus != 'Ignore' and  `JobID` = '".$_GET["uid"]."' ");
	  while( $application = mysql_fetch_array($stmt) ){ 
	  	$EmpStmt = mysql_query(" SELECT * FROM employee WHERE `UID` = '".$application['EmployeeID']."' ");
		$Employee = mysql_fetch_array($EmpStmt);
		
		$EduStmt = query(" SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$application['EmployeeID']."' ORDER BY `EducationTo` DESC limit 1 ");
		$Education = fetch( $EduStmt );
		?>
        <tr id="row-<?=$application['UID']?>">
          <td><? #print_r($Employee);?>
            Name: <?=optionVal($Employee['EmployeeTitle'])?>&nbsp;<?=$Employee['EmployeeName']?><br />
            Age: <?=($Employee['EmployeeDOB']=='0000-00-00')?"N/A":dob($Employee['EmployeeDOB'])?><br />
			City: <?=$application['City']?><br />
			Gender: <?=$Employee['EmployeeGender']?><br />
			Highest Education: <?=optionVal($Education['EducationQualification'])?> in <?=date("Y", strtotime($Education['EducationTo']))?> from <?=$Education['EducationInstitute']?><br />
			</td>
          <td><?=date("d M, Y \n h:i A", strtotime($application['SystemDate']))?></td>
          <td><?php
			$access = CheckSubAccess('unlimited-cv-download');
			//print_r($access);
			if($access['access']=='true') {
				?>
            <a href="<?=$path?>uploads/<?=$application['UploadedCV']?>" role="button" class="btn btn-success btn-xs green">Resume </a>
            <?
			} else {
				echo $access['msg'];
			}
		  ?></td>
          <td><?php if($application['ApplicationStatus']=='Interview-Schedule'){?>
            <button class="btn btn-xs btn-success">Interview Scheduled</button>
            <?php } else {?>
            <div class="btn-group">
              <button data-toggle="dropdown" class="btn btn-xs btn-<?=($application['ApplicationStatus']=='Featured')?'success':'primary'?> dropdown-toggle">
              <?=($application['ApplicationStatus']=='New')?'Action Awaited':$application['ApplicationStatus']?>
              <i class="icon-angle-down icon-on-right"></i> </button>
              <ul class="dropdown-menu">
                <li><a href="#EmailContent" data-toggle="modal" data-uid="Shortlisted|<?=$application["UID"]?>" title="">Shortlisted</a></li>
                <li><a href="javascript:;" onclick="UpdateApplicationStatus(<?=$application['UID']?>,'Ignored')">Ignored</a></li>
              </ul>
            </div>
            <?php }?>
			<?=($application['ApplicationStatus']=='Shortlisted' &&  $application['InterviewApproval']=='0')?'<br /><br /><div class="btn btn-notic btn-xs ">Wait For Interview Approval</div>':''?>
            <?=($application['ApplicationStatus']=='Shortlisted' &&  $application['InterviewApproval']=='1')?'<br /><br /><a href="#EmailContent" role="button" data-uid="ScheduleInterview|'.$application["UID"].'" data-toggle="modal" class="btn btn-primary btn-xs ">Schedule Interview</a>':''?></td>
        </tr>
        <?php
      } ?>
      </tbody>
    </table>
  </div><?php
  $sql = " SELECT * FROM jobs_apply WHERE ApplicationStatus = 'Ignored' and  `JobID` = '".$_GET["uid"]."' ";
  $rows = total ($sql);
  if($rows > 0) { ?>
	  <div class="alert alert-danger no-margin">Results for "Ignored Applications"</div>
	  <div class="table-responsive">
		<table class="table table-striped table-bordered table-hover" id="JobApplicationsIgnoreData">
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
		  $stmt = mysql_query($sql);
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
			  <td><?php
				$access = CheckSubAccess('unlimited-cv-download');
				if($access['access']=='true') {
					?><a href="<?=$path?>uploads/<?=$application['UploadedCV']?>" role="button" class="btn btn-success btn-xs green">Resume </a><?
				} else {
					echo $access['msg'];
				}
			  ?></td>
			  <td><?php if($application['ApplicationStatus']=='Interview-Schedule'){?>
				<button class="btn btn-xs btn-success">Interview Schedule</button>
				<?php } else {?>
				<div class="btn-group">
				  <button data-toggle="dropdown" class="btn btn-xs btn-<?=($application['ApplicationStatus']=='Featured')?'success':'primary'?> dropdown-toggle">
				  <?=($application['ApplicationStatus']=='New')?'Action Awaited':$application['ApplicationStatus']?>
				  <i class="icon-angle-down icon-on-right"></i> </button>
				  <ul class="dropdown-menu">
					<li><a href="#EmailContent" data-toggle="modal" data-uid="Shortlisted|<?=$application["UID"]?>" title="">Shortlisted</a></li>
					<li><a href="javascript:;" onclick="UpdateApplicationStatus(<?=$application['UID']?>,'Ignored')">Ignored</a></li>
				  </ul>
				</div>
				<?php }?>
				<?=($application['ApplicationStatus']=='Shortlisted')?'<br /><br /><a href="#EmailContent" role="button" data-uid="ScheduleInterview|'.$application["UID"].'" data-toggle="modal" class="btn btn-primary btn-xs ">Schedule Interview</a>':''?></td>
			</tr>
			<?php
		  } ?>
		  </tbody>
		</table>
	  </div>
  <?php
  }?>
  </div>  
</div>
<div class="modal-footer no-margin-top">
  <button type="button" class="btn btn-primary" data-dismiss="modal" onclick=""> Close </button>
</div>
<script>
$('.slim-scroll').each(function () {
	var $this = $(this);
	$this.slimScroll({
		height: $this.data('height') || 100,
		railVisible:true
	});
});

function UpdateApplicationStatus(uid, appstatus){
	data = uid + " | " + appstatus;
	ajaxreq('ajaxpage.php', 'action=JobApplicationStatus&UID='+uid+'&Status='+appstatus, 'JobApplicationsIgnoreData');
	$("tr#row-"+uid).remove();
}

</script>