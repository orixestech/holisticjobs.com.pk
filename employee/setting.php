<?php include('header.php');?>
<?php
if($_POST){
	//print_r($_POST);
	$stmt = mysql_query("SELECT * FROM `admin_setting` WHERE 1 order by `option_id`");
	while($rslt = mysql_fetch_array($stmt)){
		//if($_POST[$rslt['option_name']]){
			$notAllow = array('site_online');
			if( in_array($rslt['option_name'],$notAllow) ){
				if($_POST['site_online']) $siteOnline = 'on'; else $siteOnline = 'off';
				$option_id = GetData('option_id','admin_setting','option_name','site_online');
				mysql_query(" UPDATE `admin_setting` SET `option_value` = '".$siteOnline."' WHERE `option_id` = '".$option_id."'; ");
			} else {
				$option_id = GetData('option_id','admin_setting','option_name',$rslt['option_name']);
				mysql_query(" UPDATE `admin_setting` SET `option_value` = '".$_POST[$rslt['option_name']]."' WHERE `option_id` = '".$option_id."'; ");
			}
		//}	
	}
	$FormMessge = Alert('success', 'Admin Settings Change...!');
}


?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="#">Home</a> </li>
      <li class="active">Settings</li>
    </ul>
    <!-- .breadcrumb -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Settings <small> <i class="icon-double-angle-right"></i> Modify your Website Settings </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" method="post">
      <div class="row">
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <h4 class="header green">Admin Settings</h4>
          <div class="row">
            <?php
          	$stmt = mysql_query("SELECT * FROM `admin_setting` WHERE 1 order by `option_id`");
			while($rslt = mysql_fetch_array($stmt)){
			($rslt['option_name']=='site_online') ? $site_online=$rslt['option_value']:'';
			$notAllow = array('site_online');
			if( in_array($rslt['option_name'],$notAllow) ){
				//echo "xxxxxxxxxxxxxxxxxxxxxxxx";
			} else { ?>
				<div class="form-group">
				  <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
				  <?=$rslt['options_title']?>
				  </label>
				  <div class="col-sm-9"><?php
				  if(strlen($rslt['option_value']) > 250) {
				  	?><textarea id="<?=$rslt['option_name']?>" name="<?=$rslt['option_name']?>" class="col-xs-10 col-sm-5" rows="8"><?=$rslt['option_value']?></textarea><?
				  } else {
				  	?><input type="text" id="<?=$rslt['option_name']?>" name="<?=$rslt['option_name']?>" value="<?=$rslt['option_value']?>" class="col-xs-10 col-sm-5" /><?
				  } ?>
					
				  </div>
				</div>
				<div class="space-4"></div>
			<? } 
			} ?>
			<div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
              Site Live
              </label>
              <div class="col-sm-9">
                <label><input name="site_online" id="site_online" class="ace ace-switch" type="checkbox" value="<?=$site_online?>" <?=($site_online=='on')?'checked':''?> /><span class="lbl"></span></label>
              </div>
            </div>
            <div class="space-4"></div>
			
			
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
