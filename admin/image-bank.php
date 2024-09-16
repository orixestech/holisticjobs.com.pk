<?php include('header.php');
if($_GET['delete']=='true'){
	print_r($_REQUEST);
}
?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Image Bank</li>
    </ul>
    <!-- .breadcrumb -->
  </div>
  <div class="page-content">
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <h3 class="row header smaller lighter blue"> <span class="col-sm-7"> <i class="icon-th-large"></i> Image Type </span>
        </h3>
        <ul class="nav nav-pills"><?php
		  $stmt = query(" SELECT distinct `type` FROM `image_bank` WHERE 1 order by `type` ");
		  while($rslt=fetch($stmt)){ 
		  	$imgtype = $rslt['type'];
			$link = $path."admin/image-bank.php?imgtype=".$rslt['type'];
			if($_GET["imgtype"]==$rslt['type']) $class='class="active" '; else $class='' ;
			echo '<li '.$class.'> <a href="'.$link.'">'.ucwords($rslt['type']).'</a> </li>';
		  } ?>
        </ul>
      </div>
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
          <div class="col-xs-12">
            <h3 class="header smaller lighter blue">Image Bank</h3>
            <?=$message?>
            <div class="table-header"> Results for "All Images" </div>
            <div class="table-responsive">
              <table id="pages-view" class="table table-striped table-bordered table-hover ">
                <thead>
                  <tr>
                    <th>File Name</th>
                    <th width="150">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  	($_GET['imgtype']!='')? $imgtype = $_GET['imgtype'] : $imgtype = $imgtype;
                    $stmt = query(" SELECT * FROM `image_bank` WHERE `type` = '".$imgtype."' ");
                    while( $rslt = fetch($stmt) ){ ?>
                  <tr>
                    <td><img src="<?=$path.'uploads/'.$rslt['filename']?>" style="max-width:800px; padding:15px;" /></td>
                    <td><?php
                        	$data = array(
										'edit' => array('href'=>'image-bank.php?imgtype='.$imgtype.'&edit=true&code='.$rslt['id'], 'js'=>''),
										'delete' => array('href'=>'image-bank.php?imgtype='.$imgtype.'&delete=true&id='.$rslt['id'], 'js'=>'')
									);
							echo TableAction($data);
						?></td>
                  </tr>
                  <? } ?>
                </tbody>
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
<script>
var oTable1 = $('table#pages-view').dataTable(  );
</script>
<?php include('footer.php');?>
