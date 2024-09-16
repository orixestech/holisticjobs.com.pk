<?php include('header.php');?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Job Invitations</li>
        </ul>
        <!-- .breadcrumb -->
        <div id="SubscriptionExpireStatus" class="pull-right">
          <?=$SubscriptionExpireStatus?>
        </div>
      </div>
      <div class="page-content"> 
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <h3 class="header smaller lighter blue">Job Invitations</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="widget-box "> 
              <!-- -->
              <div class="widget-header">
                <h4>Filter Records</h4>
                <div class="widget-toolbar"> <a href="#" data-action="collapse"> <i class="1 icon-chevron-up bigger-125"></i> </a> </div>
              </div>
              <div class="widget-body">
                <div class="widget-main">
                  <form class="form-inline" id="invitation-filter-form" method="get">
                    <div class="col-xs-2 col-sm-2">
                      <label>Status</label>
                      <select name="status" id="status" class="chosen-select-no-single col-xs-12 col-sm-12">
                        <option value=""> Please Select</option>
                        <?php
                          $StatusStmt = query(" SELECT distinct `InvitationStatus` FROM `jobs_invitations` WHERE 1 ORDER BY `jobs_invitations`.`InvitationStatus` ASC  ");
						  while( $StatusRslt = fetch( $StatusStmt ) ){
							  echo '<option>'.$StatusRslt[0].'</option>';							  
						  } ?>
                      </select>
                    </div>
                    <div class="col-xs-2 col-sm-2">
                      <label>City</label>
                      <select name="city" id="city" class="form-control">
                        <?=formListOpt('city', 0)?>
                      </select>
                    </div>
                    <div class="col-xs-2 col-sm-2">
                      <label>Invitation Type</label>
                      <select name="InvitationType" id="InvitationType" class="form-control">
                        <option value=""> Please Select</option>
                        <option value="1">Open Invitation</option>
                        <option value="2">Advertised job Invitation</option>
                      </select>
                    </div>
                    <div class="col-xs-2 col-sm-2">
                      <label>Designation</label>
                      <select name="Designation" id="Designation" class="form-control">
                        <?=formListOpt('Designation', 0)?>
                      </select>
                    </div>
                    <div class="col-xs-2 col-sm-2">
                      <label>Qualification</label>
                      <select name="Qualification" id="Qualification" class="form-control">
                        <?=formListOpt('qualification', 0)?>
                      </select>
                    </div>
                    <div class="col-xs-1 col-sm-1">
                      <button type="submit" style="margin-top:24px;" class="btn btn-info btn-sm"> <i class="icon-search bigger-110"></i> Search </button>
                    </div>
                    <div class="col-xs-1 col-sm-1"> <a href="invitations.php" style="margin-top:24px;" class="btn btn-success btn-sm"> Clear </a> </div>
                  </form>
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>
            <div class="clearfix"><br>
            </div>
            <?php
          $WhereSQL = "  ";
		  if( $_GET['city'] > 0 ){
			  $WhereSQL .= " and `City` = '".$_GET['city']."' ";
		  }
		  
		  if( $_GET['Designation'] > 0 ){
			  $WhereSQL .= " and Designation = '".$_GET['Designation']."' ";
		  }
		  
		  if( $_GET['Qualification'] > 0 ){
			  $WhereSQL .= " and EmployeeUID in ( SELECT `UID` FROM `employee` WHERE `EmployeeQualification` = '".$_GET['Qualification']."' )  ";
		  }
		  
		  if( $_GET['status'] != '' ){
			  $WhereSQL .= " and InvitationStatus = '".$_GET['status']."' ";
		  } else {
		  	  //$WhereSQL .= " and ( InvitationStatus != 'Ignored' and InvitationStatus != 'Invitation Rejected') ";
		  }
		  
		  if( $_GET['InvitationType'] == '1' ){
			  $WhereSQL .= " and JobUID = 0 ";
		  }
		  
		  if( $_GET['InvitationType'] == '2' ){
			  $WhereSQL .= " and JobUID != 0 ";
		  }
		  
		  $WhereSQL .= " ORDER BY `SystemDate` DESC ";
		  //echo $WhereSQL; ?>
            <?php
		  $sql = "SELECT * FROM `jobs_invitations` WHERE `EmployerUID`  = '".$_SESSION['EmployerUID']."' " . $WhereSQL;
		  $rows = total ($sql);
		  if($rows > 0) { ?>
            <div class="table-header"> Results for "<?=(($_GET['status']=='') ? '' : str_replace("-"," ",$_GET['status']) . " ")?>Invitations" </div>
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="JobInvitationsData">
                <thead>
                  <tr>
                    <th width="13%">Invite Date</th>
                    <th width="30%">Applicant Details</th>
                    <th>Invite Details</th>
                    <th width="9%" class="hide">Resume</th>
                    <th width="16%">Status</th>
                    <th width="16%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  	$stmt = mysql_query($sql);
					while( $application = mysql_fetch_array($stmt) ){ 
					  $EmpStmt = mysql_query(" SELECT * FROM employee WHERE `UID` = '".$application['EmployeeUID']."' ");
					  $Employee = mysql_fetch_array($EmpStmt);
					  
					  $EduStmt = query(" SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$application['EmployeeUID']."' ORDER BY `EducationTo` DESC limit 1 ");
					  $Education = fetch( $EduStmt );?>
                  <tr id="row-<?=$application['UID']?>">
                    <td><?=date("d M, Y \n h:i A", strtotime($application['SystemDate']))?></td>
                    <td><?php #print_r($application);?>
                      <?php 
					  if( $application['InvitationStatus']=='Invitation Rejected' || $application['InvitationStatus']=='Approval Awaited' ){ ?>
                      <strong>Employee ID</strong> :
                      <?=EmpCode("", $Employee['UID'])?>
                      <br>
                      <?php } else { ?>
                      <strong>Name : </strong>
                      <?=optionVal($Employee['EmployeeTitle'])?>
                      &nbsp;
                      <?=$Employee['EmployeeName']?>
                      <br>
                      <?php } ?>
                      <strong>Age:</strong>
                      <?=($Employee['EmployeeDOB']=='')?"N/A":dob($Employee['EmployeeDOB'])?>
                      <br />
                      <strong>City:</strong>
                      <?=optionVal($Employee['EmployeeCity'])?>
                      <br />
                      <strong>Total Experience:</strong>
                      <?=($Employee['EmployeeTotalExperience']!=0)? optionVal($Employee['EmployeeTotalExperience']) :'N/A'?>
                      <br />
                      <strong>Highest Qualification:</strong>
                      <?=optionVal($Education['EducationQualification'])?>
                      
                      <!-- in <?=date("Y", strtotime($Education['EducationTo']))?> from <?=$Education['EducationInstitute']?> --> 
                      <br /></td>
                    <td><? //($application['JobUID']==0)?'Open Invitation':'<strong>Invitation Title:</strong> '.GetData('JobTitle','jobs','UID',$application['JobUID'])?>
                      <!--<br>-->
                      <strong>City:</strong>
                      <?=optionVal($application['City'])?>
                      <br>
                      <strong>Designation:</strong>
                      <?=optionVal($application['Designation'])?></td>
                    <td class="hide"><?php if($application['InvitationStatus']=='Approval Awaited'){ ?>
                      **********
                      <?php } else { ?>
                      <?php
                                  $access = CheckSubAccess('unlimited-cv-download');
                                  //print_r($access);
                                  if($access['access']=='true') {
                                      ?>
                      Downloadable CV link 
                      <!--<a href="<?=$path?>uploads/<?=$Employee['UploadedCV']?>" role="button" class="btn btn-success btn-xs green">Resume </a>-->
                      
                      <?
                                  } else {
                                      echo $access['msg'];
                                  }?>
                      <?php }?></td>
                    <td><strong>
                      <?=($application['InvitationStatus']=='Interview Accepted')?'Shortlisted':$application['InvitationStatus']?>
                      </strong></td>
                    <td><?php if($application['InvitationStatus']=='Approval Awaited'){ ?>
                      <!--<strong>Waiting For Invitation Approval</strong>-->
                      <strong>N/A</strong>
                      <?php } else if($application['InvitationStatus']=='Shortlisted'){ ?>
                      <!--<strong>Waiting For Invitation Approval</strong>-->
                      <strong>N/A</strong>
                      <?php } else if($application['InvitationStatus']=='Invitation Accepted'){ ?>
                      <a href="#EmailContent" data-toggle="modal" data-uid="InvitationShortlisted|<?=$application["UID"]?>" class="btn btn-success btn-minier" title="Shortlist">Shortlist</a> &nbsp; <a href="javascript:;" onclick="UpdateInvitationStatus(<?=$application['UID']?>,'Ignored')" class="btn btn-danger btn-minier" title="Ignore">Ignore</a>
                      <?php } else if($application['InvitationStatus']=='Interview Accepted'){ ?>
                      <a href="#EmailContent" data-uid="InvitationScheduleInterview|<?=$application["UID"]?>" data-toggle="modal" title="Schedule Interview" class="btn btn-success btn-minier">Schedule Interview</a>
					  <a href="#EmailContent" class="hide" role="button" data-uid="InvitationScheduleInterview|<?=$application["UID"]?>" data-toggle="modal" title="Schedule Interview"><i class="icon-calendar bigger-130"></i></a>
                      <?php }?>
                      <?php if($application['InvitationStatus']=='Interview Scheduled'){?>
                      <a href="<?=$path?>employer/reminders.php?module=invitation&uid=<?=$application["UID"]?>" title="Reminders"  class="btn btn-success btn-minier">Reminders</a>
                      <?php }?>
                      <?php if($application['InvitationStatus']=='Interview Rejected' || $application['InvitationStatus']=='Invitation Rejected'){ echo "<strong>N/A</strong>"; }?>
                      <?=($application['InvitationStatus']=='Shortlisted' &&  $application['InterviewApproval']=='1')?'<br /><br /><a href="#EmailContent" role="button" data-uid="ScheduleInterview|'.$application["UID"].'" data-toggle="modal" class="btn btn-primary btn-xs ">Schedule Interview</a>':''?></td>
                  </tr>
                  <?php
					} ?>
                </tbody>
                <tfoot><tr><td></td></tr></tfoot>
              </table>
            </div>
            <div class="clearfix"><br>
              <br>
            </div>
            <?php
		  }?>
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
    <!-- /.main-content --> 
    <script>
	$('.slim-scroll').each(function () {
		var $this = $(this);
		$this.slimScroll({
			height: $this.data('height') || 100,
			railVisible:true
		});
	});
	
	function UpdateInvitationStatus(uid, appstatus){
		data = uid + " | " + appstatus;
		if(confirm("Are you sure?")){
			ajaxreq('ajaxpage.php', 'action=JobInvitationStatus&UID='+uid+'&Status='+appstatus, 'JobInvitationsIgnoreData');
			$("tr#row-"+uid).remove();
			setTimeout(function(){ window.location.reload(true); }, 2000);
		} else {
			
		}
	}
	
	</script>
	<?php include('footer.php');?>
    <?=GenModelBOX('ViewJob', 'view-job.php')?>
    <?=GenModelBOX('ViewInvitations', 'view-applications.php')?>
    <?=GenModelBOX('EmailContent', 'email-content.php')?>
    
