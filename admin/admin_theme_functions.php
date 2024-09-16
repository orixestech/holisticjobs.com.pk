<?php
function timeElapsed($originalTime){

        $timeElapsed=time()-$originalTime;

        /*
          You can change the values of the following 2 variables 
          based on your opinion. For 100% accuracy, you can call
          php's cal_days_in_month() and do some additional coding
          using the values you get for each month. After all the
          coding, your final answer will be approximately equal to
          mine. That is why it is okay to simply use the average
          values below.
        */
        $averageNumbDaysPerMonth=(365.242/12);
        $averageNumbWeeksPerMonth=($averageNumbDaysPerMonth/7);

        $time1=(((($timeElapsed/60)/60)/24)/365.242);
        $time2=floor($time1);//Years
        $time3=($time1-$time2)*(365.242);
        $time4=($time3/$averageNumbDaysPerMonth);
        $time5=floor($time4);//Months
        $time6=($time4-$time5)*$averageNumbWeeksPerMonth;
        $time7=floor($time6);//Weeks
        $time8=($time6-$time7)*7;
        $time9=floor($time8);//Days
        $time10=($time8-$time9)*24;
        $time11=floor($time10);//Hours
        $time12=($time10-$time11)*60;
        $time13=floor($time12);//Minutes
        $time14=($time12-$time13)*60;
        $time15=round($time14);//Seconds

		$timeElapsed = '';
		( $time2 != 0 ) ? $timeElapsed .= $time2 . ' Year(s), ' : '' ;
		( $time5 != 0 ) ? $timeElapsed .= $time5 . ' Month(s), ' : '' ;
		( $time7 != 0 ) ? $timeElapsed .= $time7 . ' Week(s) & ' : '' ;
		( $time9 != 0 ) ? $timeElapsed .= $time9 . ' Day(s) ' : '' ;
		
        //$timeElapsed = $time2 . 'yrs ' . $time5 . 'months ' . $time7 . 'weeks ' . $time9 .  'days ' . $time11 . 'hrs ' . $time13 . 'mins and ' . $time15 . 'secs.';

        return $timeElapsed;

}

function dateleft($time)
{
	$january = new DateTime(date("Y-m-d"));
	$february = new DateTime($time);
	$interval = $february->diff($january);
	
	$year = $interval->format('%y');
	$month = $interval->format('%m');
	$days = $interval->format('%d');
	
	$formatstring = '';
	
	if($year > 0) $formatstring .= '%y years, %m months, '; else if( $month > 0 ) $formatstring .= '%m months, ';
	if($days > 0) $formatstring .= '%d days, ';
	
	return $interval->format($formatstring);

}

function SubscriptionStatus($action, $date){
	if($action=='string'){
		if( date("U", strtotime($date)) <= date("U", strtotime(date("Y-m-d"))) ){
			$result = '<font class="red"><strong>Subscription has Expired.</strong></font>';
		} else {
			$result = '<font class="red">Subscription will <strong>Expire</strong> in <strong>'.dateleft(date("Y-m-d", strtotime($date))).'</strong>.</font>';
		}
	}
	
	if($action=='check'){
		if( date("U", strtotime($date)) <= date("U", strtotime(date("Y-m-d"))) ){
			$result = false;
		} else {
			$result = true;
		}
	}
	
	if($action=='days'){
		$january = new DateTime(date("Y-m-d"));
		$february = new DateTime($date);
		$interval = $february->diff($january);
		$result = $interval->format('%a');
	}
	
	return $result;
	
}


function Track($msg, $ref='admin', $refid=0){
	if($ref!='admin'){
		@mysql_query(" INSERT INTO `admin_log` (`logid`, `logdatetime`, `lognotes`, `logstatus`, `logip`, `logref`, `logrefid`) VALUES (NULL, CURRENT_TIMESTAMP, '".$msg."', '0', '".$_SERVER['REMOTE_ADDR']."', '".$ref."', '".$refid."'); ");
	} else {
		@mysql_query(" INSERT INTO `admin_log` (`logid`, `logdatetime`, `lognotes`, `logstatus`, `logip`) VALUES (NULL, CURRENT_TIMESTAMP, '".$msg."', '0', '".$_SERVER['REMOTE_ADDR']."'); ");
	}
}

function Alert($type, $message){
	
	if($type=='error'){
		return '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Error! </strong>'.$message.'<br></div>';
	}
	if($type=='success'){
		return '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>'.$message.'<br></div>';
	}
	
}


function TableAction($data){
	
	$editHREF = $data['edit']['href'];
	$deleteHREF = $data['delete']['href'];
	
	$html = '<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons"><a class="green" href="'.$editHREF.'" '.$data['edit']['js'].'> <i class="icon-pencil bigger-130"></i> </a> <a class="red" href="'.$deleteHREF.'" '.$data['delete']['js'].'> <i class="icon-trash bigger-130"></i> </a> </div>
			<div class="visible-xs visible-sm hidden-md hidden-lg">
			  <div class="inline position-relative">
				<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown"> <i class="icon-caret-down icon-only bigger-120"></i> </button>
				<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
				  <li> <a href="'.$editHREF.'" '.$data['edit']['js'].' class="tooltip-success" data-rel="tooltip" title="Edit"> <span class="green"> <i class="icon-edit bigger-120"></i> </span> </a> </li>
				  <li> <a href="'.$deleteHREF.'" '.$data['delete']['js'].' class="tooltip-error" data-rel="tooltip" title="Delete"> <span class="red"> <i class="icon-trash bigger-120"></i> </span> </a> </li>
				</ul>
			  </div>
			</div>';
	return $html;
	
}

