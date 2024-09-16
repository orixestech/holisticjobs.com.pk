<?php include('header.php');?>
<?php
///// Subscription Access Check
$access = CheckSubAccess('unlimited-job-posting', 'employer');
if($access['access']=='false') {?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">
            <?=($_GET["id"])?'Modify':'Add New'?>
            Job </li>
        </ul>
        <!-- .breadcrumb -->
        <div id="SubscriptionExpireStatus" class="pull-right">
          <?=$SubscriptionExpireStatus?>
        </div>
        <!-- #nav-search -->
      </div>
      <div class="page-content">
        <div class="page-header">
          <h1>Access Denied.</h1>
        </div>
        <div class="row">
          <div class="col-xs-12"><h4><?=$access['msg']?></h4></div>
        </div>
      </div>
    </div><?php
} else {
	
	if($_POST){
		$contents = $_POST;
		$nowDate = date("Y-m-d h:i:s");
		$contents['JobLastDateApply'] = date("Y-m-d", strtotime($_POST['JobLastDateApply']));
		$contents['JobEmployerID'] = $_SESSION['EmployerUID'];
		
		if($contents['JobExperienceDesignation'] == 'other' && $_REQUEST['OtherJobExperience'] !='' ){
			$type = GetData("TypeId","typedata","TypeFieldName","designation");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherJobExperienceDesignation'] )."', '".$_REQUEST['OtherJobExperienceDesignation']."', '0');";
			$stmt = mysql_query($sql);
			$contents['JobExperienceDesignation'] = mysql_insert_id();
			AdminEmailForDropDown();
		}
		
		if($contents['JobExperience'] == 'other' && $_REQUEST['OtherJobExperience'] !='' ){
			$type = GetData("TypeId","typedata","TypeFieldName","experience");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherJobExperience'] )."', '".$_REQUEST['OtherJobExperience']."', '0');";
			$stmt = mysql_query($sql);
			$contents['JobExperience'] = mysql_insert_id();
			AdminEmailForDropDown();
		}
		
		if($contents['JobTotalExperience'] == 'other' && $_REQUEST['OtherJobTotalExperience'] !='' ){
			$type = GetData("TypeId","typedata","TypeFieldName","experience");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherJobTotalExperience'] )."', '".$_REQUEST['OtherJobTotalExperience']."', '0');";
			$stmt = mysql_query($sql);
			$contents['JobTotalExperience'] = mysql_insert_id();
			AdminEmailForDropDown();
		}
		
		if($contents['JobDesignation'] == 'other' && $_REQUEST['OtherDesignation'] !='' ){
			$type = GetData("TypeId","typedata","TypeFieldName","designation");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherDesignation'] )."', '".$_REQUEST['OtherDesignation']."', '0');";
			$stmt = mysql_query($sql);
			$contents['JobDesignation'] = mysql_insert_id();
			AdminEmailForDropDown();
		}
		
		if($contents['JobDepartment'] == 'other' && $_REQUEST['OtherDepartment'] !='' ){
			$type = GetData("TypeId","typedata","TypeFieldName","departments");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherDepartment'] )."', '".$_REQUEST['OtherDepartment']."', '0');";
			$stmt = mysql_query($sql);
			$contents['JobDepartment'] = mysql_insert_id();
			AdminEmailForDropDown();
		}
		
		if($contents['JobType'] == 'other' && $_REQUEST['OtherJobType'] !='' ){
			$type = GetData("TypeId","typedata","TypeFieldName","job-type");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherJobType'] )."', '".$_REQUEST['OtherJobType']."', '0');";
			$stmt = mysql_query($sql);
			$contents['JobType'] = mysql_insert_id();
			AdminEmailForDropDown();
		}
		
		
		
		//echo "<pre>"; print_r($contents);
		
		if($_GET["mode"]=="update"){
			query("DELETE FROM `jobs_extra` WHERE `JobID` = '".$_GET["code"]."' ");
			
			$options = array('JobGender','JobCity','JobSkills','JobSoftSkills','JobQualification','JobAdditionalQualification');
			foreach($options as $opt){
				foreach($_REQUEST[$opt] as $val){
					
					$JobCity = array();
					$JobCity['JobID'] = $_GET["code"];
					$JobCity['InfoType'] = $opt;
					$JobCity['InfoTypeValue'] = ($opt=='JobGender')?$val:optionVal($val);
					FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
				}
			}
			
			$run = FormData('jobs', 'update', $contents, " UID = '".$_GET["code"]."' ", $view=false );
			$FormMessge = Alert('success', 'Job Updated...!');
			
			$JOBID = $_GET["code"];
		} else {
			$contents['status'] = 'active';
			$contents['page_create_date'] = $nowDate;
			$run = FormData('jobs', 'insert', $contents, "", $view=false );
			
			$options = array('JobGender','JobCity','JobSkills','JobSoftSkills','JobQualification','JobAdditionalQualification');
			foreach($options as $opt){
				foreach($_REQUEST[$opt] as $val){
					/*if($opt=='JobCity'){
						if(optionVal($val)=='Multiple Cities'){
							$ARR = formListArray('city');
							foreach($ARR as $citykey => $citytitle){
								$TOTAL = total("SELECT * FROM `jobs_extra` WHERE `JobID` = '".$run."' and `InfoType` = '".$opt."' and `InfoTypeValue` = '".optionVal($citykey)."' ");
								if($TOTAL==0 && optionVal($citykey) != 'Multiple Cities'){
									$JobCity = array();
									$JobCity['JobID'] = $run;
									$JobCity['InfoType'] = $opt;
									$JobCity['InfoTypeValue'] = optionVal($citykey);
									FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
								}
							}
						} else {
							$JobCity = array();
							$JobCity['JobID'] = $run;
							$JobCity['InfoType'] = $opt;
							$JobCity['InfoTypeValue'] = optionVal($val);
							FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
						}
					} else {
						$JobCity = array();
						$JobCity['JobID'] = $run;
						$JobCity['InfoType'] = $opt;
						$JobCity['InfoTypeValue'] = ($opt=='JobGender')?$val:optionVal($val);
						FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
					}*/
					
					$JobCity = array();
					$JobCity['JobID'] = $run;
					$JobCity['InfoType'] = $opt;
					$JobCity['InfoTypeValue'] = ($opt=='JobGender')?$val:optionVal($val);
					FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
	
				}
			}
			
			$FormMessge = Alert('success', 'New Job Created...!');
			
			$JOBID = $run;
		}
		
		if( isset($_REQUEST['OtherJobCity']) && $_REQUEST['OtherJobCity'] !=''  ){ 
			$type = GetData("TypeId","typedata","TypeFieldName","city");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherJobCity'] )."', '".$_REQUEST['OtherJobCity']."', '0');";
			$stmt = mysql_query($sql);
			AdminEmailForDropDown();
			
			$JobCity = array();
			$JobCity['JobID'] = $JOBID;
			$JobCity['InfoType'] = "JobCity";
			$JobCity['InfoTypeValue'] = $_REQUEST['OtherJobCity'];
			FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
		}
		
		if( isset($_REQUEST['OtherJobQualification']) && $_REQUEST['OtherJobQualification'] !=''  ){ 
			$type = GetData("TypeId","typedata","TypeFieldName","qualification");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherJobQualification'] )."', '".$_REQUEST['OtherJobQualification']."', '0');";
			$stmt = mysql_query($sql);
			AdminEmailForDropDown();
			
			$JobCity = array();
			$JobCity['JobID'] = $JOBID;
			$JobCity['InfoType'] = "JobQualification";
			$JobCity['InfoTypeValue'] = $_REQUEST['OtherJobQualification'];
			FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
		}
		
		if($_REQUEST['OtherJobSkills'] !='' ){
			$type = GetData("TypeId","typedata","TypeFieldName","skills");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherJobSkills'] )."', '".$_REQUEST['OtherJobSkills']."', '0');";
			$stmt = mysql_query($sql);
			AdminEmailForDropDown();
			
			$JobCity = array();
			$JobCity['JobID'] = $JOBID;
			$JobCity['InfoType'] = "JobSkills";
			$JobCity['InfoTypeValue'] = $_REQUEST['OtherJobSkills'];
			FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
		}
		
		if($_REQUEST['OtherJobSoftSkills'] !='' ){
			$type = GetData("TypeId","typedata","TypeFieldName","soft-skills");
			$sql = "INSERT INTO `optiondata` (`OptionId`, `OptionType`, `OptionName`, `OptionDesc`, `Status`) VALUES (NULL, '".$type."', '".post_slug( $_REQUEST['OtherJobSoftSkills'] )."', '".$_REQUEST['OtherJobSoftSkills']."', '0');";
			$stmt = mysql_query($sql);
			AdminEmailForDropDown();
			
			$JobCity = array();
			$JobCity['JobID'] = $JOBID;
			$JobCity['InfoType'] = "JobSoftSkills";
			$JobCity['InfoTypeValue'] = $_REQUEST['OtherJobSoftSkills'];
			FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
		}
		
		
		
	}
		
	if($_GET["mode"]=="update"){
		$stmt = mysql_query(" SELECT * FROM `jobs` WHERE `UID` = '".$_GET["code"]."' ");
		$PAGE = mysql_fetch_array($stmt);
	}?>
	<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">
        <?=($_GET["id"])?'Modify':'Add New'?>
        Job </li>
    </ul>
    <!-- .breadcrumb -->
    <div id="SubscriptionExpireStatus" class="pull-right">
      <?=$SubscriptionExpireStatus?>
    </div>
    <!-- #nav-search -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1>
        <?=($_GET["code"])?'Modify Job':'Add Job'?>
        Form <small> <i class="icon-double-angle-right"></i>
        <?=($_GET["code"])?'Modify Job':'Post a New Job'?>
        </small> </h1>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <?=$FormMessge?>
        <form class="form-horizontal" name="postjob" id="postjob" role="form" method="post" enctype="multipart/form-data">
          <div class="tabbable ">
            <ul class="nav nav-tabs" id="myTab" style="height:36px;">
              <li class="active"> <a data-toggle="tab" href="#basic-details"> Basic Details </a> </li>
              <li> <a data-toggle="tab" href="#requirements"> Requirements </a> </li>
            </ul>
            <div class="tab-content form-horizontal">
              <div id="basic-details" class="tab-pane in active ">
                <!--<div class="form-group">
                      <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Category</label>
                      <div class="col-sm-9">
                        <select name="JobCategory" id="JobCategory" class="col-xs-5 col-sm-5 selectstyle" >
                          <?php echo CategoryDropDown('Jobs',$PAGE['JobCategory']);?>
                        </select>
                      </div>
                    </div>-->
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Job title:<i class="icon-asterisk"></i> </label>
                  <div class="col-sm-9">
                    <input type="text" id="JobTitle" name="JobTitle" placeholder="Job Title" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['JobTitle']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Designation:<i class="icon-asterisk"></i> </label>
                  <div class="col-sm-9">
                    <select name="JobDesignation" id="JobDesignation" class="col-xs-5 col-sm-5 selectstyle  validate[required]" onchange="if(this.value=='other') $('#JobDesignation_Other').removeClass('hide'); else $('#JobDesignation_Other').addClass('hide');">
                      <?=formListOpt('designation', $PAGE['JobDesignation'])?>
                      <option value="other">Any Other</option>
                    </select>
                  </div>
                </div>
                <div class="form-group hide" id="JobDesignation_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-9">
                    <input type="text" id="OtherDesignation" name="OtherDesignation" placeholder="Enter Other Option" class="col-xs-5 col-sm-5 validate[required]" value="<?=$PAGE['OtherDesignation']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Department:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="JobDepartment" id="JobDepartment" class="col-xs-5 col-sm-5 selectstyle  validate[required]" onchange="if(this.value=='other') $('#JobDepartment_Other').removeClass('hide'); else $('#JobDepartment_Other').addClass('hide');" >
                      <?=formListOpt('departments', $PAGE['JobDepartment'])?>
                      <option value="other">Any Other</option>
                    </select>
                  </div>
                </div>
                <div class="form-group hide" id="JobDepartment_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-9">
                    <input type="text" id="OtherDepartment" name="OtherDepartment" placeholder="Enter Other Option" class="col-xs-5 col-sm-5 validate[required]" value="<?=$PAGE['OtherDepartment']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Gender</label>
                  <div class="col-sm-9">
                    <?php
			  	$JobGender = JobExtra($PAGE["UID"], 'JobGender', 'array');?>
                    <select multiple=""  name="JobGender[]" id="JobGender" class="width-40 chosen-select" data-placeholder="Choose Gender..." >
                      <option value="Male" <?=(in_array('Male',$JobGender))?' selected ':''?> >Male</option>
                      <option value="Female" <?=(in_array('Female',$JobGender))?' selected ':''?> >Female</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Age Limit:</label>
                  <div class="col-sm-9">
                    <input type="text" id="JobAgeLimit" name="JobAgeLimit" placeholder="Age Limit" class="col-xs-10 col-sm-5 validate[custom[number]]" value="<?=$PAGE['JobAgeLimit']?>"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">No. of Vacancies</label>
                  <div class="col-sm-9">
                    <input type="text" id="JobNumbOfVacancy" name="JobNumbOfVacancy" placeholder="No of Vacancies" class="col-xs-10 col-sm-5" value="<?=$PAGE['JobNumbOfVacancy']?>"/>
                  </div>
                </div>
                <!--<div class="form-group">
                      <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Job Priority </label>
                      <div class="col-sm-9">
                        <select class="col-xs-5 col-sm-5 selectstyle" id="JobPriority" name="JobPriority">
                          <option value="normal" <?=($PAGE['JobPriority']=='normal')?' selected ':''?> >Normal</option>
                          <option value="top" <?=($PAGE['JobPriority']=='top')?' selected ':''?> >TOP</option>
                          <option value="premium" <?=($PAGE['JobPriority']=='premium')?' selected ':''?> >Premium</option>
                        </select>
                      </div>
                    </div>-->
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Salary Range </label>
                  <div class="col-sm-9">
                    <select name="JobSalaryFrom" id="JobSalaryFrom" class="col-xs-2 col-sm-2 selectstyle" >
                      <?=SalleryDropdown($PAGE['JobSalaryFrom'])?>
                    </select>
                    <label class="col-sm-1 col-xs-1" style="text-align:center"> to </label>
                    <select name="JobSalaryTo" id="JobSalaryTo" class="col-xs-2 col-sm-2 selectstyle" >
                      <?=SalleryDropdown($PAGE['JobSalaryTo'])?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Area:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <div class="space-2"></div>
                    <select multiple=""  name="JobCity[]" id="JobCity" class="width-40 chosen-select validate[required]" data-placeholder="Choose Cities...">
                      <?php
				  $JobCity = JobExtra($PAGE["UID"], 'JobCity', 'array');
				  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'city') order by OptionName");
				  echo '<option value=""></option>';
				  while( $rslt = mysql_fetch_array($qry) ){
					  if( in_array($rslt["OptionDesc"],$JobCity) ){
						  echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
					  } else {
						  echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
					  }
				  }?>
                    </select>
					<!--<span class="col-xs-2 col-sm-2" style="float:none">
                      <label>
                      <input class="ace" type="checkbox"  id="JobCity_checkbox" onchange="if ($(this).is(':checked')) { $('#JobCity_other').removeClass('hide'); $('#JobCity').removeClass('validate[required]');  $('#OtherJobCity').addClass('validate[required]');} else { $('#JobCity_other').addClass('hide');  $('#JobCity').addClass('validate[required]'); $('#OtherJobCity').removeClass('validate[required]'); }"  />
                      <span class="lbl"> Others</span> </label>
                      </span> <span class="col-xs-5 col-sm-5 hide" id="JobCity_other" style="float:none">
                      <input type="text" placeholder"other city" id="OtherJobCity" name="OtherJobCity"/>
                      </span> -->
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Job type</label>
                  <div class="col-sm-9">
                    <select name="JobType" id="JobType" class="col-xs-5 col-sm-5 selectstyle" onchange="if(this.value=='other') $('#JobType_Other').removeClass('hide'); else $('#JobType_Other').addClass('hide');">
                      <?=formListOpt('job-type', $PAGE['JobType'])?>
                      <option value="other">Any Other</option>
                    </select>
                  </div>
                </div>
                <div class="form-group hide" id="JobType_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-9">
                    <input type="text" id="OtherJobType" name="OtherJobType" placeholder="Enter Other Option" class="col-xs-5 col-sm-5" value="<?=$PAGE['OtherJobType']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Job nature:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="JobNature" id="JobNature" class="col-xs-5 col-sm-5 selectstyle validate[required]" onchange="if(this.value=='other') $('#JobNature_Other').removeClass('hide'); else $('#JobNature_Other').addClass('hide');">
                      <option value="field" <?=($PAGE['JobPriority']=='field')?' selected ':''?> >Field</option>
                      <option value="office" <?=($PAGE['JobPriority']=='office')?' selected ':''?> >Office</option>
                      <option value="field-office" <?=($PAGE['JobPriority']=='field-office')?' selected ':''?> >Field + Office</option>
                      <option value="factory" <?=($PAGE['JobPriority']=='factory')?' selected ':''?> >Factory</option>
                    </select>
                  </div>
                </div>
                <div class="form-group hide" id="JobNature_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-9">
                    <input type="text" id="OtherJobNature" name="OtherJobNature" placeholder="Enter Other Option" class="col-xs-5 col-sm-5" value="<?=$PAGE['OtherJobNature']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Last Date:<i class="icon-asterisk"></i></label>
                  <div class="col-xs-3 col-sm-3">
                    <div class="input-group">
                      <?php if($PAGE['JobLastDateApply']=='0000-00-00' || $PAGE['JobLastDateApply']=='1970-01-01' ){ $PAGE['JobLastDateApply'] = ''; } ?>
                      <input class="form-control date-picker validate[required]" id="JobLastDateApply" name="JobLastDateApply"  value="<?=$PAGE['JobLastDateApply']?>" type="text" data-date-format="yyyy-mm-dd" />
                      <span class="input-group-addon"> <i class="icon-calendar bigger-110"></i> </span> </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Job Description:<i class="icon-asterisk"></i> <br />
                  <span class="red">Job description must include tasks or functions and responsibilities of the position.</span> </label>
                  <div class="col-sm-7">
                    <textarea class="ContentEditor" id="JobDescription" name="JobDescription" style="height: 260px;" > <?=($PAGE['JobDescription']!='')?$PAGE['JobDescription']:'Job description is as per the norms of industry'?>
