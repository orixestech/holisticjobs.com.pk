<?php include('header.php');?>
    <?php
if($_POST){
	$contents = $_POST;
	$target_dir = MEMBER_IMAGE_DIRECTORY;
	$nowDate = date("Y-m-d h:i:s");
	$contents['EmployerPassword'] = PassWord($_POST['EmployerPassword'],'hide');
	
	if($_GET["mode"]=="update"){
		$FormMessge = '';
		if($_FILES["image"]["name"])
		{
			$uploadOk = 1;
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'employer'. "_" . rand(0, 100000); $path_parts['filename'];
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
					$contents['EmployerLogo'] = mysql_real_escape_string($fileName.".".$fileType);
					//Delete old file.
					$existing_image = GetData('EmployerLogo','employer','UID',$_GET["code"]);
					@unlink($target_dir.$existing_image);
				}
				else
				{
					$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
				}
			}
		}
		
		if($_FILES["cover"]["name"])
		{
			$uploadOk = 1;
			$target_file = $target_dir . basename($_FILES["cover"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'employer-cover'. "_" . rand(0, 100000); $path_parts['filename'];
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
				if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file))
				{
					$contents['EmployerCover'] = mysql_real_escape_string($fileName.".".$fileType);
					//Delete old file.
					$existing_image = GetData('EmployerCover','employer','UID',$_SESSION['EmployerUID']);
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
			
			$fileName = 'employer'. "_" . rand(0, 100000); $path_parts['filename'];
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
					$contents['EmployerLogo'] = mysql_real_escape_string($fileName.".".$fileType);
				}
				else
				{
					$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
				}
			}
		}
		
		if($_FILES["cover"]["name"])
		{
			$uploadOk = 1;
			$target_file = $target_dir . basename($_FILES["cover"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'employer-cover'. "_" . rand(0, 100000); $path_parts['filename'];
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
				if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file))
				{
					$contents['EmployerCover'] = mysql_real_escape_string($fileName.".".$fileType);
				}
				else
				{
					$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
				}
			}
		}
	}
	
	
	
	if($_GET["mode"]=="update"){
		$run = FormData('employer', 'update', $contents, " UID = '".$_GET["code"]."' ", $view=false );
		$FormMessge = Alert('success', 'Employer Updated...!');
	} else {
		$run = FormData('employer', 'insert', $contents, "", $view=false );
		$FormMessge = Alert('success', 'New Employer Created...!');
	}

}
	
