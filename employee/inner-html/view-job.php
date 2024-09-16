<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");
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
    Job Details (
    <?=$PAGE['JobTitle']?>
    )</div>
</div>
<form role="form" id="view-job-form">
  <div class="modal-body form-horizontal">
    <h3 style="margin-top:0;">Job Details</h3>
	<div class="slim-scroll" data-height="500">
    <table class="table table-striped table-bordered table-hover">
      <tbody>
        <tr>
          <td width="150"><strong>Category</strong></td>
          <td><?=GetCategory($PAGE['JobCategory'])?></td>
        </tr>
        <tr>
          <td><strong>Job title</strong></td>
          <td><?=$PAGE['JobTitle']?></td>
        </tr>
        <tr>
          <td><strong>Company Name</strong></td>
          <td><?=GetEmployer('EmployerCompany', $PAGE['JobEmployerID'])?></td>
        </tr>
        <tr>
          <td><strong>Designation Name</strong></td>
          <td><?=optionVal($PAGE['JobDesignation'])?></td>
        </tr>
        <tr>
          <td><strong>Department</strong></td>
          <td><?=optionVal($PAGE['JobDepartment'])?></td>
        </tr>
        <tr>
          <td><strong>Job Priority</strong></td>
          <td><?=ucwords($PAGE['JobPriority'])?></td>
        </tr>
        <tr>
          <td><strong>Salary Range</strong></td>
          <td><?=$PAGE['JobSalaryFrom']?>
            -
            <?=$PAGE['JobSalaryTo']?></td>
        </tr>
        <tr>
          <td><strong>Area</strong></td>
          <td><?=optionVal($PAGE['JobCity'])?></td>
        </tr>
        <tr>
          <td><strong>Job Type</strong></td>
          <td><?=optionVal($PAGE['JobType'])?></td>
        </tr>
        <tr>
          <td><strong>Shift</strong></td>
          <td><?=optionVal($PAGE['JobShift'])?></td>
        </tr>
        <tr>
          <td><strong>Last Date</strong></td>
          <td><?=$PAGE['JobLastDateApply']?></td>
        </tr>
      <td><strong>Job Description</strong></td>
        <td><?=$PAGE['JobDescription']?></td>
      </tr>
      <tr>
        <td><strong>Soft Skills</strong></td>
        <td><?=optionVal($PAGE['JobSoftSkills'])?></td>
      </tr>
      <tr>
        <td><strong>Technical Skills</strong></td>
        <td><?=optionVal($PAGE['JobSkills'])?></td>
      </tr>
      <tr>
        <td><strong>Experience </strong></td>
        <td><?=optionVal($PAGE['JobExperience'])?></td>
      </tr>
      <tr>
        <td><strong>Qualification</strong></td>
        <td><?=optionVal($PAGE['JobQualification'])?></td>
      </tr>
      </tbody>
      
    </table>
	 </div>
  </div>
</form>
<div class="modal-footer no-margin-top">
  <button type="submit" class="btn btn-primary" data-dismiss="modal" onclick=""> Close </button>
</div>

<script>

$('.slim-scroll').each(function () {
	var $this = $(this);
	$this.slimScroll({
		height: $this.data('height') || 100,
		railVisible:true
	});
});

</script>