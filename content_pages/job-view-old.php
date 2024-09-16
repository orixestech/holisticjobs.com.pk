<?php

$sql = " SELECT * FROM `jobs` WHERE `UID` = '".$_GET["jobid"]."' ";
$stmt = query($sql);
$JOB = fetch($stmt);
( GetEmployer('EmployerLogo', $JOB['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $JOB['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';


if($_POST){
	//print_r($_POST);
	$jobs_apply = array();
	$target_dir = "uploads/";
	
	if($_FILES["file"]["name"])
	{
		$uploadOk = 1;
		$target_file = $target_dir . basename($_FILES["file"]["name"]);
		//echo "target_file = " . $target_file . "<br />";
		
		$path_parts = pathinfo($target_file);
		//echo "<pre>";print_r($path_parts);echo "</pre>";
		
		$fileName = post_slug( $_POST["fullname"]. "_" . rand(0, 100000) ); $path_parts['filename'];
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
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 1)
		{
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
			{
				$jobs_apply['UploadedCV'] = mysql_real_escape_string($fileName.".".$fileType);
			}
			else
			{
				$formMESSAGE = Alert('error', 'Sorry, there was an error uploading your file.');
			}
		}
		
		$sql = "SELECT UID, EmployeePassword FROM `employee` WHERE `EmployeeEmail` = '".$_POST["email"]."' ";
		$stmt = total ( $sql );
		if($stmt==1){
			$sql = fetch ( query($sql) );
			if($sql[1]==''){
				$unique_key = substr(md5(rand(0, 1000000)), 10, 5);
				$query = "UPDATE `employer` SET EmployerPassword = '".PassWord($unique_key,'hide')."' WHERE `employer`.`UID` = '".$sql[0]."'; ";
			}
			$jobs_apply['EmployeeID'] = $sql[0];
			
		} else {
			$employee = array();
			$unique_key = substr(md5(rand(0, 1000000)), 10, 5); 
			$employee['EmployeeName'] = $_POST["fullname"];
			$employee['EmployeeMobile'] = $_POST["contactno"];
			$employee['EmployeeEmail'] = $_POST["email"];
			$employee['EmployeePassword'] = PassWord($unique_key,"hide");
			
			$EmployeeID = FormData('employee', 'insert', $employee, "", $view=false );
			$jobs_apply['EmployeeID'] = $EmployeeID;	
		}
		
		$sql = "SELECT `UID` FROM `jobs_apply` WHERE `EmployeeID` = '".$jobs_apply['EmployeeID']."' and `JobID` = '".$_GET["jobid"]."' ";
		$stmt = total ( $sql );
		if($stmt>0){
			$formMESSAGE = '<div class="notification error closeable">
					<p><span>Error</span>  You already apply on this Job.</p>
					<a href="#" class="close"></a>
				</div>';
		} else {
			$jobs_apply['JobID'] = $_GET["jobid"];
			$jobs_apply['CoverLetter'] = $_POST["coverletter"];
			$jobs_apply['City'] = $_POST["city"];
			$jobs_apply['ApplicationStatus'] = 'New';
				
			$run = FormData('jobs_apply', 'insert', $jobs_apply, "", $view=false );
			
			$EmployerEmail = GetEmployer('EmployerEmail', $JOB['JobEmployerID']);
			$EmployerContactEmail = GetEmployer('EmployerContactEmail', $JOB['JobEmployerID']);
			$EmployeeEmail = $_POST["email"];
			
			$message = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td class="borderColor2" style="padding:10px;" align="left">
				<p class="mainText">
				<strong>Dear Employer, </strong><br ><br >
				I would like to apply for the job vacancy of <strong>"'.$JOB['JobTitle'].'"</strong> in <strong>"'.$_POST["city"].'"</strong> at <strong>"'.GetEmployer('EmployerCompany', $JOB['JobEmployerID']).'"</strong> as this job was published in Holistic Jobs.  Consider my job request and CV for the said post. <br><br>
				Please find the CV attached.<br><br>
				<b>My Cover Letter : </b>'.$_POST["coverletter"].'<br >
				<br>
				Regards,<br><strong>'.$_POST["fullname"].'</strong><br>-via Holistic Jobs<br ><br >
				</p>
			</td></tr></table> ';
			$subject = "Job Application";
			$data = array();
			$data['From'] = $EmployeeEmail;
			$data['FromName'] = $_POST["fullname"] . ' :: Holistic Jobs';
			$data['addAddress'] = array( $EmployerEmail => GetEmployer('EmployerCompany', $JOB['JobEmployerID']) );
			$data['addAttachment'] = array( $_SESSION["root_path"].$target_dir.$jobs_apply['UploadedCV'] );
			$body = SendMail($data, $subject, $message, $show=false);
				
			$message = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td class="borderColor2" style="padding:10px;" align="left">
				<p class="mainText">
				<strong>Dear '.$_POST["fullname"].', </strong><br ><br >
				Your job application request for <strong>"'.$JOB['JobTitle'].'"</strong> has been sent to <strong>"'.GetEmployer('EmployerCompany', $JOB['JobEmployerID']).'"</strong>.<br><br>
				<br>
				Regards,<br><strong>'.$site_name.'</strong><br ><br >
				</p>
			</td></tr></table> ';
			$subject = "Job Application";
			
			$data = array();
			$data['From'] = $site_email;
			$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
			$data['addAddress'] = array( $EmployeeEmail => $_POST["fullname"] );
			$body = SendMail($data, $subject, $message, $show=false);
	
			
			if( $body == 'success' ){
				$formMESSAGE = '<div class="notification success closeable">
						<p><span>Thank You!</span>  Your application has been sent successfully!</p>
						<a href="#" class="close"></a>
					</div><input type="hidden" id="reload" value="0" /> <input type="hidden" id="reloadurl" value="'.$path.'" />';
			} else {
				//echo 'Message could not be sent. <br> Mailer Error: ' . $mail->ErrorInfo;	
			}	
		}
		
		
	} else {
		$formMESSAGE = '<div class="notification error closeable">
					<p><span>Error</span>  You must upload your CV</p>
					<a href="#" class="close"></a>
				</div>';
	}
	
}?>

<div id="titlebar">
  <div class="container">
    <div class="sixteen columns"> <span><a href="browse-jobs.html">
      <?=GetCategory($JOB['JobCategory'])?>
      </a></span>
      <h2>
        <?=$JOB['JobTitle']?>
        <?=($JOB['JobNature']=='office')?' <span class="office"> Office </span> ':''?>
        <?=($JOB['JobNature']=='field')?' <span class="field"> Field </span> ':''?>
        <?=($JOB['JobNature']=='field-office')?' <span class="part-time"> Field + Office </span> ':''?>
		<?=($JOB['JobNature']=='factory')?' <span class="factory"> Factory </span> ':''?>
      </h2>
    </div>
  </div>
</div>
<div class="margin-bottom-50"></div>
<div class="container"> 
  <!-- Recent Jobs -->
  <?=$formMESSAGE?>
  <div class="eleven columns">
    <div class="padding-right"> 
      <!-- Company Info -->
      <div class="company-info">
        <div class="content" style="width:30%"> <img src="<?=$EmployerLogo?>" style="max-width: 90%;" alt=""></div>
        <div class="content" style="width:70%">
          <h4>
            <?=GetEmployer('EmployerCompany', $JOB['JobEmployerID'])?>
          </h4>
          <span><a href="<?=GetEmployer('EmployerWeb', $JOB['JobEmployerID'])?>" target="_blank"><i class="fa fa-link"></i>
          <?=GetEmployer('EmployerWeb', $JOB['JobEmployerID'])?>
          </a> &nbsp; <a class="button pull-right" href="<?=EmployerProfileLink($JOB['JobEmployerID'])?>" style="color:#FFF;">Company Profile</a> </span> </div>
        <div class="clearfix"></div>
      </div>
      <p class="margin-reset">
        <?=ApplyTheme($JOB['JobDescription'])?>
      </p>
      <br>
    </div>
  </div>
  <!-- Widgets -->
  <div class="five columns"> 
    <!-- Sort by -->
    <div class="widget">
      <h4>Last Date to Apply 
      	<span style="text-align:right; float:right; color:#ff0000; font-weight:bold;">
        <?=date("d M, Y",strtotime($JOB['JobLastDateApply']))?>
        </span>
      </h4>
      <div class="clearfix"></div>
      <div class="job-overview">
        <ul>
          <?php $JobCity = JobExtra($_GET["jobid"], 'JobCity', 'string');?>
          <li <?=($JobCity=='')?'style="display:none;"':''?> > <i class="fa fa-map-marker"></i>
            <div> <strong>Location:</strong> <span>
              <?=$JobCity?>
              </span> </div>
          </li>
          <li <?=($JOB['JobSalaryFrom']=='' || $JOB['JobSalaryFrom']==0)?'style="display:none;"':''?> > <i class="fa fa-money"></i>
            <div> <strong>Salary:</strong> <span>
              <?=$JOB['JobSalaryFrom']?>
              to
              <?=$JOB['JobSalaryTo']?>
              </span> </div>
          </li>
          <li <?=($JOB['JobNumbOfVacancy']=='' || $JOB['JobNumbOfVacancy']==0)?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Total Vacancy:</strong> <span>
              <?=$JOB['JobNumbOfVacancy']?>
              </span> </div>
          </li>
          <li <?=($JOB['JobDesignation']=='' || $JOB['JobDesignation']==0)?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Designation:</strong> <span>
              <?=optionVal($JOB['JobDesignation'])?>
              </span> </div>
          </li>
          <li <?=($JOB['JobDepartment']=='' || $JOB['JobDepartment']==0)?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Department:</strong> <span>
              <?=optionVal($JOB['JobDepartment'])?>
              </span> </div>
          </li>
          <li <?=($JOB['JobType']=='' || $JOB['JobType']==0)?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Job Type:</strong> <span>
              <?=optionVal($JOB['JobType'])?>
              </span> </div>
          </li>
          <li <?=($JOB['JobShift']=='' || $JOB['JobShift']==0)?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Shift:</strong> <span>
              <?=optionVal($JOB['JobShift'])?>
              </span> </div>
          </li>
          <?php $JobGender = JobExtra($_GET["jobid"], 'JobGender', 'string');?>
          <li <?=($JobGender=='')?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Gender:</strong> <span>
              <?=$JobGender?>
              </span> </div>
          </li>
          <?php $JobSkills = JobExtra($_GET["jobid"], 'JobSkills', 'string');?>
          <li <?=($JobSkills=='')?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Technical Skills:</strong> <span>
              <?=optionVal($JOB['JobSkills'])?>
              <?=$JobSkills?>
              </span> </div>
          </li>
          <?php $JobSoftSkills = JobExtra($_GET["jobid"], 'JobSoftSkills', 'string');?>
          <li <?=($JobSoftSkills=='')?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Soft Skills:</strong> <span>
              <?=$JobSoftSkills?>
              </span> </div>
          </li>
          <li <?=($JOB['JobExperience']=='' || $JOB['JobExperience']==0)?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Experience:</strong> <span>
              <?=optionVal($JOB['JobExperience'])?> <?=($JOB['JobExperienceDesignation']!=0)?" as " . optionVal($JOB['JobExperienceDesignation']):''?>
              </span> </div>
          </li>
          <?php $JobQualification = JobExtra($_GET["jobid"], 'JobQualification', 'string');?>
          <li <?=($JobQualification=='')?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Qualification:</strong> <span>
              <?=$JobQualification?>
              </span> </div>
          </li>
          <?php $JobAdditionalQualification = JobExtra($_GET["jobid"], 'JobAdditionalQualification', 'string');?>
          <li <?=($JobAdditionalQualification=='')?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Additional Qualification:</strong> <span>
              <?=$JobAdditionalQualification?>
              </span> </div>
          </li>
        </ul><?php
		
		if( date("U", strtotime($JOB['JobLastDateApply'])) <= date("U", strtotime(date("Y-m-d"))) ){
			?><a href="javascript:alert('Job Expired...');" class="button">Apply For This Job</a><?
		} else { ?>
            <a href="#small-dialog" class="popup-with-zoom-anim button">Apply For This Job</a>
            <div id="small-dialog" class="zoom-anim-dialog mfp-hide apply-popup">
              <div class="small-dialog-headline">
                <h2>Apply For This Job</h2>
              </div>
              <div class="small-dialog-content">
                <form action="" method="post" id="jobapply" enctype="multipart/form-data" >
                  <input type="hidden" name="formtype" value="applyjob" />
                  <input type="text" name="fullname" id="fullname" placeholder="Full Name" value="" class="validate[required,custom[onlyLetterSp]]"/>
                  <input type="text" name="email" id="email" placeholder="Email Address" value="" class="validate[required,custom[email]]"/>
                  <input type="text" name="contactno" id="contactno" placeholder="Contact No." maxlength="11" value="" class="validate[required,custom[onlyNumberSp,minSize[11]]]"/>
                  <select class="chosen-select-no-single" name="city" id="city" data-placeholder="Apply for">
                    <?php
                     $JobCity = JobExtra($_GET["jobid"], 'JobCity', 'array');
                     foreach($JobCity as $val){
						if($val=='Multiple Cities'){
							$qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'city') order by OptionName");
							  while( $rslt = mysql_fetch_array($qry) ){
								  echo '<option value="'.$rslt["OptionDesc"].'">'.$rslt["OptionDesc"].'</option>';
							  }
						} else {
							echo '<option value="'.$val.'">'.$val.'</option>'; 
						}
                     }?>
                  </select>
                  <textarea placeholder="Your message / cover letter sent to the employer" name="coverletter" id="coverletter" ></textarea>
                  <!-- Upload CV -->
                  <div class="form upload-file">
                    <h5>Upload <span>CV</span> <span>Max. file size: 5MB</span> </h5>
                    <label class="upload-btn">
                      <input type="file" name="file" id="file" multiple />
                      <i class="fa fa-upload"></i> Browse </label>
                    <span class="fake-input">No file selected</span> </div>
                  <div class="divider"></div>
                  <button class="send">Send Application</button>
                </form>
              </div>
            </div>
		<?php } ?>
        
      </div>
    </div>
  </div>
  <!-- Widgets / End --> 
</div>
<div class="margin-bottom-50"></div>
<!-- Container / End -->
<link rel="stylesheet" type="text/css" href="<?=$path?>formcss/validationEngine.jquery.css" />
<script src="<?=$path?>formcss/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?=$path?>formcss/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script> 
<script>
$(document).ready(function(){
	$("form#jobapply").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.contactus.submit();
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
