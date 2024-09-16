<?php include('header.php');?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li> <a href="#">Website</a> </li>
      <li class="active">Content Pages</li>
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
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <?php
          	if($_GET["delete"]=='true' && $_GET["id"]){
				$pageTitle = GetData('page_physical_name','contents','id',$_GET["id"]);
				mysql_query(" DELETE FROM  `contents` WHERE `contents`.`id` = '".$_GET["id"]."' ");
				$num = mysql_affected_rows();
				if($num){
					Track('Content Page [ '.$pageTitle.' ] Deleted...!');
					$message = Alert('success', 'Content Page [ '.$pageTitle.' ] Deleted...!');
				}
			}
			
			if($_GET["type"]=='status' && $_GET["key"]!=''){
				$key = explode("|",base64_decode($_GET["key"]));
				$status = $key[1];
				$id = $key[0];
				$pageTitle = GetData('page_physical_name','contents','`id`',$id);
				mysql_query(" UPDATE `contents` SET `status` = '".$status."' WHERE `id` = '".$id."'; ");
				$message = Alert('success', 'Content Page [ '.$pageTitle.' ] Status Change to '.ucwords($status).'...!');
			}
		  ?>
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
          <div class="col-xs-12">
            <h3 class="header smaller lighter blue">Content Pages</h3>
            <?=$message?>
            <div class="table-header"> Results for "All Content Pages" <span style="float:right; margin-right:8px;"> <a href="<?=$path?>admin/page.php" class="btn btn-success btn-sm">Add New</a> </span> </div>
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th class="center">Sr. No</th>
                    <th>Link</th>
                    <th>Content Title</th>
                    <th>Last Modify</th>
                    <th class="hidden-480" width="50">Status</th>
                    <th class="hidden-480" width="115">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
					 $limit = 15;
					(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
					$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
					$paging = getPaging('contents',"  ORDER BY page_modify_date DESC ",$limit,'pages-view.php','?',$_REQUEST['pager']);
					$rs_pages = mysql_query($paging[0]) or die($paging[0]);
					$row = mysql_num_rows( $rs_pages );
					$pagination = $paging[1];
					while( $rslt = mysql_fetch_array($rs_pages) ){ $cnt++;
					  $rslt['page_physical_name'] = strtolower($rslt['page_physical_name']);?>
                  <tr>
                    <td class="center"><?=$count?></td>
                    <td><a href="<?=$path."page/".$rslt['page_physical_name']?>" target="_blank">
                      <?=$rslt['page_physical_name']?>
                      </a></td>
                    <td><?=$rslt['content_title']?></td>
                    <td class="hidden-480"><?=date('d M, Y h:i A',strtotime($rslt['page_modify_date']))?></td>
                    <td class="hidden-480"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['status']=='active')?'success':'danger'?> dropdown-toggle">
                        <?=ucwords($rslt['status'])?>
                        <i class="icon-angle-down icon-on-right"></i> </button>
                        <ul class="dropdown-menu">
                          <li> <a href="pages-view.php?type=status&key=<?=base64_encode($rslt['id'].'|active')?>">Active</a> </li>
                          <li> <a href="pages-view.php?type=status&key=<?=base64_encode($rslt['id'].'|block')?>">Block</a> </li>
                        </ul>
                      </div></td>
                    <td><?php
							$data = array(
								array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'page.php?mode=update&code='.$rslt['id'], 'js'=>' role="button" class="green" title="Edit Page"'),
								array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'pages-view.php?delete=true&id='.$rslt['id'], 'js'=>' class="red"  title="Delete Page" ')
							);
							echo TableActions($data);
						?></td>
                  </tr>
                  <? $count++;
				  } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="center" colspan="9"><div class="pull-right">
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