if($_GET["mode"]=="update"){
	$stmt = mysql_query(" SELECT * FROM `employer` WHERE UID = '".$_GET["code"]."' ");
	$PAGE = mysql_fetch_array($stmt);
} ?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li> <a href="#">Web Site</a> </li>
          <li class="active">
            <?=($_GET["mode"]=="update")?'Modify':'Add New'?>
            Employer </li>
        </ul>
        <!-- .breadcrumb -->
        <div class="nav-search" id="nav-search">
          <form class="form-search">
            <span class="input-icon">
            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
            <i class="icon-search nav-search-icon"></i> </span>
          </form>
        </div>
        <!-- #nav-search -->
      </div>
      <div class="page-content">
        <div class="page-header">
          <h1>
            <?=($_GET["mode"]=="update")?'Modify ':'Add '?>
            Employer
            Form <small> <i class="icon-double-angle-right"></i> Add or Update Employer Form </small> </h1>
        </div>
        <!-- /.page-header -->
        <?=$FormMessge?>
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" id="AdminEmployer">
          <div class="row">
            <div class="col-xs-12">
              <!-- PAGE CONTENT BEGINS -->
              <h4 class="header green">Subscription</h4>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right">Subscription Plan:<i class="icon-asterisk"></i> </label>
                <div class="col-sm-9">
                  <select name="EmployerSubscription" id="EmployerSubscription" onChange="UpdateSub(<?=$rslt['UID']?>, this.value)" class="col-xs-5 col-sm-5 selectstyle">
                    <?php
					$stmt2 = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employer' ORDER by PlanFee");
					while($rslt2=fetch($stmt2)){?>
                    <option value="<?=$rslt2['UID']?>" <?=($rslt['EmployerSubscription']==$rslt2['UID'])?'selected':''?>>
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
                  <input type="text" id="EmployerSubscriptionExpire" name="EmployerSubscriptionExpire" placeholder="Subscription Expiry Date" data-date-format="yyyy-mm-dd" class="col-xs-10 col-sm-5 date-picker validate[custom[date]]" value="<?=($PAGE['EmployerSubscriptionExpire']=='0000-00-00') ? date("Y-m-d") : date("Y-m-d", strtotime($PAGE['EmployerSubscriptionExpire']))?>" />
                </div>
              </div>
              <h4 class="header green">Account Information</h4>
              <div class="row">
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Account E-Mail:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerEmail" name="EmployerEmail" placeholder="Employer Login Email" class="col-xs-10 col-sm-5 validate[required, [custom[email]]"  required value="<?=$PAGE['EmployerEmail']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Password:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input <?=($_GET["mode"]!="update")?'type="password" id="EmployerPassword" name="EmployerPassword" ':'type="text" disabled="disabled"'?> placeholder="Employer Login Password" class="col-xs-10 col-sm-5 validate[required]" value="<?=($PAGE['EmployerPassword']!='')?PassWord($PAGE['EmployerPassword'],'show'):''?>"/>
                  </div>
                </div>
              </div>
              <h4 class="header green">Company Information</h4>
              <div class="row">
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Company Name:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerCompany" name="EmployerCompany" placeholder="Company Name" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['EmployerCompany']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerAddress" name="EmployerAddress" placeholder="Employer Address" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['EmployerAddress']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Land Line Number:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerLandLine" name="EmployerLandLine" placeholder="Employer LandLine" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['EmployerLandLine']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">City:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="EmployerCity" id="EmployerCity" class="col-xs-5 col-sm-5 selectstyle validate[required]" required>
                      <?=formListOpt('city', $PAGE['EmployerCity'])?>
                    </select>
                  </div>
                </div>
                <h4 class="header green">Contact Information</h4>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Contact Name:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerContactName" name="EmployerContactName" placeholder="Employer Contact Name" class="col-xs-10 col-sm-5" value="<?=$PAGE['EmployerContactName']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Contact Number:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerContactMobile" name="EmployerContactMobile" placeholder="Employer Contact Mobile" class="col-xs-10 col-sm-5 validate[custom[integer]]" value="<?=$PAGE['EmployerContactMobile']?>"/>
                  </div>
                </div>
                <!--<div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Contact Person Extension</label>
            <div class="col-sm-9">
              <input type="text" id="EmployerContactExt" name="EmployerContactExt" placeholder="EmployerContactExt" class="col-xs-10 col-sm-5" value="<?=$PAGE['EmployerContactExt']?>"/>
            </div>
          </div>-->
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Contact Email:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerContactEmail" name="EmployerContactEmail" placeholder="Employer Contact Email" class="col-xs-10 col-sm-5 validate[required, custom[email]]" value="<?=$PAGE['EmployerContactEmail']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Contact Person Designation:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="EmployerContactDesig" id="EmployerContactDesig" class="col-xs-5 col-sm-5 selectstyle" required>
                      <?=formListOpt('designation', $PAGE['EmployerContactDesig'])?>
                    </select>
                  </div>
                </div>
                <h4 class="header green">Public Information</h4>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">About Company:<i class="icon-asterisk"></i> </label>
                  <div class="col-sm-7">
                    <textarea class="ContentEditor" id="EmployerAboutContent" name="EmployerAboutContent" style="height: 260px;" ><?=$PAGE['EmployerAboutContent']?>
</textarea>
                  </div>
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
		$('#myTab a[href="#'+trg+'"]').trigger('click');
	}
	
	$(document).ready(function(){
		$("form#AdminEmployer").validationEngine('attach', {
			promptPosition : "centerRight", 
			scroll: false,
			onValidationComplete: function(form, status){
				if(status){
					document.AdminEmployer.submit();
				} else {
					//alert("The form status is: " +status+", it will never submit");
				}
		  }
		});
	
		$("#reset").click(function(){
			$(".formError").hide();
		});
		
	});



    </script>
    <?php include('footer.php');?>
