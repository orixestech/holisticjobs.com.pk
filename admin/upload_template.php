<?php
	include('header.php');
	
	//This is our secret file to find in zip archive.
	$secret_file = 'index.html';
	
	//This is the demo URL of the template.
	$demo_url = "http://www.w3schools.com";
	
	//echo "<pre>";print_r($_POST);echo "</pre>";
	//echo "<pre>";print_r($_FILES);echo "</pre>";

	if($_POST)
	{
		if($_FILES["main_files"]["name"])
		{
			$uploadOk = 1;
			
			$target_dir = TEMPLATE_DIRECTORY;
			$target_file = $target_dir . basename($_FILES["main_files"]["name"]);
			//echo "target_file = " . $target_file . "<br />";
			
			$path_parts = pathinfo($target_file);
			//echo "<pre>";print_r($path_parts);echo "</pre>";
			
			$fileName = $path_parts['filename'];
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
			if($fileType != "zip" && $uploadOk == 1) {
				$FormMessge = Alert('error', 'Sorry, only zip files are allowed.');
				$uploadOk = 0;
			}
			
			// Check file size
			if ($_FILES["main_files"]["size"] > TEMPLATE_ALLOWED_FILESIZE && $uploadOk == 1) {
				$FormMessge = Alert('error', 'Sorry, your file is too large.');
				$uploadOk = 0;
			}
			
			//Open Zip.
			if($uploadOk == 1)
			{
				$za = new ZipArchive();
				$za->open($_FILES["main_files"]["tmp_name"]);
				//echo '<pre>'; print_r($za); echo '</pre>';
				
				//Locate file. If file found, search for our secret hash inside.
				if($za->locateName($secret_file, ZipArchive::FL_NOCASE))
				{
					//echo 'found: ' . $secret_file . '<br />';
					
					//Get file contents.
					$file_contents = $za->getFromName($secret_file);
					//echo "file_contents = " . $file_contents . '<br />';
					//$fp = fopen('log.txt', 'w'); fwrite($fp, $file_contents); fclose($fp);
					
					
					if (preg_match("/<!--(.{64})-->/i", $file_contents, $matches))
					{
						//echo "A match was found.";
						//echo '<pre>'; print_r($matches); echo '</pre>';
						
						//Get secret hash.
						$file_secret_hash = $matches[1];
						//echo "file_secret_hash = " . $file_secret_hash . '<br />';
						
						$query = "SELECT secret_hash FROM templates WHERE secret_hash='$file_secret_hash' LIMIT 1";
						//echo "query = " . $query . '<br />';
						
						$stmt = mysql_query($query);
						$rslt = mysql_fetch_array($stmt);
						//echo $rslt['secret_hash'] . '<br />';
						
						if($rslt['secret_hash'])
						{
							$FormMessge = Alert('error', 'Sorry, the template you are uploading is duplicate.');
							$uploadOk = 0;
						}
					}
				}
				
				$za->close();
			}
			
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["main_files"]["tmp_name"], $target_file))
				{
					$append_column = ", `main_files`";
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
			$query = 
			"INSERT INTO `templates` 
				(category_id, title, `status` $append_column) 
			  VALUES 
				('0', 'New Template', 'Awaiting Approval' {$append_value});";
			//echo "query = " . $query . "<br />";
			
			mysql_query($query);
			
			$inserted_id = mysql_insert_id();
			
			$secret_hash = $inserted_id + 10000000;
			$secret_hash = hash('sha256', $secret_hash);
			
			$updateSelf = "UPDATE `templates` SET 
						secret_hash = '$secret_hash'
						WHERE id = $inserted_id";
			//echo "updateSelf = " . $updateSelf . "<br />";
			
			mysql_query($updateSelf);
			
			
			//Open the zip archive and write the new hash.
			$za = new ZipArchive();
			$za->open($target_file);
			//echo '<pre>'; print_r($za); echo '</pre>';
			
			//Locate file. If file found, search for our secret hash inside.
			if($za->locateName($secret_file, ZipArchive::FL_NOCASE))
			{
				//echo 'found: ' . $secret_file . '<br />';
				
				//Get file contents.
				$file_contents = $za->getFromName($secret_file);
				//echo "file_contents = " . $file_contents . '<br />';
				//$fp = fopen('log.txt', 'w'); fwrite($fp, $file_contents); fclose($fp);
				
				
				if (preg_match("/<!--.{64}-->/i", $file_contents, $matches))
				{
					//echo "A match was found.";
					//echo '<pre>'; print_r($matches); echo '</pre>';
					
					//Delete this hash.
					$file_contents = preg_replace("/<!--.{64}-->/i", "", $file_contents);
					//echo "file_contents = " . $file_contents . '<br />';
					//$fp = fopen('log_after.txt', 'w'); fwrite($fp, $file_contents); fclose($fp);
				}
				
				//Now write the hash.
				$to_write = "$file_contents<!--$secret_hash-->";
				$za->addFromString($secret_file, $to_write);
			}
			
			//If file not found, create a new file with secret hash and add it to the archive.
			else
			{
				$to_write = "<html><head></head><body><iframe src='".$demo_url."'></iframe></body></html><!--$secret_hash-->";
				$za->addFromString($secret_file, $to_write);
			}
			
			$za->close();
			
			$FormMessge = Alert('success', 'New template added successfully...!');
		}
	}

