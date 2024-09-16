<?php include("admin/includes/conn.php");
include("site_theme_functions.php");
$SessionID = $_SESSION["sessid"]; // print_r( $_REQUEST );



$whereSQL = "";//" and uid in (11, 12, 15) ";
$query = "SELECT * FROM `newsletters_queue` WHERE `nlg_userid` = 1 and `nlg_status` = 0 order by nlq_uid limit 50 " . $whereSQL; // 

$sql = query($query);
$totalNewLetters = total( $query );
if( $totalNewLetters > 0 ){

	$EmailSuccess='';
	while($data = fetch($sql) ){
		$body = file_get_contents("email_template/index3.html");
		$body  = str_replace('src="images/','src="'.$path.'email_template/images/',$body);
		$mail = new PHPMailer;
		$mail->isSMTP();										// Set mailer to use SMTP
		$mail->Host = 'mail.holisticjobs.com.pk';  				// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'developer@holisticjobs.com.pk';                 // SMTP username
		$mail->Password = 'developer@147';                           // SMTP password
		$mail->Port = 26;                                    // TCP port to connect to
		
		$mail->From = "info@holisticjobs.com.pk";
		$mail->FromName = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$mail->addAddress(trim($data["nlg_email"]), $data["nlg_name"]);     // Add a recipient
		$mail->addReplyTo("info@holisticjobs.com.pk", 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd');
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = "Holistic Jobs :: Holistic Solutions (Pvt.) Ltd";
		$mail->Body    = $body;
		$mail->AltBody = $body;
		//echo $body; exit;
		if( $mail->send() ){
			query( "UPDATE `newsletters_queue` SET `nlg_status` = '1' WHERE `newsletters_queue`.`nlq_uid` = '".$data["nlq_uid"]."' ");
			$EmailSuccess .= "Name : ".$data["nlg_name"]."<br>Email : ".$data["nlg_email"]."<br><strong>SUCCESS</strong><br> <br>";
		} else {
			$EmailSuccess .= "Name : ".$data["nlg_name"]."<br>Email : ".$data["nlg_email"]."<br><font style='color:#ff0000'>FAILED</font><br>";
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
		Intro Email Status.<br >
		'.$EmailSuccess.'<br>
		Admin,<strong><br>'.$site_name.'</strong><br>3rd, 4th & 5th floor, Holistic Tower, Jinnah Boulevard West,Sector A, DHA Phase-II, Islamabad. <br ><br >
		</p>
	</td></tr></table> ';
$body = SendMail("test@test.com","Holistic Jobs Intro Email (Status)",$message,$show=true);
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
$mail->addAddress("malik.shaheryar@orixestech.com", 'Shaheryar Malik');     // Add a recipient
$mail->addReplyTo("info@holisticjobs.com.pk", 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd');
$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = "Holistic Jobs Intro Email (Status)";
$mail->Body    = $body;
$mail->AltBody = $body;
$mail->send();

