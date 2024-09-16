<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");

$uid = $_REQUEST['uid'];

$stmt = " SELECT * FROM subscriptions WHERE UID = '".$uid."' ";
$subscription = fetch( query( $stmt ) );

?>

<form role="form" id="EditEmployerSubscriptionForm">
  <input type="hidden" name="uid" value="<?=$uid?>" />
  <div class="modal-header no-padding">
    <div class="table-header"> Edit Employer Subscription </div>
  </div>
  <div class="modal-body form-horizontal">
    <h4> Edit Emloyer Subscription Details </h4>
    <div id="Ajax-Result"></div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Plan Title:<i class="icon-asterisk"></i> </label>
      <div class="col-xs-8 col-sm-8">
        <input type="text" id="PlanTitle" name="PlanTitle" placeholder="" class="col-xs-8 col-sm-8" value="<?=$subscription['PlanTitle']?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Plan Fee:<i class="icon-asterisk"></i> </label>
      <div class="col-xs-8 col-sm-8">
        <input type="text" id="PlanFee" name="PlanFee" placeholder="" class="col-xs-4 col-sm-4 validate[custom[integer]]" value="<?=$subscription['PlanFee']?>"/>
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Plan Days:<i class="icon-asterisk"></i> </label>
      <div class="col-xs-7 col-sm-7">
        <select name="PlanDays" class="col-xs-4 col-sm-4"  id="PlanDays">
          <option value="">Please Select</option>
          <option value="90" <?=($subscription['PlanDays']=='90')?'selected':''?>>3 Months</option>
          <option value="180"<?=($subscription['PlanDays']=='180')?'selected':''?>>6 Months</option>
          <option value="365"<?=($subscription['PlanDays']=='365')?'selected':''?>>1 Year</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Plan Description:<i class="icon-asterisk"></i> </label>
      <div class="col-xs-8 col-sm-8">
        <textarea id="PlanDesc" name="PlanDesc" placeholder="" class="col-xs-8 col-sm-8 ContentEditor" style="height: 260px;" ><?=$subscription['PlanDesc']?></textarea>
      </div>
    </div>
    <div class="space-4"></div>
  </div>
  <div class="modal-footer no-margin-top">
    <button type="button" onclick="EditEmployerSubscription()" class="btn btn-primary"> Submit </button>
  </div>
</form>
<script type="text/javascript">
	$('.ContentEditor').redactor({ autoresize: false });
	
	function EditEmployerSubscription(){
		$("form#EditEmployerSubscriptionForm").validationEngine('validate');
		var valid = $("#EditEmployerSubscriptionForm .formError").length;
		if (valid != 0){ return false; }
	
		var form_data = $('form#EditEmployerSubscriptionForm').serialize();
		form_data = "action=EditSubscription&" + form_data;
		$.ajax({
			cache: false, 
			type: "POST",
			url: "<?=$path?>admin/ajaxpage.php",
			data: form_data,
			dataType : 'html',
			async: false,
			success: function(data){
				$("form#EditEmployerSubscriptionForm #Ajax-Result").html(data);
				setTimeout(function(){ location.href='<?=$path?>admin/employer-subscription-view.php'; }, 800);
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