<?php 

$page = end( explode("/",$_SERVER['SCRIPT_FILENAME']) );

$_SESSION['TEMPLATE_DEFAULT_SQL'] = $TEMPLATE_DEFAULT_SQL = " 1 and `status` = 'Approved' ";

function CategoryLink($id){
	$path = $_SESSION["site_path"];
	$rslt = fetch ( query ( "SELECT * FROM `category` WHERE `id` = '".$id."' ORDER BY `title` limit 1" ) );
	$url = $path . "category/" . $rslt['id'] . "-" . post_slug( $rslt['title'] );
	return strtolower($url);
}

function CategoryDropDown($CAT_TYPE='Primary',$seleted=0){
	$stmt = query(" SELECT * FROM `category` where `type` = '".$CAT_TYPE."'  order by `ordermap` ASC ");
	$catParentOption='<option value="">Please Select</option>';
	while( $rslt = fetch($stmt) ){
		$category_seperator = '';
		
		for($i=0;$i<$rslt['level'];$i++)
		{
			$category_seperator .= CATEGORY_SEPERATOR;
		}
		$category_seperator .= "&nbsp;";
		($rslt['id'] == $seleted) ? $categorySeleted = 'selected' : $categorySeleted = '';
		$catParentOption .= ' <option value="'.$rslt['id'].'" '.$categorySeleted.'>'.$category_seperator.$rslt['title'].'</option> ';
	}
	return $catParentOption;
}

function ItemLink($id){
	$path = $_SESSION["site_path"];
	$rslt = mysql_fetch_array ( mysql_query ( "SELECT * FROM `templates` WHERE `id` = '".$id."' ORDER BY `title` limit 1" ) );
	$url = $path . "item/" . $rslt['id'] . "-" . post_slug( $rslt['title'] );
	return strtolower($url);
}

function AttributesLink($type, $key){
	$path = $_SESSION["site_path"];
	$key = post_slug( $key );
	$type = post_slug( $type );
	$url = $path . "attributes/" . $type . "/" . $key ;
	return strtolower($url);
}

function SalleryDropdown($selected=0){
	$path = $_SESSION["site_path"];
	$html = '<option value=""></option>';
	for($a=5000;$a<=100000;$a+=5000){
		( $a == $selected) ? $Seleted = 'selected' : $Seleted = '';
		$html .= '<option value="'.$a.'" '.$Seleted.'>'.number_format($a, 0, ',', ', ').'</option>';
	}
	
	for($a=125000;$a<400000;$a+=25000){
		( $a == $selected) ? $Seleted = 'selected' : $Seleted = '';
		$html .= '<option value="'.$a.'" '.$Seleted.'>'.number_format($a, 0, ',', ', ').'</option>';
	}
	
	$html .= '<option value="400001">400,000 and above</option>';
	return $html;
}

function bd_nice_number($n) {
	// first strip any formatting;
	$n = (str_replace(",","",$n));
   
	// is this a number?
	if(!is_numeric($n)) return false;
   
	// now filter it;
	if($n>1000000000000) return round(($n/1000000000000),1).' trillion';
	else if($n>1000000000) return round(($n/1000000000),1).' billion';
	else if($n>1000000) return round(($n/1000000),1).' million';
	else if($n>100000) return round(($n/1000000),1).' lac';
	else if($n>1000) return round(($n/1000),1).' thousand';
   
	return number_format($n);
}


function GetEmployer($head, $uid){
	$stmt = mysql_query("SELECT ".$head." FROM `employer` WHERE `UID` = '".$uid."' limit 1 ");
	$rslt = mysql_fetch_array($stmt);
	return $rslt[0];

}

function GetCategory($uid){
	$data = '';
	$stmt = mysql_query("SELECT * FROM `category` WHERE `id` = '".$uid."' limit 1 ");
	$rslt = mysql_fetch_array($stmt);
	if($rslt['catid']!=0){
		$stmt2 = mysql_query("SELECT * FROM `category` WHERE `id` = '".$rslt['catid']."' limit 1 ");
		$rslt2 = mysql_fetch_array($stmt2);
		$data .= $rslt2['title'] .' &raquo; ';
	}
	$data .= $rslt['title'];
	return $data;

}

