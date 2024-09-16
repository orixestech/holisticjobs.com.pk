<?php
include("../../admin/includes/conn.php");
include("../../admin_theme_functions.php");

/////////////////////////////////////
///////// Front Side Functions
include("../../site_theme_functions.php");
///////////////////////////////////// 

$stmt = mysql_query(" SELECT * FROM subscriptions WHERE `UID` = '".$_GET["uid"]."' ");
$PAGE = mysql_fetch_array($stmt);
?>

  <div class="modal-header no-padding">
    <div class="table-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
      All Subscribed Employees (
      <?=$PAGE['PlanTitle']?>
      )</div>
  </div>
  <div class="modal-body form-horizontal">
  <div class="slim-scroll" data-height="500">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="sample-table-1">
        <thead>
          <tr>
            <th>Employee Name</th>
            <th>Subscription Expire Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
      $stmt = query("SELECT * FROM `employee` WHERE `EmployeeSubscription` = '".$_GET["uid"]."' ORDER BY EmployeeSubscriptionExpire ");
	  while($rslt=fetch($stmt)){?>
          <tr>
            <td><a href="<?=$path?>admin/employee.php?mode=update&code=<?=$rslt['UID']?>">Edit</a> | <?=$rslt['EmployeeName']?></td>
            <td><?php if($rslt['EmployeeSubscriptionExpire']=='0000-00-00'){ echo "N/A"; } else { ?>
						<?=(date("U", strtotime($rslt['EmployeeSubscriptionExpire'])) < date("U", strtotime(date("Y-m-d"))))?'<span class="red"><strong>'.date("d F, Y", strtotime($rslt['EmployeeSubscriptionExpire'])).'</strong></span>':date("d F, Y", strtotime($rslt['EmployeeSubscriptionExpire']))?>
			<?php } ?>
			</td>
          </tr>
          <?php
      }?>
        </tbody>
      </table>
    </div>
	</div>
  </div>
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