</textarea>
                  </div>
                </div>
                <div class="clearfix form-actions">
                  <div class="col-md-1 pull-right">
                  <button type="button" class="btn btn-success btn-next" onclick="nexttab('requirements'); return false;">Next <i class="icon-arrow-right icon-on-right"></i> </button>
                  </div>
                </div>
              </div>
              <div id="requirements" class="tab-pane">
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Technical Skills </label>
                  <div class="col-sm-9">
                    <select multiple=""  name="JobSkills[]" id="JobSkills" class="width-40 chosen-select" data-placeholder="Choose Skills...">
                      <?php
				  $JobSkills = JobExtra($PAGE["UID"], 'JobSkills', 'array');
				  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `Status` = 1 and `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'skills') order by OptionName");
				  echo '<option value=""></option>';
				  while( $rslt = mysql_fetch_array($qry) ){
					  if( in_array($rslt["OptionDesc"],$JobSkills) ){
						  echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
					  } else {
						  echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
					  }
				  }?>
                    </select>
					<span class="col-xs-2 col-sm-2" style="float:none">
                      <label>
                      <input class="ace" type="checkbox"  id="JobSkills_checkbox" onchange="if ($(this).is(':checked')) $('#JobSkills_Other').removeClass('hide'); else $('#JobSkills_Other').addClass('hide');"  />
                      <span class="lbl"> Others</span> </label>
                      </span> <span class="col-xs-5 col-sm-5 hide" id="JobSkills_Other" style="float:none">
                      <input type="text" placeholder"other skills" id="OtherJobSkills" name="OtherJobSkills"/>
                      </span> 
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Soft Skills: </label>
                  <div class="col-sm-9">
                    <select multiple="" name="JobSoftSkills[]" id="JobSoftSkills" class="width-40 chosen-select " data-placeholder="Choose Skills...">
                      <?php
				  $JobSoftSkills = JobExtra($PAGE["UID"], 'JobSoftSkills', 'array');
				  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `Status` = 1 and `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'soft-skills') order by OptionName");
				  echo '<option value=""></option>';
				  while( $rslt = mysql_fetch_array($qry) ){
					  if( in_array($rslt["OptionDesc"],$JobSoftSkills) ){
						  echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
					  } else {
						  echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
					  }
				  }?>
                    </select>
					<span class="col-xs-2 col-sm-2" style="float:none">
                      <label>
                      <input class="ace" type="checkbox"  id="JobSoftSkills_checkbox" onchange="if ($(this).is(':checked')) $('#JobSoftSkills_Other').removeClass('hide'); else $('#JobSoftSkills_Other').addClass('hide');"  />
                      <span class="lbl"> Others</span> </label>
                      </span> <span class="col-xs-5 col-sm-5 hide" id="JobSoftSkills_Other" style="float:none">
                      <input type="text" placeholder"other soft skills" id="OtherJobSoftSkills" name="OtherJobSoftSkills"/>
                      </span> 
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Experience:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-6">
                    <select name="JobExperience" id="JobExperience" class="col-xs-3 col-sm-3 selectstyle  validate[required]" onchange="if(this.value=='other') $('#JobExperience_Other').removeClass('hide'); else $('#JobExperience_Other').addClass('hide');">
                      <?=formListOpt('experience', $PAGE['JobExperience'])?>
                      <option value="other">Any Other</option>
                    </select>
                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">As:</label>
                    <div class="col-sm-8">
                      <select name="JobExperienceDesignation" id="JobExperienceDesignation" class="col-xs-5 col-sm-5 selectstyle" onchange="if(this.value=='other') $('#JobExperienceDesignation_Other').removeClass('hide'); else $('#JobExperienceDesignation_Other').addClass('hide');">
                        <?=formListOpt('designation', $PAGE['JobExperienceDesignation'])?>
                        <option value="other">Any Other</option>
                      </select>
                    </div>
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-6 hide" id="JobExperience_Other">
                    <input type="text" id="OtherJobExperience" name="OtherJobExperience" placeholder="Enter Other Option" class="col-xs-3 col-sm-3 " value="<?=$PAGE['OtherJobExperience']?>" />
                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1"></label>
                    <div class="col-sm-8 hide" id="JobExperienceDesignation_Other">
                      <input type="text" id="OtherJobExperienceDesignation" name="OtherJobExperienceDesignation" placeholder="Enter Other Option" class="col-xs-5 col-sm-5" value="<?=$PAGE['OtherJobExperienceDesignation']?>" />
                    </div>
                  </div>
                </div>
                <div class="form-group hide">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Experience:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-6">
                    <select name="JobExperiencex" id="JobExperiencex" class="col-xs-3 col-sm-3 selectstyle  validate[required]">
                      <?=formListOpt('experience', $PAGE['JobExperience'])?>
                    </select>
                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">As:</label>
                    <div class="col-sm-8">
                      <select name="JobExperienceDesignationx" id="JobExperienceDesignationx" class="col-xs-5 col-sm-5 selectstyle">
                        <?=formListOpt('designation', $PAGE['JobExperienceDesignation'])?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Total experience:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9">
                    <select name="JobTotalExperience" id="JobTotalExperience" class="col-xs-5 col-sm-5 selectstyle  validate[required]" onchange="if(this.value=='other') $('#JobTotalExperience_Other').removeClass('hide'); else $('#JobTotalExperience_Other').addClass('hide');">
                      <?=formListOpt('experience', $PAGE['JobTotalExperience'])?>
                      <option value="other">Any Other</option>
                    </select>
                  </div>
                </div>
                <div class="form-group hide" id="JobTotalExperience_Other">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                  <div class="col-sm-9">
                    <input type="text" id="OtherJobTotalExperience" name="OtherJobTotalExperience" placeholder="Enter Other Option" class="col-xs-5 col-sm-5" value="<?=$PAGE['OtherJobTotalExperience']?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Qualification required:<i class="icon-asterisk"></i></label>
                  <div class="col-sm-9"><?=$PAGE["UID"]?>
                    <select name="JobQualification[]" id="JobQualification" multiple="" class="width-80 chosen-select col-xs-5 col-sm-5 validate[required]">
                      <?php
						  $JobQualification = JobExtra($PAGE["UID"], 'JobQualification', 'array');
						  
						  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `Status` = 1 and `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'qualification') order by OptionName");
						  while( $rslt = mysql_fetch_array($qry) ){
							  if( in_array($rslt["OptionDesc"],$JobQualification) ){
								  echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
							  } else {
								  echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
							  }
						  }?>
                    </select>
					<span class="col-xs-2 col-sm-2" style="float:none">
                      <label>
                      <input class="ace" type="checkbox"  id="JobQualification_checkbox" onchange="if ($(this).is(':checked')) { $('#JobQualification_other').removeClass('hide'); $('#JobQualification').removeClass('validate[required]');  $('#OtherJobQualification').addClass('validate[required]');} else { $('#JobQualification_other').addClass('hide');  $('#JobQualification').addClass('validate[required]'); $('#OtherJobQualification').removeClass('validate[required]'); };"  />
                      <span class="lbl"> Others</span> </label>
                      </span> <span class="col-xs-5 col-sm-5 hide" id="JobQualification_other" style="float:none">
                      <input type="text" placeholder"other job qualification" id="OtherJobQualification" name="OtherJobQualification" class="validate[required]"/>
                      </span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Additional Qualification:</label>
                  <div class="col-sm-9">
                    <select name="JobAdditionalQualification[]" id="JobAdditionalQualification[]" multiple="" class="width-80 chosen-select col-xs-5 col-sm-5">
                      <?php
						  $JobAdditionalQualification = JobExtra($PAGE["UID"], 'JobAdditionalQualification', 'array');
						  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `Status` = 1 and `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'qualification') order by OptionName");
						  while( $rslt = mysql_fetch_array($qry) ){
							  if( in_array($rslt["OptionDesc"],$JobAdditionalQualification) ){
								  echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
							  } else {
								  echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
							  }
						  }?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Other Specifications:</label>
                  <div class="col-sm-7">
                    <textarea class="col-xs-8 col-sm-8" id="JobOtherSpec" name="JobOtherSpec" style="min-height: 100px;" ><?=$PAGE['JobOtherSpec']?>