function TableActions($data, $type='button'){
	$editHREF = $data['edit']['href'];
	$deleteHREF = $data['delete']['href'];
	if($type=='button'){
		$html = '<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">';
		foreach($data as $val){
			$html .='<a href="'.$val['href'].'" '.$val['js'].'> '.$val['title'].' </a>';
		}
		$html .='</div>';
	}
	
	if($type=='menu'){
		$html = '
		<div class="btn-toolbar">
			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"> Actions <span class="icon-caret-down icon-on-right"></span> </button>
					<ul class="dropdown-menu pull-left">';
		foreach($data as $val){
			$html .='<li><a href="'.$val['href'].'" '.$val['js'].'> '.$val['title'].' </a></li>';
		}
		$html .='</ul>
			</div>
		</div>';
	}
	
	return $html;
}

function CheckSubAccess($accesskey, $module='employer'){
	if($module=='employer'){
		$empid = $_SESSION['EmployerUID'];
		$subid = GetData('EmployerSubscription','employer','UID',$empid);
		$sub_expire = GetData('EmployerSubscriptionExpire','employer','UID',$empid);
	} else {
		$empid = $_SESSION['EmployeeUID'];
		$subid = GetData('EmployeeSubscription','employee','UID',$empid);
		$sub_expire = GetData('EmployeeSubscriptionExpire','employee','UID',$empid);
	}
	
	$Text = '<span class="red"><strong>';
	$stmt = query("SELECT * FROM `subscription_access` WHERE `AccessSubID` = '".$subid."' and `AccessTypeKey` = '".$accesskey."' ");
	$rslt = fetch($stmt);
	if( $rslt['AccessAllowed'] == 1 ){
		$val = 'true';
	} else {
		$val = 'false';
		$Text .= "You don't have access in your subscription plan.";
	}
	
	if( date("U", strtotime($sub_expire)) <= date("U", strtotime(date("Y-m-d"))) ){
		$val = 'false';
		$Text .= " Subscription Expire.";
	} else {
		
	} 
	$Text .= '</strong></span>';
	$data = array('access'=>$val, 'days'=>$rslt['AccessDays'], 'key'=>$accesskey, 'plan'=>$subid, 'expire_date'=>$sub_expire, 'msg'=>$Text);
	return $data;
}


function UpdateSubscription($module, $ref_id, $expire){
	
	if($module=='employee'){
		$stmt = query(" SELECT * FROM `employee` WHERE `UID` = '".$ref_id."' ");
		$employee = fetch($stmt);
		if( $expire['NewDATE']!=$employee['EmployeeSubscriptionExpire'] || $expire['UID']!=$employee['EmployeeSubscription']){
			
			$OldSubName = GetData('PlanTitle','subscriptions','UID',$employee['EmployeeSubscription']);
			$NewSubName = GetData('PlanTitle','subscriptions','UID',$expire['UID']);
			
			/* -----------------------------    EMAIL CONTENT    -----------------------*/
			$subject = "Subscription Update :: Holistic Jobs";
			$data = array();
			$data['From'] = 'info@holisticjobs.com.pk';
			$data['FromName'] = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
			$data['addAddress'] = array( $employee['EmployeeEmail'] => optionVal($employee['EmployeeTitle']) . " &nbsp;" . $employee['EmployeeName'] );
			$message = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td class="borderColor2" style="padding:10px;" align="left">
				<p class="mainText">
				<strong>Dear '.optionVal($employee['EmployeeTitle']) . " &nbsp;" . $employee['EmployeeName'].', </strong><br ><br >
				Your subscription has been <strong>updated</strong>. <br /><br />
				The details for your updated plan are:<br />
				Subscription Plan: <strong>'.$NewSubName.'</strong><br />
				Expiry Date: <strong>'.date("l, d M, Y", strtotime($employee['EmployeeSubscriptionExpire'])).'</strong><br /><br />
				
				The details for your previous plan are:<br />
				Subscription Plan: <strong>'.$OldSubName.'</strong><br />
				Expiry Date: <strong>'.date("l, d M, Y", strtotime($expire['NewDATE'])).'</strong>
				<br><br>';
			$message .= '
				<br>
				<p class="mainText">Regards,<br><strong>Team '.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. </p><br >
				</p>
			</td></tr></table> ';
			$body = SendMail($data, $subject, $message, $show=true);
			//echo $employee['EmployeeName'] . " | " . $employee['EmployeeEmail'] . " | Expire Date : " . $employee['EmployeeSubscriptionExpire'] . " | Expire Days : " . $employee['expiredays'] ." \n\n ";
		}
	}	




	
	

}





?>