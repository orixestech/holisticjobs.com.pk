<?php 
$error = 0;
if($_SESSION['EmployerLogged']!=1){
	$errorMSG = "You must be login with your Employer Account to view this page.";	
	$error = 1;
} else {
	$access = CheckSubAccess('existing-pool-cvs', 'employer');
	if($access['access']=='false'){
		$errorMSG = "You must have access on Pool of CVs in your Employer Account to view this page.";	
		$error = 1;
	}
}

if($error == 1){?>
    <div class="no-body">
      <div id="titlebar" class="single">
        <div class="container">
          <div class="sixteen columns">
            <h2> Access Denied.!</h2>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="sixteen columns">
          <p class="margin-reset" style="text-align: justify;">
            <?=$errorMSG?>
          </p>
        </div>
      </div>
      <div class="margin-top-50"></div>
    </div><?php
} else {
	
	$InvitationAccept = GetEmployerAccessToEmployee($_SESSION['Employer']['UID'], $_GET["empid"], 'invitation-accept');
	
	$sql = " SELECT * FROM `employee` WHERE `UID` =  '".$_GET["empid"]."' ";
	$stmt = query($sql);
	$EMPLOYEE = fetch($stmt);
	$ProfileScore = round(ProfileScore($_GET['empid']),0);
	
	$EmployeeLogo = ($EMPLOYEE['EmployeeLogo']!='') ? $path . 'uploads/' . $EMPLOYEE['EmployeeLogo'] : $path . 'uploads/no-image.png';
	
	if($InvitationAccept){
		$EmployeeLogo = ($EMPLOYEE['EmployeeLogo']!='') ? $path . 'uploads/' . $EMPLOYEE['EmployeeLogo'] : $path . 'uploads/no-image.png';	
	} else {
		$EmployeeLogo = $path . 'uploads/no-image.png';	
	} ?>
<style>
.red {
	color:#FF0000;
}
</style>
<div id="titlebar" class="resume">
  <div class="container">
    <div class="ten columns">
      <div class="resume-titlebar"> <img src="<?=$EmployeeLogo?>" alt="" />
        <div class="resumes-list-content">
          <h4><?php
			if($InvitationAccept){
				$EmployeeTitle = optionVal($EMPLOYEE['EmployeeTitle']) . " " . $EMPLOYEE['EmployeeName'];
			} else {
				$EmployeeTitle = EmpCode("Employee Code :", $_REQUEST["empid"]);
			}?>
			<?=$EmployeeTitle?> <font class="field" style="width: 100%; background: rgb(24, 111, 201) none repeat scroll 0% 0%; color: rgb(255, 255, 255); padding: 0px 10px; font-size: 80%;"> <?=GetEmployeeExpInYear( $_GET['empid'] )?> Experience</font> 
          </h4>
          <?php
		  if($InvitationAccept){
			$sql = "SELECT * FROM `employee_experience` WHERE `ExperienceEmployeeID` = '".$_GET['empid']."' and `employee_experience`.`ExperienceTo` = '1971-01-01' ";
			$stmt = query($sql);
			$experience = fetch($stmt);
			if( total($sql) > 0 ) {?>
				<?php if ($experience['ExperienceEmployer'] != "" ) {?>
				<span class="icons">
				<?=optionVal($experience['ExperienceDesignation'])?>
				</span><br />
				<?php } ?>
				<?php if ($experience['ExperienceEmployer'] != "" ) {?>
				<span class="icons"> at
				<?=$experience['ExperienceEmployer']?>
				</span>
				<?php } ?>
			<?php }
		  }
		  
		 ?>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="six columns">
      <p class="pull-right" style="font-size:18px;"> <i class="fa fa-star red"></i> Profile Score: <?=$ProfileScore?> % </p>
      <?php
	  if($InvitationAccept){
		  ?><a href="<?=$path?>holistic-cv/<?=base64_encode($EMPLOYEE['UID']."|".$EMPLOYEE['EmployeeName'])?>" target="_blank" type="button" class="zoom-anim button"><i class="fa fa-download"></i> Download CV</a><?php
	  } else {
		  /*?><a href="#" type="button" class="zoom-anim button"></a><?php*/		
	  } ?> </div>
  </div>
</div>
<div class="container">
<div class="columns"><span style="margin-right:30px; color:red;"><i class="fa fa-globe"></i> Attention Portal members! Currently you are seen this incomplete profile, You must send invitation to this employee and when you get approved status in invitation then you are able to see complete profile.</span></div>
  <div class="eight columns">
    <div class="padding-right">
      <?php if ($EMPLOYEE['EmployeeObjective'] != ""){?>
              <h3 class="margin-bottom-5">My Objectives</h3>
              <p class="margin-reset">
                <?=$EMPLOYEE['EmployeeObjective']?>
              </p>
      <?php } ?>
      
      <?php if($InvitationAccept){ ?>
              <h3 class="margin-bottom-5">Personal Information</h3>
              <p style="font-size:18px; line-height:35px;">
                <?php if($EMPLOYEE['EmployeeCity']!=0 && $EMPLOYEE['EmployeeState']!=0 ){ ?>
                        <i class="fa fa-map-marker red"></i> &nbsp;
                        <?=optionVal($EMPLOYEE['EmployeeCity'])?> , <?=optionVal($EMPLOYEE['EmployeeState'])?>
                        <br />
                <?php }?>
                <?php if($EMPLOYEE['EmployeeDOB']!="0000-00-00"){ ?>
                        <i class="fa fa-calendar-o red"></i> &nbsp; DOB: <?=date("d M, Y", strtotime($EMPLOYEE['EmployeeDOB']))?>
                        <br />
                <?php }?>
                <?php if ($EMPLOYEE['EmployeeMobile'] != ""){ ?>
                        <i class="fa fa-mobile red"></i> &nbsp; <?=$EMPLOYEE['EmployeeMobile']?>
                        <br />
                <?php } ?>
                <?php if ($EMPLOYEE['EmployeeEmail'] != ""){?>
                        <a href="mailto:<?=$EMPLOYEE['EmployeeEmail']?>" style="color:#666"><i class="fa fa-envelope red"></i> &nbsp; <?=$EMPLOYEE['EmployeeEmail']?> </a>
                <?php } ?>
              </p>
      <?php }?>
      
      <?php
	  $sql = " SELECT * FROM `employee_extra` WHERE `InfoType` = 'EmployeeSoftSkills' and `EmployeeID` = '".$EMPLOYEE['UID']."' ORDER BY `employee_extra`.`InfoTypeValue` ASC"; 
	  if( total($sql) > 0 ) { ?>
          <h3 class="margin-bottom-5">Soft Skills</h3>
          <ul class="list-1"> <?php
            $stmt = query($sql);
            while($rslt=fetch($stmt)){
                echo '<li>'.$rslt['InfoTypeValue'].'</li>';
            }?>
          </ul>
      <?php }?>
      <?php
	  $sql = " SELECT * FROM `employee_extra` WHERE `InfoType` = 'EmployeeSkills' and `EmployeeID` = '".$EMPLOYEE['UID']."' ORDER BY `employee_extra`.`InfoTypeValue` ASC"; 
	  if( total($sql) > 0 ) { ?>
          <h3 class="margin-bottom-5">Technical Skills</h3>
          <ul class="list-1">
            <?php
            $stmt = query($sql);
            while($rslt=fetch($stmt)){
                echo '<li>'.$rslt['InfoTypeValue'].'</li>';
            }?>
          </ul>
      <?php }?>
      <?php
	  $sql = " SELECT * FROM `employee_extra` WHERE `InfoType` = 'EmployeeInterests' and `EmployeeID` = '".$EMPLOYEE['UID']."' ORDER BY `employee_extra`.`InfoTypeValue` ASC"; 
	  if( total($sql) > 0 ) { ?>
          <h3 class="margin-bottom-5">Interests</h3>
          <ul class="list-1">
            <?php
            $stmt = query($sql);
            while($rslt=fetch($stmt)){
                echo '<li>'.$rslt['InfoTypeValue'].'</li>';
           }?>
          </ul>
      <?php }?>
      <?php if ($EMPLOYEE['AdditionalInformation'] != ""){?>
              <h3 class="margin-bottom-5">Additional Information</h3>
              <p class="margin-reset">
                <?=$EMPLOYEE['AdditionalInformation']?>
              </p>
      <?php } ?>
      
      <?php
	  $sql = "SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$_GET['empid']."' ORDER BY `employee_education`.`EducationTo` DESC";
	  if( total($sql) > 0 ) {?>
		  <h3 class="margin-bottom-20">Education</h3>
		  <dl class="resume-table">
			<?php
			$stmt = query($sql);
			while($education = fetch($stmt)){ ?>
				<dt> <strong>
				  <?=optionVal($education['EducationQualification'])?>
				  </strong> </dt>
				<dd>
				  <?php if ($education['EducationInstitute'] != "") { ?>
				  at
				  <?=$education['EducationInstitute']?>
				  <br />
				  <?php } ?>
				  <?php if ($education['EducationFrom'] != "0000-00-00" && $education['EducationTo'] != "0000-00-00") { ?>
				  <?=date("M, Y", strtotime($education['EducationFrom']))?>
				  -
				  <?=date("M, Y", strtotime($education['EducationTo']))?>
				  <?php }?>
				</dd>
			<?php } ?>
		  </dl>
	  <?php }?>
	  
	  <?php
	  $sql = "SELECT * FROM `employee_certificate` WHERE `EmployeeID` = '".$_GET['empid']."' ORDER BY CertificateDate DESC";
	  if( total($sql) > 0 ) {?>
		  <h3 class="margin-bottom-20">Certificates</h3>
		  <dl class="resume-table">
			<?php
			$stmt = query($sql);
			while($certificate = fetch($stmt)){ ?>
				<dt><strong>
				  <?=$certificate['CertificateQualification']?>
				  </strong> </dt>
				<dd>
				  <?php if ($certificate['CertificateInstitute'] != "") {?>
				  at
				  <?=$certificate['CertificateInstitute']?>
				  <br />
				  <?php } ?>
				  <?=date("M, Y", strtotime($certificate['CertificateDate']))?>
				</dd>
			<?php }?>
		  </dl>
	  <?php }?>
      
      
      
    </div>
  </div>
  <div class="eight columns">
    <?php
	$sql = "SELECT * FROM `employee_experience` WHERE `ExperienceEmployeeID` = '".$_GET['empid']."' ORDER BY UID ASC";
	if( total($sql) > 0 ) {?>
        <h3 class="margin-bottom-20">Experience <?=GetEmployeeExpInYear( $_GET['empid'] )?></h3>
        <dl class="resume-table">
          <?php
          $stmt = query($sql);
          while($experience = fetch($stmt)){
			  $experience['ExperienceFrom'] = ( $experience['ExperienceFrom'] == '0000-00-00' ) ? '' : $experience['ExperienceFrom'] ;
			  if( $experience['ExperienceTo'] == "1971-01-01" ){
				$ExperienceTo = '- Present';
			  } else 
			  if( $experience['ExperienceTo'] == "0000-00-00" ){
				$ExperienceTo = '';
			  } else {
				$ExperienceTo = "- ".date("d M, Y", strtotime($experience['ExperienceTo']));
			  }
			  
			  
			  
			  if($InvitationAccept && $experience['ExperienceTo'] == "1971-01-01"){ ?>
                  <dt><strong>
                    <?=optionVal($experience['ExperienceDesignation'])?>
                    </strong> </dt>
                  <dd>
                    <?php if ($experience['ExperienceEmployer'] != "" ) {?>
                    at
                    <?=$experience['ExperienceEmployer']?> <font style="color:#090;">(Present)</font> 
                    <br />
                    <?php } ?>
                    <?=date("d M, Y", strtotime($experience['ExperienceFrom']))?>&nbsp;<?=$ExperienceTo?><br />
                    <?php $exptext = GetEmployeeExpInYearByJobID($experience['UID']);
					if ($exptext != "" ) {?>
						<?=$exptext?> <br />
                    <?php } ?>
                    <?php if ($experience['ExperienceSallery'] != "0") { ?>
                    Salary:
                    <?=$experience['ExperienceSallery']?>
                    <?php }?>
                  </dd><?php
			  } else { ?>
                  <dt><strong>
                    <?=optionVal($experience['ExperienceDesignation'])?>
                    </strong> </dt>
                  <dd>
                    <?php if ($experience['ExperienceEmployer'] != "" ) {?>
                    at
                    <?=$experience['ExperienceEmployer']?>
                    <br />
                    <?php } ?>
                    <?=date("d M, Y", strtotime($experience['ExperienceFrom']))?>&nbsp;<?=$ExperienceTo?><br />
                    <?php $exptext = GetEmployeeExpInYearByJobID($experience['UID']);
					if ($exptext != "" ) {?>
						<?=$exptext?> <br />
                    <?php } ?>
                    <?php if ($experience['ExperienceSallery'] != "0") { ?>
                    Salary:
                    <?=$experience['ExperienceSallery']?>
                    <?php }?>
                  </dd><?php
			  }?>
          <?php }?>
        </dl>
    <?php  }?>
    
	
    
    
  </div>
</div>
<?php
}?>
