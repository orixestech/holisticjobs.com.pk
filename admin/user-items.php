<?php include('header.php');?>

<?php 
//if($_SESSION["UserID"]<1) { ?>
<!--<script language="javascript">window.location = '<?php //echo $path?>page/access-denied'</script>-->
<?php //exit;}
if($_POST)
{
	$data = array();
	$target_dir = "uploads/";
	$avatar_file = date("U") . '_' . str_replace(" ", "_", strtolower( basename($_FILES["avatar"]["name"]) ) ) ;
	$target_file = $target_dir . $avatar_file;
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
	if ($uploadOk == 1) {
		if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
			$data['avatar'] = $avatar_file;
		}
	}
	
	$avatar_file = date("U") . '_' . str_replace(" ", "_", strtolower( basename($_FILES["homepageimage"]["name"]) ) ) ;
	$target_file = $target_dir . $avatar_file;
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($_FILES["homepageimage"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
	if ($uploadOk == 1) {
		if (move_uploaded_file($_FILES["homepageimage"]["tmp_name"], $target_file)) {
			$data['homepageimage'] = $avatar_file;
		}
	}
	
	
	$data['last_signin'] = date("Y-m-d h:i:s");
	$data['profile_heading'] = $_REQUEST['profile-heading'];
	$data['profile_text'] = $_REQUEST['profile-text'];
	$insert_id = FormData('members', 'update', $data, " `id` = '".$_SESSION["UserID"]."' ", $view=false );
	$successMSG =  '<div class="bg-success">Successfully Update.!</div>';
	echo "<script language='javascript'>window.location = '".$path."user/public-profile';</script>";exit;
}
?>

<link href="<?=$path?>css/paging.css" rel="stylesheet">
<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li> <a href="upload_template.php">Templates</a> </li>
      <li class="active">Upload Item</li>
    </ul>
    <!-- .breadcrumb -->
  </div>

  <div class="page-content">
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
        <div class="col-xs-12">
            <h3 class="header smaller lighter blue">Upload Item</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="table-responsive" id="upload_item_view">
	<div class="row">
	<form role="form" method="post" id="upload-item" name="upload-item" onsubmit="UpdateUserForm(); return false;" enctype="multipart/form-data">
	  <input type="hidden" name="type" value="upload-item" >
	  <div class="col-lg-9 col-md-9 col-sm-7">
		<h1 class="section-title-inner"><span><i class="fa fa-briefcase"></i> Upload an Item </span></h1>
		<div class="row userInfo">
		  <div class="col-lg-12 col-xs-12">
			<p class=""><sup>*</sup>  field</p>
		  </div>
		  <div class="form-group  col-xs-12 col-sm-12">
			<label>Name </label>
			<p>Maximum 50 characters. Follow our Item Title Naming Conventions.</p>
			<input  type="text" class="form-control required" required id="title" name="title" value="">
		  </div>
		  <div class="form-group  col-xs-12 col-sm-12">
			<label>Description </label>
			<textarea  type="text" class="form-control required" required id="description" name="description"></textarea>
			<p>This field is used for search, so please be descriptive! If you're linking to external images see our image performance tips for authors.</p>
		  </div>
		  <div class="form-group  col-xs-12 col-sm-12">
			<label>Demo URL </label>
			<input  type="text" class="form-control required" required id="demo_url" name="demo_url" value="">
		  </div>
		  <div class="form-group  col-xs-12 col-sm-12">
			<h3>File & Uplodes</h3>
		  </div>
		  <div class="form-group  col-xs-12 col-sm-12">
			<label><a href="#"  data-toggle="modal" data-target="#ModalFileUpload" onclick="LoadFileUploader('ModalFileUpload');"><button class="btn btn-primary" type="button" style="font-size:30px;"><i class="fa fa-floppy-o"></i> Files  </button></a></label><?php //print_r($_SESSION["UploadFile"]);  print_r($_SESSION["UploadDelete"]);  ?>
			<div id="Current-File-Box-Ajax-Responce"></div>
			<div style="clear:both"><br /></div>
			<p>This field is used for search, so please be descriptive! If you're linking to external images see our image performance tips for authors.</p>
		  </div>
		  
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Thumbnail </label>
			<select name="thumbnail" id="thumbnail" class="UploadedFileList " required onchange="CheckImageExt(this)" ></select>
			<p>JPEG or PNG 80x80px Thumbnail</p>
		  </div>
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Theme Preview </label>
			<select name="theme-preview" id="theme-preview" class="UploadedFileList " required onchange="CheckImageExt(this)" ></select>
			<p>ZIP file of images (png/jpg) w/ optional text descriptions for display on the site</p>
		  </div>
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Main File(s) </label>
			<select name="main-file" id="main-file" class="UploadedFileList " required onchange="CheckZIPExt(this)"></select>
			<p>ZIP - All files for buyers, not including preview images</p>
		  </div>
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Template File </label>
			<select name="template-file" id="template-file" class="UploadedFileList " required onchange="CheckZIPExt(this)"></select>
			<p>ZIP containing an installable Files</p>
		  </div>
		  
		  <div class="form-group  col-xs-12 col-sm-12">
			<h3>Category & Attributes</h3>
		  </div>
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Category </label>
			<select name="category" id="category" class="selectstyle" required><?php echo CategoryDropDown();?></select>
		  </div>
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Resolution </label>
			<select name="resolution" id="resolution" class="selectstyle" required><?=formListOpt('resolution', 0)?></select>
		  </div>
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Widget </label>
			<select name="widget-support" id="widget-support" class="selectstyle" required><?=formListOpt('widget-support', 0)?></select>
		  </div>
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Layout </label>
			<select name="layout-support" id="layout-support" class="selectstyle" required><?=formListOpt('layout-support', 0)?></select>
		  </div>
		  
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Compatible Browsers </label><br />
			<select name="compatible-browsers[]" id="compatible-browsers" required  multiple="multiple" class="multiselectstyle col-xs-12 col-sm-12"><?php echo formListOpt('compatible-browsers', 0)?></select>
		  </div>
		  <div class="form-group  col-xs-6 col-sm-6">
			<label>Compatible With </label><br />
			<select name="compatible-with[]" id="compatible-with" required multiple="multiple" class="multiselectstyle col-xs-12 col-sm-12"><?php echo formListOpt('compatible-with', 0)?></select>
		  </div>
		  <div class="form-group  col-xs-6 col-sm-6">
			<label> Available Colors </label><br />
			<select name="template-color[]" id="template-color" required  multiple="multiple" class="multiselectstyle col-xs-12 col-sm-12"><?php echo formListOpt('template-color', 0)?></select>
		  </div>
		  
		  <div class="col-lg-12 col-xs-12 clearfix">
			<div style="clear:both"><br />
			</div>
			<button type="button" class="btn btn-primary" onclick="UpdateUserForm()" ><i class="fa fa-floppy-o"></i> Save </button>
			<div id="upload-itemBox-Ajax-Responce">
			  <?=$successMSG?>
			</div>
		  </div>
		  <div class="col-lg-12 col-xs-12  clearfix ">
			<ul class="pager">
			  <li class="previous pull-right hide"><a href="index.html"> <i class="fa fa-home"></i> Go to Shop </a></li>
			  <li class="next pull-left"><a href="<?=$path?>user/dashboard">&larr; Back to My Account</a></li>
			</ul>
		  </div>
		</div>
	  </div>
	  <div class="col-lg-3 col-md-3 col-sm-5"> </div>
	</form>
	</div>
			</div>
        </div>
        </div>
        <!-- PAGE CONTENT ENDS -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.page-content -->
</div>
<!-- /.main-content -->

<div class="modal signUpContent fade" id="ModalFileUpload" tabindex="-1" role="dialog" >
  <div class="modal-dialog " style="max-width:60%; width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="UpdateFileList()"> &times; </button>
        <h3 class="modal-title-site text-center" > Devmarket File Uploader </h3>
      </div>
      <div class="modal-body">
        <iframe src="" style="width:100%; height:300px; border:0px;"></iframe>
      </div>
      <div class="modal-footer">
        <p>For files over 500MB we recommend uploading via FTP. The maximum file size allowed is 1GB.</p>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
setTimeout(function(){ 
		UpdateFileList();
}, 1000);

function CheckImageExt(obj){
	var filename = $(obj).val();
	var extension = filename.replace(/^.*\./, '');
	extension = extension.toLowerCase();
	if(extension!='jpg' && extension!='jpeg' && extension!='png' ){
		alert("Invalid Image File...!");
		$(obj).val('');
	} else {
	}
}

function CheckZIPExt(obj){
	var filename = $(obj).val();
	var extension = filename.replace(/^.*\./, '');
	extension = extension.toLowerCase();
	if(extension!='zip'){
		alert("Invalid Zip File...!");
		$(obj).val('');
	} else {
	}
}

function UpdateFileList(){
	$.ajax({
	  cache: false, 
	  type: "POST",
	  <?php
	  if ($_SERVER['HTTP_HOST'] != 'localhost'){
	  	?>url: "http://"+window.location.hostname+"/ajax-page.php",<?
	  } else {
	  	?>url: "http://"+window.location.hostname+"/devmarket/ajax-page.php",<?
	  }?>
	  beforeSend: function(){
			$("#Current-File-Box-Ajax-Responce").html('Loading....');
			$("#Current-File-Box-Ajax-Responce").fadeIn('fast');
	  },
	  dataType: "html",
	  data:'type=current-file-upload',
	  success: function(data){
		$("#Current-File-Box-Ajax-Responce").html(data);
		$("#Current-File-Box-Ajax-Responce").fadeIn('slow');
		$files = $("#Current-File-SelectOptions").html();
		$("select.UploadedFileList").html($files);
		
		/*$("select.UploadedFileList").show();
		$("div.minict_wrapper").remove();
		$("select.UploadedFileList").minimalect();
		$("select.selectstyle").minimalect();*/
			
	  },error: function(){
		//alert('Error Loading AJAX Request...!');
	  }
	});


	//ajaxreq("ajax-page.php", "type=current-file-upload", 'Current-File-Box-Ajax-Responce');
	setTimeout(function(){ 
		//$files = $("#Current-File-SelectOptions").html();
		//$("select.UploadedFileList").html($files);
		//$("div.minict_wrapper").remove();
		//$("select.selectstyle").minimalect();
	}, 1000);
	
}
function DeleteFile(file){
	if(confirm("Are You Wants to Delete This File...!")){
		ajaxreq("../ajax-page.php", "type=delete-file-upload&filename="+file, 'Current-File-Box-Ajax-Responce');
		setTimeout(function(){ 
			UpdateFileList();
		}, 1000);
	} else {
	
	}
}
function LoadFileUploader(divid){
	$('#'+divid+' iframe ').attr('src', "<?=$path?>file_uploader/index.php?rand=<?=rand(0,9999)?>")

}

function FileUploader(){
	$('#fileuploader').modal('show');
}

function UpdateUserForm(){
	///$( "#public-profile").submit();
	//CheckZIPExt('main-file');
	var data = $( "form#upload-item" ).serialize();
	
	ajaxreq("../ajax-page.php", data, 'upload-itemBox-Ajax-Responce');
	return false;
}

</script>

<?php include('footer.php');?>