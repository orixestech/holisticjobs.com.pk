<?php include('header.php');

if($_POST){
	$contents = $_POST;
	$target_dir = MEMBER_IMAGE_DIRECTORY;
	$nowDate = date("Y-m-d h:i:s");
	$contents['EmployerPassword'] = PassWord($_POST['EmployerPassword'],'hide');
	$_GET["mode"]='';
	

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
				$existing_image = GetData('EmployerLogo','employer','UID',$_SESSION['EmployerUID']);
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
	
	if($contents['EmployerCity'] == 'other' && $_REQUEST['OtherCity'] !='' ){
		$type = GetData("TypeId","typedata","TypeFieldName","city");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherCity'] )."', '".$_REQUEST['OtherCity']."', '0');";
		$stmt = mysql_query($sql);
		$contents['EmployerCity'] = mysql_insert_id();
		AdminEmailForDropDown();
	}
	
	if($contents['EmployerContactDesig'] == 'other' && $_REQUEST['OtherEmployerContactDesig'] !='' ){
		$type = GetData("TypeId","typedata","TypeFieldName","designation");
		$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherEmployerContactDesig'] )."', '".$_REQUEST['OtherEmployerContactDesig']."', '0');";
		$stmt = mysql_query($sql);
		$contents['EmployerContactDesig'] = mysql_insert_id();
		AdminEmailForDropDown();
	}
	

	$run = FormData('employer', 'update', $contents, " UID = '".$_SESSION['EmployerUID']."' ", $view=false );
	$FormMessge = Alert('success', 'Employer Updated...!');

}
	
