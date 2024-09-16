<?php include('header.php');?>
<?php
if($_POST){
	$contents = $_POST;
	$target_dir = MEMBER_IMAGE_DIRECTORY;
	$nowDate = date("Y-m-d h:i:s");
	$contents['EmployeePassword'] = PassWord($_POST['EmployeePassword'],'hide');
	
	if($_GET["mode"]=="update"){
		$FormMessge = '';
		if($_FILES["image"]["name"])
		{
			$uploadOk = 1;
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'employee'. "_" . rand(0, 100000); $path_parts['filename'];
			//echo "fileName = " . $fileName . "<br />";
			
			$fileType = $path_parts['extension'];
			//echo "fileType = " . $fileType . "<br />";
			$target_file = $target_dir . $fileName . "." . $fileType;
			
			// Check if file already exists
			while(file_exists($target_file)) {
				$fileName = $fileName . "_" . rand(0, 100000);
				$target_file = $target_dir . $fileName . "." . $fileType;
			}
			//echo "target_file = " . $target_file . "<br />";
			
			// Allow certain file formats
			if(strtolower($fileType) != "jpg" && strtolower($fileType) != "png") {
				$FormMessge = Alert('error', 'Sorry, only jpg & jpeg files are allowed.');
				$uploadOk = 0;
			}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))
				{
					$contents['EmployeeLogo'] = mysql_real_escape_string($fileName.".".$fileType);
					//Delete old file.
					$existing_image = GetData('EmployeeLogo','employee','UID',$_GET["code"]);
					@unlink($target_dir.$existing_image);
				}
				else
				{
					$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
				}
			}
		}
	
	} else {
		$FormMessge = '';
		if($_FILES["image"]["name"])
		{
			$uploadOk = 1;
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'employee'. "_" . rand(0, 100000); $path_parts['filename'];
			//echo "fileName = " . $fileName . "<br />";
			
			$fileType = $path_parts['extension'];
			//echo "fileType = " . $fileType . "<br />";
			$target_file = $target_dir . $fileName . "." . $fileType;
			
			// Check if file already exists
			while(file_exists($target_file)) {
				$fileName = $fileName . "_" . rand(0, 100000);
				$target_file = $target_dir . $fileName . "." . $fileType;
			}
			//echo "target_file = " . $target_file . "<br />";
			
			// Allow certain file formats
			if(strtolower($fileType) != "jpg" && strtolower($fileType) != "png") {
				$FormMessge = Alert('error', 'Sorry, only jpg & jpeg files are allowed.');
				$uploadOk = 0;
			}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))
				{
					$contents['EmployeeLogo'] = mysql_real_escape_string($fileName.".".$fileType);
				}
				else
				{
					$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
				}
			}
		}
	}
	
	if($_GET["mode"]=="update"){
		$sub = array('UID'=>$_POST['EmployeeSubscription'], 'NewDATE'=>$_POST['EmployeeSubscriptionExpire']);
		UpdateSubscription('employee', $_GET["code"], $sub);
		
		$run = FormData('employee', 'update', $contents, " UID = '".$_GET["code"]."' ", $view=false ); 
		$FormMessge = Alert('success', 'Employee Updated...!');
	} else {
		$run = FormData('employee', 'insert', $contents, "", $view=false );
		$FormMessge = Alert('success', 'New Employee Created...!');
	}
}
	
