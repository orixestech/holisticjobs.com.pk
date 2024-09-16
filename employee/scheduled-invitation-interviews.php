<?php include('header.php');?>
<title>Scheduled Interviews</title>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Scheduled Invitation Interviews</li>
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
          <h3 class="header smaller lighter blue">Scheduled Invitation Interviews</h3>
		  <?php $ACCESS = CheckSubAccess('applying-for-job', 'employee');
		//  if($ACCESS['access']=='false'){ ?>
			  <!--<div class="well">
				<h4 class="red smaller lighter">Access Denied..!</h4>
				You don't have access on this feature, please update your subscription for <strong>"Applying for Job"</strong>.
			  </div>-->
		  <?php
		//  } else { ?>
		  
			  <div id="ajax-result">
				<?=$message?>
			  </div>
			  <div class="col-xs-12">
				<div class="widget-box collapsed hide">
				  <div class="widget-header">
					<h4>Filter Records</h4>
					<div class="widget-toolbar"> <a href="#" data-action="collapse"> <i class="1 icon-chevron-up bigger-125"></i> </a> </div>
				  </div>
				  <div class="widget-body">
					<div class="widget-main">
					  <form class="form-inline" id="admin-meeting-filter-form" method="get">
						<div class="col-xs-3 col-sm-3">
						  <label> Job Title</label>
						  <input class="form-control " type="text" name="JobTitle" id="JobTitle"/>
						</div>
						<div class="col-xs-1 col-sm-1">
						  <button type="submit" style="margin-top:24px;" class="btn btn-info btn-sm"> <i class="icon-search bigger-110"></i> Search </button>
						</div>
						<div class="col-xs-1 col-sm-1"> <a href="jobs-view.php" style="margin-top:24px;" class="btn btn-success btn-sm"> Clear </a> </div>
					  </form>
					  <div class="clearfix"></div>
					</div>
				  </div>
				</div>
			  </div>
			  <div class="table-header"> Results for "All Interviews" </div>
			  <div class="table-responsive">
				<table class="table table-striped table-bordered table-hover "><?php
				$limit = 15;
				$whereSQL = "INNER JOIN `employee` ON (`jobs_invitations`.`EmployeeUID` = `employee`.`UID`)
							INNER JOIN `employer` ON (`jobs_invitations`.`EmployerUID` = `employer`.`UID`)
							WHERE (`jobs_invitations`.`InvitationStatus` = 'Interview Scheduled' AND `jobs_invitations`.`EmployeeUID` = '".$_SESSION['EmployeeUID']."' )";
				$select = ' `jobs_invitations`.* , `employee`.`EmployeeName`,  `employer`.`EmployerCompany` ';
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('jobs_invitations',$whereSQL . " ORDER BY `jobs_invitations`.`InterviewDate` DESC",$limit,'scheduled-invitation-interviews.php','?'.$QUERYSTRING,$_REQUEST['pager'],$select);
				//echo $paging[0];
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
				  <thead>
					<tr>
					  <th width="6%">Sr. No</th>
					  <th>Designation</th>
					  <th>Company</th>
					  <th width="15%">Invite Date</th>
					  <th width="15%">Interview Date</th>
					  <th>Interview Venue</th>
					  <th>Interview City</th>
					  <!--<th width="6%">Action</th>-->
					</tr>
				  </thead>
				  <tbody><?php
				  if($row>0){
					  while( $rslt = mysql_fetch_array($rs_pages) ){
					  $Designation = optionVal( $rslt['Designation'] );?>
						<tr>
						  <td><?=$count?></td>
						  <td><?=$Designation?></td>
						  <td><?=$rslt['EmployerCompany']?></td>
						  <td><?=date("d M, Y", strtotime($rslt['SystemDate']))?></td>
						  <td><?=date("d M, Y", strtotime($rslt['InterviewDate']))?></td>
						  <td><?=$rslt['InterviewVenue']?></td>
						  <td><?=optionVal($rslt['InterviewCity'])?></td>
						  <!--<td><?php
							$data = array(
										array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' class="red ConfirmDelete"  title="Archieve" ')
										);
							echo TableActions($data);?></td>-->
						</tr>
						<? $count++;
					  }
				  } else {  ?>
					  <tr>
						<th class="center" colspan="7">No Records Found!</th>
					  </tr> <?
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
		//  }?>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.page-content -->
</div>
<!-- /.main-content -->
<?php include('footer.php');?>