?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Templates</li>
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
            <h3 class="header smaller lighter blue">Templates</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="table-header"> Results for "All Templates" 
			<span style="float:right; margin-right:8px;">
			<button type="button" class="btn btn-success btn-sm" onclick="location.href='user-items.php'">Add New</button>
			</span>
			</div>
            <div class="table-responsive">
              <table id="pages-view" class="table table-bordered table-hover datatable">
                <thead>
                  <tr>
                    <th>Upload Date</th>
					<th>Member Email</th>
					<th>Title</th>
					<th>Preview Image</th>
                    <!--<th>Download</th>-->
					<th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $stmt = mysql_query(" SELECT * FROM `templates` order by `id` DESC ");
                    while( $rslt = mysql_fetch_array($stmt) )
					{
					?>
						<tr>
						  <td>
						  <?php echo date("d M, Y i:h A",strtotime($rslt['created'])); ?>
						  </td>
						  <td>
						  <?php echo GetData('email','members','id',$rslt['userid']); ?>
						  </td>
						  <td>
						  <?php echo $rslt['title']; ?>
						  </td>
						  <td>
						  <?php echo $rslt['theme_preview']; ?>
						  </td>
						  <!--<td>
						  <a href="<?php echo $path . "uploads/" . $rslt['main_files']; ?>"><?php echo $rslt['main_files']; ?></a>
						  </td>-->
						  <td>
						  <div class="btn-group" id="template_<?=$rslt['id']?>">
							<button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['status']=='Approved')?'success':'danger'?> dropdown-toggle">
							<?php echo $rslt['status']; ?>
							<i class="icon-angle-down icon-on-right"></i>
							</button>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0);" onclick="openStatusTemplate(<?=$rslt['id']?>, 'Approved');">Approved</a></li>
								<li><a href="javascript:void(0);" onclick="openStatusTemplate(<?=$rslt['id']?>, 'Blocked');">Block</a></li>
							</ul>
			
						</div>
						  
						  </td>
						</tr>
                <? } ?>
                </tbody>
              </table>
            </div>
          </div>
		  
          <!--<div class="col-xs-4">
            <form class="form-horizontal" role="form" method="post" id="templateForm" name="templateForm" enctype="multipart/form-data">
			<input type="hidden" name="mode" id="mode" value="insert" />
			<input type="hidden" name="tid" id="tid"/>
              <div class="row">
                <div class="col-xs-12">
                  <h4 class="header green">Add New Template</h4>
                  <div class="row">
				  <?php //echo $FormMessge?>
                    <!--<div class="form-group">
                      <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Category </label>
                      <div class="col-sm-8">
                        <select name="catid" id="catid" class="col-xs-10 col-sm-10">
                          <option value="0">Category</option>
                          <?php //echo $catParentOption ?>
                        </select>
                      </div>
                    </div>
                    <div class="space-4"></div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Template Title </label>
                      <div class="col-sm-8">
                        <input type="text" id="title" name="title" placeholder="Template Title" class="col-xs-10 col-sm-10" value=""/>
                      </div>
                    </div>
					<div class="space-4"></div>--
					<div class="form-group">
                      <label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Template </label>
                      <div class="col-sm-8">
						<img id="existing_image" src="" style="display:none; width:50px; height:50px; margin-bottom:10px;" />
                        <input type="file" id="main_files" name="main_files" class="col-xs-10 col-sm-10" />
                      </div>
                    </div>
                  </div>
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <button type="submit" class="btn btn-info"> <i class="icon-ok bigger-110"></i> Submit </button>
                      <button type="reset" class="btn"> <i class="icon-undo bigger-110"></i> Reset </button>
                    </div>
                  </div>
                  <!-- PAGE CONTENT ENDS --
                </div>
                <!-- /.col --
              </div>
              <!-- /.row --
            </form>
          </div>-->
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

<script>
function openStatusTemplate(template_id, status)
{
	form_data = "action=template_status&template_id="+template_id+"&status="+status;
	//alert(form_data);
	
	$.ajax({
		cache: false, 
		type: "POST",
		url: "template-ajax.php",
		data: form_data,
		dataType : 'json',
		async: false,
		success: function(data){
			//alert(data.status);
			
			if(data.status == 'success')
			{
				$("#ajax-result").html(data.data);
				setTimeout("window.location = location.href;", 1500);
				//updateMembersGrid();
			}
		},
		error: function(){
			//alert('Error Loading AJAX Request...!');
		}
	});
}

</script>


<?php include('footer.php');?>