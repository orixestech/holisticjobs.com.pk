<?php include('header.php');?>
<?php
if($_POST){
	$contents = $_POST;
	$nowDate = date("Y-m-d h:i:s");
	$contents['JobLastDateApply'] = date("Y-m-d", strtotime($_POST['JobLastDateApply']));
	
	if($_GET["mode"]=="update"){
		query("DELETE FROM `jobs_extra` WHERE `JobID` = '".$_GET["code"]."' ");
		
		$options = array('JobGender','JobCity','JobSkills','JobSoftSkills','JobQualification','JobAdditionalQualification');
		foreach($options as $opt){
			foreach($_REQUEST[$opt] as $val){
				/*if($opt=='JobCity'){
					if(optionVal($val)=='Multiple Cities'){
						$ARR = formListArray('city');
						foreach($ARR as $citykey => $citytitle){
							$TOTAL = total("SELECT * FROM `jobs_extra` WHERE `JobID` = '".$_GET["code"]."' and `InfoType` = '".$opt."' and `InfoTypeValue` = '".optionVal($citykey)."' ");
							if($TOTAL==0 && optionVal($citykey) != 'Multiple Cities'){
								$JobCity = array();
								$JobCity['JobID'] = $_GET["code"];
								$JobCity['InfoType'] = $opt;
								$JobCity['InfoTypeValue'] = optionVal($citykey);
								FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
							}
						}
					} else {
						$JobCity = array();
						$JobCity['JobID'] = $_GET["code"];
						$JobCity['InfoType'] = $opt;
						$JobCity['InfoTypeValue'] = optionVal($val);
						FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
					}
				} else {
					$JobCity = array();
					$JobCity['JobID'] = $_GET["code"];
					$JobCity['InfoType'] = $opt;
					$JobCity['InfoTypeValue'] = ($opt=='JobGender')?$val:optionVal($val);
					FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
				}*/
				$JobCity = array();
				$JobCity['JobID'] = $_GET["code"];
				$JobCity['InfoType'] = $opt;
				$JobCity['InfoTypeValue'] = ($opt=='JobGender')?$val:optionVal($val);
				FormData('jobs_extra', 'insert', $JobCity, "", $view=false );
			}
		}
		
		$run = FormData('jobs', 'update', $contents, " UID = '".$_GET["code"]."' ", $view=false );
		$FormMessge = Alert('success', 'Job Updated...!');
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
	}
}


$PAGE['JobNumbOfVacancy'] = '';

if($_GET["mode"]=="update"){
	$stmt = mysql_query(" SELECT * FROM `jobs` WHERE `UID` = '".$_GET["code"]."' ");
	$PAGE = mysql_fetch_array($stmt);
} ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li> <a href="#">Web Site</a> </li>
      <li class="active">
        <?=($_GET["id"])?'Modify':'Add New'?>
        Page </li>
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
        <?=($_GET["id"])?'Modify':'Add Job'?>
        Form <small> <i class="icon-double-angle-right"></i> Add or Update Job Form </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" method="post" name="postjob" id="postjob">
      <div class="row">
        <h4 class="header green">Basic Details</h4>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <div class="row">
            <!--<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Category:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <select name="JobCategory" id="JobCategory" class="col-xs-5 col-sm-5 selectstyle validate[required]" >
                  <?php echo CategoryDropDown('Jobs',$PAGE['JobCategory']);?>
                </select>
              </div>
            </div>-->
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Job title:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <input type="text" id="JobTitle" name="JobTitle" placeholder="Job Title" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['JobTitle']?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Company Name:<i class="icon-asterisk"></i> </label>
              <div class="col-sm-9">
                <select name="JobEmployerID" id="JobEmployerID" class="col-xs-5 col-sm-5 selectstyle validate[required]" >
                  <?php
					$stmt = query("SELECT UID, EmployerCompany  FROM `employer` WHERE 1 ORDER BY `employer`.`EmployerCompany` ASC ");
					while($rslt = fetch($stmt)){
						?>
                  <option value="<?=$rslt['UID']?>" <?=($PAGE['JobEmployerID']==$rslt['UID'])?'selected':''?> >
                  <?=$rslt['EmployerCompany']?>
                  </option>
                  <?					
					}?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Designation:<i class="icon-asterisk"></i> </label>
              <div class="col-sm-9">
                <select name="JobDesignation" id="JobDesignation" class="col-xs-5 col-sm-5 selectstyle validate[required]" >
                  <?=formListOpt('designation', $PAGE['JobDesignation'])?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Department:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <select name="JobDepartment" id="JobDepartment" class="col-xs-5 col-sm-5 selectstyle validate[required]" >
                  <?=formListOpt('departments', $PAGE['JobDepartment'])?>
                </select>
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
                <input type="number" id="JobAgeLimit" name="JobAgeLimit" placeholder="Age Limit" class="col-xs-10 col-sm-5 validate[custom[number]]" value="<?=$PAGE['JobAgeLimit']?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">No. of Vacancies:</label>
              <div class="col-sm-9">
                <input type="text" id="JobNumbOfVacancy" name="JobNumbOfVacancy" placeholder="No of Vacancies" class="col-xs-10 col-sm-5" value="<?=$PAGE['JobNumbOfVacancy']?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Job Priority:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <select class="col-xs-5 col-sm-5 selectstyle validate[required]" id="JobPriority" name="JobPriority">
                  <option value="normal" <?=($PAGE['JobPriority']=='normal')?' selected ':''?> >Normal</option>
                  <option value="top" <?=($PAGE['JobPriority']=='top')?' selected ':''?> >TOP</option>
                  <option value="premium" <?=($PAGE['JobPriority']=='premium')?' selected ':''?> >Premium</option>
                </select>
              </div>
            </div>
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
                <select multiple=""  name="JobCity[]" id="JobCity" class="width-40 chosen-select validate[required]" data-placeholder="Choose Cities..." >
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
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Job type</label>
              <div class="col-sm-9">
                <select name="JobType" id="JobType" class="col-xs-5 col-sm-5 selectstyle"  >
                  <?=formListOpt('job-type', $PAGE['JobType'])?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Job nature:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <select name="JobNature" id="JobNature" class="col-xs-5 col-sm-5 selectstyle validate[required]"  >
                  <option value="field" <?=($PAGE['JobNature']=='field')?' selected ':''?> >Field</option>
                  <option value="office" <?=($PAGE['JobNature']=='office')?' selected ':''?> >Office</option>
                  <option value="field-office" <?=($PAGE['JobNature']=='field-office')?' selected ':''?> >Field + Office</option>
                  <option value="factory" <?=($PAGE['JobNature']=='factory')?' selected ':''?> >Factory</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Shift</label>
              <div class="col-sm-9">
                <select name="JobShift" id="JobShift" class="col-xs-5 col-sm-5 selectstyle"  >
                  <?=formListOpt('shifts', $PAGE['JobShift'])?>
                </select>
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
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Job Description:<i class="icon-asterisk"></i></label>
              <div class="col-sm-7">
                <textarea class="ContentEditor" id="JobDescription" name="JobDescription" style="height: 260px;" ><?=($PAGE['JobDescription']!='')?$PAGE['JobDescription']:'Job description is as per the norms of society'?>
