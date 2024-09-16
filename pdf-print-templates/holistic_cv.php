<?php
//echo base64_decode($_GET['key']);
$key = explode( "|", base64_decode($_GET['key']) );
//$key = explode( "|", $_GET['key'] );
$UID = $key[0];
$Name = $key[1];


$EMPLOYEE = fetch( query( " SELECT * FROM `employee` WHERE `UID` = '".$UID."' " ) );
$CERTIFICATE = query( " SELECT * FROM `employee_certificate` WHERE `EmployeeID` = '".$UID."' " ) ;
$EDUCATION = query( " SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$UID."' ORDER BY `employee_education`.`EducationTo` DESC" ) ;
$EXPERIENCE = query( " SELECT * FROM `employee_experience` WHERE `ExperienceEmployeeID` = '".$UID."' " ) ;
$EXTRA = fetch( query( " SELECT * FROM `employee_extra` WHERE `EmployeeID` = '".$UID."' " ) );

$EXPHTML='';
while( $experience = fetch($EXPERIENCE) ){
	$EXPHTML .='
		<ul>
	<li><h3>'.optionVal($experience['ExperienceDesignation']).'';
			$experience['ExperienceFrom'] = ( $experience['ExperienceFrom'] == '0000-00-00' ) ? '' : $experience['ExperienceFrom'] ;
			$experience['ExperienceFrom'] = ( $experience['ExperienceFrom'] == '1971-01-01' ) ? '' : $experience['ExperienceFrom'] ;
				
				if( $experience['ExperienceTo'] == "1971-01-01" ){
				  $ExperienceTo = 'To Present';
				} else 
				if( $experience['ExperienceTo'] == "0000-00-00" ){
				  $ExperienceTo = '';
				} else {
				  $ExperienceTo = 'To ' .date("F, Y", strtotime( $experience['ExperienceTo'] ) );
				} 
				
				if($experience['ExperienceFrom'] != '' ){
					$experience['ExperienceFrom'] = date("F, Y", strtotime($experience['ExperienceFrom']));
				}
				
			$totalExp = GetEmployeeExpInYearByJobID($experience['UID']);
			$totalExp = ( $totalExp == '' ) ? '' : '( '.$totalExp.' )';
							
			$EXPHTML .=' '.$totalExp.'
		<small><br /><strong>at</strong> '.$experience['ExperienceEmployer'].'
		'.(($experience['ExperienceSallery']>0)?'<br /><strong>Salary: </strong>'.$experience['ExperienceSallery'].'':'').' <br />
		'.$experience['ExperienceFrom'].'  '.$ExperienceTo.'</small></h3>
		<hr class="grey" />
		</li>
	</ul>
			<div style="margin:5px 0"></div>
	';
}

$EDUHTML=''; $cnt=0;
$EduHead = '---';
while( $education = fetch($EDUCATION) ){
	if($cnt==0){
		$EduHead = optionVal($education['EducationQualification']) . " at " . $education['EducationInstitute'];
	} 
	
	$EDUHTML .='
		<ul>
	<li><h3>'.optionVal($education['EducationQualification']).' <small>at '.$education['EducationInstitute'].'</small></h3></li></ul>
		<table width="100%" style="padding:0; align:right;  margin-left:80px; vertical-align:top">
			<tr>
				<td width="30%">
					<h4>From: </h4>
				</td>
				<td width="70%"> 
					<p> '.date("M, Y", strtotime($education['EducationFrom'])).' </p>
				</td>
			</tr>
			<tr>
				<td>
					<h4>To:</h4>
				</td>
				<td> 
					<p> '.date("M, Y", strtotime($education['EducationTo'])).' </p>
				</td>
			</tr>
		</table>
		<div style="margin:5px 0"></div>	
	';
	$cnt++;
}

$CERHTML='';
while( $certificate = fetch($CERTIFICATE) ){
	$CERHTML .='
			<ul>
	<li><h3>'.$certificate['CertificateQualification'].' <small>at '.$certificate['CertificateInstitute'].'</small></h3></li></ul>
		<table width="100%" style="padding:0; align:right;  margin-left:80px; vertical-align:top">
			<tr>
				<td width="30%">
					<h4>Certificate Date: </h4>
				</td>
				<td width="70%"> 
					<p> '.date("M, Y", strtotime($certificate['CertificateDate'])).' </p>
				</td>
			</tr>
		</table>
		<div style="margin:5px 0"></div>
	';
}

$SKILLHTML='';
$options = array('EmployeeInterests','EmployeeSoftSkills','EmployeeSkills'); 
foreach($options as $opt){ 
	if( $opt == "EmployeeInterests" ) $heading = 'Interests';
	if( $opt == "EmployeeSoftSkills" ) $heading = 'Soft Skills';
	if( $opt == "EmployeeSkills" ) $heading = 'Technical Skills';
	
	$sql = " SELECT * FROM `employee_extra` WHERE `InfoType` = '".$opt."' and `EmployeeID` = '".$UID."' ORDER BY `employee_extra`.`InfoTypeValue` ASC";
	if(total($sql)>0){
		$SKILLHTML .='
		<h3 style="margin-left:25px;"><strong>'.$heading.'</strong></h3>
		<ul>';
		$stmt = query($sql);
		while($rslt=fetch($stmt)){
		$query = " SELECT * FROM `employee_extra` WHERE `InfoType` = '".$opt."' and `EmployeeID` = '".$UID."' ORDER BY `employee_extra`.`InfoTypeValue` ASC ";
		$stmt = query( $query );
		if( total($query) > 0 ){
			if( $opt == "EmployeeInterests" ) $heading = 'Interests';
			if( $opt == "EmployeeSoftSkills" ) $heading = 'Soft Skills';
			if( $opt == "EmployeeSkills" ) $heading = 'Technical Skills';
			
			$SKILLHTML .='
			<ul>';
			
			while($rslt=fetch($stmt)){
				$SKILLHTML .='
				<li>'.$rslt['InfoTypeValue'].'</li>';
			}
			$SKILLHTML .='
			</ul>
			<div style="margin:5px 0"></div>';
		}
	
		$SKILLHTML .='
		</ul><div style="margin:5px 0"></div>';
		}
		
	}
}
$filename = $EMPLOYEE['EmployeeName']."-".date("d M, y");
$htmlview = "no";
$htmlheader = '
	<header>
	</header>

';

$html = '<div class="container">';


($EMPLOYEE['EmployeeState']==0) ? $EMPLOYEE['EmployeeState'] = '---' : $EMPLOYEE['EmployeeState'] = optionVal($EMPLOYEE['EmployeeState']) ;
($EMPLOYEE['EmployeeCity']==0) ? $EMPLOYEE['EmployeeCity'] = '---' : $EMPLOYEE['EmployeeCity'] = optionVal($EMPLOYEE['EmployeeCity']) ;
($EMPLOYEE['EmployeeGender']=='') ? $EMPLOYEE['EmployeeGender'] = '---' : $EMPLOYEE['EmployeeGender'] = $EMPLOYEE['EmployeeGender'];
($EMPLOYEE['EmployeeMotherLanguage']==0) ? $EMPLOYEE['EmployeeMotherLanguage'] = '---' : $EMPLOYEE['EmployeeMotherLanguage'] = optionVal($EMPLOYEE['EmployeeMotherLanguage']) ;
($EMPLOYEE['EmployeeMaritalStatus']==0) ? $EMPLOYEE['EmployeeMaritalStatus'] = '---' : $EMPLOYEE['EmployeeMaritalStatus'] = optionVal($EMPLOYEE['EmployeeMaritalStatus']) ;
($EMPLOYEE['EmployeeLandLine']=='') ? $EMPLOYEE['EmployeeLandLine'] = '---' : $EMPLOYEE['EmployeeLandLine'] = $EMPLOYEE['EmployeeLandLine'];
($EMPLOYEE['EmployeeMobile']=='') ? $EMPLOYEE['EmployeeMobile'] = '---' : $EMPLOYEE['EmployeeMobile'] = $EMPLOYEE['EmployeeMobile'];
($EMPLOYEE['EmployeeTotalExperience']==0) ? $EMPLOYEE['EmployeeTotalExperience'] = '---' : $EMPLOYEE['EmployeeTotalExperience'] = optionVal($EMPLOYEE['EmployeeTotalExperience']) ;
($EMPLOYEE['EmployeeAddress']=='') ? $EMPLOYEE['EmployeeAddress'] = '---' : $EMPLOYEE['EmployeeAddress'] = $EMPLOYEE['EmployeeAddress'];
($EMPLOYEE['EmployeeWeb']=='') ? $EMPLOYEE['EmployeeWeb'] = '---' : $EMPLOYEE['EmployeeWeb'] = $EMPLOYEE['EmployeeWeb'];
($EMPLOYEE['EmployeeNIC']=='') ? $EMPLOYEE['EmployeeNIC'] = '---' : $EMPLOYEE['EmployeeNIC'] = $EMPLOYEE['EmployeeNIC'];




$html .= '
		<table style="vertical-align:top; line-height:23px;" width="100%">
		  <tr>
			<td width="40%"><img src="'.$path.'uploads/'.( ($EMPLOYEE['EmployeeLogo']!='')?$EMPLOYEE['EmployeeLogo']:'no-image.png' ).'" width="200px"></td>
			 <td width="60%"> 
			 <h1> '.optionVal($EMPLOYEE['EmployeeTitle']).'&nbsp;'.$EMPLOYEE['EmployeeName'].' </h1>
				<table width="100%" border="0" cellpadding="3" cellspacing="3">
				  <tr>
					<td valign=top colspan=2>'.nl2br($EMPLOYEE['EmployeeObjective']).'</td>
				  </tr>
				  <tr>
					<td width="19%"><strong>Area/City:</strong> </td>
					<td>'.$EMPLOYEE['EmployeeCity'].'</td>
				  </tr>
				  <tr>
					<td style="vertical-align:top"><strong>Education:</strong></td>
					<td>'.$EduHead.'</td>
				  </tr>
				  <tr>
					<td style="vertical-align:top"><strong>Experience:</strong></td>
					<td>'.$EMPLOYEE['EmployeeTotalExperience'].'</td>
				  </tr>
				</table>			 
			 </td>
		  </tr>
		</table>
		<br><br>
		
		<h2 style="margin-bottom:0px; vertical-align:middle;"><img src="images/contact-icon.png" width="30px"  style="vertical-align:bottom;"> Contact</h2>
		<hr class="grey">
		<table width="100%" style="vertical-align: top; margin-left: 25px;">
			<tr>
			  <td width="50%"><h4>Email Address:</h4></td>
			  <td width="50%"><p>'.$EMPLOYEE['EmployeeEmail'].'</p></td>
			</tr>
			<tr>
			  <td><h4>Mobile Phone:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeMobile'].'</p></td>
			</tr>
			<tr>
			  <td><h4>Land Line Number:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeLandLine'].'</p></td>
			</tr>
			<tr>
			  <td><h4>Province or State:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeState'].'</p></td>
			</tr>
			<tr>
			  <td><h4>City:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeCity'].'</p></td>
			</tr>
			<tr>
			  <td><h4>Residential Address:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeAddress'].'</p></td>
			</tr>
			<tr>
			  <td><h4>Website:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeWeb'].'</p></td>
			</tr>
		</table>
		<br><br>
		
		<h2 style="margin-bottom:0px; vertical-align:middle;"><img src="images/personal-info.png" width="30px" style="vertical-align:bottom;"> Personal Information</h2>
		<hr class="grey">
		<table width="100%" style="vertical-align: top; margin-left: 25px;">
			<tr>
			  <td width="50%"><h4>N.I.C Number:</h4></td>
			  <td width="50%"><p>'.$EMPLOYEE['EmployeeNIC'].'</p></td>
			</tr>
			<tr>
			  <td><h4>Date of Birth:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeDOB'].'</p></td>
			</tr>
			<tr>
			  <td><h4>Gender:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeGender'].'</p></td>
			</tr>
			<tr>
			  <td><h4>Mother Language:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeMotherLanguage'].'</p></td>
			</tr>
			<tr>
			  <td><h4>Marital Status:</h4></td>
			  <td><p>'.$EMPLOYEE['EmployeeMaritalStatus'].'</p></td>
			</tr>
		</table>
		<br><br>
		
		<h2 style="margin-bottom:0px; vertical-align:middle;"> <img src="images/experience.png" width="30px" style="vertical-align:bottom;"> Experience </h2>
		<hr class="grey">
		<table>
					<tr>
			  <td><h4><strong> &nbsp;&nbsp;&nbsp; &nbsp; Total Experience:</strong></h4></td>
			  <td><p>&nbsp; '.optionVal($EMPLOYEE['EmployeeTotalExperience']).'</p></td>
			</tr>
			</table>
			'.$EXPHTML.'
					
		<div style="margin:10px 0"></div>
		
		<h2 style="margin-bottom:0px; vertical-align:middle;"> <img src="images/education.jpg" width="45px" style="vertical-align:bottom;"> Education</h2>
		<hr class="grey">
		'.$EDUHTML.'
			
		<div style="margin:10px 0"></div>
		
		<h2 style="margin-bottom:0px; vertical-align:middle;"> <img src="images/certificate.png" width="30px" style="vertical-align:bottom;"> Certifications</h2>
		<hr class="grey">
		'.$CERHTML.'
';

if($SKILLHTML!=''){
	$html .='
		<div style="margin:10px 0"></div>
		<h2 style="margin-bottom:0px; vertical-align:middle;"><img src="images/skills.png" width="30px" style="vertical-align:bottom;"> Skills & Interests</h2>
		<hr class="grey">
		'.$SKILLHTML.'
		<br/>
		<br>';
}

if($EMPLOYEE['AdditionalInformation']!=''){
	$html .='
		<h2 style="margin-bottom:0px; vertical-align:middle;"><img src="images/objectives.png" width="30px" style="vertical-align:bottom;"> Additional Information </h2>
		<hr class="grey"><p>'.nl2br($EMPLOYEE['AdditionalInformation']).'</p>';
}


$html .= '</div>';