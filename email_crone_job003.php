<?php include("admin/includes/conn.php");
include("site_theme_functions.php");
$SessionID = $_SESSION["sessid"]; // print_r( $_REQUEST );

$whereSQL = "";//" and uid in (11, 12, 15) ";
$query = "SELECT * FROM `employee` WHERE `TestPhaseEmail` = 0 ORDER BY `employee`.UID limit 100" . $whereSQL; // 

$sql = query($query);
$totalNewLetters = total( $query );
if( $totalNewLetters > 0 ){

	$EmailSuccess='';
	while($data = fetch($sql) ){
		//print_r($data);
		//$password = PassWord($data['EmployeePassword'] ,"show");
		if($data['EmployeePassword']==''){
			//$password = substr(md5(rand(0, 1000000)), 10, 5);
			//$sql1 = "UPDATE `employee` SET `EmployeePassword` = '".PassWord($password,"hide")."' WHERE `employee`.`UID` = '".$data['UID']."'; ";
			//mysql_query($sql1);
		} 
		//$EmailSuccess .= "Email : ".$data["EmployeeName"]." | Password : ".$password." | <strong>Email Generated</strong><br> <br>";
		
		$message = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td class="borderColor2" style="padding:10px;" align="left">
				<p class="mainText">
				<strong>Dear <font style="color:#F00; text-transform: capitalize;">'.$data['EmployeeName'].'</font>, </strong><br ><br >
				We are pleased to announce that test phase for Holistic Jobs is over. We now invite you to come back and update your profile for increased chances of getting  job.<br ><br>
				We want to take this opportunity to let you know about Holistic Jobs Invitation. Any company can view your qualification and professional profile (personal information like name, number, email, address or current company\'s is not revealed) and then invite you for any job. On your acceptance of the invitation, formal procedure will start. So, more the profile is updated, more will be the chances of getting invitations for jobs.<br><br>
				
				<a href="http://www.holisticjobs.com.pk/page/employee-login">Click here </a>to Login and update your profile.<br><br>

				Regards,<strong><br>Team Holistic Jobs</strong>
				</p>
			</td></tr></table> ';
		$body = SendMail("test@test.com","Update Your Profile",$message,$show=true);
		$mail = new PHPMailer;
		$mail->isSMTP();										 // Set mailer to use SMTP
		$mail->Host = 'mail.holisticjobs.com.pk';				// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;								  // Enable SMTP authentication
		$mail->Username = 'developer@holisticjobs.com.pk';	   // SMTP username
		$mail->Password = 'developer@147';                      // SMTP password
		$mail->Port = 26;                                    	// TCP port to connect to
		
		$mail->From = "info@holisticjobs.com.pk";
		$mail->FromName = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$mail->addAddress(trim($data["EmployeeEmail"]), $data["EmployeeName"]);     // Add a recipient
		$mail->addReplyTo("info@holisticjobs.com.pk", 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd');
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = "Update Your Profile";
		$mail->Body    = $body;
		$mail->AltBody = $body;
		//echo $body; exit;
		if( $mail->send() ){
			query( "UPDATE `employee` SET `TestPhaseEmail` = 1 WHERE `UID` = '".$data["UID"]."' ");
			$EmailSuccess .= "Name : ".$data["EmployeeName"]." | Email : ".$data["EmployeeEmail"]." | <strong>SUCCESS</strong><br> <br>";
		} else {
			$EmailSuccess .= "Name : ".$data["EmployeeName"]." | Email : ".$data["EmployeeEmail"]." | <font style='color:#ff0000'>FAILED</font><br>";
		}
		$mail = '';
		unset($mail);
	}
} else {
	$EmailSuccess='Email Queue is Empty ....<br >';
}
echo str_replace("<br>","\n", $EmailSuccess);

$message = '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="borderColor2" style="padding:10px;" align="left">
		<p class="mainText">
		<strong>Dear Admin, </strong><br ><br >
		Test Phase Email Status.<br >
		'.$EmailSuccess.'<br>
		Admin,<strong><br>'.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
		</p>
	</td></tr></table> ';
$body = SendMail("test@test.com","Holistic Jobs Test Phase Email (Status)",$message,$show=true);
$mail = new PHPMailer;
$mail->isSMTP();										// Set mailer to use SMTP
$mail->Host = 'mail.holisticjobs.com.pk';  				// Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'developer@holisticjobs.com.pk';                 // SMTP username
$mail->Password = 'developer@147';                           // SMTP password
$mail->Port = 26;                                    // TCP port to connect to

$mail->From = "info@holisticjobs.com.pk";
$mail->FromName = 'Holistic Solutions (Pvt.) Ltd';
$mail->addAddress("bdo1@holisticsolutions.com.pk", 'Maleeha BDO');     // Add a recipient
$mail->addAddress("info@orixestech.com", 'Orixes Developers');     // Add a recipient
$mail->addReplyTo("info@holisticjobs.com.pk", 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd');
$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = "Holistic Jobs Test Phase Email (Status)";
$mail->Body    = $body;
$mail->AltBody = $body;
$mail->send();

