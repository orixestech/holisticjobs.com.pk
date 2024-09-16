<?php include('header.php');?>

<?php
if($_POST){
	$contents = $_POST;
	$nowDate = date("Y-m-d h:i:s");
	$contents['page_physical_name'] = str_replace(" ","-",$contents['page_physical_name']);
	$contents['robots'] = 'index,follow';
	$contents['page_physical_name'] = preg_replace('/[^\da-z\-.]/i', '',$contents['page_physical_name']);
	$contents['page_modify_date'] = $nowDate;
	
	if($_GET["mode"]=="update"){
		$run = FormData('contents', 'update', $contents, " id = '".$_GET["code"]."' ", $view=false );
		$FormMessge = Alert('success', 'Page Updated...!');
	} else {
		$contents['status'] = 'active';
		$contents['page_create_date'] = $nowDate;
		
		 
		$run = FormData('contents', 'insert', $contents, "", $view=false );
		$FormMessge = Alert('success', 'New Page Created...!');
	}

}
	
if($_GET["mode"]=="update"){
	$stmt = mysql_query(" SELECT * FROM `contents` WHERE `id` = '".$_GET["code"]."' ");
	$PAGE = mysql_fetch_array($stmt);
} ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li> <a href="#">Web Site</a> </li>
      <li class="active">
        <?=($_GET["id"])?'Modify':'Add New'?> Page
	  </li>
    </ul>
    <!-- .breadcrumb -->
    <div class="nav-search" id="nav-search">
      <form class="form-search">
        <span class="input-icon">
        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
        <i class="icon-search nav-search-icon"></i> </span>
      </form>
    </div>
    <!-- #nav-search -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1>
        <?=($_GET["id"])?'Modify':'Add New'?>
        Page <small> <i class="icon-double-angle-right"></i> Add or Update Website Pages </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" method="post">
      <div class="row">
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <h4 class="header green">SEO</h4>
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Page URL </label>
              <div class="col-sm-9">
                <input type="text" id="page_physical_name" name="page_physical_name" placeholder="Page URL" class="col-xs-10 col-sm-5" value="<?=$PAGE['page_physical_name']?>" />
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title Tag </label>
              <div class="col-sm-9">
                <input type="text" id="page_title" name="page_title" placeholder="Title Tag" class="col-xs-10 col-sm-5" value="<?=$PAGE['page_title']?>" />
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta Keywords </label>
              <div class="col-sm-9">
                <textarea id="keywords" name="keywords" placeholder="Meta Keywords" class="col-xs-10 col-sm-5"><?=$PAGE['keywords']?></textarea>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Meta Description </label>
              <div class="col-sm-9">
                <textarea id="description" name="description" placeholder="Meta Description" class="col-xs-10 col-sm-5"><?=$PAGE['description']?></textarea>
              </div>
            </div>
          </div>
          <h4 class="header green">Page Content</h4>
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title </label>
              <div class="col-sm-9">
                <input type="text" id="content_title" name="content_title" placeholder="Content Title" class="col-xs-10 col-sm-5" value="<?=$PAGE['content_title']?>"/>
              </div>
            </div>
            <div class="space-4"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description </label>
              <div class="col-sm-7">
                <textarea class="ContentEditor" id="content_desc" name="content_desc" style="height: 260px;"><?=$PAGE['content_desc']?></textarea>
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
