<?php include('header.php');?>
<?php
if($_POST){
	$target_dir = BANNER_IMAGE_DIRECTORY;
	
	if($_POST['mode']=='insert'){
		
		$FormMessge = '';
		if($_FILES["image"]["name"])
		{
			$uploadOk = 1;
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'banner'; $path_parts['filename'];
			//echo "fileName = " . $fileName . "<br />";
			
			$fileType = $path_parts['extension'];
			//echo "fileType = " . $fileType . "<br />";
			
			// Check if file already exists
			while(file_exists($target_file)) {
				$fileName = $fileName . "_" . rand(0, 100000);
				$target_file = $target_dir . $fileName . "." . $fileType;
			}
			//echo "target_file = " . $target_file . "<br />";
			
			
			// Allow certain file formats
			if(strtolower($fileType) != "jpg" && strtolower($fileType) != "png") {
				$FormMessge = Alert('error', 'Sorry, only jpg & jpeg files are allowed.');
				$uploadOk = 0;
			}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))
				{
					$append_column = ", `BannerImage`";
					$append_value  = ", '".mysql_real_escape_string($fileName.".".$fileType)."'";
				}
				else
				{
					$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
				}
			}
		}
		
		
		if(!$FormMessge)
		{
			mysql_query( "INSERT INTO `banners` 
			(`UID`, `BannerHeading`, `BannerDesc` $append_column) 
			VALUES 
			(NULL, '".$_POST['BannerHeading']."', '".$_POST['BannerDesc']."' $append_value);" );
			
			$inserted_id = mysql_insert_id();
			$FormMessge = Alert('success', 'New banner added successfully...!');
		}
	}
	
	if($_POST['mode']=='update'){
		
		$FormMessge = '';
		
		if($_FILES["image"]["name"])
		{
			$uploadOk = 1;
			
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'banner';$path_parts['filename'];
			//echo "fileName = " . $fileName . "<br />";
			
			$fileType = $path_parts['extension'];
			//echo "fileType = " . $fileType . "<br />";
			
			// Check if file already exists
			//while(file_exists($target_file)) {
				$fileName = $fileName . "_" . rand(0, 100000);
				$target_file = $target_dir . $fileName . "." . $fileType;
			//}
			//echo "target_file = " . $target_file . "<br />";
			
			
			// Allow certain file formats
			if($fileType != "jpg" && $fileType != "png") {
				$FormMessge = Alert('error', 'Sorry, only jpg & jpeg files are allowed.');
				$uploadOk = 0;
			}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))
				{
					$append = ", `BannerImage` = '".mysql_real_escape_string($fileName.".".$fileType)."'";
					
					//Delete old file.
					$existing_image = GetData('BannerImage','banners','UID',$_GET["code"]);
					@unlink($target_dir.$existing_image);
				}
				else
				{
					$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
				}
			}
		}
		
		
		if(!$FormMessge)
		{
			$sql = " UPDATE `banners` SET 
			`BannerHeading` = '".$_POST['BannerHeading']."', 
			`BannerDesc` = '".$_POST['BannerDesc']."'
			$append
			WHERE `UID` = '".$_GET["code"]."'; ";
			mysql_query($sql);
			$FormMessge = Alert('success', 'Banenr updated successfully...!');
		}
	}
}
	
if($_GET["mode"]=="update"){
	$stmt = mysql_query(" SELECT * FROM `banners` WHERE `UID` = '".$_GET["code"]."' ");
	$PAGE = mysql_fetch_array($stmt);
} ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li> <a href="#">Banners</a> </li>
      <li class="active">
        <?=($_GET["code"])?'Modify':'Add New'?>
        Banner </li>
    </ul>
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1>
        <?=($_GET["code"])?'Modify':'Add'?> Banner Form <small> <i class="icon-double-angle-right"></i> Add or Update Banner </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" id="mode" value="<?=($_GET["mode"]=="update")?'update':'insert'?>" />
      <div class="row">
        <h4 class="header green">Banner Details</h4>
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Heading </label>
              <div class="col-sm-9">
                <input type="text" id="BannerHeading" name="BannerHeading" placeholder="Banner Heading" class="col-xs-10 col-sm-5" value="<?=$PAGE['BannerHeading']?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Description </label>
              <div class="col-sm-9">
                <textarea class="ContentEditor col-xs-10 col-sm-5" id="BannerDesc" name="BannerDesc" style="height: 120px;" ><?=$PAGE['BannerDesc']?>
</textarea>
              </div>
            </div>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Browse <small>(1920*650)</small></label>
              <div class="col-sm-9">
			 <input type="file" id="image" name="image" />
			 <?=($PAGE['BannerImage']!='')?'<img src="'.$path.'images/banners/'.$PAGE['BannerImage'].'" class="col-xs-10 col-sm-5" />':''?>
              </div>
            </div>
          </div>
          <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
              <button type="submit" class="btn btn-info"> <i class="icon-ok bigger-110"></i> Submit </button>
              <button type="reset" class="btn"> <i class="icon-undo bigger-110"></i> Reset </button>
            </div>
          </div>
          <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </form>
  </div>
  <!-- /.page-content -->
</div>
<!-- /.main-content -->
<?php include('footer.php');?>
<script>				$('#id-input-file-1 , #id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
</script>