<?php
include("../../admin/includes/conn.php");
include("../admin_theme_functions.php");?>

<form role="form" id="AddJobAlertForm" onsubmit="return false;">
  <div class="modal-header no-padding">
    <div class="table-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
      Add Job Alert</div>
  </div>
  <div class="modal-body form-horizontal">
    <div class="row " >
      <div class="col-xs-12 col-sm-12">
        <h4>Add Job Alert</h4>
        <div class="modal-body form-horizontal">
          <div class="form-group">
            <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Alert Title: </label>
            <div class="col-xs-5 col-sm-5">
              <input type="text" name="AlertTitle" id="AlertTitle" class="col-xs-11 col-sm-11 validate[required]" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Company: </label>
            <div class="col-xs-5 col-sm-5">
              <select name="AlertCompany" id="AlertCompany" class="col-xs-11 col-sm-11 selectstyle" >
			  	<option value="">Please Select</option><?php
				$stmt = query("SELECT UID, EmployerCompany  FROM `employer` WHERE 1 ORDER BY `employer`.`EmployerCompany` ASC ");
				while($rslt = fetch($stmt)){ ?>
					<option value="<?=$rslt['UID']?>">
					<?=$rslt['EmployerCompany']?>
					</option> <?					
				}?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Designation: </label>
            <div class="col-xs-5 col-sm-5">
              <select name="AlertDesignation" id="AlertDesignation" class="col-xs-11 col-sm-11 selectstyle" onchange="if(this.value=='other') $('#AlertDesignation_Other').removeClass('hide');">
                <?=formListOpt('designation', 0)?>
				<option value="other">Any Other</option>
              </select>
            </div>
          </div>
		  <div class="form-group hide" id="AlertDesignation_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-xs-5 col-sm-5">
                    <input type="text" id="OtherDesignation" name="OtherDesignation" placeholder="Enter Other Option" class="col-xs-11 col-sm-11" value="<?=$PAGE['OtherDesignation']?>" />
                  </div>
                </div>
          <div class="form-group">
            <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">City: </label>
            <div class="col-xs-5 col-sm-5">
              <select name="AlertArea" id="AlertArea" class="col-xs-11 col-sm-11 selectstyle" onchange="if(this.value=='other') $('#AlertArea_Other').removeClass('hide');">
                <?=formListOpt('city', 0)?>
				<option value="other">Any Other</option>
              </select>
            </div>
          </div>
		  <div class="form-group hide" id="AlertArea_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-xs-5 col-sm-5">
                    <input type="text" id="OtherAlertArea" name="OtherCity" placeholder="Enter Other Option" class="col-xs-11 col-sm-11" value="<?=$PAGE['OtherCity']?>" />
                  </div>
                </div>
          <div class="space-4"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer no-margin-top">
    <div id="Ajax-Result" class="pull-left"></div>
    <button type="button" class="btn btn-primary" onclick="validateForm();"> Submit </button>
  </div>
</form>
<script>
//LoadScripts();
function validateForm(){
	if($('#AlertTitle').val()==''){
		alert('Please enter Alert title.');
		$('#AlertTitle').focus();
		return false;
	}

	Company = document.getElementById("AlertCompany").value;
	Designation = document.getElementById("AlertDesignation").value;
	Area = document.getElementById("AlertArea").value;
	
	if((Company != "") || (Designation != "") || (Area != ""))
	{
		AddJobAlertForm();
	}
	else
	{
		alert("Please select any one option Company, Designation or Area.");
		return false;
	}
}

function AddJobAlertForm(){
	$("form#AddJobAlertForm").validationEngine('validate');
	var valid = $("#AddJobAlertForm .formError").length;
	if (valid != 0){ return false; }

	var form_data = $('form#AddJobAlertForm').serialize();
	form_data = "action=JobAlertForm&FormType=add&uid=<?=$_GET['uid']?>&" + form_data;
	$.ajax({
		cache: false, 
		type: "POST",
		url: "<?=$path?>employee/ajaxpage.php",
		data: form_data,
		dataType : 'html',
		async: false,
		success: function (data){
			$("form#AddJobAlertForm #Ajax-Result").html(data);
			setTimeout(function(){ location.href='<?=$path?>employee/job-alerts.php'; }, 800);
			return false;
		},
		error: function(){
			ALERT("Error with your request, Please try again...!", 'error');
		}
	});
}

</script>
