<?php
include("../../admin/includes/conn.php");
include("../../admin_theme_functions.php");
/////////////////////////////////////
///////// Front Side Functions
include("../../site_theme_functions.php");
/////////////////////////////////////

$EMAILHTML = '<strong>Dear Portal Member!</strong><br ><br >
			  <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; We are hereby pleased to inform you that you have been invited by <strong class="red">"||COMPNAME||"</strong> for the post of  <strong class="red">"||DESIGNATION||"</strong> in <strong class="red"> "||CITY||"</strong>. Login to your account and visit "My Invitations" section to accept or reject this invitation. Once you accept it, only then employer can view your complete profile and proceed with the further procedure of shortlisting.</p><br />
';
			  
$EMAILHEAD = 'Employee Invitation';
$HEAD = 'Employee Invitation Form';
$formAction = 'EmployeeInvitation';?>

<div class="modal-header no-padding">
  <div class="table-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
    Invite an Employee</div>
</div>
<div class="modal-body form-horizontal">
  <h3 style="margin-top:0;">Invitation Details</h3>
  <div class="slim-scroll" data-height="500">
    <form role="form" id="InviteContentForm">
      <input type="hidden" name="EmployeeUID" value="<?=$_GET["uid"]?>" />
      <div id="Ajax-Result"><i class="fa fa-search fa-1"></i></div>
      <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Invitation Title:</label>
        <div class="col-sm-9">
          <select name="InviteTitle" id="InviteTitle" class="col-xs-5 col-sm-5 selectstyle  validate[required]" onchange="if(this.value=='AdvertisedJob') $('#JobTitle_Dropdown').removeClass('hide'); else $('#JobTitle_Dropdown').addClass('hide'); if(this.value=='Open') $('#designation_dropdown').removeClass('hide') && $('#AdvertisedCity_dropdown').addClass('hide') && $('#City_dropdown').removeClass('hide'); else $('#designation_dropdown').addClass('hide') && $('#City_dropdown').addClass('hide');" >
            <option value="">Please Select</option>
            <option value="Open">Open Invitation</option>
            <option value="AdvertisedJob">Advertised Job Invitation</option>
          </select>
        </div>
      </div>
      <div class="form-group hide" id="JobTitle_Dropdown">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Job Title:</label>
        <div class="col-sm-9">
          <?php $sql=" SELECT * FROM `jobs` WHERE `JobEmployerID` = '".$_SESSION['EmployerUID']."'  ";?>
          <select name="JobTitle" id="JobTitle" class="col-xs-5 col-sm-5 selectstyle  validate[required]" onchange="if(this.value) $('#AdvertisedCity_dropdown').removeClass('hide'); else $('#AdvertisedCity_dropdown').addClass('hide'); LoadJobIDCity( this.value );" >
            <option value="">Please select</option>
            <?php
			$stmt = query($sql);
			while($rslt = fetch($stmt)){
				echo '<option value="'.$rslt['UID'].'">'.$rslt['JobTitle'].' Expire on '.date( "d M, Y", strtotime($rslt['JobLastDateApply']) ).'</option>';
			}?>
          </select>
        </div>
      </div>
      <div class="form-group hide" id="designation_dropdown">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Designation:</label>
        <div class="col-sm-9">
          <select name="JobDesignation" id="JobDesignation" class="col-xs-5 col-sm-5 selectstyle validate[required]" >
            <?=formListOpt('designation', 0)?>
          </select>
        </div>
      </div>
      <div class="form-group hide" id="City_dropdown">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">City:</label>
        <div class="col-sm-9">
          <select name="AllCity" id="AllCity" class="col-xs-5 col-sm-5 selectstyle validate[required]" >
            <?=formListOpt('city',0)?>
          </select>
        </div>
      </div>
      <div class="form-group hide" id="AdvertisedCity_dropdown">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">City:</label>
        <div class="col-sm-9">
          <?php $sql=" SELECT * FROM `jobs` WHERE `JobEmployerID` = '".$_SESSION['EmployerUID']."'  ";?>
          <select name="JobCity" id="JobCity" class="col-xs-5 col-sm-5 selectstyle  validate[required]" >
            <option value="">Please select</option>
            <?php
			$stmt = query($sql);
			while($rslt = fetch($stmt)){
				?> <option value="<?=$rslt['UID']?>-123"><?=$rslt['JobCity']?></option> <?php
			}?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Email Content: <br />
        <i class="red">Do not remove below code from your message.<br />
        ||COMPNAME||<br />
        ||DESIGNATION||<br />
        ||CITY||<br />
        this will auto change with your submission.</i></label>
        <div class="col-sm-9">
          <textarea id="EmailContent" name="EmailContent" class="ContentEditor col-sm-12"  style="height: 250px; width:350px"><?=$EMAILHTML?>
</textarea>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal-footer no-margin-top">
  <button type="button" onclick="EmployeeInvitation()" class="btn btn-success"> Submit</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal" onclick=""> Close </button>
</div>
<script>

$('.ContentEditor').redactor({ autoresize: false });
$("div#InviteContent form#InviteContentForm").validationEngine('validate');

setTimeout(function(){ $("div#InviteContent form#InviteContentForm").validationEngine('validate'); }, 3000);

function EmployeeInvitation(){
	$("div#InviteContent form#InviteContentForm").validationEngine('validate');
	//alert("Form Ready to Process..."); //return false;
	var validate = $("div#InviteContent form#InviteContentForm").validationEngine('validate');
	var valid = $("div#InviteContent form#InviteContentForm .formError").length;
	//alert(valid);
	if (valid != 0){ alert("Please clear validation."); return false; }
	
	var form_data = $('div#InviteContent form#InviteContentForm').serialize();
	form_data = "action=<?=$formAction?>&email_head=<?=$EMAILHEAD?>&type=<?=$type?>&" + form_data;
	//alert(form_data); return false;
	$.ajax({
		cache: false, 
		type: "POST",
		url: "<?=$path?>employer/ajaxpage.php",
		data: form_data,
		dataType : 'html',
		async: false,
		success: function(data){
			$("form#InviteContentForm #Ajax-Result").html(data);
			setTimeout(function(){ window.location.reload(true); }, 3000);
			return false;
		},
		error: function(){
		}
	});
}

function LoadJobIDCity( UID ){
	ajaxreq('ajaxpage.php', 'action=LoadJobIDCity&jobid='+UID, 'JobCity');
}
	
</script>
