<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");

?>

<form role="form" id="AddEmployeeSubscriptionForm">
  <input type="hidden" name="PlanModule" value="Employee" />
  <div class="modal-header no-padding">
    <div class="table-header"> Add Employee Subscription plan </div>
  </div>
  <div class="modal-body form-horizontal">
    <h4> Add Subscription Details </h4>
    <div id="Ajax-Result"></div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Plan Title:<i class="icon-asterisk"></i> </label>
      <div class="col-xs-8 col-sm-8">
        <input type="text" id="PlanTitle" name="PlanTitle" placeholder="" class="col-xs-8 col-sm-8 validate[required]" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Plan Fee:<i class="icon-asterisk"></i> </label>
      <div class="col-xs-8 col-sm-8">
        <input type="text" id="PlanFee" name="PlanFee" placeholder="" class="col-xs-4 col-sm-4 validate[required, custom[integer]]"/>
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Plan Days:<i class="icon-asterisk"></i> </label>
      <div class="col-xs-8 col-sm-8">
        <select name="PlanDays" class="col-xs-4 col-sm-4 validate[required]"  id="PlanDays">
          <option value="">Please Select</option>
          <option value="90">3 Months</option>
          <option value="180">6 Months</option>
          <option value="365">1 Year</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Plan Description:<i class="icon-asterisk"></i> </label>
      <div class="col-xs-8 col-sm-8">
        <textarea id="PlanDesc" name="PlanDesc" placeholder="" class="col-xs-8 col-sm-8 ContentEditor"  style="height: 260px;" value=""></textarea>
      </div>
    </div>
    <div class="space-4"></div>
  </div>
  <div class="modal-footer no-margin-top">
    <button type="button" onclick="AddEmployeeSubscription()" class="btn btn-primary"> Submit </button>
  </div>
</form>
<script type="text/javascript">
	$('.ContentEditor').redactor({ autoresize: false });

	function AddEmployeeSubscription(){
		var validate = $("form#AddEmployeeSubscriptionForm").validationEngine('validate');
		var valid = $("#AddEmployeeSubscriptionForm .formError").length;
		if (valid != 0){ return false; }
		
		var form_data = $('form#AddEmployeeSubscriptionForm').serialize();
		form_data = "action=AddSubscription&" + form_data;
		//alert(form_data);
		$.ajax({
			cache: false, 
			type: "POST",
			url: "<?=$path?>admin/ajaxpage.php",
			data: form_data,
			dataType : 'html',
			async: false,
			success: function(data){
				$("form#AddEmployeeSubscriptionForm #Ajax-Result").html(data);
				setTimeout(function(){ location.href='<?=$path?>admin/employee-subscription-view.php'; }, 800);
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