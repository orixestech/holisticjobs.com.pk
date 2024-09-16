<?php include('header.php');

if($_GET["delete"]=='true' && $_GET["id"]){
	$pageTitle = GetData('PlanTitle','subscriptions','UID',$_GET["id"]);
	mysql_query(" DELETE FROM `subscriptions` WHERE `UID` = '".$_GET["id"]."' ");
	mysql_query(" DELETE FROM `subscription_access` WHERE `AccessSubID` = '".$_GET["id"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Plan Title [ '.$pageTitle.' ] Deleted...!');
		$message = Alert('success', 'Plan Title [ '.$pageTitle.' ] Deleted...!');
	}
}

if($_GET["status"]=='true' && $_GET["code"]){
	$status = base64_decode($_GET["code"]);
	$status = explode("|",$status);
	
	$pageTitle = GetData('PlanTitle','subscriptions','UID',$status[0]);
	$sql = " UPDATE  subscriptions SET PlanStatus = '".$status[1]."' WHERE `UID` = '".$status[0]."' ";
	mysql_query($sql);
	$num = mysql_affected_rows();
	if($num){
		Track('Plan [ '.$pageTitle.' ] Status Change ...!');
		$message = Alert('success', 'Plan [ '.$pageTitle.' ] Status Change ...!');
	}
}

 ?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Employer Subscription</li>
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
                <h3 class="header smaller lighter blue">Employer Subscription</h3>
                <div id="ajax-result"> </div>
                <div class="col-xs-12">
                  <div class="table-header"> Results for "All Employer Subscription Plans" <span style="float:right; margin-right:8px;"> <a href="#AddEmployerSubscription" role="button" class="btn btn-success btn-sm green" data-toggle="modal"> Add New </a> </span> </div>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover ">
                      <?php
					$QUERYSTRING = '';
					$whereSQL = "where PlanModule = 'Employer' ";
					$limit = 15;
					(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
					$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
					$paging = getPaging('subscriptions',$whereSQL . " ORDER BY PlanTitle ",$limit,'employer-subscription-view.php','?'.$QUERYSTRING,$_REQUEST['pager']);
					//echo $paging[0];
					$rs_pages = mysql_query($paging[0]) or die($paging[0]);
					$row = mysql_num_rows( $rs_pages );
					$pagination = $paging[1];?>
                      <thead>
                        <tr>
                          <th width="85">Sr. No</th>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Month</th>
                          <th>Fee</th>
                          <th>Total Subscriptions</th>
						  <th>Tools</th>
                          <th>Status</th>
                          <th class="hidden-480" width="85">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
					  if($row>0){
						while( $rslt = mysql_fetch_array($rs_pages) ){ $totalSub = total("SELECT * FROM `employer` WHERE `EmployerSubscription` = '".$rslt["UID"]."' ");?>
                        <tr>
                          <td><?=$count?></td>
                          <td><?=$rslt['PlanTitle']?></td>
                          <td><?=$rslt['PlanDesc']?></td>
                          <td><?=round($rslt['PlanDays']/30, 0)?> Months</td>
                          <td>Rs.
                            <?=$rslt['PlanFee']?></td>
                          <td><a href="#<?=($totalSub>0)?'ViewEmployerTotalSubscriptions':''?>" data-toggle="modal" data-uid="<?=$rslt["UID"]?>"><strong><?=$totalSub?></strong>  Subscriptions</a></td>
						  <td><a href="#ManageEmployerAccess" class="btn btn-primary btn-xs blue" data-toggle="modal" data-uid="<?=$rslt["UID"]?>">Manage Access</a> 
						  <a href="#Discount" class="btn btn-success btn-xs green" data-toggle="modal" data-uid="<?=$rslt["UID"]?>">Discount</a> </td>
                          <td><div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['PlanStatus']=='Featured')?'success':'default'?> dropdown-toggle">
                              <?=$rslt['PlanStatus']?>
                              <i class="icon-angle-down icon-on-right"></i> </button>
                              <ul class="dropdown-menu">
                                <li><a href="employer-subscription-view.php?status=true&code=<?=base64_encode($rslt['UID']."|Normal")?>">Normal</a></li>
                                <li><a href="employer-subscription-view.php?status=true&code=<?=base64_encode($rslt['UID']."|Featured")?>">Featured</a></li>
                              </ul>
                            </div></td>
                          <td><?php
							$data = array(
										array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'#EditEmployerSubscription', 'js'=>' role="button" class="green" data-toggle="modal" title="View Subscription" data-uid="'.$rslt["UID"].'" '),
										array('title'=>'<i class="icon-zoom-in bigger-130"></i>', 'href'=>'#ViewEmployerSubscription', 'js'=>' role="button" class="blue" data-toggle="modal" title="View Subscription" data-uid="'.$rslt["UID"].'" '),								
										array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'employer-subscription-view.php?delete=true&id='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete" ')
		
									);
							echo TableActions($data);?></td>
                        </tr>
                        <? $count++;
					  }
					} else {  ?>
                        <tr>
                          <th class="center" colspan="10">No Records Found.!</th>
                        </tr><?
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
    <?=GenModelBOX('ViewEmployerSubscription', 'view-employer-subscription.php')?>
    <?=GenModelBOX('EditEmployerSubscription', 'edit-employer-subscription.php')?>
    <?=GenModelBOX('AddEmployerSubscription', 'add-employer-subscription.php')?>
    <?=GenModelBOX('ViewEmployerTotalSubscriptions', 'view-total-employer-subscriptions.php')?>
	<?=GenModelBOX('ManageEmployerAccess', 'manage-employer-access.php')?>
	<?=GenModelBOX('Discount', 'discount.php')?>
    <?php include('footer.php');?>
