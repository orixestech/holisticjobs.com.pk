<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");

$uid = $_REQUEST['uid'];

$stmt = " SELECT * FROM subscription_accesstype WHERE UID = '".$uid."' ";
$subscription_accesstype = fetch( query( $stmt ) );

?>

<form role="form" id="EditAccessTypeForm">
  <input type="hidden" name="uid" value="<?=$uid?>" />
  <div class="modal-header no-padding">
    <div class="table-header"> Edit Access Type </div>
  </div>
  <div class="modal-body form-horizontal">
    <h4> Edit Access Type </h4>
    <div id="Ajax-Result"></div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Key: </label>
      <div class="col-xs-8 col-sm-8">
        <?=$subscription_accesstype['AccessTypeKey']?>
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Title: </label>
      <div class="col-xs-8 col-sm-8">
        <input type="text" name="AccessTypeTitle" id="AccessTypeTitle" class="col-xs-8 col-sm-8" value="<?=$subscription_accesstype['AccessTypeTitle']?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Description: </label>
      <div class="col-xs-8 col-sm-8">
        <textarea id="AccessTypeDesc" name="AccessTypeDesc" placeholder="" class="col-xs-10 col-sm-10 ContentEditor" style="height: 260px;" ><?=$subscription_accesstype['AccessTypeDesc']?>
</textarea>
      </div>
    </div>
    <div class="space-4"></div>
  </div>
  <div class="modal-footer no-margin-top">
    <button type="button" onclick="EditAccessType()" class="btn btn-primary"> Submit </button>
  </div>
</form>
<script type="text/javascript">
	$('.ContentEditor').redactor({ autoresize: false });
	
	function EditAccessType(){
		$("form#EditAccessTypeForm").validationEngine('validate');
		var valid = $("#EditAccessTypeForm .formError").length;
		if (valid != 0){ return false; }
	
		var form_data = $('form#EditAccessTypeForm').serialize();
		form_data = "action=EditAccessType&" + form_data;
		$.ajax({
			cache: false, 
			type: "POST",
			url: "<?=$path?>admin/ajaxpage.php",
			data: form_data,
			dataType : 'html',
			async: false,
			success: function(data){
				$("form#EditAccessTypeForm #Ajax-Result").html(data);
				setTimeout(function(){ location.href='<?=$path?>admin/<?=($subscription_accesstype['AccessTypeModule']=='Employee')?'employee-access-type.php':''?><?=($subscription_accesstype['AccessTypeModule']=='Employer')?'employer-access-type.php':''?>'; }, 1500);
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