</textarea>
                  </div>
                </div>
                <div class="clearfix form-actions">
                  <div class="col-md-3 pull-right">
                    <button type="submit" id="Submit" class="btn btn-info"> <i class="icon-ok bigger-110"></i> Submit </button>
                    <button type="reset" class="btn"> <i class="icon-undo bigger-110"></i> Reset </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- /.page-header -->
  </div>
  <!-- /.page-content -->
</div>
	<script>
        function nexttab(trg){
            $("form#postjob").validationEngine('validate');
            var valid = $("form#postjob .formError").length;
            if (valid > 0){
                return false; 
            } else {
                $('#myTab a[href="#'+trg+'"]').trigger('click');
                return false;
            }
            return false;
        }
        
        $(document).ready(function(){
            $("form#postjob").validationEngine('attach', {
                promptPosition : "centerRight", 
                scroll: false,
                onValidationComplete: function(form, status){
                    if(status){
                        $("#Submit").click(function (){
                            document.postjob.submit();
                        });
                        
                        
                    } else {
                        //alert("The form status is: " +status+", it will never submit");
                    }
                    //document.postjob.submit();
              }
            });
        
            $("#reset").click(function(){
                $(".formError").hide();
            });
            
            $('#myTab a').click(function(){
                $("form#postjob").validationEngine('validate');	
                var valid = $("form#postjob .formError").length;
                if (valid != 0){
                    return false; 
                } else {
                    return true;
                }
            });
            
        });
    
    
    
        </script> <?php
} ?>

<?php include('footer.php');?>