if($_GET["mode"]=="update"){
	$stmt = mysql_query(" SELECT * FROM `employee` WHERE UID = '".$_GET["code"]."' ");
	$PAGE = mysql_fetch_array($stmt);
} ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li> <a href="employee-view.php">Employees</a> </li>
      <li class="active">
        <?=($_GET["code"])?'Modify':'Add New'?>
        Employee </li>
    </ul>
    <!-- .breadcrumb -->
    <!-- #nav-search -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1>
        <?=($_GET["code"])?'Modify':'Add Employee'?>
        Form <small> <i class="icon-double-angle-right"></i> Add or Update Employee Form </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" id="EmployeeForm">
      <div class="row">
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
		  <h4 class="header green">Subscription</h4>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">Subscription Plan:<i class="icon-asterisk"></i> </label>
                <div class="col-sm-9">
                  <select name="EmployeeSubscription" id="EmployeeSubscription" class="col-xs-5 col-sm-5 selectstyle">
                    <?php
					$stmt2 = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employee' ORDER by PlanFee");
					while($rslt2=fetch($stmt2)){?>
                    <option value="<?=$rslt2['UID']?>" <?=($PAGE['EmployeeSubscription']==$rslt2['UID'])?'selected':''?>>
                    <?=$rslt2['PlanTitle']?>
                    for
                    <?=($rslt2['PlanFee']==0)?'Free':"Rs. ".$rslt2['PlanFee']?>
                    </option>
                    <?php
					}?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">Expiry Date</label>
                <div class="col-sm-9">
                  <input type="text" id="EmployeeSubscriptionExpire" name="EmployeeSubscriptionExpire" placeholder="Subscription Expiry Date" data-date-format="yyyy-mm-dd" class="col-xs-10 col-sm-5 date-picker validate[custom[date]]" value="<?=($PAGE['EmployeeSubscriptionExpire']=='0000-00-00') ? date("Y-m-d") : date("Y-m-d", strtotime($PAGE['EmployeeSubscriptionExpire']))?>" />
                </div>
              </div>
          <h4 class="header green">Login Details</h4>
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">E-Mail Address:<i class="icon-asterisk"></i></label>
            <div class="col-sm-9">
              <input type="text" id="EmployeeEmail" name="EmployeeEmail" placeholder"EmployeeEmail" class="col-xs-5 col-sm-5  validate[required, custom[email]]"  value="<?=$PAGE['EmployeeEmail']?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Password:<i class="icon-asterisk"></i></label>
            <div class="col-sm-9">
              <input type="text" id="EmployeePassword" name="EmployeePassword" placeholder"EmployeePassword" class="col-xs-5 col-sm-5 validate[required]" value="<?=($PAGE['EmployeePassword']!='')?PassWord($PAGE['EmployeePassword'],'show'):''?>" />
            </div>
          </div>
          <div class="space-4"></div>
          <h4 class="header green">Basic Details</h4>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Employee Name:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <input type="text" id="EmployeeName" name="EmployeeName" placeholder="EmployeeName" class="col-xs-5 col-sm-5 validate[required]" value="<?=$PAGE['EmployeeName']?>" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date of Birth</label>
              <div class="col-sm-9">
                <input type="text" id="EmployeeDOB" name="EmployeeDOB" placeholder="EmployeeDOB" data-date-format="yyyy-mm-dd" class="col-xs-5 col-sm-5 date-picker validate[custom[date],future[1950-01-01],past[2005-01-01]]" value="<?=date("Y-m-d", strtotime($PAGE['EmployeeDOB']))?>" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Gender:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <select name="EmployeeGender" id="EmployeeGender" class="col-xs-5 col-sm-5 selectstyle validate[required]" >
                  <option value="Male" <?=($PAGE['EmployeeSubscription']=='Male')?'selected':''?>>Male</option>
                  <option value="Female" <?=($PAGE['EmployeeSubscription']=='Female')?'selected':''?>>Female</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">City:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <select name="EmployeeCity" id="EmployeeCity" class="col-xs-5 col-sm-5 selectstyle validate[required]"  >
                  <?=formListOpt('city', $PAGE['EmployeeCity'])?>
                </select>
              </div>
            </div>
          <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
              <button type="submit" class="btn btn-info"> <i class="icon-ok bigger-110"></i> Submit </button>
              <button type="reset" class="btn"> <i class="icon-undo bigger-110"></i> Reset </button>
            </div>
          </div>
          <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </form>
  </div>
  <!-- /.page-content -->
</div>
<!-- /.main-content -->
<script>



    function nexttab(trg){
		$("form#EmployeeForm").validationEngine('validate');
		var valid = $("form#EmployeeForm .formError").length;
		//alert(valid);
		if (valid != 0){
			return false; 
		} else {
			$('#myTab a[href="#'+trg+'"]').trigger('click');
		}
		return false;
	}
	
	$(document).ready(function(){
		$("form#EmployeeForm").validationEngine('attach', {
			promptPosition : "centerRight", 
			scroll: false,
			onValidationComplete: function(form, status){
				if(status){
					document.EmployeeForm.submit();
				} else {
					//alert("The form status is: " +status+", it will never submit");
				}
		  }
		});
	
		$("#reset").click(function(){
			$(".formError").hide();
		});
		
		
		$.mask.definitions['~']='[+-]';
		$('form#EmployeeForm #EmployeeNIC').mask('99999-9999999-9');
		$('form#EmployeeForm #EmployeeMobile').mask('9999-9999999');
		$('form#EmployeeForm #EmployeeDOB').mask('9999-99-99');
		$('form#EmployeeForm #EmployeeLandLine').mask('999-9999999');

	});


    </script>
<?php include('footer.php');?>
