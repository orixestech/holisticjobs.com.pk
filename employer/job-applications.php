<?php include('header.php');?>
    <?php
$QUERYSTRING = '';
$whereSQL = ' ';

if($_GET['city'] != ''){
	$whereSQL .= " and `City` in ( SELECT `OptionDesc` FROM `optiondata` WHERE `OptionId` = '".$_GET['city']."' ) ";
	$QUERYSTRING .= 'city='.$_GET['city'].'&';
}

if($_GET['gender'] != ''){
	$whereSQL .= " and EmployeeID in ( SELECT `UID` FROM `employee` WHERE `EmployeeGender` = '".$_GET['gender']."' ) ";
	$QUERYSTRING .= 'gender='.$_GET['gender'].'&';
}

if($_GET['experience'] != ''){
	$whereSQL .= " and EmployeeID in ( SELECT `UID` FROM `employee` WHERE `EmployeeTotalExperience` = '".$_GET['experience']."' ) ";
	$QUERYSTRING .= 'experience='.$_GET['experience'].'&';
}

if($_GET['qualification'] != ''){
	$whereSQL .= " and EmployeeID in ( SELECT EducationEmployeeID FROM `employee_education` WHERE `EducationQualification` = '".$_GET['qualification']."'  ) ";
	$QUERYSTRING .= 'qualification='.$_GET['qualification'].'&';
}?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Job Applications </li>
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
            <div class="col-xs-12">
              <h3 class="header smaller lighter blue">Job Applications</h3>
              <div id="ajax-result">
                <?=$message?>
              </div>
              <div class="col-xs-12">
                <div class="widget-box collapsed"> 
                  <!-- -->
                  <div class="widget-header">
                    <h4>Filter Records</h4>
                    <div class="widget-toolbar"> <a href="#" data-action="collapse"> <i class="1 icon-chevron-down bigger-125"></i> </a> </div>
                  </div>
                  <div class="widget-body">
                    <div class="widget-main">
                      <form class="form-inline" id="job-application-filter-form" method="get">
                        <input type="hidden" name="uid" value="<?=$_GET["uid"]?>">
                        <div class="col-xs-2 col-sm-2">
                          <label>Gender</label>
                          <select name="gender" id="gender" class="form-control">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                          </select>
                        </div>
                        <div class="col-xs-2 col-sm-2">
                          <label>City</label>
                          <select name="city" id="city" class="form-control">
                            <option value=""> Please Select</option>
                            <?=formListOpt('city', 0)?>
                          </select>
                        </div>
                        <div class="col-xs-2 col-sm-2">
                          <label>Experience Year</label>
                          <select name="experience" id="experience" class="form-control">
                            <option value=""> Please Select</option>
                            <?=formListOpt('experience', 'EmployeeTotalExperience')?>
                          </select>
                        </div>
                        <div class="col-xs-2 col-sm-2">
                          <label>Qualification</label>
                          <select name="qualification" id="qualification" class="form-control">
                            <option value=""> Please Select</option>
                            <?=formListOpt('qualification', 0)?>
                          </select>
                        </div>
                        <div class="col-xs-1 col-sm-1">
                          <button type="submit" style="margin-top:24px;" class="btn btn-info btn-sm"> <i class="icon-search bigger-110"></i> Search </button>
                        </div>
                        <div class="col-xs-1 col-sm-1"> <a href="job-applications.php?uid=<?=$_GET["uid"]?>" style="margin-top:24px;" class="btn btn-success btn-sm"> Clear </a> </div>
                      </form>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="table-header"> Results for "All Applications" <span style="float:right; margin-right:8px;"> <a href="employee-comparison.php?uid=<?=$_GET["uid"]?>" class="btn btn-success btn-sm">Compare Applicants</a> </span> </div>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover" id="JobApplicationsData">
                    <thead>
                      <tr>
                        <th>Applicant Details</th>
                        <th width="13%">Date</th>
                        <th width="9%">CV</th>
                        <th width="9%">Status</th>
                        <th width="10%">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
				$sql = " SELECT * FROM jobs_apply WHERE ApplicationStatus != 'Ignored' and  `JobID` = '".$_GET["uid"]."' " . $whereSQL;
				$rows = total ($sql);
				 if($rows > 0) {
					  $stmt = query( $sql );
					  while( $application = fetch($stmt) ){ 
						$EmpStmt = query(" SELECT * FROM employee WHERE `UID` = '".$application['EmployeeID']."' ");
						$Employee = fetch($EmpStmt);
						
						$EduStmt = query(" SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$application['EmployeeID']."' ORDER BY `EducationTo` DESC limit 1 ");
						$Education = fetch( $EduStmt );
						$GetEmployeeExpInYear = GetEmployeeExpInYear( $application['EmployeeID'], 'no-bracket' ); ?>
                      <tr id="row-<?=$application['UID']?>" data-employee="<?=$application['EmployeeID']?>">
                        <td><? #print_r($Employee);?>
                          <strong> Name:</strong>
                          <?=optionVal($Employee['EmployeeTitle'])?>
                          &nbsp;
                          <?=$Employee['EmployeeName']?>
                          <br />
                          <strong> Age:</strong>
                          <?=($Employee['EmployeeDOB']=='0000-00-00')?"N/A":dob($Employee['EmployeeDOB'])?>
                          <br />
                          <strong>Applied for City:</strong>
                          <?=($application['City']!='')?$application['City']:'N/A'?>
                          <br />
                          <strong>Total Experience:</strong>
                          <?=($GetEmployeeExpInYear!='') ? $GetEmployeeExpInYear : 'N/A'?>
                          <br />
                          <strong>Highest Education:</strong>
                          <?=($Education['EducationQualification']!='')?optionVal($Education['EducationQualification']):'N/A'?>
                          <br /></td>
                        <td><?=date("d M, Y \n h:i A", strtotime($application['SystemDate']))?></td>
                        <td><?php
							$access = CheckSubAccess('unlimited-cv-download');
							//print_r($access);
							if($access['access']=='true') {
								?>
                          <a href="<?=$path?>uploads/<?=$application['UploadedCV']?>" title="Download" role="button" class="btn btn-success btn-minier green">Download </a>
                          <?
							} else {
								echo $access['msg'];
							} ?></td>
                        <td><strong>
                          <?php 
						  if($application['ApplicationStatus']=="New"){
							  echo "Action Awaited";
						  } else if($application['ApplicationStatus']=="Interview-Schedule"){
							  echo "Interview Scheduled";
						  } else if($application['ApplicationStatus']=="Shortlisted"){
							  if($application['InterviewApproval']=='0') echo 'Shortlisted (Acceptance Awaiting)' ;
							  if($application['InterviewApproval']=='1') echo 'Approved by Candidate' ;
							  if($application['InterviewApproval']=='2') echo 'Rejected by Candidate' ;
						  } else {
							  echo $application['ApplicationStatus'];
						  }?>
                          </strong></td>
                        <td><?php 
						  if($application['ApplicationStatus']=='New'){
							?>
                          <a href="#EmailContent" class="btn btn-minier btn-primary" style="margin-bottom: 3px;" data-toggle="modal" data-uid="Shortlisted|<?=$application["UID"]?>" title="Shortlist">Shortlist</a>&nbsp;<a href="javascript:;" style="margin-bottom: 3px;" class="btn btn-minier btn-danger" onclick="UpdateApplicationStatus(<?=$application['UID']?>,'Ignored')" title="Ignore">Ignore</i></a><br />
                          <br />
                          <?php 
						  } else 						  
						  if($application['ApplicationStatus']=="Shortlisted"){
							  if($application['InterviewApproval']=='0') echo '<strong>N/A</strong>' ;
							  if($application['InterviewApproval']=='1') echo '<a href="#EmailContent" style="margin-bottom: 3px;" class="btn btn-minier btn-success" role="button" data-uid="ScheduleInterview|'.$application["UID"].'" data-toggle="modal" title="Schedule Interview">Schedule Interview</a>' ;
							  if($application['InterviewApproval']=='2') echo '<strong>N/A</strong>' ;
						  } ?>
                          <? //($application['ApplicationStatus']=='Shortlisted' &&  $application['InterviewApproval']=='0')?'<div class="btn btn-notic btn-minier ">Shortlisted (Acceptance Awaiting)</div>':''?>
                          <?php if($application['ApplicationStatus']=='Interview-Schedule'){?>
                          
                          <!--<strong>Interview Scheduled</strong>--> 
                          <a class="btn btn-minier btn-primary" style="margin-bottom: 3px;" href="<?=$path?>employer/reminders.php?module=application&uid=<?=$application["UID"]?>" title="Reminders">Reminders</a>
                          <?php }?></td>
                      </tr>
                      <?php
					  }
				 } else { ?>
                      <tr>
                        <td colspan="5">No Record Found!</td>
                      </tr>
                      <?php
				 }?>
                    </tbody>
                  </table>
                </div>
              </div>
              <?php
	  $sql = " SELECT * FROM jobs_apply WHERE ApplicationStatus = 'Ignored' and  `JobID` = '".$_GET["uid"]."'";
	  $rows = total ($sql);
	  if($rows > 0) { ?>
              <div class="clearfix"><br>
                <br>
              </div>
              <div class="table-header">Results for "Ignored Applications"</div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="JobApplicationsIgnoreData">
                  <thead>
                    <tr>
                      <th>Applied for City</th>
                      <th width="13%">Date</th>
                      <th width="9%">CV</th>
                      <th width="10%">Status</th>
                      <th width="10%">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
		  $stmt = mysql_query($sql);
		  while( $application = mysql_fetch_array($stmt) ){ 
			$EmpStmt = mysql_query(" SELECT * FROM employee WHERE `UID` = '".$application['EmployeeID']."' ");
			$Employee = mysql_fetch_array($EmpStmt);?>
                    <tr id="row-<?=$application['UID']?>">
                      <td><? #print_r($Employee);?>
                        <strong>Name:</strong>
                        <?=optionVal($Employee['EmployeeTitle'])?>
                        &nbsp;
                        <?=$Employee['EmployeeName']?>
                        <br />
                        <strong>City:</strong>
                        <?=$application['City']?>
                        <br />
                        <strong>Age:</strong>
                        <?=($Employee['EmployeeDOB']=='')?"N/A":dob($Employee['EmployeeDOB'])?>
                        <br />
                        <strong>Total Experience:</strong>
                        <?=$Employee['EmployeeTotalExperience']?>
                        <br />
                        <strong>Highest Education:</strong>
                        <?=optionVal($Education['EducationQualification'])?>
                        <br /></td>
                      <td><?=date("d M, Y \n h:i A", strtotime($application['SystemDate']))?></td>
                      <td><?php
				$access = CheckSubAccess('unlimited-cv-download');
				if($access['access']=='true') {
					?>
                        <a href="<?=$path?>uploads/<?=$application['UploadedCV']?>" role="button" class="btn btn-success btn-minier green">Download </a>
                        <?
				} else {
					echo $access['msg'];
				}
			  ?></td>
                      <td><strong>
                        <?php 
						  if($application['ApplicationStatus']=="Ignored"){
							  echo "Ignored";
							  }?>
                        </strong></td>
                      <td><a href="#EmailContent" class="btn btn-minier btn-success" style="margin-bottom: 3px;" data-toggle="modal" data-uid="Shortlisted|<?=$application["UID"]?>" title="Shortlist">Shortlist</a><br /></td>
                    </tr>
                    <?php
			  } ?>
                  </tbody>
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
$('.slim-scroll').each(function () {
	var $this = $(this);
	$this.slimScroll({
		height: $this.data('height') || 100,
		railVisible:true
	});
});

function UpdateApplicationStatus(uid, appstatus){
	data = uid + " | " + appstatus;
	if(confirm("Are you sure?")){
		ajaxreq('ajaxpage.php', 'action=JobApplicationStatus&UID='+uid+'&Status='+appstatus, 'JobApplicationsIgnoreData');
		$("tr#row-"+uid).remove();
		setTimeout(function(){ window.location.reload(true); }, 2000);
	} else {
		
	}
	
		
}

</script>
    <?=GenModelBOX('ViewJob', 'view-job.php')?>
    <?=GenModelBOX('ViewApplications', 'view-applications.php')?>
    <?=GenModelBOX('EmailContent', 'email-content.php')?>
    <?php include('footer.php');?>
