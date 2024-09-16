<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");

$uid = $_REQUEST['uid'];

$stmt = " SELECT * FROM subscription_accesstype WHERE UID = '".$uid."' ";
$subscription_accesstype = fetch( query( $stmt ) );?>

<div class="modal-header no-padding">
  <div class="table-header">Access Type</div>
</div>
<div class="modal-body form-horizontal">
  <h3>Subscription Access Type</h3>
  <table class="table table-striped table-bordered table-hover">
    <tbody>
      <tr>
        <td width="150"><strong>Key</strong></td>
        <td><?=$subscription_accesstype['AccessTypeKey']?></td>
      </tr>
      <tr>
        <td width="150"><strong>Title </strong></td>
        <td><?=$subscription_accesstype['AccessTypeTitle']?></td>
      </tr>
      <tr>
        <td><strong>Description</strong></td>
        <td><?=$subscription_accesstype['AccessTypeDesc']?></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="modal-footer no-margin-top">
  <button type="button" class="btn btn-primary" data-dismiss="modal"> Close </button>
</div>
