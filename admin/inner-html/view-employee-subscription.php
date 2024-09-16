<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");

$uid = $_REQUEST['uid'];

$stmt = " SELECT * FROM subscriptions WHERE UID = '".$uid."' ";
$subscription = fetch( query( $stmt ) );?>

<div class="modal-header no-padding">
  <div class="table-header"> Subscription Details</div>
</div>
<div class="modal-body form-horizontal">
  <h3>Subscription Details</h3>
  <table class="table table-striped table-bordered table-hover">
    <tbody>
      <tr>
        <td width="150"><strong>Plan Title </strong></td>
        <td><?=$subscription['PlanTitle']?></td>
      </tr>
      <tr>
        <td width="150"><strong>Plan Fee </strong></td>
        <td>Rs.
          <?=$subscription['PlanFee']?></td>
      </tr>
      <tr>
        <td><strong>Plan Days</strong></td>
        <td><?=round($subscription['PlanDays']/30, 0)?>
          Months</td>
      </tr>
      <tr>
        <td width="150"><strong>Plan Description </strong></td>
        <td><?=$subscription['PlanDesc']?></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="modal-footer no-margin-top">
  <button type="button" class="btn btn-primary" data-dismiss="modal"> Close </button>
</div>
