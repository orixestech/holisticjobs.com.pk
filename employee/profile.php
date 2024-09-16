<?php include('header.php');

if($_GET['delete'] && $_GET['uid']){
	$sql = "DELETE FROM `employee_".$_GET['delete']."` WHERE `UID` = '".$_GET['uid']."' ";
	@query($sql);
	$FormMessge = Alert('success', ucwords('Employee '.$_GET['delete'].' Deleted...!') );
	//Track(ucwords('Employee '.$_GET['delete'].' Deleted...!'), 'employee', $_SESSION['EmployeeUID']);
}



if($_POST && $malik=='Shaheryar'){
	$contents = $_POST;
	$target_dir = MEMBER_IMAGE_DIRECTORY;
	$nowDate = date("Y-m-d h:i:s");
	$contents['EmployeePassword'] = PassWord($_POST['EmployeePassword'],'hide');
	$_GET["mode"]='';
	

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
				$existing_image = GetData('EmployeeLogo','employee','UID',$_SESSION['EmployeeUID']);
				@unlink($target_dir.$existing_image);
			}
			else
			{
				$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
			}
		}
	}
	
	
	query("DELETE FROM `employee_extra` WHERE EmployeeID = '".$_SESSION['EmployeeUID']."' ");
	  
	$options = array('EmployeeInterests','EmployeeSoftSkills','EmployeeSkills','EmployeeObjective');
	foreach($options as $opt){
		foreach($_REQUEST[$opt] as $val){
			$JobCity = array();
			$JobCity['EmployeeID'] = $_SESSION['EmployeeUID'];
			$JobCity['InfoType'] = $opt;
			$JobCity['InfoTypeValue'] = ($opt=='EmployeeGender')?$val:optionVal($val);
			FormData('employee_extra', 'insert', $JobCity, "", $view=false );
		}
	}


	$run = FormData('employee', 'update', $contents, " UID = '".$_SESSION['EmployeeUID']."' ", $view=false );
	$FormMessge = Alert('success', 'Employee Updated...!');

}
	
$stmt = query(" SELECT * FROM `employee` WHERE UID = '".$_SESSION['EmployeeUID']."' ");
$PAGE = mysql_fetch_array($stmt);

( $PAGE['EmployeeDOB'] == '0000-00-00' ) ? $PAGE['EmployeeDOB'] = '' : $PAGE['EmployeeDOB'] = date("Y-m-d", strtotime($PAGE['EmployeeDOB'])) ;

$ProfileScore = round(ProfileScore($_SESSION['EmployeeUID']),0);

