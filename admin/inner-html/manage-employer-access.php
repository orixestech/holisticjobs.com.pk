<?php
include("../../admin/includes/conn.php");
include("../../admin_theme_functions.php");

/////////////////////////////////////
///////// Front Side Functions
include("../../site_theme_functions.php");
///////////////////////////////////// 

$uid = $_REQUEST['uid'];

$stmt = " SELECT * FROM subscriptions WHERE UID = '".$uid."' ";
$subscription = fetch( query( $stmt ) );





?>

<form id="ManageAccessForm">
  <input type="hidden" name="Module" id="Module" value="Employer" />
  <div class="modal-header no-padding">
    <div class="table-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
      Manage Access for <strong>
      <?=$subscription['PlanTitle']?>
      </strong></div>
  </div>
  <div class="modal-body form-horizontal">
    <div class="slim-scroll" data-height="500">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="ManageAccess">
        <thead>
          <tr>
            <th>Title / Description</th>
            <th width="70">Allow</th>
            <th width="70">Deny</th>
            <th width="100">Days</th>
          </tr>
        </thead>
        <tbody>
          <?php
        $stmt = query(" SELECT * FROM subscription_accesstype WHERE AccessTypeModule = 'Employer' ORDER BY AccessTypeTitle ");
		while($rslt = fetch($stmt)){
			$stmtAccess = query("SELECT * FROM `subscription_access` WHERE `AccessSubID` = '".$_REQUEST['uid']."' and `AccessTypeKey` = '".$rslt['AccessTypeKey']."'");
			$rsltAccess = fetch($stmtAccess);?>
          <tr>
            <td><input type="hidden" name="AccessTypeKey<?=$rslt['UID']?>" value="<?=$rslt['AccessTypeKey']?>"/>
              <strong>
              <?=$rslt['AccessTypeTitle']?>
              </strong><br />
              <?=ucwords($rslt['AccessTypeDesc'])?></td>
            <td><input type="radio" name="Access<?=$rslt['UID']?>" value="1" <?=($rsltAccess['AccessAllowed']==1)?'checked="checked"':''?>  /></td>
            <td><input type="radio" name="Access<?=$rslt['UID']?>" value="0" <?=($rsltAccess['AccessAllowed']==0)?'checked="checked"':''?>/></td>
            <td><input type="text" name="AccessDays<?=$rslt['UID']?>" value="<?=$rsltAccess['AccessDays']?>" class="col-xs-10 col-sm-10 validate[custom[integer]]" maxlength="3"/></td>
          </tr>
          <?php
        }?>
        </tbody>
      </table>
      <div id="Ajax-Result"></div>
    </div>
	</div>
  </div>
  <div class="modal-footer no-margin-top">
    <button type="button" class="btn btn-primary" onclick="ManageSubAccess()"> Submit </button>
  </div>
</form>
<script type="text/javascript">
	$('.slim-scroll').each(function () {
	var $this = $(this);
	$this.slimScroll({
		height: $this.data('height') || 100,
		railVisible:true
	});
});

	$('.ContentEditor').redactor({ autoresize: false });
	
	function ManageSubAccess(){
		$("form#ManageAccessForm").validationEngine('validate');
		var valid = $("#ManageAccessForm .formError").length;
		if (valid != 0){ return false; }
		
		var form_data = $('form#ManageAccessForm').serialize();
		form_data = "action=ManageSubAccess&uid=<?=$_REQUEST['uid']?>&" + form_data;
		//alert(form_data);
		$.ajax({
			cache: false, 
			type: "POST",
			url: "<?=$path?>admin/ajaxpage.php",
			data: form_data,
			dataType : 'html',
			async: false,
			success: function(data){
				$("form#ManageAccessForm #Ajax-Result").html(data);
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