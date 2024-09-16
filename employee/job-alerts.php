<?php include('header.php');
if($_GET["delete"]=='true' && $_GET["id"]){
	$pageTitle = GetData('AlertTitle','jobs_alerts','UID',$_GET["id"]);
	mysql_query(" DELETE FROM `jobs_alerts` WHERE `jobs_alerts`.`UID` = '".$_GET["id"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Job Alert [ '.$pageTitle.' ] Deleted...!');
		$message = Alert('success', 'Job Alert [ '.$pageTitle.' ] Deleted...!');
	}
}

?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">My Job Alerts</li>
    </ul>
    <div class="pull-right" id="SubscriptionExpireStatus">
      <?=$SubscriptionExpireStatus?>
    </div>
    <!-- .breadcrumb --> 
  </div>
  <div class="page-content"> 
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <div class="col-xs-12">
          <h3 class="header smaller lighter blue">My Job Alerts</h3>
          <?php $ACCESS = CheckSubAccess('job-alerts', 'employee');
		  if($ACCESS['access']=='false'){ ?>
          <div class="well">
            <h4 class="red smaller lighter">Access Denied..!</h4>
            You don't have access on this feature, please update your subscription for <strong>"Job Alerts"</strong>. </div>
          <?php
		  } else { ?>
          <div id="ajax-result">
            <?=$message?>
          </div>
          <div class="table-header"> Results for "All Job Alerts"
            <div class="widget-toolbar no-border pull-right">
              <div class="pull-right"> <a href="#AddJobAlerts" id="AddJobAlertsBtn" data-toggle="modal" role="button" class="btn btn-success btn-sm green"> Add New </a> </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ">
              <?php
				$limit = 15;
				$whereSQL = " Where AlertEmployeeUID = '".$_SESSION['EmployeeUID']."' ";
				$select = ' * ';
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('jobs_alerts',$whereSQL . " ORDER BY `SystemDate` DESC ",$limit,'job-alerts.php','?'.$QUERYSTRING,$_REQUEST['pager'],$select);
				//echo $paging[0];
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
              <thead>
                <tr>
                  <th width="6%">Sr. No</th>
                  <th>Alert Title</th>
                  <th>Company</th>
                  <th>City</th>
                  <th>Designation</th>
                  <th>Status</th>
                  <th width="6%">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
				  if($row>0){
					  while( $rslt = mysql_fetch_array($rs_pages) ){ ?>
                <tr>
                  <td><?=$count?></td>
                  <td><?=$rslt['AlertTitle']?></td>
                  <td><?=($rslt['AlertCompany']==0)?'---':GetEmployer('EmployerCompany', $rslt['AlertCompany'])?></td>
                  <td><?=($rslt["AlertArea"]==0)?'---':optionVal($rslt["AlertArea"])?></td>
                  <td><?=($rslt["AlertDesignation"]==0)?'---':optionVal($rslt["AlertDesignation"])?></td>
                  <td><label class="inline">
                      <input id="id-button-borders" checked="" type="checkbox" class="ace ace-switch ace-switch-5" />
                      <span class="lbl"></span> </label></td>
                  <td><?php
							$data = array(
										array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'#EditJobAlerts', 'js'=>' role="button" class="green" title="Edit Job Alerts" data-toggle="modal" data-uid="'.$rslt["UID"].'" '),
										array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'job-alerts.php?delete=true&id='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete" ')
										);
							echo TableActions($data);?></td>
                </tr>
                <? $count++;
					  }
				  } else {  ?>
                <tr>
                  <th class="center" colspan="7">No Records Found!</th>
                </tr>
                <?
				  } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th class="center" colspan="7"><div class="pull-right">
                      <?=$pagination?>
                    </div></th>
                </tr>
              </tfoot>
            </table>
          </div>
          <?php
		  }?>
        </div>
      </div>
      <!-- /.col --> 
    </div>
    <!-- /.row --> 
  </div>
  <!-- /.page-content --> 
</div>
<!-- /.main-content -->
<?=GenModelBOX('EditJobAlerts', 'edit-job-alerts.php')?>
<?=GenModelBOX('AddJobAlerts', 'add-job-alerts.php')?>
<script type="application/javascript">
	setTimeout(function() { 
		$("#AddJobAlertsBtn").click();
	}, 1500 );
</script>
<?php include('footer.php');?>
