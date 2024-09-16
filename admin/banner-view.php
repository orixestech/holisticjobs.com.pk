<?php include('header.php');?>
<?php
if($_GET["delete"]=='true' && $_GET["id"]){
	//Delete old file.
	$target_dir = BANNER_IMAGE_DIRECTORY;
	$existing_image = GetData('BannerImage','banners','UID',$_GET["id"]);
	@unlink($target_dir.$existing_image);

	$pageTitle = GetData('BannerHeading','banners','UID',$_GET["id"]);
	mysql_query(" DELETE FROM  `banners` WHERE `UID` = '".$_GET["id"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Job Title [ '.$pageTitle.' ] Deleted...!');
		$message = Alert('success', 'Job Title [ '.$pageTitle.' ] Deleted...!');
	}
}




?>
<link href="<?=$path?>css/paging.css" rel="stylesheet">
<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Banners</li>
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
            <h3 class="header smaller lighter blue">Banners</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="table-header"> Results for "All Banners" <span style="float:right; margin-right:8px;"> <a href="<?=$path?>admin/banner.php" class="btn btn-success btn-sm">Add New</a> </span> </div>
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover ">
                <?php
				$limit = 15;
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('banners'," ORDER BY `UID` DESC ",$limit,'banner-view.php','?',$_REQUEST['pager']);
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
                <thead>
                  <tr>
                    <th width="85">Sr. No</th>
					<th>Heading</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th class="hidden-480" width="85">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
			  if($row>0){
				while( $rslt = mysql_fetch_array($rs_pages) ){
			  ?>
                  <tr id="row_<?=$rslt['fq_id']?>">
                    <td><?=$count?></td>
					<td><?=$rslt['BannerHeading']?></td>
                    <td><?=$rslt['BannerDesc']?></td>
                    <td width="200"><img src="<?=$path?>images/banners/<?=$rslt['BannerImage']?>" height="150" /></td>
                    <td><?php
					$data = array(
								array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'banner.php?mode=update&code='.$rslt['UID'], 'js'=>' role="button" class="green" title="Edit Banner"'),
								array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'banner-view.php?delete=true&id='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete Banner" ')
							);
					echo TableActions($data);?>
                    </td>
                  </tr>
                  <? $count++;
				  }
				} else {  ?>
                  <tr>
                    <th class="center" colspan="10">No Records Found.!</th>
                  </tr>
                  <?
				  } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="center" colspan="10"><div class="pull-right">
                        <?=$pagination?>
                      </div></th>
                  </tr>
                </tfoot>
              </table>
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
<?php include('footer.php');?>
