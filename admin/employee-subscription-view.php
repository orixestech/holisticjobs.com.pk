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
          <li class="active">Subscription</li>
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
                <h3 class="header smaller lighter blue">Employee Subscription</h3>
                <div id="ajax-result"> </div>
                <div class="col-xs-12">
                  <div class="table-header"> Results for "All Employee Subscription Plans" <span style="float:right; margin-right:8px;"> <a href="#AddEmployeeSubscription" role="button" class="btn btn-success btn-sm green" data-toggle="modal"> Add New </a> </span> </div>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover ">
                      <?php
					$QUERYSTRING = '';
					$whereSQL = "where PlanModule = 'Employee' ";
					$limit = 15;
					(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
					$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
					$paging = getPaging('subscriptions',$whereSQL . " ORDER BY PlanTitle ",$limit,'employee-subscription-view.php','?'.$QUERYSTRING,$_REQUEST['pager']);
					//echo $paging[0];
					$rs_pages = mysql_query($paging[0]) or die($paging[0]);
					$row = mysql_num_rows( $rs_pages );
					$pagination = $paging[1];?>
                      <thead>
                        <tr>
                          <th width="85">Sr. No</th>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Duration</th>
                          <th>Monthly Fee</th>
                          <th>Total Subscriptions</th>
						  <th>Tools</th>
                          <th class="hide">Status</th>
                          <th class="hidden-480" width="85">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
					  if($row>0){
						while( $rslt = mysql_fetch_array($rs_pages) ){ $totalSub = total("SELECT * FROM `employee` WHERE `EmployeeSubscription` = '".$rslt["UID"]."' ");?>
                        <tr>
                          <td><?=$count?></td>
                          <td><?=$rslt['PlanTitle']?></td>
                          <td><?=$rslt['PlanDesc']?></td>
                          <td><?=round($rslt['PlanDays']/30, 0)?> Months</td>
                          <td>Rs.
                            <?=$rslt['PlanFee']?></td>
                          <td><a href="#<?=($totalSub>0)?'ViewTotalEmployeeSubscriptions':''?>" data-toggle="modal" data-uid="<?=$rslt["UID"]?>"><strong><?=$totalSub?></strong>  Subscriptions</a></td>
						  <td><a href="#ManageEmployeeAccess" class="btn btn-primary btn-xs blue" data-toggle="modal" data-uid="<?=$rslt["UID"]?>">Manage Access</a> 
                          <td class="hide"><div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['PlanStatus']=='Featured')?'success':'default'?> dropdown-toggle">
                              <?=$rslt['PlanStatus']?>
                              <i class="icon-angle-down icon-on-right"></i> </button>
                              <ul class="dropdown-menu">
                                <li><a href="employee-subscription-view.php?status=true&code=<?=base64_encode($rslt['UID']."|Normal")?>">Normal</a></li>
                                <li><a href="employee-subscription-view.php?status=true&code=<?=base64_encode($rslt['UID']."|Featured")?>">Featured</a></li>
                              </ul>
                            </div></td>
                          <td><?php
							$data = array(
										array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'#EditEmployeeSubscription', 'js'=>' role="button" class="green" data-toggle="modal" title="View Subscription" data-uid="'.$rslt["UID"].'" '),
										array('title'=>'<i class="icon-zoom-in bigger-130"></i>', 'href'=>'#ViewEmployeeSubscription', 'js'=>' role="button" class="blue" data-toggle="modal" title="View Subscription" data-uid="'.$rslt["UID"].'" '),								
										array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'employee-subscription-view.php?delete=true&id='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete" ')
		
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
    <?=GenModelBOX('ViewEmployeeSubscription', 'view-employee-subscription.php')?>
    <?=GenModelBOX('EditEmployeeSubscription', 'edit-employee-subscription.php')?>
    <?=GenModelBOX('AddEmployeeSubscription', 'add-employee-subscription.php')?>
    <?=GenModelBOX('ViewTotalEmployeeSubscriptions', 'view-total-employee-subscriptions.php')?>
	<?=GenModelBOX('ManageEmployeeAccess', 'manage-employee-access.php')?>
    <?php include('footer.php');?>
