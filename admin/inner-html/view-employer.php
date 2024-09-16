<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");

$uid = $_REQUEST['uid'];

$stmt = " SELECT * FROM employer WHERE UID = '".$uid."' ";
$employer = fetch( query( $stmt ) );
 ?>

<div class="modal-header no-padding">
  <div class="table-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
    Employer Details </div>
</div>
 <div class="slim-scroll" data-height="500">
  <div class="modal-body">
      <h3>Subscription Details</h3>
    <table class="table table-striped table-bordered table-hover">
      <tbody>
        <tr>
          <td width="200"><strong>Subscription Plan</strong></td>
          <td><?=GetData('PlanTitle','subscriptions','UID',$employer['EmployerSubscription'])?></td>
        </tr>
        <tr>
          <td width="200"><strong>Expire Date</strong></td>
            <td><?=(date("U", strtotime($employer['EmployerSubscriptionExpire'])) < date("U", strtotime(date("Y-m-d"))))?'<span class="red"><strong>'.date("d F, Y", strtotime($employer['EmployerSubscriptionExpire'])).'</strong></span>':date("d F, Y", strtotime($employer['EmployerSubscriptionExpire']))?></td>
        </tr>
      </tbody>
    </table>

    <h3>Login Details <span id="SendLoginDetails" class=" pull-right"><button onclick="SendLoginDetails('employer',<?=$uid?>,'ViewEmployer span#SendLoginDetails')" class="btn btn-primary btn-xs" type="submit"> Send Login Details </button></span></h3>
    <table class="table table-striped table-bordered table-hover">
      <tbody>
        <tr>
          <td width="200"><strong>Login Email</strong></td>
          <td><?=$employer['EmployerEmail']?></td>
        </tr>
        <tr>
          <td width="200"><strong>Login Password</strong></td>
          <td><?=PassWord($employer['EmployerPassword'],'show')?></td>
        </tr>
      </tbody>
    </table>
    <h3>Employer Details</h3>
    <table class="table table-striped table-bordered table-hover">
      <tbody>
        <tr>
          <td width="200"><strong>Company Name</strong></td>
          <td><?=$employer['EmployerCompany']?></td>
        </tr>
        <tr>
          <td width="200"><strong>Address</strong></td>
          <td><?=$employer['EmployerAddress']?></td>
        </tr>
        <tr>
          <td width="200"><strong>LandLine</strong></td>
          <td><?=$employer['EmployerLandLine']?></td>
        </tr>
        <tr>
          <td width="200"><strong>State</strong></td>
          <td><?=optionVal($employer['EmployerState'])?></td>
        </tr>
        <tr>
          <td width="200"><strong>City</strong></td>
          <td><?=optionVal($employer['EmployerCity'])?></td>
        </tr>
        <tr>
          <td width="200"><strong>Website</strong></td>
          <td><a href="<?=$employer['EmployerWeb']?>" target="_blank"><?=$employer['EmployerWeb']?></a></td>
        </tr>
      </tbody>
    </table>
    <h3>Contact Person Details</h3>
    <table class="table table-striped table-bordered table-hover">
      <tbody>
	  <tr>
          <td width="200"><strong>Contact Title</strong></td>
          <td><?=optionVal($employer['EmployerContactTitle'])?> <?=$employer['EmployerContactName']?></td>
        </tr>
        <tr>
          <td width="200"><strong>Contact Person Name</strong></td>
          <td><?=optionVal($employer['EmployerCompany'])?> <?=$employer['EmployerCompany']?></td>
        </tr>
        <tr>
          <td width="200"><strong>Contact Person Designation</strong></td>
          <td><?=optionVal($employer['EmployerContactDesig'])?></td>
        </tr>
		<tr>
          <td width="200"><strong>Contact Person Mobile</strong></td>
          <td><?=$employer['EmployerContactMobile']?></td>
        </tr>
        <tr>
          <td width="200"><strong>Contact Person Email</strong></td>
          <td><?=$employer['EmployerContactEmail']?></td>
        </tr>
      </tbody>
    </table>
	<h3>Public Profile</h3>
    <table class="table table-striped table-bordered table-hover">
      <tbody>
	  <tr>
          <td width="200"><strong>About Company</strong></td>
          <td><?=$employer['EmployerAboutContent']?></td>
        </tr>
        <tr>
          <td width="200"><strong>Key Achievements</strong></td>
          <td><?=$employer['EmployerAchieveContent']?></td>
        </tr>
      </tbody>
    </table>
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