?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active"> Employee Profile </li>
    </ul>
    <!-- .breadcrumb -->
    <div class=" col-xs-4 pull-right" style="margin-top:15px">
      <div class="progress progress-big active" data-percent="<?=$ProfileScore?>%">
        <div class="progress-bar progress-bar-success" style="width: <?=$ProfileScore?>%;"></div>
      </div>
    </div>
    <!-- #nav-search -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Update Profile <small> <i class="icon-double-angle-right"></i> Update Public Profile </small></h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <?=$FormMessge?>
        <?php $ACCESS = CheckSubAccess('profile-build-up', 'employee');?>
        <div id="EmployeeProfileResult"></div>
        <div class="tabbable ">
          <ul class="nav nav-tabs" id="myTab" style="auto">
            <li class="active"> <a data-toggle="tab" href="#PersonalInformation"> Personal Information </a> </li>
            <li> <a data-toggle="tab" href="#Education"> Education </a> </li>
            <li> <a data-toggle="tab" href="#Experience"> Experience </a> </li>
            <li> <a data-toggle="tab" href="#Others"> Skills & Interests </a> </li>
            <li> <a data-toggle="tab" href="#AdditionalInformation"> Objective & Additional Information </a> </li>
          </ul>
          <div class="tab-content form-horizontal">
            <div id="PersonalInformation" class="tab-pane in active ">
              <form class="form-horizontal" role="form" method="post" onsubmit="return false;" id="EmployeeProfile_PersonalInformation">
                <input type="hidden" name="action" value="EmployeeProfileSubmit" />
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Employee Title</label>
                  <div class="col-sm-9">
                    <select name="EmployeeTitle" id="EmployeeTitle" class="col-xs-5 col-sm-5 selectstyle" >
                      <?=formListOpt('contact-title', $PAGE['EmployeeTitle'])?>
                    </select>
                  </div>
                </div>
                <div class="space-4"></div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Employee Name:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployeeName" name="EmployeeName" placeholder="EmployeeName" class="col-xs-5 col-sm-5 validate[required]" value="<?=$PAGE['EmployeeName']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">N.I.C Number</label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployeeNIC" name="EmployeeNIC" placeholder="EmployeeNIC" class="col-xs-5 col-sm-5" value="<?=$PAGE['EmployeeNIC']?>" />
                  </div>
                </div>
                <div class="space-4"></div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date of Birth:</label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployeeDOB" name="EmployeeDOB" placeholder="EmployeeDOB" class="col-xs-5 col-sm-5 date-picker " data-date-format="yyyy-mm-dd"  value="<?=$PAGE['EmployeeDOB']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Gender:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="EmployeeGender" id="EmployeeGender" class="col-xs-5 col-sm-5 selectstyle validate[required]" >
                      <option value="Male" <?=($PAGE['EmployeeGender']=='Male')?'selected':''?>>Male</option>
                      <option value="Female" <?=($PAGE['EmployeeGender']=='Female')?'selected':''?>>Female</option>
                    </select>
                  </div>
                </div>
                <div class="space-4"></div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Mobile Number:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployeeMobile" name="EmployeeMobile" placeholder="EmployeeMobile" class="col-xs-5 col-sm-5 validate[required, custom[integer]]" maxlength="11" value="<?=$PAGE['EmployeeMobile']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Land Line Number:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployeeLandLine" name="EmployeeLandLine" placeholder="" class="col-xs-5 col-sm-5 validate[required, custom[integer]]" value="<?=$PAGE['EmployeeLandLine']?>" />
                  </div>
                </div>
                <div class="space-4"></div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Residential Address</label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployeeAddress" name="EmployeeAddress" placeholder"employeeaddress" class="col-xs-5 col-sm-5" value="<?=$PAGE['EmployeeAddress']?>" />
                  </div>
                </div>
                <div class="space-4"></div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">City:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="EmployeeCity" id="EmployeeCity" class="col-xs-5 col-sm-5 selectstyle validate[required]" onchange="if(this.value=='other') $('#EmployeeCity_Other').removeClass('hide'); else $('#EmployeeCity_Other').addClass('hide');">
                      <?=formListOpt('city', $PAGE['EmployeeCity'])?>
                      <option value="other">Any Other</option>
                    </select>
                  </div>
                </div>
                <div class="form-group hide" id="EmployeeCity_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-9">
                    <input type="text" id="OtherCity" name="OtherCity" placeholder="Enter Other Option" class="col-xs-5 col-sm-5" value="<?=$PAGE['OtherCity']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Province or State:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="EmployeeState" id="EmployeeState" class="col-xs-5 col-sm-5 selectstyle validate[required]"  >
                      <?=formListOpt('state', $PAGE['EmployeeState'])?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Mother Language</label>
                  <div class="col-sm-9">
                    <select name="EmployeeMotherLanguage" id="EmployeeMotherLanguage" class="col-xs-5 col-sm-5 selectstyle" onchange="if(this.value=='other') $('#EmployeeMotherLanguage_Other').removeClass('hide'); else $('#EmployeeMotherLanguage_Other').addClass('hide');">
                      <?=formListOpt('mother-language', $PAGE['EmployeeMotherLanguage'])?>
                      <option value="other">Any Other</option>
                    </select>
                  </div>
                </div>
                <div class="form-group hide" id="EmployeeMotherLanguage_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-9">
                    <input type="text" id="OtherLanguage" name="OtherLanguage" placeholder="Enter Other Option" class="col-xs-5 col-sm-5" value="<?=$PAGE['OtherLanguage']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Marital Status</label>
                  <div class="col-sm-9">
                    <select name="EmployeeMaritalStatus" id="EmployeeMaritalStatus" class="col-xs-5 col-sm-5 selectstyle" >
                      <?=formListOpt('marital-status', $PAGE['EmployeeMaritalStatus'])?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Total Experience:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="EmployeeTotalExperience" id="EmployeeTotalExperience" class="col-xs-5 col-sm-5 selectstyle validate[required]" onchange="if(this.value=='other') $('#EmployeeTotalExperience_Other').removeClass('hide'); else $('#EmployeeTotalExperience_Other').addClass('hide');">
                      <?=formListOpt('experience', $PAGE['EmployeeTotalExperience'])?>
                      <!-- <option value="other">Any Other</option> -->
                    </select>
                  </div>
                </div>
                <!-- <div class="form-group hide" id="EmployeeTotalExperience_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-9">
                    <input type="text" id="OtherTotalExperience" name="OtherTotalExperience" placeholder="Enter Other Option" class="col-xs-5 col-sm-5" value="<?=$PAGE['OtherTotalExperience']?>" />
                  </div>
                </div> -->
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Website</label>
                  <div class="col-sm-9">
                    <input type="text" id="EmployeeWeb" name="EmployeeWeb" placeholder"employeeweb" class="col-xs-5 col-sm-5" value="<?=$PAGE['EmployeeWeb']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right">Upload Profile Picture<small>130*130</small>:<br />
                  <small class="red">If you upload a picture that does not confirm to the mentioned size, it can appear distorted when resized</small></label>
                  <div class="col-sm-9">
                    <input type="file" id="image" name="image" class="col-xs-5 col-sm-5 <?=($PAGE['EmployeeLogo']=='')?'':''?>" value=""/>
                    <? // ($PAGE['EmployeeLogo']!='')?'<img src="/image.php?image='.$path.'uploads/'.$PAGE['EmployeeLogo'].'" class="clearfix col-sm-5" style="clear: both; max-width: 100%;"/>':''?>
                    <?=($PAGE['EmployeeLogo']!='')?'<div id="ImageSection"><i onclick="RemoveLogo()" style="cursor:pointer;" class="icon-trash bigger-130 red"></i><img src="'.$path.'uploads/'.$PAGE['EmployeeLogo'].'" class="clearfix mainimage col-sm-5" style="clear: both; max-width: 100%;"/></div>':''?>
                  </div>
                </div>
                <div class="clearfix form-actions">
                  <div class="pull-right">
                    <button onclick="nexttab('Education', true, 'PersonalInformation')" class="btn btn-success btn-next">Save & Next <i class="icon-arrow-right icon-on-right"></i></button>
                  </div>
                </div>
              </form>
            </div>
            <div id="Education" class="tab-pane">
              <div class="row">
                <div class=" col-xs-6 col-md-6 ">
                  <form class="form-horizontal" role="form" method="post" onsubmit="return false;" id="EmployeeProfile_Education">
                    <div class="row">
                      <?php 
				if($ACCESS['access']=='false'){ ?>
                      <div class=" col-xs-12 col-md-12 ">
                        <div class="well">
                          <h4 class="red smaller lighter">Access Denied..!</h4>
                          You don't have access on this feature, please update your subscription for <strong>"Job Building Education Section"</strong>. </div>
                      </div>
                      <?php
				} else { ?>
                      <div class="col-xs-12 col-md-12 form-horizontal" id="EducationHistorySection"></div>
                      <div class="col-xs-12 col-md-12">
                        <h4>Education History</h4>
                        <div class="table-responsive" id="EducationHistoryData"> </div>
                        <div class="clearfix"><br />
                        </div>
                      </div>
                      <?php 
				}?>
                      <div class="clearfix"><br />
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-xs-6 col-md-6 ">
                  <form class="form-horizontal" role="form" method="post" onsubmit="return false;" id="EmployeeProfile_Certificate">
                    <div class="row">
                      <?php 
				if($ACCESS['access']=='false'){ ?>
                      <div class=" col-xs-12 col-md-12 ">
                        <div class="well">
                          <h4 class="red smaller lighter">Access Denied..!</h4>
                          You don't have access on this feature, please update your subscription for <strong>"Job Building Education Section"</strong>. </div>
                      </div>
                      <?php
				} else { ?>
                      <div class="col-xs-12 col-md-12 form-horizontal" id="CertificateHistorySection"></div>
                      <div class="col-xs-12 col-md-12">
                        <h4>Certificate History</h4>
                        <div class="table-responsive" id="CertificateHistoryData"></div>
                        <div class="clearfix"><br />
                        </div>
                      </div>
                      <?php 
				}?>
                      <div class="clearfix"><br />
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-xs-12 col-md-12">
                  <div class="clearfix form-actions">
                    <div class="pull-left">
                      <button onclick="nexttab('PersonalInformation', false, 'Education')" class="btn btn-success btn-back"><i class="icon-arrow-left icon-on-right"></i> Back</button>
                    </div>
                    <div class="pull-right">
                      <button onclick="nexttab('Experience',false, 'Education')" class="btn btn-success btn-next">Save & Next <i class="icon-arrow-right icon-on-right"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="Experience" class="tab-pane">
              <form class="form-horizontal" role="form" method="post" onsubmit="return false;" id="EmployeeProfile_Expereience">
                <div class="row"> <?php 
				if($ACCESS['access']=='false'){ ?>
                  <div class=" col-xs-12 col-md-12 ">
                    <div class="well">
                      <h4 class="red smaller lighter">Access Denied..!</h4>
                      You don't have access on this feature, please update your subscription for <strong>"Job Building Experience Section"</strong>. </div>
                  </div> <?php
          		} else { ?>
                  <div class="col-xs-12 col-md-12 form-horizontal" id="ExperienceHistorySection"></div>
                  <div class="col-xs-12 col-md-12">
                    
                    <div class="table-responsive" id="ExperienceHistoryData"> </div>
                    <div class="clearfix"><br /></div>
                  </div> <?php
          		}?>
                  <div class="clearfix"><br />
                  </div>
                </div>
                <div class="clearfix form-actions">
                  <div class="pull-left">
                    <button onclick="nexttab('Education', false, 'Education')" class="btn btn-success btn-back"><i class="icon-arrow-left icon-on-right"></i> Back</button>
                  </div>
                  <div class="pull-right">
                    <button onclick="nexttab('Others',false, 'Expereience')" class="btn btn-success btn-next">Save & Next <i class="icon-arrow-right icon-on-right"></i></button>
                  </div>
                </div>
              </form>
            </div>
            <div id="Others" class="tab-pane">
              <form class="form-horizontal" role="form" method="post" onsubmit="return false;" id="EmployeeProfile_Others">
                <input type="hidden" name="action" value="EmployeeProfileSubmit" />
                <input type="hidden" name="form" value="EmployeeProfileSkills" />
                <div class="row">
                  <?php 
				if($ACCESS['access']=='false'){ ?>
                  <div class=" col-xs-12 col-md-12 ">
                    <div class="well">
                      <h4 class="red smaller lighter">Access Denied..!</h4>
                      You don't have access on this feature, please update your subscription for <strong>"Job Building Skills Section"</strong>. </div>
                  </div>
                  <?php
				} else { ?>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Technical Skills </label>
                    <div class="col-sm-9">
                      <select multiple=""  name="EmployeeSkills[]" id="JobSkills" class="width-40 chosen-select" data-placeholder="Choose Skills..." >
                        <?php
						$EmployeeSkills = EmployeeExtra($PAGE["UID"], 'EmployeeSkills', 'array');  
						$qry = query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'skills') order by OptionName");
						echo '<option value=""></option>';
						while( $rslt = mysql_fetch_array($qry) ){
							if( in_array($rslt["OptionDesc"],$EmployeeSkills) ){
								echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
							} else {
								echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
							}
						}?>
                      </select>
                      <span class="col-xs-2 col-sm-2" style="float:none">
                      <label>
                      <input class="ace" type="checkbox"  id="EmployeeSkills_checkbox" onchange="if ($(this).is(':checked')) $('#EmployeeSkills_other').removeClass('hide'); else $('#EmployeeSkills_other').addClass('hide');"  />
                      <span class="lbl"> Others</span> </label>
                      </span> <span class="col-xs-5 col-sm-5 hide" id="EmployeeSkills_other" style="float:none">
                      <input type="text" placeholder"other employee skills" id="OtherEmployeeSkills" name="OtherEmployeeSkills"/>
                      </span> </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Soft Skills: </label>
                    <div class="col-sm-9">
                      <select multiple="" name="EmployeeSoftSkills[]" id="EmployeeSoftSkills" class="width-40 chosen-select " data-placeholder="Choose Skills..."  >
                        <?php
						$EmployeeSoftSkills = EmployeeExtra($PAGE["UID"], 'EmployeeSoftSkills', 'array');
						$qry = query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'soft-skills') order by OptionName");
						echo '<option value=""></option>';
						while( $rslt = mysql_fetch_array($qry) ){
							if( in_array($rslt["OptionDesc"],$EmployeeSoftSkills) ){
								echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
							} else {
								echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
							}
						}?>
                      </select>
                      <span class="col-xs-2 col-sm-2" style="float:none">
                      <label>
                      <input class="ace" type="checkbox"  id="EmployeeSoftSkills_checkbox" onchange="if ($(this).is(':checked')) $('#EmployeeSoftSkills_other').removeClass('hide'); else $('#EmployeeSoftSkills_other').addClass('hide');"/>
                      <span class="lbl"> Others</span> </label>
                      </span> <span class="col-xs-5 col-sm-5 hide" id="EmployeeSoftSkills_other" style="float:none">
                      <input type="text" id="OtherEmployeeSoftSkills" name="OtherEmployeeSoftSkills" placeholder"other employee skills" value="" />
                      </span> </div>
                  </div>
                  <?php
				}?>
                  <div class="clearfix"><br />
                  </div>
                </div>
                <div class="row">
                  <?php 
				if($ACCESS['access']=='false'){ ?>
                  <div class=" col-xs-12 col-md-12 ">
                    <div class="well">
                      <h4 class="red smaller lighter">Access Denied..!</h4>
                      You don't have access on this feature, please update your subscription for <strong>"Job Building Interests Section"</strong>. </div>
                  </div>
                  <?php
				} else { ?>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Interests: </label>
                    <div class="col-sm-9">
                      <select multiple="" name="EmployeeInterests[]" id="EmployeeInterests" class="width-40 chosen-select " data-placeholder="Choose Interst..."  >
                        <?php
						$EmployeeInterests = EmployeeExtra($PAGE["UID"], 'EmployeeInterests', 'array');
						$qry = query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'interests') order by OptionName");
						echo '<option value=""></option>';
						while( $rslt = mysql_fetch_array($qry) ){
							if( in_array($rslt["OptionDesc"],$EmployeeInterests) ){
								echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
							} else {
								echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
							}
						}?>
                      </select>
                      <span class="col-xs-2 col-sm-2" style="float:none">
                      <label>
                      <input class="ace" type="checkbox" id="EmployeeInterests_checkbox" onchange="if ($(this).is(':checked')) $('#EmployeeInterests_other').removeClass('hide'); else $('#EmployeeInterests_other').addClass('hide'); " />
                      <span class="lbl"> Others</span> </label>
                      </span> <span class="col-xs-5 col-sm-5 hide" id="EmployeeInterests_other" style="float:none">
                      <input type="text" id="OtherEmployeeInterests" name="OtherEmployeeInterests" placeholder"other employee skills"/>
                      </span> </div>
                  </div>
                  <?php 
				}?>
                  <div class="clearfix"><br />
                  </div>
                </div>
                <div class="clearfix form-actions">
                  <div class="pull-left">
                    <button onclick="nexttab('Experience', false, 'Others')" class="btn btn-success btn-back"><i class="icon-arrow-left icon-on-right"></i> Back</button>
                  </div>
                  <div class="pull-right">
                    <button onclick="nexttab('AdditionalInformation',true,'Others')" class="btn btn-success btn-next">Save & Next <i class="icon-arrow-right icon-on-right"></i></button>
                  </div>
                </div>
              </form>
            </div>
            <div id="AdditionalInformation" class="tab-pane">
              <form class="form-horizontal" role="form" method="post" onsubmit="return false;" id="EmployeeProfile_AdditionalInformation">
                <input type="hidden" name="action" value="EmployeeProfileSubmit" />
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Objective</label>
                  <div class="col-sm-9">
                    <textarea type="text" id="EmployeeObjective" name="EmployeeObjective" class="col-xs-5 col-sm-5 ContentEditor" style="height: 260px;"><?=$PAGE['EmployeeObjective']?>
</textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Additional Information</label>
                  <div class="col-sm-9">
                    <textarea type="text" id="AdditionalInformation" name="AdditionalInformation" class="col-xs-5 col-sm-5 ContentEditor" style="height: 260px;"><?=$PAGE['AdditionalInformation']?>
</textarea>
                  </div>
                </div>
                <div class="clearfix form-actions">
                  <div class="pull-left">
                    <button onclick="nexttab('Others', false, 'AdditionalInformation')" class="btn btn-success btn-back"><i class="icon-arrow-left icon-on-right"></i> Back</button>
                  </div>
                  <div class="pull-right">
                    <button onclick="nexttab('PersonalInformation',true,'AdditionalInformation')" class="btn btn-success btn-next">Confirm Changes & Submit <i class="icon-arrow-right icon-on-right"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.page-content -->
</div>
<!-- /.main-content -->
<script>
    function nexttab(trg,form, formid){

      if(formid=='Others'){
        if( $("#EmployeeSkills_checkbox").is(':checked') ){
          if( $("#OtherEmployeeSkills").val() == '' ){
            alert("Please enter valid other value.");
            $("#OtherEmployeeSkills").focus();
            return false;
          }
        }
        if( $("#EmployeeSoftSkills_checkbox").is(':checked') ){
          if( $("#OtherEmployeeSoftSkills").val() == '' ){
            alert("Please enter valid other value.");
            $("#OtherEmployeeSoftSkills").focus();
            return false;
          }
        }
        if( $("#EmployeeInterests_checkbox").is(':checked') ){
          if( $("#OtherEmployeeInterests").val() == '' ){
            alert("Please enter valid other value.");
            $("#OtherEmployeeInterests").focus();
            return false;
          }
        }
        



      }
	  
	    if(formid=='PersonalInformation'){
        if( $("#EmployeeCity").val() == 'other' ){
          if( $("#OtherCity").val() == '' ){
            alert("Please enter valid other value.");
            $("#OtherCity").focus();
            return false;
          }
        }
        
        if( $("#EmployeeMotherLanguage").val() == 'other' ){
          if( $("#OtherLanguage").val() == '' ){
            alert("Please enter valid other value.");
            $("#OtherLanguage").focus();
            return false;
          }
        }

        if( $("#EmployeeTotalExperience").val() == 'other' ){
          if( $("#OtherTotalExperience").val() == '' ){
            alert("Please enter valid other value.");
            $("#OtherTotalExperience").focus();
            return false;
          }
        }


      }


		setTimeout(function(){
			LoadScripts();
		}, 1000);
		if(form==true){
			$("form#EmployeeProfile_"+formid).validationEngine('validate');
			var valid = $("form#EmployeeProfile_" + formid + " .formError").length;
			if (valid != 0){
				alert("Please complete form & validation...!");
			} else {
				ProcessForm(formid, trg);
			}
		} else {
			$('#myTab a[href="#'+trg+'"]').trigger('click');
		}
		
		return false;
	}
	
	$(document).ready(function(){
		$("form").validationEngine('attach', {

			promptPosition : "centerRight", 
			scroll: false,
			onValidationComplete: function(form, status){
				if(status){
					//$("form#EmployeeProfile")[0].submit();
					//document.EmployeeProfile.submit();
				} else {
					//alert("The form status is: " +status+", it will never submit");
				}
		  }
		});
	
		$("#reset").click(function(){
			$(".formError").hide();
		});
		
		setTimeout(function(){
			LoadEmployeeEducationData('Education #EducationHistoryData');
			LoadEmployeeEducationForm(0, 'EducationHistorySection');

			LoadEmployeeExperienceData('Experience #ExperienceHistoryData');
			LoadEmployeeExperienceForm(0, 'ExperienceHistorySection');

			LoadEmployeeCertificateData('Education #CertificateHistoryData');
			LoadEmployeeCertificateForm(0, 'CertificateHistorySection');
			
			LoadScripts();
		}, 1500);
		
	});

	$('#myTab > li > a').click( function() {
		$("form#EmployeeProfile").validationEngine('validate');
		var valid = $("form#EmployeeProfile .formError").length;
		if (valid != 0){
			alert("Please complete form & validation...!");
			return false;
		} else {
		
		}

	});
	
	function ProcessForm(formid, trg){
		data = new window.FormData($('#EmployeeProfile_' + formid)[0]); /* EmployeeProfileSubmit */
		$.ajax({
			cache: false,
			contentType: false,
			processData: false,
			xhr: function () {  
				return $.ajaxSettings.xhr();
			},
			type: "POST",
			async: false,
			url: "<?=$path?>employee/ajaxpage.php",
			beforeSend: function () {
			},
			dataType: 'html',
			data: data,
			success: function (data) {
				//$("#EmployeeProfileResult").html(data);
				//alert(data);
				if(trg=='PersonalInformation'){
					setTimeout("window.location = 'index.php';", 200);
				} else {
					$('#myTab a[href="#'+trg+'"]').trigger('click');
				}
				
			},
			error: function () {
				//alert("Something Wrong with Server...!", 'Error');
			}
		});
	}
	
	function OtherOption(field, optiontype, newvalue){
		
		
	
	}
	
	function RemoveLogo(){
		ajaxreq('ajaxpage.php', 'action=RemoveLogo&uid=<?=$_SESSION['EmployeeUID']?>', 'ImageSectionAjax');
		$("#ImageSection i.icon-trash").remove();
		$("#ImageSection img.mainimage").attr("src","<?=$path?>uploads/no-image.png");
	}
	
    </script>
<?php include('footer.php');?>
