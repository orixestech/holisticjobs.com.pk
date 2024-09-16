<?php include('header.php');

if(isset($_GET['delete']) && $_GET['uid']>0){
	$target_dir = "../uploads/";
	$ResumeFilename = GetData("ResumeFilename","employee_resume","UID",$_GET['uid']);
	$ResumeTitle = GetData("ResumeTitle","employee_resume","UID",$_GET['uid']);
	
	$file = $target_dir . $ResumeFilename;
	query(" DELETE FROM `employee_resume` WHERE `employee_resume`.`UID` = '".$_GET['uid']."' ");
	@unlink($file);
	
	$FormMESSAGE = Alert('success','Your CV ['.$ResumeTitle.'] Successfully Deleted.');
	Track(ucwords('Your CV ['.$ResumeTitle.'] Successfully Deleted.'), 'employee', $_SESSION['EmployeeUID']);
	
}


if($_POST){
	//print_r($_POST);
	$employee_resume = array();
	$target_dir = "../uploads/";
	
	if($_FILES["file"]["name"])
	{
		$uploadOk = 1;
		$target_file = $target_dir . basename($_FILES["file"]["name"]);
		//echo "target_file = " . $target_file . "<br />";
		
		$path_parts = pathinfo($target_file);
		//echo "<pre>";print_r($path_parts);echo "</pre>";
		
		$fileName = post_slug( $_SESSION['Employee']['EmployeeName'] . "_" . $_POST["ResumeTitle"]. "_" . rand(0, 100000) ); $path_parts['filename'];
		//echo "fileName = " . $fileName . "<br />";
		
		$fileType = $path_parts['extension'];
		
		$allowEXT = array('doc', 'docx', 'pdf');
		if( !in_array($fileType, $allowEXT) ){
			$uploadOk = 0;
			$formMESSAGE = Alert('error', 'Sorry, Invalid File format.');
		}
		
		$target_file = $target_dir . $fileName . "." . $fileType;
		
		// Check if file already exists
		while(file_exists($target_file)) {
			$fileName = $fileName . "_" . rand(0, 100000);
			$target_file = $target_dir . $fileName . "." . $fileType;
		}
		// echo "target_file = " . $target_file . "<br />";
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 1)
		{
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
			{
				$employee_resume['ResumeFilename'] = mysql_real_escape_string($fileName.".".$fileType);
				$employee_resume['ResumeEmployeeID'] = $_SESSION['EmployeeUID'];
				$employee_resume['ResumeTitle'] = $_POST["ResumeTitle"];
				$run = FormData('employee_resume', 'insert', $employee_resume, "", $view=false );
				$formMESSAGE = Alert('success','Your resume has been uploaded successfully!');
				echo '<script>setTimeout("window.location = location.href;", 2200);</script>';
			}
			else
			{
				$formMESSAGE = Alert('error', 'Sorry, there was an error uploading your file.');
			}
		}
		
	} else {
		$formMESSAGE = Alert('error','File Uploading Erorr, Please try again.');
	}
	
}

?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">My Resume</li>
    </ul>
    <!-- .breadcrumb --> 
    <span class="pull-right">
    <?=$SubscriptionExpireStatus?>
    </span> 
    <!-- #nav-search --> 
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1>My Resume<small> <i class="icon-double-angle-right"></i> Update your Resume</small> </h1>
    </div>
    <!-- /.page-header -->
    
    <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12"> <h5 class="red">Chances of being selected are minimized if you haven't completed your profile. Go to Update Profile to complete it.</h5></div>
      <div class="col-xs-6 col-sm-6 col-md-6">
        <h4>All Resumes</h4>
        <?=$FormMESSAGE?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="agenda-report">
            <thead>
              <tr>
                <th width="1%">Sr.No</th>
                <th>Title</th>
                <th width="18%">Date</th>
                <th width="12%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
            $stmt = query("SELECT * FROM `employee_resume` WHERE ResumeEmployeeID = '".$_SESSION['EmployeeUID']."' order by SystemDate DESC");$cnt=0;
			while($rslt = fetch($stmt)){ $cnt++;?>
              <tr>
                <td><?=$cnt?></td>
                <td><?=$rslt['ResumeTitle']?></td>
                <td><?=date("d M, Y", strtotime($rslt['SystemDate']))?></td>
                <td><div class="visible-md visible-lg hidden-sm hidden-xs action-buttons"> <a title="Download" class="green" role="button" href="<?=$path?>uploads/<?=$rslt['ResumeFilename']?>"> <i class="icon-download bigger-130"></i> </a> <a title="Delete" class="red ConfirmDelete" onclick="ConfirmDelete('resume.php?delete=true&uid=<?=$rslt['UID']?>')" href="javascript:void(0)"> <i class="icon-trash bigger-130"></i> </a></div></td>
              </tr>
              <?php
            }?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-xs-5 col-sm-5 col-md-5">
        <h4>Add New </h4>
        <form enctype="multipart/form-data" id="EmployeeResume" method="post" role="form" class="form-horizontal">
          <?=$formMESSAGE?>
          <div class="form-horizontal">
            <div class="space-4"></div>
            <div class="form-group">
              <label for="form-field-1" class="col-sm-2 col-md-2 col-xs-6 control-label no-padding-right">Title:<i class="icon-asterisk"></i></label>
              <div class="col-sm-7 col-xs-7 col-md-7">
                <input type="text" value="" class="col-xs-12 col-sm-12 col-md-12 validate[required]" placeholder"your resume title." id="ResumeTitle" name="ResumeTitle">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-xs-6 col-md-2 control-label no-padding-right">Upload CV:<i class="icon-asterisk"></i></label>
              <div class="col-sm-7 col-xs-10 col-md-7" style="padding:0">
                <input type="file" value="" class="col-xs-12 col-sm-12 col-md-12 validate[required]" name="file" id="file" accept=".pdf, .doc, .docx" >
                <br />
              </div>
              <div class="col-sm-10 pull-left"><small class="red">Allowed file formats: .doc, .docx, .pdf</small></div>
            </div>
            <div class="clearfix pull-right">
              <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info btn-xs" type="submit"> <i class="icon-ok bigger-110"></i> Submit </button>
              </div>
            </div>
            <div class="space-4"></div>
          </div>
          <div class="clearfix"><br>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<!-- /.page-content -->
</div>
<!-- /.main-content --> 
<script>
    function nexttab(trg){
		$('#myTab a[href="#'+trg+'"]').trigger('click');
	}
	
	$(document).ready(function(){
		$("form#EmployeeResume").validationEngine('attach', {
			promptPosition : "centerRight", 
			scroll: false,
			onValidationComplete: function(form, status){
				if(status){
					document.EmployeeResume.submit();
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
<?php include('footer.php');?>
