<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");

?>

<form role="form" id="UpdateInvoiceForm">
  <div class="modal-header no-padding">
    <div class="table-header"> Update Invoice </div>
  </div>
  <div class="modal-body form-horizontal">
    <h4>Update Invoice Details </h4>
    <div id="Ajax-Result"></div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Status: </label>
      <div class="col-xs-8 col-sm-8">
        <select name="Status" class="col-xs-4 col-sm-4"  id="Status">
          <option value="">UnPaid</option>
          <option value="">Paid</option>
        </select>
      </div>
    </div>
	<div class="form-group">
              <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1"> Subscription Expiry Date:</label>
              <div class="row">
                <div class="col-xs-4 col-sm-4">
                  <div class="input-group">
                    <input class="form-control validate[required,custom[date]]" name="ExpiryDate" id="ExpiryDate" type="text" value="<?=date("Y-m-d")?>" />
                  </div>
                </div>
              </div>
            </div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Remarks:* </label>
      <div class="col-xs-8 col-sm-8">
        <textarea id="Remarks" name="Remarks" placeholder="" class="col-xs-8 col-sm-8 ContentEditor"  style="height: 150px;" value=""></textarea>
      </div>
    </div>
    <div class="space-4"></div>
  </div>
  <div class="modal-footer no-margin-top">
    <button type="button" onclick="UpdateInvoice()" class="btn btn-primary"> Submit </button>
  </div>
</form>
<script type="text/javascript">
	$('.ContentEditor').redactor({ autoresize: false });

	function UpdateInvoice(){
		var validate = $("form#UpdateInvoiceForm").validationEngine('validate');
		var valid = $("#UpdateInvoiceForm .formError").length;
		if (valid != 0){ return false; }
		
	
		var form_data = $('form#UpdateInvoiceForm').serialize();
		form_data = "action=UpdateInvoice&uid=<?=$_GET['uid']?>&" + form_data;
		$.ajax({
			cache: false, 
			type: "POST",
			url: "<?=$path?>ajax-page.php",
			data: form_data,
			dataType : 'html',
			async: false,
			success: function(data){
				$("form#UpdateInvoiceForm #Ajax-Result").html(data);
				setTimeout(function(){ location.href='<?=$path?>subscription/list'; }, 800);
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