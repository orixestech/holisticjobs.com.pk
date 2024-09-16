<?php
if($_POST){
	exit;
	$UploadCV = array();
	$target_dir = "uploads/";
	
	$sql = "SELECT * FROM `careers` WHERE `Email` = '".$_POST["Email"]."' ";
	$stmt = total ( $sql );
	if($stmt>0){
		$formMESSAGE = '<div class="notification error closeable"> <p><span>Error!</span> Your request is already submitted. Thank you!</p> </div>';
	} else {
		if($_FILES["file"]["name"])
		{
			$uploadOk = 1;
			$target_file = $target_dir . basename($_FILES["file"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = post_slug( $_POST["Name"]. "_" . rand(0, 100000) ); $path_parts['filename'];
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
					$UploadCV['UploadCV'] = mysql_real_escape_string($fileName.".".$fileType);
				}
				else
				{
					$formMESSAGE = '<div class="notification error closeable"> <p><span>Error!</span> Sorry, there was an error uploading your file.</p> </div>';
				}
			}
		}
		
		$SpecialtyExperience=array();
		for($a=0; $a<count($_POST['Specialty']); $a++){
			$SpecialtyExperience[$a] = $_POST['Experience'][$a] . ' Experience in '.$_POST['Specialty'][$a];
		
		}
		$SpecialtyExperience = implode(", ",$SpecialtyExperience);
		
		$UploadCV['Name'] = $_POST["Name"];
		$UploadCV['Email'] = $_POST["Email"];
		$UploadCV['ContactNo'] = $_POST["ContactNo"];
		$UploadCV['IntrestedArea'] = ($_POST["IntrestedArea"]=='other')?$_POST["IntrestedAreaOther"]:$_POST["IntrestedArea"];
		$UploadCV['IntrestedDesignation'] = ($_POST["IntrestedDesignation"]=='other')?$_POST["IntrestedDesignationOther"]:$_POST["IntrestedDesignation"];
		$UploadCV['IntrestedCompany'] = ($_POST["IntrestedCompany"]=='other')?$_POST["IntrestedCompanyOther"]:$_POST["IntrestedCompany"];
		$UploadCV['TopSoftSkills'] = $_POST["TopSoftSkills"];
		$UploadCV['TopTechnicalSkills'] = implode(", ",$_POST["TopTechnicalSkills"]);
		$UploadCV['CurrentStatus'] = $_POST["CurrentStatus"];
		$UploadCV['SpecialtyExperience'] = $SpecialtyExperience;
			
		$run = FormData('careers', 'insert', $UploadCV, "", $view=false );
		
		$message = '
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td class="borderColor2" style="padding:10px;" align="left">
			<p class="mainText">
			<strong>Dear Admin, </strong><br ><br >
			New CV Uploaded in your Careers Section. For more details, Please check your admin panel <br><br>
			Regards,<br><strong>Team '.$site_name.'</strong><br ><br >
			</p>
		</td></tr></table> ';
		$subject = "Upload CV";
		$body = SendMail("malik.shaheryar@hotmail.com", $subject, $message, $show=true);
		//echo $body; exit;
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->ContentType = 'text/html';
		//$mail->isSMTP();										// Set mailer to use SMTP
		//$mail->SMTPDebug  = 2;
		$mail->Host = 'mail.holisticjobs.com.pk';  						// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'developer@holisticjobs.com.pk';                 // SMTP username
		$mail->Password = 'developer@147';                           // SMTP password
		$mail->Port = 26;                                    // TCP port to connect to
		
		$mail->From = $site_email;
		$mail->FromName = 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd';
		$mail->addAddress($site_email, 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd');
		//$mail->addAddress("developer@holisticsolutions.com.pk", 'Holistic Solutions IT Team');
		$mail->addReplyTo($site_email, 'Holistic Jobs :: Holistic Solutions (Pvt.) Ltd');
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $body;
		$mail->AltBody = $body;
		//$mail->send(); unset($mail);
		if( $mail->send() ){
			$formMESSAGE = '<div class="notification success closeable">
					<p><span>Thank You!</span>  Your CV has been sent successfully!</p>
					<a href="#" class="close"></a>
				</div><input type="hidden" id="reload" value="0" /> <input type="hidden" id="reloadurl" value="'.$path.'" />';
		} else {
			//echo 'Message could not be sent. <br> Mailer Error: ' . $mail->ErrorInfo;	
		}	
	}
} ?>

<div id="titlebar" class="single">
  <div class="container">
    <div class="sixteen columns">
      <h2>
        <?=$CONTENT['content_title']?>
      </h2>
      <nav id="breadcrumbs">
        <ul>
          <li>You are here:</li>
          <li><a href="<?=$path?>">Home</a></li>
          <li>
            <?=$CONTENT['content_title']?>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>
<!-- Content
================================================== -->
<div class="container">
  <div class="sixteen columns">
    <p class="margin-reset">
      <?=$CONTENT['content_desc']?>
    </p>
    <div class="container"> 
      <!-- Submit Page -->
      <div class="eleven columns">
        <div class="submit-page"> 
          <!-- Notice -->
          <div class="notification notice closeable margin-bottom-40">
            <h4><span>Looking for job?</span><br />
              Please fill the form below and post your CV. Provide authentic information in order to be recommended for a job.</h4>
          </div>
          <h3 class="margin-bottom-5">Post Your CV</h3>
          <!-- Name -->
          <?=$formMESSAGE?>
          <form id="uploadcv" name="uploadcv" method="post" enctype="multipart/form-data">
            <div class="form">
              <h5>Name</h5>
              <input class="search-field validate[required,custom[onlyLetterSp]]" type="text" placeholder="Your full name" value="" name="Name" id="Name"/>
            </div>
            <div class="form">
              <h5>Email</h5>
              <input class="search-field" type="text" placeholder="Your Email" value="" name="Email" id="Email"/>
            </div>
            <div class="form">
              <h5>ContactNo</h5>
              <input class="search-field validate[required,custom[onlyNumberSp]]" type="text" placeholder="Your Contact No" value="" name="ContactNo" id="ContactNo"/>
            </div>
            <div class="form">
              <h5>Intrested Area</h5>
              <select data-placeholder="Choose Area" class="chosen-select" name="IntrestedArea" id="IntrestedArea" onchange="if(this.value=='other') $('#IntrestedAreaOther').show(); else $('#IntrestedAreaOther').hide();">
                <?php
				$qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'city') order by OptionName");
				$form_select = '<option value="">Please Select</option>';
				while( $rslt = mysql_fetch_array($qry) ){
					echo '<option value="'.$rslt["OptionDesc"].'">'.$rslt["OptionDesc"].'</option>';
				}?>
                <option value="other">Other</option>
              </select>
              <input class="search-field" type="text" placeholder="Other Intrested Area" value="" name="IntrestedAreaOther" id="IntrestedAreaOther" style="display:none; margin-top:5px;"/>
            </div>
            <!-- Contact -->
            <div class="form">
              <h5>Intrested Designation</h5>
              <select data-placeholder="Choose Designation" class="chosen-select" name="IntrestedDesignation" id="IntrestedDesignation" onchange="if(this.value=='other') $('#IntrestedDesignationOther').show(); else $('#IntrestedDesignationOther').hide();">
                <?php
				$qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'designation') order by OptionName");
				$form_select = '<option value="">Please Select</option>';
				while( $rslt = mysql_fetch_array($qry) ){
					echo '<option value="'.$rslt["OptionDesc"].'">'.$rslt["OptionDesc"].'</option>';
				}?>
                <option value="other">Other</option>
              </select>
              <input class="search-field" type="text" placeholder="Other Intrested Designation" value="" name="IntrestedDesignationOther" id="IntrestedDesignationOther" style="display:none; margin-top:5px;"/>
            </div>
            <!-- Applying For -->
            <div class="form">
              <h5>Intrested Company</h5>
              <select data-placeholder="Choose Company" class="chosen-select" name="IntrestedCompany" id="IntrestedCompany" onchange="if(this.value=='other') $('#IntrestedCompanyOther').show(); else $('#IntrestedCompanyOther').hide();">
                <?php
				$stmt = query("SELECT EmployerCompany  FROM `employer` WHERE 1 ORDER BY `employer`.`EmployerCompany` ASC ");
				while($rslt = fetch($stmt)){ ?>
                <option value="<?=$rslt['EmployerCompany']?>" >
                <?=$rslt['EmployerCompany']?>
                </option>
                <?
				}?>
                <option value="other">Other</option>
              </select>
              <input class="search-field" type="text" placeholder="Other Intrested Company" value="" name="IntrestedCompanyOther" id="IntrestedCompanyOther" style="display:none; margin-top:5px;"/>
            </div>
            <!-- 3 soft skills -->
            <div class="form">
              <h5>Top 3 soft skills </h5>
              <select data-placeholder="Choose Skills" class="chosen-select" multiple name="TopSoftSkills[]" id="TopSoftSkills">
                <?php
				$qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'soft-skills') order by OptionName");
				$form_select = '<option value="">Please Select</option>';
				while( $rslt = mysql_fetch_array($qry) ){
					echo '<option value="'.$rslt["OptionDesc"].'">'.$rslt["OptionDesc"].'</option>';
				}?>
              </select>
            </div>
            <!-- 3 Technical skills -->
            <div class="form">
              <h5>Top 3 technical skills </h5>
              <select data-placeholder="Choose Skills" class="chosen-select" multiple name="TopTechnicalSkills[]" id="TopTechnicalSkills">
                <?php
				$qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'skills') order by OptionName");
				$form_select = '<option value="">Please Select</option>';
				while( $rslt = mysql_fetch_array($qry) ){
					echo '<option value="'.$rslt["OptionDesc"].'">'.$rslt["OptionDesc"].'</option>';
				}?>
              </select>
            </div>
            <!-- Total Experience -->
            <div id="SpecialtyExperience" class="clearfix">
              <div class="clearfix">
                <div class="five columns form">
                  <h5>Specialty </h5>
                  <input class="search-field" type="text" placeholder="Specialty" value="" name="Specialty[]"/>
                </div>
                <!-- Specialty wise experience  -->
                <div class="two columns form">
                  <h5>Experience </h5>
                  <select data-placeholder="Choose Skills" class="chosen-select" name="Experience[]">
                    <?php
					$qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'experience') order by OptionName");
					$form_select = '<option value="">Please Select</option>';
					while( $rslt = mysql_fetch_array($qry) ){
						echo '<option value="'.$rslt["OptionDesc"].'">'.$rslt["OptionDesc"].'</option>';
					}?>
                  </select>
                </div>
                <div class="columns form">
                  <h5><a href="javascript:AddSpecialtyExperience();" class=" small margin-top-40">Add <i class="fa fa-plus-circle"></i></a> </h5>
                </div>
              </div>
            </div>
            <!-- Current status -->
            <div class="form">
              <h5>Current status</h5>
              <input class="search-field" type="text" placeholder="Current status" value="" name="CurrentStatus" id="CurrentStatus"/>
            </div>
            <!-- Resume -->
            <div class="form upload-file">
              <h5>Upload <span>CV</span></h5>
              <label class="upload-btn">
                <input type="file" name="file" id="file"  />
                <i class="fa fa-upload"></i> Browse </label>
              <span class="fake-input ">No file selected</span> </div>
              <div class="clearfix"></div>
              <div class=" margin-top-0 padding-reset"><br /></div>
            <input type="submit" class="button big margin-top-5" value='Submit'>
            <div class=" margin-top-0 padding-reset"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
