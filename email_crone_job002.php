<?php include("admin/includes/conn.php");
include("site_theme_functions.php");
$SessionID = $_SESSION["sessid"]; // print_r( $_REQUEST );

$whereSQL = "";//" and uid in (11, 12, 15) ";
$query = "SELECT * FROM `employee` WHERE `WelcomeEmail` = 0 ORDER BY `employee`.UID limit 50 " . $whereSQL; // 

$sql = query($query);
$totalNewLetters = total( $query );
if( $totalNewLetters > 0 ){

	$EmailSuccess='';
	while($data = fetch($sql) ){
		//print_r($data);
		$password = PassWord($data['EmployeePassword'] ,"show");
		if($data['EmployeePassword']==''){
			$password = substr(md5(rand(0, 1000000)), 10, 5);
			$sql1 = "UPDATE `employee` SET `EmployeePassword` = '".PassWord($password,"hide")."' WHERE `employee`.`UID` = '".$data['UID']."'; ";
			mysql_query($sql1);
		}
		//$EmailSuccess .= "Email : ".$data["EmployeeName"]." | Password : ".$password." | <strong>Email Generated</strong><br> <br>";
		
		$message = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td class="borderColor2" style="padding:10px;" align="left">
				<p class="mainText">
				<strong>Dear <font style="color:#F00; text-transform: capitalize;">'.$data['EmployeeName'].'</font>, </strong><br ><br >
				<strong>Thank you</strong> for registering with Holistic Jobs. We <font style="color:#F00">welcome</font> you at first pharmaceutical job portal of Pakistan, and hope it will support in your career growth.<br ><br>
				Please note your updated Log in information:<br><br>
				Login Email: '.$data['EmployeeEmail'].'<br>
				Password: '.$password.'<br><br>
				You can login to your employee portal of Holistic Jobs with the information provided above.<br>
				<a href="http://www.holisticjobs.com.pk/page/employee-login">Click here to login </a>and start using Holistic Jobs.<br>
				<font style="color:#F00">Note: You will be able to change your password when you log in to your account.</font><br><br>

				Regards,<strong><br>Team Holistic Jobs</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
				</p>
			</td></tr></table> ';
		$body = SendMail("test@test.com","Holistic Jobs Welcome Email",$message,$show=true);
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
		$mail->Subject = "Holistic Jobs Welcome Email";
		$mail->Body    = $body;
		$mail->AltBody = $body;
		//echo $body; exit;
		if( $mail->send() ){
			query( "UPDATE `employee` SET `WelcomeEmail` = 1 WHERE `UID` = '".$data["UID"]."' ");
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
		Welcome Email Status.<br >
		'.$EmailSuccess.'<br>
		Admin,<strong><br>'.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
		</p>
	</td></tr></table> ';
$body = SendMail("test@test.com","Holistic Jobs Welcome Email (Status)",$message,$show=true);
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
$mail->Subject = "Holistic Jobs Welcome Email (Status)";
$mail->Body    = $body;
$mail->AltBody = $body;
$mail->send();