function dob ($dob){
	if($dob=='0000-00-00')
		return "N/A";
	if(!empty($dob)){
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        return $age;
    } else{
        return 0;
    }
}

function GetEmployeeExpInYear($uid, $type = 'bracket'){
	$stmt = query("
			  SELECT ExperienceTo, ExperienceFrom, 
				 SUM(
				  DATEDIFF( IF(ExperienceTo='1971-01-01',CURDATE(), ExperienceTo ), ExperienceFrom)
				) AS DateDiffInDays 
			  FROM `employee_experience` 
			  WHERE `ExperienceEmployeeID` = '".$uid."' 
				AND `ExperienceTo` != '0000-00-00' AND `ExperienceFrom` != '0000-00-00' AND `ExperienceFrom` != '1971-01-01' 
			  ORDER BY `ExperienceEmployeeID` ASC ");
	
	$rslt = fetch($stmt);
	$days = $rslt['DateDiffInDays'];
	$months = round($rslt['DateDiffInDays'] / 30, 0);
	$years = round($months / 12, 1);
		
	if($type=='bracket')
		$years = ( $years > 0 ) ? ( ($years == 1) ? "( " . $years . " Year )" : "( " . $years . " Years )" ) : '' ;
	else 
		$years = ( $years > 0 ) ? ( ($years == 1) ? "" . $years . " Year" : "" . $years . " Years" ) : '' ;
	return $years;
	
	
	/*$stmt = query("
			  SELECT ExperienceTo, ExperienceFrom 
			  FROM `employee_experience` 
			  WHERE UID = '".$uid."' 
				AND `ExperienceTo` != '0000-00-00' AND `ExperienceFrom` != '0000-00-00' 
				AND `ExperienceTo` != '1971-01-01' AND `ExperienceFrom` != '1971-01-01' ");
	$totalMonth = 0;
	while( $rslt = mysql_fetch_array($stmt) ){
		$interval = dateDifference($rslt['ExperienceTo'] , $rslt['ExperienceFrom'] );
		
		$year = $interval->format("%y");
		$month = $interval->format("%m");
		$html .= $rslt['ExperienceTo'] . " | " ;
		//$totalMonth += $month;
		$totalMonth++;
	}
	
	return $totalMonth;*/ 
	
}

function GetEmployeeExpInYearByJobID($uid){
	/*$stmt = query("
			  SELECT ExperienceTo, ExperienceFrom, 
				SUM(
				  DATEDIFF(ExperienceTo, ExperienceFrom)
				) AS DateDiffInDays 
			  FROM `employee_experience` 
			  WHERE UID = '".$uid."' 
				AND `ExperienceTo` != '0000-00-00' AND `ExperienceFrom` != '0000-00-00' 
				AND `ExperienceTo` != '1971-01-01' AND `ExperienceFrom` != '1971-01-01' 
			  ORDER BY `ExperienceEmployeeID` ASC ");
	
	$rslt = fetch($stmt);
	$days = $rslt['DateDiffInDays'];
	$months = round($rslt['DateDiffInDays'] / 30, 0);
	$years = round($months / 12, 0);
	
	$years = ( $years > 0 ) ? ( ($years == 1) ? $years . " Year" : $years . " Years" ) : '' ;
	return $years;*/
	
	
	$stmt = query("
			  SELECT ExperienceTo, ExperienceFrom 
			  FROM `employee_experience` 
			  WHERE UID = '".$uid."' 
				AND `ExperienceTo` != '0000-00-00' AND `ExperienceFrom` != '0000-00-00' AND `ExperienceFrom` != '1971-01-01' ");
	
	$rslt = fetch($stmt);
	$interval =  dateDifference( ($rslt['ExperienceTo']=='1971-01-01') ? date("Y-m-d") : $rslt['ExperienceTo'] , $rslt['ExperienceFrom'] );
	
	$year = $interval->format("%y");
	$month = $interval->format("%m");
	$days = $interval->format("%d");
	
	if($year==0 && $month==0 && $days==0){
		return '';		
	} else if($year==0 && $month==0 && $days>0){
		return $interval->format("%d Days");
	} else if($year==0 && $month>0){
		return $interval->format("%m Month");
	} else if($year>0 && $month>0){
		return $interval->format('%y Year %m Month');
	}
	
}

function GetEmployerAccessToEmployee($employerid, $employeeid, $action){
	$sql = " SELECT * FROM `jobs_invitations` WHERE `EmployeeUID` = '".$employeeid."' AND `EmployerUID` = '".$employerid."' ";
	
	if($action=='invitation-accept'){
		$sql .= ' and InvitationApproval = 1 ';
		
		if(total($sql) > 0 ){
			return true;
		} else {
			return false;
		}
	}
	

	
}

function JobLink($uid){
	$path = $_SESSION["site_path"];
	$stmt = mysql_query("SELECT JobTitle FROM `jobs` WHERE `UID` = '".$uid."' limit 1 ");
	$rslt = mysql_fetch_array($stmt);
	$str = $uid . ' ' . $rslt[0];
	$link = post_slug($str);
	
	$link = $path . "job/view/" . $link;
	return $link;
}

function EmployerProfileLink($uid){
	$path = $_SESSION["site_path"];
	$stmt = mysql_query("SELECT EmployerCompany FROM employer WHERE `UID` = '".$uid."' limit 1 ");
	$rslt = mysql_fetch_array($stmt);
	$str = $uid . ' ' . $rslt[0];
	$link = post_slug($str);
	
	$link = $path . "employer-profile/view/" . $link;
	return $link;
}
function EmployeeProfileLink($uid){
	$path = $_SESSION["site_path"];
	$stmt = mysql_query("SELECT EmployeeName FROM employee WHERE `UID` = '".$uid."' limit 1 ");
	$rslt = mysql_fetch_array($stmt);
	$str = $uid . ' ' . $rslt[0];
	$link = post_slug($str);
	
	$link = $path . "employee-profile/view/" . $link;
	return $link;
}
function JobExtra($uid, $type, $action){
	if($action=="string"){
		$result = "";
		$stmt = query("SELECT * FROM `jobs_extra` WHERE `JobID` = '".$uid."' and `InfoType` = '".$type."'");
		while( $rslt = fetch($stmt) ){
			if($result!='') $result .= ", ";
			$result .= $rslt['InfoTypeValue'];
		}
	}
	
	if($action=="array"){
		$result = array();
		$stmt = query("SELECT * FROM `jobs_extra` WHERE `JobID` = '".$uid."' and `InfoType` = '".$type."'");
		while( $rslt = fetch($stmt) ){
			$result[] = $rslt['InfoTypeValue'];
		}
	}
	
	return $result;
}

function EmployeeExtra($uid, $type, $action){
	if($action=="string"){
		$result = "";
		$stmt = query("SELECT * FROM employee_extra WHERE EmployeeID = '".$uid."' and `InfoType` = '".$type."'");
		while( $rslt = fetch($stmt) ){
			if($result!='') $result .= ", ";
			$result .= $rslt['InfoTypeValue'];
		}
	}
	
	if($action=="array"){
		$result = array();
		$stmt = query("SELECT * FROM employee_extra WHERE EmployeeID = '".$uid."' and `InfoType` = '".$type."'");
		while( $rslt = fetch($stmt) ){
			$result[] = $rslt['InfoTypeValue'];
		}
	}
	
	return $result;
}

function ApplyTheme($content){
	$content = str_replace("<ul>", '<ul class="list-1">', $content);
	$content = str_replace("<p>", '<p class="margin-reset">', $content);
	
	$content = str_replace("<h1>", '<h1 class="margin-bottom-10">', $content);
	$content = str_replace("<h2>", '<h2 class="margin-bottom-10">', $content);
	$content = str_replace("<h3>", '<h3 class="margin-bottom-10">', $content);
	$content = str_replace("<h4>", '<h4 class="margin-bottom-10">', $content);
	$content = str_replace("<h5>", '<h5 class="margin-bottom-10">', $content);
	
	
	
	
	
	
	
	
	return $content;
}


function JobBox($id){
	//GetEmployer('EmployerCompany', $rslt['JobEmployerID'])
	$path = $_SESSION["site_path"];
	$stmt = query("SELECT * FROM `jobs` WHERE `UID` = '".$id."' ");
	$rslt = fetch( $stmt );
	
	( GetEmployer('EmployerLogo', $rslt['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $rslt['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';
	
	/* 
	'.( ($rslt['JobNature']=='factory')?' <span class="factory"> Factory </span> ':'' ).'
	'.( ($rslt['JobNature']=='office')?' <span class="office"> Office </span> ':'' ).'
	'.( ($rslt['JobNature']=='field')?' <span class="field"> Field </span> ':'' ).'
	'.( ($rslt['JobNature']=='field-office')?' <span class="part-time"> Field + Office </span> ':'' ).'
	*/
	$html = '<li><!--<a href="#admin" class="adminedit"><i class="fa fa-pencil"></i> edit</a>-->
		  <a href="'.EmployerProfileLink($rslt['JobEmployerID']).'" target="_blank" title="Company Profile" style="margin-right:1%"> <img src="'.$EmployerLogo.'"  style="max-width: 165px; min-height:auto; max-height: 130px;"alt=""></a>
          <a href="'.JobLink($rslt['UID']).'" title="Job Details"> 
          <div class="job-list-content">
            <h4>
              '.$rslt['JobTitle'].' <span class="appynow pull-right" style="margin-right:1px;">Apply Now </span><br>
              
              <span style="float: right; color: rgb(255, 0, 0); font-weight: bold; margin: 0px 1px; padding: 0px 1px;">Last Date : '.date("d M, Y",strtotime($rslt['JobLastDateApply'])).'</span>
            </h4>
            <div class="job-icons"> 
				<span><i class="fa fa-building-o"></i>'.GetEmployer('EmployerCompany', $rslt['JobEmployerID']).'</span>
				<span><i class="fa fa-map-marker"></i>'.JobExtra($rslt['UID'], 'JobCity', 'string').'</span>
				<!--<span><i class="fa fa-briefcase"></i>'.GetCategory($rslt['JobCategory']).'</span>-->
				<span><i class="fa fa-briefcase"></i>'.optionVal($rslt['JobDepartment']).'</span>
			</div>
          </div>
          </a>
          <div class="clearfix"></div>
        </li>';
		
	return $html;
}

function CreatePDF($data){
include("mpdf/mpdf.php");
	$path = $_SESSION["site_path"];
	

	$stmt = query(" SELECT * FROM `employer` WHERE `UID`= '".$data['EmpID']."' ");
	$EMPLOYER = fetch( $stmt );
	
	$stmt = query(" SELECT * FROM `subscriptions` WHERE `UID` = '".$data['PlanID']."' ");
	$PLAN = fetch( $stmt );
	
	$stmt = query(" SELECT `AccessTypeTitle`, `AccessAllowed`, `AccessDays`
					FROM `subscription_access`
					INNER JOIN `subscription_accesstype` ON (`subscription_access`.`AccessTypeKey` = `subscription_accesstype`.`AccessTypeKey`)
					WHERE ( `AccessAllowed` = 1 AND `AccessSubID` = '".$data['PlanID']."' ) ORDER BY AccessTypeTitle ASC; ");
	$PlanDesc = '<ul>';
	while( $PlanAccess = fetch($stmt) ){
		if( $PlanAccess['AccessDays'] > 0 ){
			$PlanDesc .= '<li>'.$PlanAccess['AccessTypeTitle'].' <span style="float:right;">('.$PlanAccess['AccessDays'].' Days)</span></li>';
		} else {
			$PlanDesc .= '<li>'.$PlanAccess['AccessTypeTitle'].'</li>';
		}		
	}
	$PlanDesc .= '</ul>';
	
	$total = $PLAN['PlanFee'];
	
	$insert = array();
	$insert['InvoiceUserType'] = 'Employer';
	$insert['InvoiceUserID'] = $data['EmpID'];
	$insert['InvoiceFee'] = $total;
	$insert['InvoicePlan'] = $data['PlanID'];
	$insert['InvoiceStatus'] = 'Un-Paid';
	
	$insert_id = FormData('invoices', 'insert', $insert, "", $view=false );
	
	$htmlFile = str_replace(" ","-",$EMPLOYER['EmployerCompany'] ).'-'.Code('HJ', $insert_id).'.pdf';
	
	$update = array();
	$update['InvoiceFilename'] = $htmlFile;
	$update_id = FormData('invoices', 'update', $update, " UID = '$insert_id' ", $view=false );
	
	$htmlheader = '
		<header>
		<table style="padding:0; align:right; vertical-align:top">
		  <tr>
			<td width="20%"><img src="'.$path.'images/holistic-jobs-logo.png" title="Holistic Jobs" style="height:100px"/></td>
			<td width="60%" align="left"><h2>Holistic Jobs</h2> <p class="red-text">Holistic Tower Plot # 28, Jinnah Boulevard West, Sector A, <br>
			DHA Phase-II, Islamabad,<br>
			 Land Line No. 051-4493740 <br>
			 www.holisticjobs.com.pk <br> 
			 E-mail: info@holisticjobs.com.pk </p></td>
			 <td width="20%" align="right"> <h1> INVOICE</h1></td>
		  </tr>
		</table>
		</header>
	';
	
	
	
	$html = '
		<table style="padding:0; align:left" width="100%">
			<tr>
			  <td width="70%"><h4>Bill To. <span class="text"> '.$EMPLOYER['EmployerCompany'].' </span></h4></td>
			  <td width="30%"><h4>Date. <span class="text"> '.date("d M, Y").' </span></h4></td>
			</tr>
			<tr>
			  <td width="70%"><h4>Address. <span class="text">'.$EMPLOYER['EmployerAddress'].'&nbsp;'.optionVal($EMPLOYER['EmployerCity']).'</span></h4></td>
			  <td width="30%"><h4>Invoice No. <span class="text">'.( Code('HJ', $insert_id) ).'</span></h4></td>
			</tr>
		</table>
		<br>
		<table width="100%" class="border-table" style="text-align:center">
			<thead>
				<tr>
				  <th style="border-left-color:#FFFFFF !important;">Description</th>
				  <th width="18%" style="border-left-color:#FFFFFF !important;">Subscription Charges</th>
				  <th width="15%">Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				  <td align="left"><strong>'.$PLAN['PlanTitle'].'</strong><br>'.$PLAN['PlanDesc'].'<br><br><strong>Plan Access Mention Below</strong><br>'.$PlanDesc.'</td>
				  <td>Rs. '.$PLAN['PlanFee'].'</td>
				  <td>Rs. '.$PLAN['PlanFee'].'</td>
				</tr>
				<tr>
				  <td style="text-align:right" colspan="2">Total Amount</td>
				  <td>Rs. '.$total.'</td>
				</tr>
			</tbody>
		</table>
	';
		
	$htmlfooter = '
		<footer>
		  <hr class="green">
		  <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
			<tr>
			  <td width="20%" align="left"><span style="font-weight: bold; font-style: italic;">{DATE j M, Y}</span></td>
			  <td align="center"><span style="font-weight: bold; color:#FF0000; font-style: italic;">This is an auto-generated invoice which does not require any official stamp</td>
			  <td width="20%" align="right" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg} </td>
			</tr>
		  </table>
		</footer>
	';
	
	
	
	$mpdf = new mPDF('c', '', 0, '', 5, 5, 40, 5, 5, 5); 
	
	// LOAD a stylesheet
	$stylesheet = file_get_contents($path.'pdf-print.css');
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
	
	$mpdf->SetHTMLHeader($htmlheader); 
	$mpdf->WriteHTML($html);
	$mpdf->SetHTMLFooter($htmlfooter);
	
	if ($_SERVER['HTTP_HOST'] != 'localhost'){
		$mpdf->Output($_SERVER["DOCUMENT_ROOT"] . "/invoices/" . $htmlFile,'F');
	} else {
		$mpdf->Output($_SERVER["DOCUMENT_ROOT"] . "/holisticjobs.com.pk/invoices/" . $htmlFile,'F');	
	}
		
	return $htmlFile;	
}


/*
$pdf = array();
$pdf['EmpID'] = 7;
$pdf['PlanID'] = 4;
echo $filename = CreatePDF($pdf);
*/

function ProfileScore($uid){
	$stmt = mysql_query("SELECT * FROM `employee` WHERE `UID` = '".$uid."' ");
	$profile = mysql_fetch_array($stmt);
	
	$score= 0;
	if($profile['EmployeeNIC']!='') $score += 2 ;
	if($profile['EmployeeDOB']!='0000-00-00') $score += 3 ;
	if($profile['EmployeeGender']!=0) $score += 0.5 ;
	if($profile['EmployeeMaritalStatus']!=0) $score += 1 ;
	if($profile['EmployeeTitle']!=0) $score += 0.5 ;
	if($profile['EmployeeName']!='') $score += 4 ;
	if($profile['EmployeeMobile']!='') $score += 3 ;
	if($profile['EmployeeLandLine']!='') $score += 1.5 ;
	if($profile['EmployeeAddress']!='') $score += 1.5 ;
	if($profile['EmployeeCity']!='') $score += 3 ;
	if($profile['EmployeeState']!='') $score += 1 ;
	if($profile['EmployeeMotherLanguage']!='') $score += 0.5 ;
	if($profile['EmployeeWeb']!='') $score += 0.5 ;
	if($profile['EmployeeLogo']!='') $score += 3 ;
	if($profile['EmployeeObjective']!='') $score += 4 ;
	if($profile['AdditionalInformation']!='') $score += 1 ;
	
	$EmployeeInterests = EmployeeExtra($uid, 'EmployeeInterests', 'array');
	if( count($EmployeeInterests) > 0 ) $score += 2 ;
	
	$EmployeeSoftSkills = EmployeeExtra($uid, 'EmployeeSoftSkills', 'array');
	if( count($EmployeeSoftSkills) > 0 ) $score += 4 ;
	
	
	$EmployeeSkills = EmployeeExtra($uid, 'EmployeeSkills', 'array');
	if( count($EmployeeSkills) > 0 ) $score += 4 ;
	
	/////////////////// EDUCATION 
	$edu = total("SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$uid."' ");
	if($edu==1) $score += 10 ;
	if($edu==2) $score += 20 ;
	if($edu>=3) $score += 30 ;
	
	/////////////////// EXPERIENCE 
	$exp = total("SELECT * FROM `employee_experience` WHERE `ExperienceEmployeeID` = '".$uid."' ");
	if($exp>0) $score += 20 ;

	/////////////////// Certificate 
	$cert = total("SELECT * FROM `employee_certificate` WHERE `EmployeeID` = '".$uid."' ");
	if($cert>0) $score += 10 ;
	
	//$Score = round( ($score / $totalscore ) * 100 );
	return $score;
}


function AdminEmailForDropDown(){

	$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">
		Dear Admin,<br>
		Please check admin panel to approve new dropdown value<br>
		<br>
		Regards,<br>
		Team Holistic Jobs</p>
	</td></tr></table> ';
	$subject = "New Dropdown Added in System";
	$data = array();
	$data['From'] = $site_email;
	$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
	$data['addAddress'] = array( $site_email => 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd' );
	$body = SendMail($data, $subject, $message, $show=false);
}


function diffInMonths(\DateTime $date1, \DateTime $date2)
{
    $diff =  $date1->diff($date2);
    $months = $diff->y * 12 + $diff->m + $diff->d / 30;
    return (int) round($months);
}


function dateDifference($date_1 , $date_2 )
{
	// %y Year %m Month %d Day %h Hours %i Minute %s Seconds
    $datetime1 = @date_create($date_1);
    $datetime2 = @date_create($date_2);
    
    $interval = date_diff($datetime1, $datetime2);
	
	return $interval;
	
	$year = $interval->format("%y");
	$month = $interval->format("%m");
	
	if($year==0 && $month==0){
		return '';		
	} else if($year==0 && $month>0){
		return $interval->format("%m Month");
	} else if($year>0 && $month>0){
		return $interval->format('%y Year %m Month');
	}
    
    
    
}
