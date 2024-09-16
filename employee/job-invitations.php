<?php include('header.php');?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Jobs Invitations</li>
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
              <h3 class="header smaller lighter blue">Jobs Invitations</h3>
              <?php $ACCESS = CheckSubAccess('applying-for-job', 'employee');
			  if($ACCESS['access']=='false'){ ?>
              <div class="well">
                <h4 class="red smaller lighter">Access Denied..!</h4>
                You don't have access on this feature, please update your subscription for <strong>"Applying for Job"</strong>. </div>
              <?php
			  } else { ?>
              <div id="ajax-result">
                <?=$message?>
              </div>
              <div class="table-header"> Results for "All Job Invitations" </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover ">
                  <?php
            $limit = 15;
            $whereSQL = "
				INNER JOIN `employee` 
					ON (`jobs_invitations`.`EmployeeUID` = `employee`.`UID`)
				INNER JOIN `employer` 
					ON (`jobs_invitations`.`EmployerUID` = `employer`.`UID`)
				LEFT JOIN `jobs` 
        			ON (`jobs_invitations`.`JobUID` = `jobs`.`UID`)
				WHERE (`jobs_invitations`.`EmployeeUID` = '".$_SESSION['EmployeeUID']."' ) ";
            $select = ' `jobs_invitations`.* , `employer`.`EmployerCompany` , `employee`.`EmployeeName`, jobs_invitations.`SystemDate` as InviteDate, `jobs`.`JobTitle` ';
            (!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
            $count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
            $paging = getPaging('jobs_invitations',$whereSQL . " ORDER BY jobs_invitations.`SystemDate` DESC ",$limit,'applied-jobs.php','?'.$QUERYSTRING,$_REQUEST['pager'],$select);
            //echo $paging[0];
            $rs_pages = mysql_query($paging[0]) or die($paging[0]);
            $row = mysql_num_rows( $rs_pages );
            $pagination = $paging[1];?>
                  <thead>
                    <tr>
                      <th width="6%">Sr. No</th>
                      <th>Invitation Title</th>
                      <th>Designation</th>
                      <th>Company</th>
                      <th>City</th>
                      <th>Invitation Status</th>
                      <th>Approval</th>
                      <th width="15%">Invite Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
              if($row>0){
                  while( $rslt = mysql_fetch_array($rs_pages) ){ // $rslt['JobTitle'] ?>
                    <tr>
                      <td><?=$count?></td>
                      <td><?=( $rslt['JobTitle']=='')? "Open Invitation" : "Advertised Invitation" ?></td>
                      <td><?=optionVal($rslt['Designation'])?></td>
                      <td><?=$rslt['EmployerCompany']?></td>
                      <td><?=optionVal($rslt['City'])?></td>
                      <td><?=$rslt['InvitationStatus']?>
                        <?=($rslt['InvitationStatus']=='Interview Scheduled')?' on '.date("d M, Y", strtotime($rslt['InterviewDate'])):''?></td>
                      <td id="InvitationApproval_<?=$rslt['UID']?>">
					  <?php if($rslt['InvitationStatus']=='Approval Awaited'){ ?>
                        <?php if($rslt['InvitationApproval']==0){?>
                                <label class="inline">
                                  <input name="jobinvitation_<?=$rslt['UID']?>" value="1" onchange="InvitationApproval(<?=$rslt['UID']?>, this.value)" type="radio" />
                                  Accept </label>
                                <label class="inline">
                                  <input name="jobinvitation_<?=$rslt['UID']?>" value="2" onchange="InvitationApproval(<?=$rslt['UID']?>, this.value)" type="radio" />
                                  Reject </label>
						<?php }?>
                      <?php } else if($rslt['InvitationStatus']=='Shortlisted'){ ?>
                        <?php if($rslt['InterviewApproval']==0){?>
                                <label class="inline">
                                  <input name="jobinvitation_<?=$rslt['UID']?>" value="1" onchange="InterviewApproval(<?=$rslt['UID']?>, this.value)" type="radio" />
                                  Accept </label>
                                <label class="inline">
                                  <input name="jobinvitation_<?=$rslt['UID']?>" value="2" onchange="InterviewApproval(<?=$rslt['UID']?>, this.value)" type="radio" />
                                  Reject </label>
						<?php }?>
                      <?php } else { echo "&nbsp; - &nbsp;"; }?>
                      </td>
                      <td><?=date("d M, Y h:i A", strtotime($rslt['InviteDate']))?></td>
                      
                    </tr>
                    <? $count++;
                  }
              } else { ?>
                    <tr>
                      <th class="center" colspan="8">No Records Found!</th>
                    </tr> <?
              } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="center" colspan="8"><div class="pull-right">
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
		function InvitationApproval(uid, val){
			if( confirm("Are you sure?") ){
				data = 'action=InvitationApproval&uid=' + uid + '&val=' + val;
				ajaxreq('ajaxpage.php', data, 'InvitationApproval_' + uid);
			}
		}
		function InterviewApproval(uid, val){
			if( confirm("Are you sure?") ){
				data = 'action=InvitationInterviewApproval&uid=' + uid + '&val=' + val;
				ajaxreq('ajaxpage.php', data, 'InvitationApproval_' + uid);
			}
		}
	</script>
    <?php include('footer.php');?>