$stmt = mysql_query(" SELECT * FROM `employer` WHERE UID = '".$_SESSION['EmployerUID']."' ");
$PAGE = mysql_fetch_array($stmt); ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active"> Company Profile </li>
    </ul>
    <!-- .breadcrumb -->
    <div id="SubscriptionExpireStatus" class="pull-right"><?=$SubscriptionExpireStatus?></div>
    <!-- #nav-search --> 
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Update Profile <small> <i class="icon-double-angle-right"></i> Update Public Profile </small> <a href="<?=EmployerProfileLink($_SESSION['EmployerUID'])?>" target="_blank" class="pull-right btn btn-success">View Public Profile</a></h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <?=$FormMessge?>
        <form class="form-horizontal" role="form" method="post" id="EmployerProfile" enctype="multipart/form-data">
          <div class="tabbable ">
            <ul class="nav nav-tabs" id="myTab" style="height:36px;">
              <li class="active"> <a data-toggle="tab" href="#basic-details"> Basic Details </a> </li>
              <li> <a data-toggle="tab" href="#contact-person"> Contact Person </a> </li>
              <li> <a data-toggle="tab" href="#public-profile"> Public Profile </a> </li>
              <li> <a data-toggle="tab" href="#media"> Media </a> </li>
            </ul>
            <div class="tab-content form-horizontal">
              <div id="basic-details" class="tab-pane in active ">
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Company Name:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerCompany" name="EmployerCompany" placeholder="EmployerCompany" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['EmployerCompany']?>"/>
                  </div>
                </div>
                <div class="space-4"></div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right"> Address:<i class="icon-asterisk"></i> </label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerAddress" name="EmployerAddress" placeholder="EmployerAddress" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['EmployerAddress']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Land Line Number:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerLandLine" name="EmployerLandLine" placeholder="EmployerLandLine" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['EmployerLandLine']?>"/>
                  </div>
                </div>
                <div class="space-4"></div>
                <!--<div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Fax Number</label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerFax" name="EmployerFax" placeholder="EmployerFax" class="col-xs-10 col-sm-5" value="<?=$PAGE['EmployerFax']?>"/>
                  </div>
                </div>-->
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Province or State:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="EmployerState" id="EmployerState" class="col-xs-5 col-sm-5 selectstyle validate[required]" required>
                      <?=formListOpt('state', $PAGE['EmployerState'])?>
                    </select>
                  </div>
                </div>
                <div class="space-4"></div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">City:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="EmployerCity" id="EmployerCity" class="col-xs-5 col-sm-5 selectstyle validate[required]" onchange="if(this.value=='other') $('#EmployerCity_Other').removeClass('hide'); else $('#EmployerCity_Other').addClass('hide');" required>
                      <?=formListOpt('city', $PAGE['EmployerCity'])?>
					  <option value="other">Any Other</option>
                    </select>
                  </div>
                </div>
				<div class="form-group hide" id="EmployerCity_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-9">
                    <input type="text" id="OtherCity" name="OtherCity" placeholder="Enter Other Option" class="col-xs-5 col-sm-5" value="<?=$PAGE['OtherCity']?>"/>
                  </div>
                </div>
                <!--<div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Zip Code</label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerZipCode" name="EmployerZipCode" placeholder="EmployerZipCode" class="col-xs-10 col-sm-5" value="<?=$PAGE['EmployerZipCode']?>"/>
                  </div>
                </div>-->
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Website</label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerWeb" name="EmployerWeb" placeholder="EmployerWeb" class="col-xs-10 col-sm-5" value="<?=$PAGE['EmployerWeb']?>"/>
                  </div>
                </div>
                <div class="clearfix form-actions">
                  <div class="col-md-1 pull-right"><a href="javascript:;" onclick="nexttab('contact-person')" class="btn btn-success btn-next">Next <i class="icon-arrow-right icon-on-right"></i></a> </div>
                </div>
              </div>
              <div id="contact-person" class="tab-pane">
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Contact title:</label>
                  <div class="col-sm-9">
                    <select name="EmployerContactTitle" id="EmployerContactTitle" class="col-xs-5 col-sm-5 selectstyle">
                      <?=formListOpt('contact-title', $PAGE['EmployerContactTitle'])?>
                    </select>
                  </div>
                </div>
                <div class="space-4"></div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Contact Name:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerContactName" name="EmployerContactName" placeholder="EmployerContactName" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['EmployerContactName']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Contact Number:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerContactMobile" name="EmployerContactMobile" placeholder="EmployerContactMobile" class="col-xs-10 col-sm-5 validate[required,custom[integer]]" value="<?=$PAGE['EmployerContactMobile']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Contact Email:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployerContactEmail" name="EmployerContactEmail" placeholder="Employer Contact Email" class="col-xs-10 col-sm-5 validate[required,custom[email]]" value="<?=$PAGE['EmployerContactEmail']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Contact Person Designation:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="EmployerContactDesig" id="EmployerContactDesig" class="col-xs-5 col-sm-5 selectstyle validate[required]" onchange="if(this.value=='other') $('#EmployerContactDesig_Other').removeClass('hide'); else $('#EmployerContactDesig_Other').addClass('hide');"required>
                      <?=formListOpt('designation', $PAGE['EmployerContactDesig'])?>
					   <option value="other">Any Other</option>
                    </select>
                  </div>
                </div>
				<div class="form-group hide" id="EmployerContactDesig_Other">
	  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
	  <div class="col-sm-9">
		<input type="text" id="OtherEmployerContactDesig" name="OtherEmployerContactDesig" placeholder="Enter Other Option" class="col-xs-5 col-sm-5" value="<?=$PAGE['OtherEmployerContactDesig']?>" />
	  </div>
	</div>
                <div class="clearfix form-actions">
                <div class="pull-left">
                      <button onclick="nexttab('basic-details')" class="btn btn-success btn-back"><i class="icon-arrow-left icon-on-right"></i> Back</button>
                    </div>
                  <div class="col-md-1 pull-right"><a href="javascript:;" onclick="nexttab('public-profile')" class="btn btn-success btn-next">Next <i class="icon-arrow-right icon-on-right"></i></a> </div>
                </div>
              </div>
              <div id="public-profile" class="tab-pane">
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">About Company:<i class="icon-asterisk"></i> </label>
                  <div class="col-sm-7">
                    <textarea class="ContentEditor " id="EmployerAboutContent" name="EmployerAboutContent" style="height: 260px;" ><?=$PAGE['EmployerAboutContent']?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Key Achievements </label>
                  <div class="col-sm-7">
                    <textarea class="ContentEditor " id="EmployerAchieveContent" name="EmployerAchieveContent" style="height: 260px;" ><?=$PAGE['EmployerAchieveContent']?></textarea>
                  </div>
                </div>
                
                <div class="clearfix form-actions">
                <div class="pull-left">
                      <button onclick="nexttab('contact-person')" class="btn btn-success btn-back"><i class="icon-arrow-left icon-on-right"></i> Back</button>
                    </div>
                  <div class="col-md-1 pull-right"><a href="javascript:;" onclick="nexttab('media')" class="btn btn-success btn-next">Next <i class="icon-arrow-right icon-on-right"></i></a> </div>
                </div>
              </div>
              <div id="media" class="tab-pane">
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Upload Logo <small>130*130</small>:<i class="icon-asterisk"></i><br /><small class="red">If you upload a logo that does not confirm to the mentioned size, it can appear distorted when resized</small></label>
                  <div class="col-sm-9">
                    <input type="file" id="image" name="image" class="col-xs-5 col-sm-5 <?=($PAGE['EmployerLogo']=='')?'validate[required]':''?>" value=""/>
                    <?=($PAGE['EmployerLogo']!='')?'<img src="'.$path.'uploads/'.$PAGE['EmployerLogo'].'" class="clearfix col-sm-5" style="clear: both; max-width: 100%;"/>':''?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Upload Cover <small>1920*350</small>:</label>
                  <div class="col-sm-9">
                    <input type="file" id="cover" name="cover" class="col-xs-5 col-sm-5 " value=""/>
                    <?=($PAGE['EmployerCover']!='')?'<img src="'.$path.'uploads/'.$PAGE['EmployerCover'].'" class="clearfix" style="clear: both; max-width: 100%;"/>':''?>
                  </div>
                </div>
                <div class="clearfix form-actions">
                <div class="pull-left">
                      <button onclick="$('#myTab a[href=public-profile]').click();" class=" hide btn btn-success btn-back"><i class="icon-arrow-left icon-on-right"></i> Back</button>
                    </div>
                  <div class="col-md-3 pull-right">
                    <button type="submit" class="btn btn-info"> <i class="icon-ok bigger-110"></i> Submit </button>
                    <button type="reset" class="btn"> <i class="icon-undo bigger-110"></i> Reset </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /.page-content --> 
</div>
<!-- /.main-content --> 
<script>
    function nexttab(trg){
		$("form#EmployerProfile").validationEngine('validate');
		var valid = $("form#EmployerProfile .formError").length;
		if (valid != 0){
			return false; 
		} else {
			$('#myTab a[href="#'+trg+'"]').trigger('click');
		}
		return false;
	}
	
	$(document).ready(function(){
		$("form#EmployerProfile").validationEngine('attach', {
			promptPosition : "centerRight", 
			scroll: false,
			onValidationComplete: function(form, status){
				if(status){
					if( $("#myTab li.active a").attr('href') == '#media'){
						document.EmployerProfile.submit();
					}
					
				} else {
					//alert("The form status is: " +status+", it will never submit");
				}
		  }
		});
	
		$("#reset").click(function(){
			$(".formError").hide();
		});
		
	});


  $('#myTab > li > a').click( function() {
    $("form#EmployerProfile").validationEngine('validate');
    var valid = $("form#EmployerProfile .formError").length;
    if (valid != 0){
      alert("Please complete form & validation...!");
      return false;
    } else {

    }

  });


</script>
<?php include('footer.php');?>
