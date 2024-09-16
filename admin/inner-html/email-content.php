<?php
include("../../admin/includes/conn.php");
include("../../admin_theme_functions.php");

/////////////////////////////////////
///////// Front Side Functions
include("../../site_theme_functions.php");
///////////////////////////////////// 

?>

<form role="form" id="EmailContentForm">
  <div class="modal-header no-padding">
    <div class="table-header"> Email Content </div>
  </div>
  <div class="modal-body form-horizontal">
    <h4> Add Email Content </h4>
    <div id="Ajax-Result"></div>
    <div class="form-group">
      <div class="col-xs-8 col-sm-8">
        <textarea id="EmailContent" name="EmailContent" placeholder="" class="col-xs-8 col-sm-8 ContentEditor"  style="height: 260px;" value=""></textarea>
      </div>
    </div>
    <div class="space-4"></div>
  </div>
  <div class="modal-footer no-margin-top">
    <button type="button" onclick="EmailContent()" class="btn btn-primary"> Submit </button>
  </div>
</form>
<script type="text/javascript">
	$('.ContentEditor').redactor({ autoresize: false });

	function EmailContent(){
		var validate = $("form#EmailContentForm").validationEngine('validate');
		var valid = $("#EmailContentForm .formError").length;
		if (valid != 0){ return false; }
		
		var form_data = $('form#EmailContentForm').serialize();
		form_data = "action=EmailContent&" + form_data;
		//alert(form_data);
		$.ajax({
			cache: false, 
			type: "POST",
			url: "<?=$path?>admin/ajaxpage.php",
			data: form_data,
			dataType : 'html',
			async: false,
			success: function(data){
				$("form#EmailContentForm #Ajax-Result").html(data);
				setTimeout(function(){ location.href='<?=$path?>admin/subscription-view.php'; }, 800);
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