<?php
@session_start();
if(@$_SESSION['EmployeeLogged'] != 1){
	echo '<meta http-equiv="refresh" content="0;URL=\'login.php\'" />';exit;
}
if(isset($_GET['logout']) && $_GET['logout']=='true'){
	$_SESSION['EmployeeLogged'] = $_SESSION['Employee'] = '';
	@session_destroy();	
}






?>
<?php include("header.php"); ?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="#">Home</a> </li>
          <li class="active">Dashboard</li>
        </ul>
        <!-- .breadcrumb -->
        <div class="pull-right" id="SubscriptionExpireStatus">
          <?=$SubscriptionExpireStatus?>
        </div>
      </div>
      <div class="page-content">
        <?php
		$stmt = query(" SELECT * FROM `employee` WHERE UID = '".$_SESSION['EmployeeUID']."' ");
		$PAGE = mysql_fetch_array($stmt);
		$ProfileScore = round(ProfileScore($_SESSION['EmployeeUID']),0);
		( $PAGE['EmployeeDOB'] == '0000-00-00' ) ? $PAGE['EmployeeDOB'] = '' : $PAGE['EmployeeDOB'] = date("Y-m-d", strtotime($PAGE['EmployeeDOB'])) ;?>
        <div class="page-header">
          <div class="row" style="height:30px">
            <div class="col-xs-4 col-sm-4">
              <h1> Dashboard <small> <i class="icon-double-angle-right"></i> overview &amp; stats </small> </h1>
            </div>
            <div class="col-xs-7 col-sm-7">
              <div class="inline middle blue bigger-110"> Your profile is
                <?=$ProfileScore?>
                % complete</div>
              <div style="width:55%;" data-percent="<?=$ProfileScore?>%" class="inline middle no-margin progress active">
                <div class="progress-bar progress-bar-success" style="width:<?=$ProfileScore?>%"></div>
              </div>
            </div>
            <div class="col-xs-1 col-sm-1 pull-right"> <a href="<?=$path?>holistic-cv/<?=base64_encode($PAGE['UID']."|".$PAGE['EmployeeName'])?>" target="_blank" class="btn btn-success pull-right btn-xs" type="button">Download Profile</a> </div>
          </div>
        </div>
        <div id="user-profile-2" class="user-profile">
          <div class="row">
            <div class="col-xs-3 col-sm-3 center"> <span class="profile-picture" style="max-height:255px;">
              <?=($PAGE['EmployeeLogo']!='')?'<img src="'.$path.'uploads/'.$PAGE['EmployeeLogo'].'" style="max-height: 245px;" id="avatar" class="editable img-responsive"/>':'<img id="avatar" class="editable img-responsive" style="max-height: 245px;" alt="" src="assets/avatars/classprofile.png" />'?>
              </span>
              <div class="space space-4"></div>
            </div>
            <div class="col-xs-4 col-sm-4">
              <h4 class="blue"> <span class="middle">
                <?=optionVal($PAGE['EmployeeTitle'])?>
                <?=$PAGE['EmployeeName']?>
                </span> </h4>
              <div class="profile-user-info">
                <div class="profile-info-row">
                  <div class="profile-info-name"> Email Address</div>
                  <div class="profile-info-value"> <span>
                    <?=$PAGE['EmployeeEmail']?>
                    &nbsp; </span> </div>
                </div>
                <div class="profile-info-row">
                  <div class="profile-info-name"> Mobile Number</div>
                  <div class="profile-info-value"><span>
                    <?=$PAGE['EmployeeMobile']?>
                    &nbsp; </span></div>
                </div>
                <div class="profile-info-row">
                  <div class="profile-info-name">Land Line</div>
                  <div class="profile-info-value"> <span>
                    <?=$PAGE['EmployeeLandLine']?>
                    &nbsp; </span> </div>
                </div>
                <div class="profile-info-row">
                  <div class="profile-info-name"> City</div>
                  <div class="profile-info-value"> <span>
                    <?=optionVal($PAGE['EmployeeCity'])?>
                    &nbsp; </span> </div>
                </div>
                <div class="profile-info-row">
                  <div class="profile-info-name"> Province</div>
                  <div class="profile-info-value"> <span>
                    <?=optionVal($PAGE['EmployeeState'])?>
                    &nbsp; </span> </div>
                </div>
                <div class="profile-info-row">
                  <div class="profile-info-name"> Gender </div>
                  <div class="profile-info-value"> <span>
                    <?=$PAGE['EmployeeGender']?>
                    &nbsp; </span> </div>
                </div>
                <div class="profile-info-row">
                  <div class="profile-info-name"> Date of Birth </div>
                  <div class="profile-info-value"> <span>
                    <?=$PAGE['EmployeeDOB']?>
                    &nbsp; </span> </div>
                </div>
              </div>
            </div>
            <div class="col-xs-5 col-sm-5">
              <div class="widget-box transparent">
                <div class="row">
                  <?php
			$applications = total( " SELECT * FROM `jobs_apply` WHERE `EmployeeID` = '".$PAGE['UID']."' " );
			$interview = total( " SELECT * FROM `jobs_apply` WHERE `EmployeeID` = '".$PAGE['UID']."' and InterviewCity > 0" );
			$alerts = total( " SELECT * FROM `jobs_alerts` WHERE `AlertEmployeeUID` = '".$PAGE['UID']."' " );
			?>
                  <div class="col-xs-3 col-sm-3"><span class="btn btn-app btn-bg btn-yellow no-hover"> <span class="line-height-1 bigger-170">
                    <?=$applications?>
                    </span> <br />
                    <span class="line-height-1 smaller-90">Applications </span> </span> </div>
                  <div class="col-xs-3 col-sm-3"><span class="btn btn-app btn-bg btn-success no-hover"> <span class="line-height-1 bigger-170">
                    <?=$interview?>
                    </span> <br />
                    <span class="line-height-1 smaller-90"> Interviews</span> </span> </div>
                  <div class="col-xs-3 col-sm-3"> <span class="btn btn-app btn-bg btn-primary no-hover"> <span class="line-height-1 bigger-170">
                    <?=$alerts?>
                    </span> <br />
                    <span class="line-height-1 smaller-90"> Job Alerts</span> </span> </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="widget-box transparent <?=($PAGE['EmployeeObjective']=='')?'hide':''?>">
                <div class="widget-header widget-header-small">
                  <h4 class="smaller"> <i class="icon-check bigger-110"></i> My Objectives</h4>
                </div>
                <div class="widget-body">
                  <div class="widget-main">
                    <p>
                      <?=$PAGE['EmployeeObjective']?>
                    </p>
                  </div>
                </div>
              </div>
              <div class="widget-box transparent">
                <div class="widget-header widget-header-small">
                  <h4 class="blue smaller"><i class="icon-book bigger-110"></i> Education History </h4>
                  <div class="widget-toolbar action-buttons"> </div>
                </div>
                <div class="widget-body">
                  <div class="widget-main padding-8">
                    <div id="profile-feed-1" class="profile-feed">
                      <ul>
                        <?php
						  $stmt = query("SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$_SESSION['EmployeeUID']."' ORDER BY `employee_education`.`EducationTo` DESC");
						  while($education = fetch($stmt)){ ?>
                        <li>
                          <h5><strong>
                            <?=optionVal($education['EducationQualification'])?>
                            </strong></h5>
                          <?=( ($education['EducationFrom']=='0000-00-00' || $education['EducationTo']=='0000-00-00') ? '' : '<p>'.date("M, Y", strtotime($education['EducationFrom'])).' - '.date("M, Y", strtotime($education['EducationTo'])).'</p>' )?>
                          
                          <p>
                            <?=$education['EducationInstitute']?>
                          </p>
                          <hr class="dotted" />
                        </li>
                        <?php
						  }?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
			  
			  <?php
			  $sql = "SELECT * FROM `employee_certificate` WHERE `EmployeeID` = '".$_SESSION['EmployeeUID']."' ORDER BY CertificateDate DESC";
			  if( total($sql) > 0 ) {?>
              <div class="widget-box transparent">
                <div class="widget-header widget-header-small">
                  <h4 class="blue smaller"> <i class="icon-certificate bigger-110"></i> Certificate History </h4>
                  <div class="widget-toolbar action-buttons"> </div>
                </div>
                <div class="widget-body">
                  <div class="widget-main padding-8">
                    <div id="profile-feed-1" class="profile-feed">
                      <ul>
                        <?php
						  $stmt = query($sql);
						  while($certificate = fetch($stmt)){ ?>
                        <li>
                          <h5><strong>
                            <?=$certificate['CertificateQualification']?>
                            </strong></h5>
                          <?=( ($certificate['CertificateDate']=='0000-00-00') ? '' : '<p>'.date("M, Y", strtotime($certificate['CertificateDate'])).'</p>' )?>
                          <p>
                            <?=$certificate['CertificateInstitute']?>
                          </p>
                          <hr class="dotted" />
                        </li>
                        <?php
						  }?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
			  <?php 
			  }?>
			  
			  <?php
			  $sql = " SELECT * FROM `employee_extra` WHERE `InfoType` = 'EmployeeSoftSkills' and `EmployeeID` = '".$PAGE['UID']."' ORDER BY `employee_extra`.`InfoTypeValue` ASC"; 
			  if( total($sql) > 0 ) { ?>
              <div class="widget-box transparent">
                <div class="widget-header widget-header-small">
                  <h4 class="smaller"> <i class="icon-laptop bigger-110"></i> Soft Skills</h4>
                </div>
                <div class="widget-body">
                  <div class="widget-main">
                    <ul>
                      <?php
						$stmt = query($sql);
						while($rslt=fetch($stmt)){
							echo '<li>'.$rslt['InfoTypeValue'].'</li>';
						}
						?>
                    </ul>
                  </div>
                </div>
              </div>
              <?php 
			  }?>
			  
              <?php
			  $sql = " SELECT * FROM `employee_extra` WHERE `InfoType` = 'EmployeeSkills' and `EmployeeID` = '".$PAGE['UID']."' ORDER BY `employee_extra`.`InfoTypeValue` ASC"; 
			  if( total($sql) > 0 ) { ?>
              <div class="widget-box transparent">
                <div class="widget-header widget-header-small">
                  <h4 class="smaller"> <i class="icon-wrench bigger-110"></i> Technical Skills</h4>
                </div>
                <div class="widget-body">
                  <div class="widget-main">
                    <ul>
                      <?php
						$stmt = query($sql);
						while($rslt=fetch($stmt)){
							echo '<li>'.$rslt['InfoTypeValue'].'</li>';
						}
						?>
                    </ul>
                  </div>
                </div>
              </div>
              <?php 
			  }?>
			  
			  <?php
			  $sql = " SELECT * FROM `employee_extra` WHERE `InfoType` = 'EmployeeInterests' and `EmployeeID` = '".$PAGE['UID']."' ORDER BY `employee_extra`.`InfoTypeValue` ASC"; 
			  if( total($sql) > 0 ) { ?>
              <div class="widget-box transparent">
                <div class="widget-header widget-header-small">
                  <h4 class="smaller"> <i class="icon-key bigger-110"></i> Interests</h4>
                </div>
                <div class="widget-body">
                  <div class="widget-main">
                    <ul>
                      <?php
          						$stmt = query($sql);
          						while($rslt=fetch($stmt)){
          							echo '<li>'.$rslt['InfoTypeValue'].'</li>';
          						}
          						?>
                    </ul>
                  </div>
                </div>
              </div>
              <?php 
			  }?>
			  <div class="widget-box transparent <?=($PAGE['AdditionalInformation']=='')?'hide':''?>">
                <div class="widget-header widget-header-small">
                  <h4 class="smaller"> <i class="icon-check bigger-110"></i> Additional Info</h4>
                </div>
                <div class="widget-body">
                  <div class="widget-main">
                    <p>
                      <?=$PAGE['AdditionalInformation']?>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xs-6 col-sm-6 col-md-6">
			  <?php
			  $sql = "SELECT * FROM `employee_experience` WHERE `ExperienceEmployeeID` = '".$_SESSION['EmployeeUID']."' ORDER BY UID ";
			  if( total($sql) > 0 ) {?>
              <div class="widget-box transparent">
                <div class="widget-header widget-header-small">
                  <h4 class="blue smaller"> <i class="icon-briefcase bigger-110"></i> Experience History <?=GetEmployeeExpInYear( $_SESSION['EmployeeUID'] )?></h4>
                  <div class="widget-toolbar action-buttons"> </div>
                </div>
                <div class="widget-body">
                  <div class="widget-main padding-8">
                    <div id="profile-feed-1" class="profile-feed">
                      <ul>
                        <?php
                      $stmt = query($sql);
                      while($experience = fetch($stmt)){ ?>
                        <li>
                          <h5><strong>
                            <?=optionVal($experience['ExperienceDesignation'])?>
                            </strong><?php 
							$experience['ExperienceFrom'] = ( $experience['ExperienceFrom'] == '0000-00-00' ) ? '' : $experience['ExperienceFrom'] ;
							$experience['ExperienceFrom'] = ( $experience['ExperienceFrom'] == '1971-01-01' ) ? '' : $experience['ExperienceFrom'] ;
							
							if( $experience['ExperienceTo'] == "1971-01-01" ){
							  $ExperienceTo = 'Present';
							} else 
							if( $experience['ExperienceTo'] == "0000-00-00" ){
							  $ExperienceTo = '';
							} else {
							  $ExperienceTo = date("F, Y", strtotime( $experience['ExperienceTo'] ) );
							} 
							
							if($experience['ExperienceFrom'] != '' ){
								$experience['ExperienceFrom'] = date("F, Y", strtotime($experience['ExperienceFrom']));
							}
							
							$totalExp = GetEmployeeExpInYearByJobID($experience['UID']);
							$totalExp = ( $totalExp == '' ) ? '' : '( '.$totalExp.' )'; ?>
                            
                            <?=$totalExp?></h5>
                          <p><strong>at</strong>
                            <?=$experience['ExperienceEmployer']?>
                          </p>
						  <?=(($experience['ExperienceSallery']>0)?'<p><strong>Salary: </strong>'.$experience['ExperienceSallery'].'</p>':'')?>
                          <hr class="dotted" />
                        </li>
                        <?php
						  }?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <?php 
			  }?>
            </div>
          </div>
        </div>
        <!--<div class="row">
      <div class="col-xs-12">
	  <img src="<?=$path?>employee/images/welcome.png" alt="Welcome Holistic Employee" width="100%" />
      </div>
    </div>-->
      </div>
      <!-- /.page-content -->
    </div>
    <!-- /.main-content -->
    <!-- page specific plugin scripts -->
    <!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
		<![endif]-->
    <?php include("footer.php"); ?>