</textarea>
              </div>
            </div>
            <h4 class="header green">Requirements</h4>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Technical Skills </label>
              <div class="col-sm-9">
                <select multiple=""  name="JobSkills[]" id="JobSkills" class="width-40 chosen-select" data-placeholder="Choose Skills..." >
                  <?php
				  $JobSkills = JobExtra($PAGE["UID"], 'JobSkills', 'array');
				  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'skills') order by OptionName");
				  echo '<option value=""></option>';
				  while( $rslt = mysql_fetch_array($qry) ){
					  if( in_array($rslt["OptionDesc"],$JobSkills) ){
						  echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
					  } else {
						  echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
					  }
				  }?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Soft Skills: </label>
              <div class="col-sm-9">
                <select multiple="" name="JobSoftSkills[]" id="JobSoftSkills" class="width-40 chosen-select" data-placeholder="Choose Skills..."  >
                  <?php
				  $JobSoftSkills = JobExtra($PAGE["UID"], 'JobSoftSkills', 'array');
				  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'soft-skills') order by OptionName");
				  echo '<option value=""></option>';
				  while( $rslt = mysql_fetch_array($qry) ){
					  if( in_array($rslt["OptionDesc"],$JobSoftSkills) ){
						  echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
					  } else {
						  echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
					  }
				  }?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Experience:<i class="icon-asterisk"></i></label>
              <div class="col-sm-7">
                <select name="JobExperience" id="JobExperience" class="col-xs-2 col-sm-2 selectstyle  validate[required]">
                  <?=formListOpt('experience', $PAGE['JobExperience'])?>
                </select>
                <label class="col-sm-1 control-label no-padding-right" for="form-field-1">As:</label>
                <div class="col-sm-8">
                  <select name="JobExperienceDesignation" id="JobExperienceDesignation" class="col-xs-5 col-sm-5 selectstyle  ">
                    <?=formListOpt('designation', $PAGE['JobExperienceDesignation'])?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Qualification required:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9"><?=optionVal($PAGE['JobQualification'])?>
                <select name="JobQualification[]" id="JobQualification[]" multiple="" class="width-40 chosen-select validate[required]">
                  <?php
						  $JobQualification = JobExtra($PAGE["UID"], 'JobQualification', 'array');
						  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'qualification') order by OptionName");
						  while( $rslt = mysql_fetch_array($qry) ){
							  if( in_array($rslt["OptionDesc"],$JobQualification) ){
								  echo  '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionDesc"].'</option>';
							  } else {
								  echo '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionDesc"].'</option>';
							  }
						  }?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Additional Qualification:</label>
              <div class="col-sm-9">
                <select name="JobAdditionalQualification[]" id="JobAdditionalQualification[]" multiple="" class="width-40 chosen-select">
                  <?php
						  $JobAdditionalQualification = JobExtra($PAGE["UID"], 'JobAdditionalQualification', 'array');
						  $qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'qualification') order by OptionName");
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
$(document).ready(function(){
	$("form#postjob").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.postjob.submit();
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
