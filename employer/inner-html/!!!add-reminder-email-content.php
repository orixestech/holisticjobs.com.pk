<?php
include("../../admin/includes/conn.php");
include("../../admin_theme_functions.php");
include("../../site_theme_functions.php");

print_r($_REQUEST);

echo $EMAILHTML = '<p>Dear Portal Member!</p>
			  <p>We are hereby pleased to inform you that you have been invited for the interview at for the post of . Login to your account and visit "Invitation " section to accept or reject this interview.<br> 
			  Once you accept it then you will be contacted with the details of the interview soon.</p><br>
			  <p>All the best for your job hunt!</p>';

$EMAILHEAD = '';
$HEAD = '';
$formAction = '';

$ApplicationID = $_GET['uid'];


?>

<div class="modal-header no-padding">
  <div class="table-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
    Add Reminders</div>
</div>
<div class="modal-body form-horizontal">
  <h3 style="margin-top:0;">Set a Reminder for an Applicant</h3>
  <div class="slim-scroll" data-height="500">
    <form role="form" id="ReminderContentForm" name="ReminderContentForm">
      <input type="hidden" name="ReminderJobAppID" value="<?=$ApplicationID?>" />
      <div id="Ajax-Result"></div>
	  <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Reminder Date:</label>
        <div class="col-sm-9">
          <input type="text" name="ReminderDate" id="ReminderDate" class="col-xs-5 col-sm-5 date-picker"  />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Title:</label>
        <div class="col-sm-9">
          <input type="text" name="ReminderTitle" id="ReminderTitle" class="col-xs-5 col-sm-5"  />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Email Content:</label>
        <div class="col-sm-9">
          <textarea id="EmailContent" name="EmailContent" class="ContentEditor col-sm-12"  style="height: 250px; width:350px"><?=$EMAILHTML?></textarea>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal-footer no-margin-top">
  <button type="button" onclick="" class="btn btn-success"> Submit</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="EmployeeInvitation()"> Close </button>
</div>
<script>

$('.ContentEditor').redactor({ autoresize: false });
$("form#ReminderContentForm").validationEngine('validate');
$('.date-picker').datepicker({autoclose:true}).next().on(ace.click_event, function(){
	$(this).prev().focus();
});


function EmployeeInvitation(){
	alert("Form Ready to Process..."); return false;
	var validate = $("form#ReminderContentForm").validationEngine('validate');
	var valid = $("#ReminderContentForm .formError").length;
	if (valid != 0){ return false; }
	
	
	
	alert("Form Submit;;;;");
	
}
	
</script> 
