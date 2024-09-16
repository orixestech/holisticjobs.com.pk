<?php
function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}
$sql = " SELECT * FROM `jobs` WHERE `UID` = '".$_GET["jobid"]."' ";
$stmt = query($sql);
$JOB = fetch($stmt);
( GetEmployer('EmployerLogo', $JOB['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $JOB['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';

$sql = "SELECT `UID` FROM `jobs_apply` WHERE `EmployeeID` = '".$_SESSION['EmployeeUID']."' and `JobID` = '".$_GET["jobid"]."' ";
$stmt = total ( $sql );
if($stmt>0){
	$jobs_applyMESSAGE = '<div class="notification error closeable">
			<p><span>Error! </span> You have already applied for this job.</p>
			<a href="#" class="close"></a>
		</div>';
}

if($_POST){
	//print_r($_POST);
	$jobs_apply = array();
	$target_dir = "uploads/";
	
	$CVFile = GetData('ResumeFilename','employee_resume','UID',$_POST["cv"]);
	
	if($CVFile!='')
	{
		$jobs_apply['EmployeeID'] = $_SESSION['EmployeeUID'];	
		
		$sql = "SELECT `UID` FROM `jobs_apply` WHERE `EmployeeID` = '".$jobs_apply['EmployeeID']."' and `JobID` = '".$_GET["jobid"]."' ";
		$stmt = total ( $sql );
		if($stmt>0){
			$formMESSAGE = '<div class="notification error closeable">
					<p><span>Error! </span>  You have already applied for this job.</p>
					<a href="#" class="close"></a>
				</div>';
		} else {
			( isset($_POST["coverletter"]) )?'':$_POST["coverletter"] = 'Not mentioned';
			
			$jobs_apply['JobID'] = $_GET["jobid"];
			$jobs_apply['CoverLetter'] = $_POST["coverletter"];
			$jobs_apply['City'] = $_POST["city"];
			$jobs_apply['ApplicationStatus'] = 'New';
			$jobs_apply['InterviewApproval'] = 0;
			$jobs_apply['ExpectedSallery'] = $_POST["ExpectedSalleryFrom"];
			$jobs_apply['UploadedCV'] = $CVFile;
				
			$run = FormData('jobs_apply', 'insert', $jobs_apply, "", $view=false );
			
			Track( ucfirst('New Job Application for "'.$JOB['JobTitle'].'" Submitted.'), 'employer', $JOB['JobEmployerID']);
			
			$EmployerEmail = GetEmployer('EmployerEmail', $JOB['JobEmployerID']);
			$EmployerContactEmail = GetEmployer('EmployerContactEmail', $JOB['JobEmployerID']);
			$EmployeeEmail = $_SESSION['Employee']['EmployeeEmail'];
			
			$message = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td class="borderColor2" style="padding:10px;" align="left">
				<p class="mainText">
				<strong>Dear Employer, </strong><br ><br >
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '.$_POST["coverletter"].'<br ><br>
				<b>Expected Sallery : </b>'.$_POST["ExpectedSalleryFrom"].' to '.$_POST["ExpectedSalleryTo"].'<br><br>
				Click on the following link to access the CV. <a href="http://www.holisticjobs.com.pk/page/employer-login">Login</a><br><br>
				Regards,<br><strong>'.$_SESSION['Employee']['EmployeeName'].'</strong><br>-via Holistic Jobs<br ><br >
				</p>
			</td></tr></table> ';
			$subject = "Job Application";
			$data = array();
			$data['From'] = $EmployeeEmail;
			$data['FromName'] = $_SESSION['Employee']['EmployeeName'] . ' :: Holistic Jobs';
			$data['addAddress'] = array( $EmployerEmail => GetEmployer('EmployerCompany', $JOB['JobEmployerID']) );
			//$data['addAttachment'] = array( $_SESSION["root_path"].$target_dir.$jobs_apply['UploadedCV'] );
			$body = SendMail($data, $subject, $message, $show=false);
				
			$message = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td class="borderColor2" style="padding:10px;" align="left">
				<p class="mainText">
				<strong>Dear '.$_SESSION['Employee']['EmployeeName'].', </strong><br ><br >
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Your job application request for <strong>"'.$JOB['JobTitle'].'"</strong> has been sent to <strong>"'.GetEmployer('EmployerCompany', $JOB['JobEmployerID']).'"</strong>.<br><br>
				<br>
				Regards,<br><strong>Team Holistic Jobs</strong><br ><br >
				</p>
			</td></tr></table> ';
			$subject = "Job Application";
			
			$data = array();
			$data['From'] = $site_email;
			$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
			$data['addAddress'] = array( $EmployeeEmail => $_SESSION['Employee']['EmployeeName'] );
			$body = SendMail($data, $subject, $message, $show=false);
	
			
			if( $body == 'success' ){
				$formMESSAGE = '<div class="notification success closeable">
						<p><span>Thank You!</span>  Your application has been sent successfully!</p>
						<a href="#" class="close"></a>
					</div><input type="hidden" id="reload" value="0" /> <input type="hidden" id="reloadurl" value="'.$path.'" />';
				echo "<script language='javascript'>setTimeout(\"window.location = '".$path."jobs/list';\", 2000);</script>";
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
    <div class="sixteen columns"> <span><?=GetCategory($JOB['JobCategory'])?></span>
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
  <?=$jobs_applyMESSAGE?>
  <div class="eleven columns">
    <div class="padding-right"> 
      <!-- Company Info -->
      <div class="company-info">
        <div class="content" style="width:30%"> <img src="<?=$EmployerLogo?>" style="max-width: 90%;" alt=""></div>
        <div class="content" style="width:70%">
          <h4>
            <?=GetEmployer('EmployerCompany', $JOB['JobEmployerID'])?>
          </h4>
          <span ><a href="<?=addhttp( GetEmployer('EmployerWeb', $JOB['JobEmployerID']) )?>" target="_blank" class="<?=( GetEmployer('EmployerWeb', $JOB['JobEmployerID']) == '' )?'hide':''?>"><i class="fa fa-link"></i>
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
      <h4>Last Date to Apply <span style="color:#ff0000; font-weight:bold;">
        <?=date("d M, Y",strtotime($JOB['JobLastDateApply']))?>
        </span> </h4>
      <div class="clearfix"></div>
      <div class="job-overview" style="padding: 0px;">
        <div style="background-color:#FF0000; height: 35px;">
          <?php
		//echo $_SESSION['EmployeeUID'];
		if( date("U", strtotime($JOB['JobLastDateApply'])) <= date("U", strtotime(date("Y-m-d")) )){?>
          <a href="javascript:alert('Job Expired...');" class="button" style="margin-top: 0px;">Apply For This Job</a> <?
		} else {
			if( isset($_SESSION['EmployeeUID']) && $_SESSION['EmployeeUID'] > 0){
				$ACCESS = CheckSubAccess('applying-for-job', 'employee');
				if($ACCESS['access']=='false'){ ?>
                  	<a href="javascript:alert('Job Expired...');" class="button" style="margin-top: 0px;">Access Denied<br />
                  	Please update your Subscription.</a> <?
				} else if( $jobs_applyMESSAGE!='' ){ ?>
          			<a href="javascript:alert('Error! You have already applied for this job.');" class="button">Application Sent</a> <?
				} else { ?>
                    <a href="#small-dialog" class="popup-with-zoom-anim button" style="margin-top: 0px;">Apply Now</a>
                    <div id="small-dialog" class="zoom-anim-dialog mfp-hide apply-popup">
                      <div class="small-dialog-headline">
                        <h2>Apply For This Job</h2>
                      </div>
                      <div class="small-dialog-content">
                        <form action="" method="post" id="jobapply" enctype="multipart/form-data" >
                          <input type="hidden" name="formtype" value="applyjob" />
                          <h5>Name :
                            <?=$_SESSION['Employee']['EmployeeName']?>
                          </h5>
                          <br />
                          <h5>Email :
                            <?=$_SESSION['Employee']['EmployeeEmail']?>
                          </h5>
                          <br />
                          <h5>Contact # :
                            <?=$_SESSION['Employee']['EmployeeMobile']?>
                          </h5>
                          <br />
                          <label>Select City</label>
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
                          <div class="container">
                            <label>Select Expected Salary Range</label>
                            <div class="three columns" style="margin-left:0">
                              <select class="validate[required]" name="ExpectedSalleryFrom" id="ExpectedSalleryFrom" data-placeholder="Expected Sallery">
                                <option value="">Must Select</option>
                                <option value="<?=SalleryDropdown($PAGE['ExpectedSalleryFrom'])?>"></option>
                              </select>
                            </div>
                            <div class="one columns">
                              <p style="text-align:center">To</p>
                            </div>
                            <div class="three columns">
                              <select class="validate[required]" name="ExpectedSalleryTo" id="ExpectedSalleryTo" data-placeholder="Expected Sallery">
                                <option value="">Must Select</option>
                                <option value="<?=SalleryDropdown($PAGE['ExpectedSalleryTo'])?>"></option>
                              </select>
                            </div>
                          </div>
                          <?php
						  $sql = "SELECT * FROM `employee_resume` WHERE `ResumeEmployeeID` = '".$_SESSION['EmployeeUID']."' ";
						  $rows = total( $sql );
						  if($rows > 0){
							  echo '<label>Uploaded Cvs:</label><select class="chosen-select-no-single" name="cv" id="cv" data-placeholder="Uploaded CVs">';
							  $stmt = query( $sql );
							  while( $rslt = fetch($stmt) ){
								  echo '<option value="'.$rslt["UID"].'">'.$rslt["ResumeTitle"].'</option>';
							  }
							  echo '</select>';
							  
						  } else { ?>
                          	<label>Your CV is not Uploaded (Go to '<a href="<?=$path?>employee/resume.php">Upload CV</a>' section in your Employee Portal</label> <br /> <?php	
                          }?>
						  <label>My Cover Letter</label>
                          <textarea placeholder="Your message / cover letter sent to the employer" name="coverletter" id="coverletter" class="validate[required]" >I would like to apply for the job vacancy of "<?=$JOB['JobTitle']?>" at "<?=GetEmployer('EmployerCompany', $JOB['JobEmployerID'])?>" as this job was published on Holistic Jobs. Consider my job request and CV for the said post. </textarea>
                          <div class="divider"></div>
                          <button class="send">Send Application</button>
                        </form>
                      </div>
                    </div> <?
				}
			} else { ?>
          		<a href="<?=$path?>page/employee-login" class="button" style="margin-top: 0px;">Apply Now</a> <?php 
			} 
		}?>
        </div>
        <ul style="margin: 10px;">
          <?php $JobCity = JobExtra($_GET["jobid"], 'JobCity', 'string');?>
          <li <?=($JobCity=='')?'style="display:none;"':''?> > <i class="fa fa-map-marker"></i>
            <div> <strong>Location:</strong> <span>
              <?=$JobCity?>
              </span> </div>
          </li>
          <li <?=($JOB['JobSalaryFrom']=='' || $JOB['JobSalaryFrom']==0)?'style="display:none;"':''?> > <i class="fa fa-money"></i>
            <div> <strong>Salary:</strong> <span>
              <?=$JOB['JobSalaryFrom']?>
              <?=($JOB['JobSalaryTo']!=0 && $JOB['JobSalaryTo'] != '')?' to ' . $JOB['JobSalaryTo'] :''?>
              </span> </div>
          </li>
          <li <?=($JOB['JobNumbOfVacancy']=='' || $JOB['JobNumbOfVacancy']==0)?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Total Vacancy:</strong> <span>
              <?=$JOB['JobNumbOfVacancy']?>
              </span> </div>
          </li>
          <li <?=($JOB['JobAgeLimit']=='' || $JOB['JobAgeLimit']==0)?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Age Limit:</strong> <span>
              <?=$JOB['JobAgeLimit']?>
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
              <?=optionVal($JOB['JobExperience'])?>
              <?=($JOB['JobExperienceDesignation']!=0)?" as " . optionVal($JOB['JobExperienceDesignation']):''?>
              </span> </div>
          </li>
          <li <?=($JOB['JobTotalExperience']=='' || $JOB['JobTotalExperience']==0)?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Total Experience:</strong> <span>
              <?=optionVal($JOB['JobTotalExperience'])?>
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
          <li <?=($JOB['JobOtherSpec']=='')?'style="display:none;"':''?> > <i class="fa fa-circle"></i>
            <div> <strong>Other Job Specification:</strong> <span>
              <?=$JOB['JobOtherSpec']?>
              </span> </div>
          </li>
        </ul>
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
