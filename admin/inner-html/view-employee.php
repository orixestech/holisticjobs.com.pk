<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");
/////////////////////////////////////
///////// Front Side Functions
include("../../site_theme_functions.php");
/////////////////////////////////////


$uid = $_REQUEST['uid'];

$stmt = " SELECT * FROM employee WHERE UID = '".$uid."' ";
$employee = fetch( query( $stmt ) );
$ProfileScore = round(ProfileScore($employee['UID']),0); ?>

<div class="modal-header no-padding">
  <div class="table-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
    Employee Details</div>
</div>
<div class="modal-body form-horizontal">
  <div class="row">
    <div class="col-sm-12">
      <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab" style="auto">
          <li class="active"> <a data-toggle="tab" href="#login-details"> Login Details </a> </li>
          <li> <a data-toggle="tab" href="#basic-details"> Basic Details </a> </li>
          <li> <a data-toggle="tab" href="#extra">Extra Details</a> </li>
          <li> <a data-toggle="tab" href="#education"> Education </a> </li>
          <li> <a data-toggle="tab" href="#expereience"> Experience </a> </li>
          <li> <a data-toggle="tab" href="#skills-interests"> Skills & Interests </a> </li>
        </ul>
        <div class="tab-content">
          <div id="login-details" class="tab-pane in active">
            <h3>Login Details<span id="SendLoginDetails" class=" pull-right">
              <button onclick="SendLoginDetails('employee',<?=$uid?>,'ViewEmployee span#SendLoginDetails')" class="btn btn-primary btn-xs" type="submit"> Send Login Details </button>
              </span></h3>
            <div class="modal-body form-horizontal">
              <table class="table table-striped table-bordered table-hover">
                <tbody>
                  <tr>
                    <td width="200"><strong>Login Email</strong></td>
                    <td><?=$employee['EmployeeEmail']?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Login Password</strong></td>
                    <td><?=PassWord($employee['EmployeePassword'],'show')?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div id="basic-details" class="tab-pane">
            <h3> Basic Details </h3>
            <div class="modal-body form-horizontal">
              <table class="table table-striped table-bordered table-hover">
                <tbody>
                  <tr>
                    <td width="200"><strong>Employee Title</strong></td>
                    <td><?=optionVal($employee['EmployeeTitle'])?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Name</strong></td>
                    <td><?=$employee['EmployeeName']?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>N.I.C Number</strong></td>
                    <td><?=$employee['EmployeeNIC']?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Date of Birth</strong></td>
                    <td><?=date("d M, Y", strtotime($employee['EmployeeDOB']))?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Gender</strong></td>
                    <td><?=$employee['EmployeeGender']?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Mobile Number</strong></td>
                    <td><?=$employee['EmployeeMobile']?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Residential Address</strong></td>
                    <td><?=$employee['EmployeeAddress']?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>City</strong></td>
                    <td><?=optionVal($employee['EmployeeCity'])?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Province or State </strong></td>
                    <td><?=optionVal($employee['EmployeeState'])?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div id="extra" class="tab-pane">
            <h3>Extra Details</h3>
            <div class="modal-body form-horizontal">
              <table class="table table-striped table-bordered table-hover">
                <tbody>
                  <tr>
                    <td width="200"><strong>Objective</strong></td>
                    <td><?=$employee['EmployeeObjective']?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Mother Language</strong></td>
                    <td><?=optionVal($employee['EmployeeMotherLanguage'])?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Marital Status</strong></td>
                    <td><?=optionVal($employee['EmployeeMaritalStatus'])?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Land Line Number</strong></td>
                    <td><?=$employee['EmployeeLandLine']?></td>
                  </tr>
                  <tr>
                    <td width="200"><strong>Website</strong></td>
                    <td><a href="<?=$employee['EmployeeWeb']?>" target="_blank">
                      <?=$employee['EmployeeWeb']?>
                      </a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div id="education" class="tab-pane">
            <h3>Education</h3>
            <div class="modal-body form-horizontal">
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th>College or University</th>
                    <th>Qualification</th>
                    <th>From - To</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  $stmt = query("SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$uid."' ORDER BY `employee_education`.`EducationTo` DESC");
				  while($education = fetch($stmt)){ ?>
                  <tr>
                    <td><?=$education['EducationInstitute']?></td>
                    <td><?=optionVal($education['EducationQualification'])?></td>
                    <td><?=date("M, Y", strtotime($education['EducationFrom']))?>
                      -
                      <?=date("M, Y", strtotime($education['EducationTo']))?></td>
                  </tr>
                  <?php
				  }?>
                </tbody>
              </table>
            </div>
          </div>
          <div id="expereience" class="tab-pane">
            <h3>Experience</h3>
            <div class="modal-body form-horizontal">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Company</th>
                      <th>Designation</th>
                      <th>Salary</th>
                      <th>Years</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
				  $stmt = query("SELECT * FROM `employee_experience` WHERE `ExperienceEmployeeID` = '".$uid."' ");
				  while($experience = fetch($stmt)){ ?>
                    <tr>
                      <td><?=$experience['ExperienceEmployer']?></td>
                      <td><?=optionVal($experience['ExperienceDesignation'])?></td>
                      <td><?=$experience['ExperienceSallery']?></td>
                      <td><?=optionVal($experience['ExperienceYear'])?></td>
                    </tr>
                    <?php
				  }?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div id="skills-interests" class="tab-pane">
            <h3>Skills and Interests</h3>
            <div class="modal-body form-horizontal">
              <table class="table table-striped table-bordered table-hover">
                <tbody>
                  <tr>
                    <td width="28%"><strong>Technical Skills</strong></td>
                    <td><?=EmployeeExtra($uid, 'EmployeeSkills', 'string')?></td>
                  </tr>
                  <tr>
                    <td><strong>Soft Skills</strong></td>
                    <td><?=EmployeeExtra($uid, 'EmployeeSoftSkills', 'string')?></td>
                  </tr>
                  <tr>
                    <td><strong>Interests</strong></td>
                    <td><?=EmployeeExtra($uid, 'EmployeeInterests', 'string')?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer no-margin-top">
  <div class="row">
    <div class="col-md-8 col-xs-8" style="padding-top: 10px; font-size: 15px;">
      <div class="col-md-2 col-xs-2"> <span><strong>Profile Score</strong></span> </div>
      <div class="col-md-6 col-xs-6 pull-left">
        <div class="progress progress-big progress-striped active" data-percent="<?=$ProfileScore?>%" style="margin-bottom:0;">
          <div class="progress-bar progress-bar-success" style="width: <?=$ProfileScore?>%;"></div>
        </div>
      </div>
    </div>
    <div class="col-md-2 col-xs-2 pull-right">
      <button type="button" class="btn btn-primary" data-dismiss="modal"> Close </button>
    </div>
  </div>
</div>
<script>
$('.slim-scroll').each(function () {
	var $this = $(this);
	$this.slimScroll({
		height: $this.data('height') || 100,
		railVisible:true
	});
});

</script>
