<?php include('header.php');?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Applied Jobs</li>
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
              <h3 class="header smaller lighter blue">Applied Jobs</h3>
			  <?php $ACCESS = CheckSubAccess('applying-for-job', 'employee');
			  if($ACCESS['access']=='false'){ ?>
				  <div class="well">
					<h4 class="red smaller lighter">Access Denied..!</h4>
					You don't have access on this feature, please update your subscription for <strong>"Applying for Job"</strong>.
				  </div>
			  <?php
			  } else { ?>
			  
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
              <div class="table-header"> Results for "All Jobs" </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover ">
                  <?php
            $limit = 15;
            $whereSQL = " INNER JOIN `jobs` ON (`jobs`.`UID` = `jobs_apply`.`JobID`)
                          INNER JOIN `employer` ON (`employer`.`UID` = `jobs`.`JobEmployerID`)
                          WHERE `jobs_apply`.`ApplicationStatus` != 'Interview-Schedule' and `jobs_apply`.`EmployeeID` = '".$_SESSION['EmployeeUID']."' ";
            $select = ' `jobs_apply`.* , `jobs_apply`.SystemDate as ApplyDate, `employer`.`EmployerCompany` , `jobs`.`JobTitle` ';
            (!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
            $count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
            $paging = getPaging('jobs_apply',$whereSQL . " ORDER BY `jobs_apply`.`SystemDate` DESC ",$limit,'applied-jobs.php','?'.$QUERYSTRING,$_REQUEST['pager'],$select);
            //echo $paging[0];
            $rs_pages = mysql_query($paging[0]) or die($paging[0]);
            $row = mysql_num_rows( $rs_pages );
            $pagination = $paging[1];?>
                  <thead>
                    <tr>
                      <th width="6%">Sr. No</th>
                      <th>Job Title</th>
                      <th>Company</th>
                      <th>Application Status</th>
                      <th width="20%">Interview Approval</th>
                      <th width="15%">Applied Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
              if($row>0){
                  while( $rslt = mysql_fetch_array($rs_pages) ){ ?>
                    <tr>
                      <td><?=$count?></td>
                      <td><?=$rslt['JobTitle']?></td>
                      <td><?=$rslt['EmployerCompany']?></td>
                      <td><?=$rslt['ApplicationStatus']?>
                        <?=($rslt['ApplicationStatus']=='Interview-Schedule')?' on '.date("d M, Y", strtotime($rslt['InterviewDate'])):''?></td>
                      <td id="InterviewApproval_<?=$rslt['UID']?>">
					  <?php if($rslt['ApplicationStatus']=='Shortlisted'){ ?>
					  		<?php if($rslt['InterviewApproval']==2){ 
									echo "<strong class='red'>Rejected</strong>"; 
								  } else if($rslt['InterviewApproval']==1){
									echo "<strong>Accepted</strong>";
								  } else { ?>
									<label class="inline"><input name="jobapplication_<?=$rslt['UID']?>" value="1" <?=($rslt['InterviewApproval']==1)?'checked="checked"':''?> onchange="InterviewApproval(<?=$rslt['UID']?>, this.value)" type="radio" />Accept </label>
									<label class="inline"><input name="jobapplication_<?=$rslt['UID']?>" value="2" <?=($rslt['InterviewApproval']==2)?'checked="checked"':''?> onchange="InterviewApproval(<?=$rslt['UID']?>, this.value)" type="radio" />Reject </label>
                                    <!--<label class="inline"><input name="jobapplication_<?=$rslt['UID']?>" value="2" <?=($rslt['InterviewApproval']==2)?'checked="checked"':''?> onChange="InterviewApproval(<?=$rslt['UID']?>, this.value)" type="radio" />Response Awaited </label>-->
							<?php }?>
					  <?php }?>
                      </td>
                      <td><?=date("d M, Y h:i A", strtotime($rslt['ApplyDate']))?></td>
                    </tr>
                    <? $count++;
                  }
              } else {  ?>
                    <tr>
                      <th class="center" colspan="6">No Records Found!</th>
                    </tr>
                    <?
              } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="center" colspan="6"><div class="pull-right">
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
    <script>
		function InterviewApproval(uid, val){
			data = 'action=InterviewApproval&uid=' + uid + '&val=' + val;
			ajaxreq('ajaxpage.php', data, 'InterviewApproval_' + uid);
		}
	</script>
    <?php include('footer.php');?>
