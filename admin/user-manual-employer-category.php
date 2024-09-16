<?php include('header.php');

$_GET['type'] = $_GET['type'] ? $_GET['type'] : 'User-Manual-Employer';

if($_POST){
	if($_POST['mode']=='insert'){
		
		$FormMessge = '';
		
		if($_FILES["image"]["name"])
		{
			$uploadOk = 1;
			
			$target_dir = CATEGORY_IMAGE_DIRECTORY;
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'CAT-'.$_POST['cid'];$path_parts['filename'];
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
			
			// Check file size
			if ($_FILES["image"]["size"] > CATEGORY_IMAGE_ALLOWED_FILESIZE) {
				$FormMessge = Alert('error', 'Sorry, your file is too large.');
				$uploadOk = 0;
			}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))
				{
					$append_column = ", `image`";
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
			mysql_query( "INSERT INTO `category` 
			(`id`, `title`, `orderid`, `description`, `catid`, `status`, `type` $append_column) 
			VALUES 
			(NULL, '".$_POST['title']."', '0', NULL, '".$_POST['catid']."', '1', '".$_POST['type']."' $append_value);" );
			
			$inserted_id = mysql_insert_id();
			$parent_id	 = $_POST['catid'];
			
			$level		= 0;
			$ordermap	= '';
			
			if($parent_id != 0)
			{
				$mapDetails = getCategoryMapDetail($parent_id);
				//echo '<pre>';print_r($mapDetails); echo '</pre>';
				
				$level		= $mapDetails['level'];
				$ordermap	= $mapDetails['ordermap'];
			}
			$ordermap .= $inserted_id;
			
			$updateSelf = "UPDATE `category` SET 
						`ordermap` = '$ordermap', 
						`level` = '$level' 
						WHERE id = $inserted_id";
			mysql_query($updateSelf);
			
			$FormMessge = Alert('success', 'New category added successfully...!');
		}
	}
	
	if($_POST['mode']=='update'){
		
		$FormMessge = '';
		
		if($_FILES["image"]["name"])
		{
			$uploadOk = 1;
			
			$target_dir = CATEGORY_IMAGE_DIRECTORY;
			$target_file = $target_dir . basename($_FILES["image"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = 'CAT-'.$_POST['cid'];$path_parts['filename'];
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
			if($fileType != "jpg" && $fileType != "png") {
				$FormMessge = Alert('error', 'Sorry, only jpg & jpeg files are allowed.');
				$uploadOk = 0;
			}
			
			// Check file size
			if ($_FILES["image"]["size"] > CATEGORY_IMAGE_ALLOWED_FILESIZE) {
				$FormMessge = Alert('error', 'Sorry, your file is too large.');
				$uploadOk = 0;
			}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))
				{
					$append = ", `image`='".mysql_real_escape_string($fileName.".".$fileType)."'";
					
					//Delete old file.
					$existing_image = GetData('image','category','id',$_POST['cid']);
					@unlink(CATEGORY_IMAGE_DIRECTORY.$existing_image);
				}
				else
				{
					$FormMessge = Alert('error', 'Sorry, there was an error uploading your file.');
				}
			}
		}
		
		
		if(!$FormMessge)
		{
			$sql = " UPDATE `category` SET 
			`title` = '".$_POST['title']."', 
			`catid` = '".$_POST['catid']."'
			$append
			WHERE `id` = '".$_POST['cid']."'; ";
			mysql_query($sql);
			
			$inserted_id = $_POST['cid'];
			$parent_id	 = $_POST['catid'];
			
			$level		= 0;
			$ordermap	= '';
			
			if($parent_id != 0)
			{
				$mapDetails = getCategoryMapDetail($parent_id);
				//echo '<pre>';print_r($mapDetails); echo '</pre>';
				
				$level		= $mapDetails['level'];
				$ordermap	= $mapDetails['ordermap'];
			}
			$ordermap .= $inserted_id;
			
			$updateSelf = "UPDATE `category` SET 
						`ordermap` = '$ordermap', 
						`level` = '$level' 
						WHERE id = $inserted_id";
			mysql_query($updateSelf);
			
			$FormMessge = Alert('success', 'Category updated successfully...!');
		}
	}
}
?>
<script language="javascript">
function updateCat(id, title, pid, image){
	//alert(id+' | '+title+' | '+pid);
	$("form #catid").val(pid);
	$("form #title").val(title);
	$("form #mode").val('update');
	$("form #cid").val(id);
	
	if(image)
	{
		$("#existing_image").attr("src",image);
		$("#existing_image").show();
	}
	else
	{
		$("#existing_image").attr("src",'');
		$("#existing_image").hide();
	}
	return false;
}
function togglediv(id) { 
	$(id).toggle("slow");
};
</script>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">
        <?php
	  if($_GET['type'] == 'User-Manual') echo "Employer User Manual Categories"; ?>
      </li>
    </ul>
    <!-- .breadcrumb -->
  </div>
  <div class="page-content">
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <?php
			if($_GET["action"]=='status' && $_GET["key"]){
				
				$key	= explode("|",base64_decode($_GET["key"]));
				$id		= $key[0];
				$status = $key[1];
				
				$pageTitle = GetData('title','category','id',$id);
				
				mysql_query(" UPDATE `category` SET `status` = '".$status."' WHERE `id` = '".$id."'; ");
				$message = Alert('success', 'Category [ '.$pageTitle.' ] status changed to '.ucwords($status ? 'Active' : 'Block').'...!');
			}
			
          	if($_GET["action"]=='delete' && $_GET["id"]){
				$pageTitle = GetData('title','category','id',$_GET["id"]);
				
				mysql_query(" DELETE FROM `category` WHERE id = '".$_GET["id"]."' ");
				$num = mysql_affected_rows();
				if($num){
					Track('Category [ '.$pageTitle.' ] Deleted...!');
					$message = Alert('success', 'Category [ '.$pageTitle.' ] deleted successfully...!');
				}
			}
		?>
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
          <div class="col-xs-8">
            <h3 class="header smaller lighter blue">
              <?php
			  if($_GET['type'] == 'User-Manual') echo "Employer User Manual Categories"; ?>
            </h3>
            <?=$message?>
            <div class="table-header"> Results for "All
              <?php
			  if($_GET['type'] == 'User-Manual') echo "Employer User Manual Categories"; ?>
              " </div>
            <div class="table-responsive">
              <table id="pages-view" class="table table-bordered table-hover datatable">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th class="hidden-480" width="50">Status</th>
                    <th class="hidden-480" width="115">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
					$sql = " SELECT * FROM `category` WHERE `type` = '".$_GET['type']."' order by `ordermap` ASC ";
                    $stmt = mysql_query($sql);
					$catParentOption='';
                    while( $rslt = mysql_fetch_array($stmt) ){
						$category_seperator = '';
						for($i=0;$i<$rslt['level'];$i++)
						{
							$category_seperator .= CATEGORY_SEPERATOR;
						}
						$category_seperator .= "&nbsp;";
						if( $rslt['level'] < 1 ) 
							$catParentOption .= ' <option value="'.$rslt['id'].'">'.$category_seperator.$rslt['title'].'</option> ';
						
						$TRID = ($rslt['level']==0)?$rslt['id']:$rslt['catid'];
						
						$TRID = ($rslt['catid']==0)?$rslt['id']:$rslt['catid']; ?>
                  <tr <?=($rslt['level']!=0)?'style="display:none;" class="cat-'.$TRID.'"':''?> >
                    <td><a href="javascript:;"  onClick='togglediv("tr.cat-<?=$TRID?>")'>
                      <?php
							echo $category_seperator;
							echo $rslt['title'];
						  ?>
                      </a> </td>
                    <td class="hidden-480"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['status']==1)?'success':'danger'?> dropdown-toggle">
                        <?=($rslt['status']==1)?' Active ':' Block '?>
                        <i class="icon-angle-down icon-on-right"></i> </button>
                        <ul class="dropdown-menu">
                          <li> <a href="category-view.php?type=<?=$_GET['type']?>&action=status&key=<?=base64_encode($rslt['id'].'|1')?>">Active</a> </li>
                          <li> <a href="category-view.php?type=<?=$_GET['type']?>&action=status&key=<?=base64_encode($rslt['id'].'|0')?>">Block</a> </li>
                        </ul>
                      </div></td>
                    <td ><?php
						if($rslt['image'])
						{
							$rslt['image'] = CATEGORY_IMAGE_DIRECTORY.$rslt['image'];
						}
						$data = array(
									'edit' => array('href'=>'javascript:;', 'js'=>' onclick="updateCat(\''.$rslt['id'].'\',\''.$rslt['title'].'\',\''.$rslt['catid'].'\',\''.$rslt['image'].'\')" '),
									'delete' => array('href'=>'category-view.php?type='.$_GET['type'].'&action=delete&id='.$rslt['id'], 'js'=>'')
						);
						echo TableAction($data);
					?></td>
                  </tr>
                  <? } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-xs-4">
            <form class="form-horizontal" role="form" method="post" id="categoryForm" name="categoryForm" enctype="multipart/form-data">
              <input type="hidden" name="mode" id="mode" value="insert" />
              <input type="hidden" name="type" id="type" value="<?php echo $_GET['type'] ? $_GET['type'] : 'Primary' ?>" />
              <input type="hidden" name="cid" id="cid"/>
              <div class="row">
                <div class="col-xs-12">
                  <h4 class="header green">Add New
                    <?php
					if($_GET['type'] == 'User-Manual') echo "User Manual Categories"; ?>
                  </h4>
                  <div class="row">
                    <?=$FormMessge?>
                    <div class="form-group">
                      <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Parent </label>
                      <div class="col-sm-8">
                        <select name="catid" id="catid" class="col-xs-10 col-sm-10">
                          <option value="0">Parent</option>
                          <?=$catParentOption?>
                        </select>
                      </div>
                    </div>
                    <div class="space-4"></div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Category Name </label>
                      <div class="col-sm-8">
                        <input type="text" id="title" name="title" placeholder="Category Title" class="col-xs-10 col-sm-10" value=""/>
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
<?php include('footer.php');?>