<div class="margin-top-40"></div>
<link rel="stylesheet" type="text/css" href="<?=$path?>formcss/validationEngine.jquery.css" />
<script src="<?=$path?>formcss/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?=$path?>formcss/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script> 
<script>
$(document).ready(function(){
	$("form#uploadcv").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.uploadcv.submit();
			} else {
				//alert("The form status is: " +status+", it will never submit");
			}
	  }
	});

	$("#reset").click(function(){
		$(".formError").hide();
	});
	
});

function AddSpecialtyExperience(){
	html = '<div class="clearfix">\
	  <div class="five columns form">\
		<input class="search-field" type="text" placeholder="Specialty" value="" name="Specialty[]"/>\
	  </div>\
	  <div class="columns form">\
		<select data-placeholder="Choose Skills" class="chosen-select" name="Experience[]"> <?php
					$qry = mysql_query("SELECT * FROM `optiondata` WHERE `OptionType` in (SELECT `TypeId` FROM `typedata` WHERE `TypeFieldName` = 'experience') order by OptionName");
					$form_select = '<option value="">Please Select</option>';
					while( $rslt = mysql_fetch_array($qry) ){
						echo '<option value="'.$rslt["OptionDesc"].'" >'.$rslt["OptionDesc"].'</option>';
					}?></select>\
	  </div></div>';
	
	$("#SpecialtyExperience").append(html);
	var config = {
          '.chosen-select'           : {disable_search_threshold: 10, width:"100%"},
          '.chosen-select-deselect'  : {allow_single_deselect:true, width:"100%"},
          '.chosen-select-no-single' : {disable_search_threshold:10, width:"100%"},
          '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
          '.chosen-select-width'     : {width:"95%"}
        };
        for (var selector in config) {
          $(selector).chosen(config[selector]);
        }


}

</script> 
