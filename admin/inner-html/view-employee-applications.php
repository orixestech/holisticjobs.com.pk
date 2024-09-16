<?php
include("../../admin/includes/conn.php");
include("../../admin_theme_functions.php");

/////////////////////////////////////
///////// Front Side Functions
include("../../site_theme_functions.php");
///////////////////////////////////// 
$stmt = mysql_query(" SELECT * FROM `employee` WHERE `UID` = '".$_GET["uid"]."' ");
$PAGE = mysql_fetch_array($stmt);
?>

<div class="modal-header no-padding">
  <div class="table-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
    Job Applications (
    <?=$PAGE['EmployeeName']?>
    )</div>
</div>
<div class="modal-body form-horizontal">
  <h3 style="margin-top:0;">Job Applications</h3>
  <div class="slim-scroll" data-height="500">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover "> <?php
		$sql = "
			SELECT `jobs_apply`.*, `jobs_apply`.SystemDate as ApplyDate , `jobs`.`JobTitle` , `employer`.`EmployerCompany`
			FROM `jobs_apply`
			INNER JOIN `jobs` ON (`jobs_apply`.`JobID` = `jobs`.`UID`)
			INNER JOIN `employer` ON (`jobs`.`JobEmployerID` = `employer`.`UID`)
			WHERE (`jobs_apply`.`EmployeeID`  = '".$_GET["uid"]."' ); ";
		$rs_pages = query($sql) or die($sql);
		$row = mysql_num_rows( $rs_pages ); ?>
        <thead>
          <tr>
            <th>Sr. No</th>
            <th>Job Title</th>
            <th>Company</th>
            <th>Applied Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
		if($row>0){ $count=1;
			while( $rslt = fetch($rs_pages) ){ ?>
                <tr>
                  <td><?=$count?></td>
                  <td><?=$rslt['JobTitle']?></td>
                  <td><?=$rslt['EmployerCompany']?></td>
                  <td><?=date("d M, Y h:i A", strtotime($rslt['ApplyDate']))?></td>
                  
                </tr>
                <? $count++;
			}
		} else { ?>
          <tr>
            <th class="center" colspan="4">No Records Found.!</th>
          </tr> <?
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