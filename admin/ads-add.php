<?php include('header.php');?>
<?php
if($_POST){
	foreach($_POST as $key=>$val){
		mysql_query(" UPDATE `site_ads` SET `ads_embed_code` = '".mysql_real_escape_string($val)."' WHERE ads_type = '".$key."' limit 1");
	}
	$FormMessge = Alert('success', 'Ads Updated...!');
} ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active"> Ads Managment </li>
    </ul>
    <!-- .breadcrumb -->
    <!-- #nav-search -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Ads Managment </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" method="post">
      <div class="row">
        <div class="col-xs-12">
          <div class="row"> <?php
		  $stmt = mysql_query( " SELECT * FROM `site_ads` WHERE 1 order by `ads_id` " );
		  while( $rslt = mysql_fetch_array( $stmt ) ){ ?>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
              <?=$rslt['ads_title']?>
              </label>
              <div class="col-sm-9">
                <textarea id="<?=$rslt['ads_type']?>" name="<?=$rslt['ads_type']?>" placeholder="<?=$rslt['ads_title']?>" class="col-xs-10 col-sm-5" rows="10"><?=$rslt['ads_embed_code']?>
</textarea>
              </div>
            </div>
            <div class="space-4"></div>
            <?php } ?>
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
