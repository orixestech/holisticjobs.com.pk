<?php include('header.php'); // include("../site_theme_functions.php"); ?>
<?php
if($_POST){
$nowDate = date("Y-m-d h:i:s");
define ("MAX_SIZE","9900");
$_POST['title'] = str_replace("_"," ",$_POST['title']);
$_POST['description'] = str_replace("_"," ",$_POST['description']);
$errors=0;
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$image = $_FILES["file"]["name"];
	$uploadedfile = $_FILES['file']['tmp_name'];
	if ($image) 
	{ 
	$filename = stripslashes($_FILES['file']['name']);
	$extension = getExtension($filename);
	$extension = strtolower($extension);
	if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
	{
	$change='<div class="msgdiv">Unknown Image extension </div> ';
	$errors=1;
	}
	else
	{
	$size=filesize($_FILES['file']['tmp_name']);
	if ($size > MAX_SIZE*1024)
	{
	$change='<div class="msgdiv">You have exceeded the size limit!</div> ';
	$errors=1;
	}
	if($extension=="jpg" || $extension=="jpeg" )
	{
	$uploadedfile = $_FILES['file']['tmp_name'];
	$src = imagecreatefromjpeg($uploadedfile);
	}
	else if($extension=="png")
	{
	$uploadedfile = $_FILES['file']['tmp_name'];
	$src = imagecreatefrompng($uploadedfile);
	}
	else 
	{
	$src = imagecreatefromgif($uploadedfile);
	}
	echo $scr;
	list($width,$height)=getimagesize($uploadedfile);
	$newwidth1=600;
	$newheight1=($height/$width)*$newwidth1;
	$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
	imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
	$filename1 = "thumb_".time().".".$extension;
	imagejpeg($tmp1,"../images/thumb/".$filename1,100);
	imagedestroy($src);
	imagedestroy($tmp1);
	$thumb_url = $path."images/thumb/".$filename1;
	if($_GET["mode"]=="update"){ mysql_query( " update `videos` set `thumb_url` = '".$thumb_url."' WHERE `video_id` = '".$_GET["code"]."' " ); }
	}
	}
}	

if($_GET["mode"]=="update"){
	if($_POST['server']=='dailymotion'){
		//$_POST['thumb_url'] = video_thumb('large',$_POST['video_embed_code']); 
	}
	$run = FormData('videos', 'update', $_POST, " video_id = '".$_GET["code"]."' ", $view=false );
	$FormMessge = Alert('success', 'Video Updated...!');
} else {
	$_POST['status'] = 'active';
	if($_POST['server']=='dailymotion'){
	//$thumb_url = video_thumb('large',$_POST['video_embed_code']);
	//$_POST['thumb_url'] = $thumb_url;
	} else {
	//$_POST['thumb_url'] = $thumb_url;
	}
	//echo $_POST['thumb_url'];
	$run = FormData('videos', 'insert', $_POST, "", $view=false );
	$FormMessge = Alert('success', 'New video add successfully...!');
}
}
if($_GET["mode"]=="update"){
$stmt = mysql_query(" SELECT * FROM `videos` WHERE `video_id` = '".$_GET["code"]."' ");
$PAGE = mysql_fetch_array($stmt);
$PAGE['title'] = str_replace("_"," ",$PAGE['title']);
$PAGE['description'] = str_replace("_"," ",$PAGE['description']);
} ?>
<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">
        <?=($_GET["id"])?'Modify':'Add New'?>
        Video </li>
    </ul>
    <!-- .breadcrumb -->
    <!-- #nav-search -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1>
        <?=($_GET["id"])?'Modify':'Add New'?>
        Page <small> <i class="icon-double-angle-right"></i> Add or Update Website Videos </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <h4 class="header green">SEO</h4>
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title Tag </label>
              <div class="col-sm-9">
                <input type="text" id="page_title" name="seo_title" placeholder="Title Tag" class="col-xs-10 col-sm-5" value="<?=$PAGE['seo_title']?>" />
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta Keywords </label>
              <div class="col-sm-9">
                <textarea id="keywords" style="height:100px;" name="seo_keywords" placeholder="Meta Keywords" class="col-xs-10 col-sm-5"><?=$PAGE['seo_keywords']?>
</textarea>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta Description </label>
              <div class="col-sm-9">
                <textarea id="description" style="height:100px;" name="seo_description" placeholder="Meta Description" class="col-xs-10 col-sm-5"><?=$PAGE['seo_description']?>
</textarea>
              </div>
            </div>
          </div>
          <h4 class="header green">Vedio Detail</h4>
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title </label>
              <div class="col-sm-9">
                <input type="text" id="title" name="title" placeholder="Video Title" class="col-xs-10 col-sm-5" value="<?=$PAGE['title']?>"/>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Category <br />
              <small>comma separated</small> </label>
              <div class="col-sm-9">
                <select name="category" id="category" class="col-xs-10 col-sm-5">
                  <option value="0">Parent</option>
                  <?php
$stmt = mysql_query(" SELECT * FROM `category` WHERE catid = 0 order by `title`  ");
while($rslt = mysql_fetch_array($stmt) ){
?>
                  <option value="<?=$rslt['id']?>" <?=( $PAGE['category'] == $rslt['id'])?' selected ':''?>>
                  <?=$rslt['title']?>
                  </option>
                  <?
$stmt1 = mysql_query(" SELECT * FROM `category` WHERE catid = '".$rslt['id']."' order by `title`  ");
while($rslt1 = mysql_fetch_array($stmt1) ){
?>
                  <option value="<?=$rslt1['id']?>" <?=( $PAGE['category'] == $rslt1['id'])?' selected ':''?>>
                  <?=str_repeat("&nbsp;",5)?>
                  &raquo;
                  <?=$rslt1['title']?>
                  </option>
                  <?
}
} ?>
                </select>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tags <br />
              <small>comma separated</small> </label>
              <div class="col-sm-9">
                <textarea id="tags" name="tags" style="height:100px;" placeholder="Tags comma separated" class="col-xs-10 col-sm-5"><?=$PAGE['tags']?>
</textarea>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Video Embed Code </label>
              <div class="col-sm-9">
                <textarea id="video_embed_code" name="video_embed_code" style="height:100px;" placeholder="Video Embed Code" class="col-xs-10 col-sm-5"><?=$PAGE['video_embed_code']?>
</textarea>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Ads Embed Code </label>
              <div class="col-sm-9">
                <textarea id="ads_embed_code" name="ads_embed_code" style="height:100px;" placeholder="Ads Embed Code" class="col-xs-10 col-sm-5"><?=$PAGE['ads_embed_code']?>
</textarea>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Video Description </label>
              <div class="col-sm-7">
                <textarea class="ContentEditor" id="description" name="description" style="height: 260px;"><?=$PAGE['description']?>
</textarea>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Server </label>
              <div class="col-sm-9">
                <select name="server" id="server" class="col-xs-10 col-sm-5">
                  <option value="other" <?=($PAGE['server']=='other')?'selected':''?>>Other</option>
                  <option value="dailymotion" <?=($PAGE['server']=='dailymotion')?'selected':''?>>Daily Motion</option>
                </select>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Thumbnail </label>
              <div class="col-sm-9">
                <input type="file" id="file" name="file" />
                <?php
if($_GET["mode"]=="update"){
$thumb = $path.$PAGE['thumb_url'];
if($thumb==''){ $thumb = $path.'images/no-available-image.png';  } ?>
                <img src="<?=$thumb?>" class="col-sm-3" style="border:1px solid #CCCCCC;padding:3px;margin-top:5px;height:150px; width:300px;" />
                <?
} ?>
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
