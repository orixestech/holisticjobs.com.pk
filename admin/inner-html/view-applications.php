<?php
include("../../admin/includes/conn.php");
include("../../admin_theme_functions.php");

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
  <h3 style="margin-top:0;">Job Applications</h3>
  <div class="slim-scroll" data-height="500">
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="JobApplicationsData">
      <thead>
        <tr>
          <th>Applicant Details</th>
          <th width="13%">Date</th>
          <th width="15%">Resume</th>
		  <th width="15%">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
      $stmt = mysql_query(" SELECT * FROM jobs_apply WHERE `JobID` = '".$_GET["uid"]."' order by SystemDate desc ");
	  while( $application = mysql_fetch_array($stmt) ){ 
	  	$UploadedCV = ( $application['UploadedCV']=='' )? "#" : $path . "uploads/" . $application['UploadedCV'];
	  	$EmpStmt = mysql_query(" SELECT * FROM employee WHERE `UID` = '".$application['EmployeeID']."' ");
		$Employee = mysql_fetch_array($EmpStmt);
		?>
        <tr id="row-<?=$application['UID']?>">
          <td><? #print_r($Employee);?>Name: <?=optionVal($Employee['EmployeeTitle'])?>&nbsp;<?=$Employee['EmployeeName']?><br />City: <?=$application['City']?></td>
          <td><?=date("d M, Y \n h:i A", strtotime($application['SystemDate']))?></td>
          <td><a href="<?=$UploadedCV?>" >Click Here To Download </a></td>
          <td><?=$application['ApplicationStatus']?></td>
        </tr>
        <?php
      } ?>
      </tbody>
    </table>
  </div>
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
	
	$("tr#row-"+uid).remove();
}

</script>