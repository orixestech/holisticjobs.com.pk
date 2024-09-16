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
<form id="TotalSubscriptionForm">
<div class="modal-header no-padding">
  <div class="table-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
    All Subscribed Employers ( <?=$PAGE['PlanTitle']?> )</div>
</div>
<div class="modal-body form-horizontal">
<div class="slim-scroll" data-height="500">
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="sample-table-1">
      <thead>
        <tr>
          <th>Employer Name</th>
          <th>Subscription Expire Date</th>
		  <th>Invoice</th>
        </tr>
      </thead>
      <tbody><?php
      $stmt = query("SELECT * FROM `employer` WHERE `EmployerSubscription` = '".$_GET["uid"]."' ORDER BY EmployerSubscriptionExpire ");
	  while($rslt=fetch($stmt)){?>
        <tr>
            <td><?=$rslt['EmployerCompany']?></td>
            <td><?=(date("U", strtotime($rslt['EmployerSubscriptionExpire'])) < date("U", strtotime(date("Y-m-d"))))?'<span class="red"><strong>'.date("d F, Y", strtotime($rslt['EmployerSubscriptionExpire'])).'</strong></span>':date("d F, Y", strtotime($rslt['EmployerSubscriptionExpire']))?></td>
            <td><?=(date("U", strtotime($rslt['EmployerSubscriptionExpire'])) < date("U", strtotime(date("Y-m-d"))))?'<a class="btn btn-primary btn-sm blue" target="_blank" href="'.$path.'reports/147/subscription-invoice">Generate New Invoice</a>':'-'?> </td>
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
</form>


<script>
$('.slim-scroll').each(function () {
	var $this = $(this);
	$this.slimScroll({
		height: $this.data('height') || 100,
		railVisible:true
	});
});

</script>