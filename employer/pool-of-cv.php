<?php include('header.php');?>
<?php

$access = CheckSubAccess('existing-pool-cvs', 'employer');
if($access['access']=='false') {?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">
            <?=($_GET["id"])?'Modify':'Add New'?>
            Job </li>
        </ul>
        <!-- .breadcrumb -->
        <div id="SubscriptionExpireStatus" class="pull-right">
          <?=$SubscriptionExpireStatus?>
        </div>
        <!-- #nav-search -->
      </div>
      <div class="page-content">
        <div class="page-header">
          <h1>Access Denied.</h1>
        </div>
        <div class="row">
          <div class="col-xs-12"><h4><?=$access['msg']?></h4></div>
        </div>
      </div>
    </div><?php
} else {
	$QUERYSTRING = '';
	$whereSQL = 'where 1 ';
	if($_GET['gender'] != ''){
		$whereSQL .= " and ( EmployeeGender = '".$_GET['gender']."' ) ";
		$QUERYSTRING .= 'gender='.$_GET['gender'].'&';
	}
	
	if($_GET['city'] != ''){
		$whereSQL .= " and EmployeeCity  = '".$_GET['city']."' ";
		$QUERYSTRING .= 'city='.$_GET['city'].'&';
	}
	
	if($_GET['experience'] != ''){
		$whereSQL .= " and EmployeeTotalExperience = '".$_GET['experience']."' ";
		$QUERYSTRING .= 'experience='.$_GET['experience'].'&';
	}
	
	if( isset ($_GET['qualification']) ){
		//print_r($_GET['qualification']);
		$whereSQL .= " and `UID` IN ( SELECT `EducationEmployeeID` FROM `employee_education` WHERE `EducationQualification` in ( ".implode(",",$_GET['qualification'])." )  )";
		$QUERYSTRING .= 'qualification='.$_GET['qualification'].'&';
	}
	
	if( isset ($_GET['designations']) ){
		//print_r($_GET['qualification']);
		$whereSQL .= " and `UID` IN ( SELECT `ExperienceEmployeeID` FROM `employee_experience` WHERE ExperienceTo = '1971-01-01' and `ExperienceDesignation` IN ( ".implode(",",$_GET['designations'])." ) )";
		$QUERYSTRING .= 'designations='.$_GET['designations'].'&';
	}
	
	
	/**/
	
	?>
    <link href="<?=$path?>css/paging.css" rel="stylesheet">
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Pool of CVs</li>
        </ul>
      </div>
      <div class="page-content"> 
        <div class="row">
              <div class="col-xs-12">
                <h3 class="header smaller lighter blue">Pool of CVs</h3>
                <div id="ajax-result">
                  <?=$message?>
                </div>
                <div class="col-xs-12">
                  <div class="widget-box "> 
                    <!-- -->
                    <div class="widget-header">
                      <h4>Filter Records</h4>
                      <div class="widget-toolbar"> <a href="#" data-action="collapse"> <i class="1 icon-chevron-up bigger-125"></i> </a> </div>
                    </div>
                    <div class="widget-body">
                      <div class="widget-main">
                        <form class="form-inline" id="employee-view-filter-form" method="get">
                          <div class="col-xs-3 col-md-3 col-sm-3">
                            <label>Gender</label>
                            <select name="gender" id="gender" class="chosen-select-no-single col-xs-12 col-sm-12">
                              <option value=""> Please Select</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                            </select>
                          </div>
                          <div class="col-xs-3 col-md-3 col-sm-3">
                            <label>City</label>
                            <select name="city" id="city" class="form-control">
                              <?=formListOpt('city', 0)?>
                            </select>
                          </div>
                          
                          <div class="col-xs-3 col-md-3 col-sm-3">
                            <label>Experience Year</label>
                            <select name="experience" id="experience" class="form-control">
                              <?=formListOpt('experience', 0)?>
                            </select>
                          </div>
                          <div class="clearfix"><br></div>
                          <div class="col-xs-4 col-md-4 col-sm-4">
                            <label>Qualification</label><br />
                            <select name="qualification[]" id="qualification" multiple="" style="width:95%" class="chosen-select">
                              <?=formListOpt('qualification', 0, 0)?>
                            </select>
                          </div>
                          <div class="col-xs-4 col-md-4 col-sm-4">
                            <label>Current Designation</label>
                            <select name="designations[]" id="CurrentDesignation" multiple="" style="width:95%" class="chosen-select">
                              <?=formListOpt('designation', 0, 0)?>
                            </select>
                          </div>
                          
                          
                          <div class="col-xs-2 col-md-1 col-sm-2 pull-right"> <a href="pool-of-cv.php" style="margin-top:24px;" class="btn btn-success btn-sm"> Clear </a> </div>
                          <div class="col-xs-2 col-md-2 col-sm-2 pull-right" style="width: 100px;">
                            <button type="submit" style="margin-top:24px;" class="btn btn-info btn-sm"> <i class="icon-search bigger-110"></i> Search </button>
                          </div>
                           </form>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12">
                  <div class="table-header"> Results for "All Employees"</div>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover ">
                      <?php 
                    $limit = 25;
                    (!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
                    $count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
                    $paging = getPaging('employee',$whereSQL . "  ORDER BY SystemDate DESC ",$limit,'pool-of-cv.php','?'.$QUERYSTRING,$_REQUEST['pager']);
                    //echo $paging[0];
                    $rs_pages = mysql_query($paging[0]) or die($paging[0]);
                    $row = mysql_num_rows( $rs_pages );
                    $pagination = $paging[1];?>
                      <thead>
                        <tr>
                          <th width="70">Sr. No</th>
                          <th>Basic Details</th>
                          <th width="25%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                    //if( $row>0 && count($_GET) > 1 ){
                     while( $rslt = mysql_fetch_array($rs_pages) ){
                        $ProfileScore = round(ProfileScore($rslt['UID']),0); 
                        
                        $invite = total("SELECT * FROM `jobs_invitations` WHERE `EmployerUID`  = '".$_SESSION['EmployerUID']."' and `EmployeeUID` = '".$rslt['UID']."' ");
                        
                        $edu = query("SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$rslt['UID']."' ORDER BY `employee_education`.`EducationTo` DESC limit 1");
                        $education = fetch($edu);
						
						$GetEmployeeExpInYear = GetEmployeeExpInYear( $rslt['UID'], 'no-bracket' ); ?>
                        
                        <tr id="row_<?=$rslt['UID']?>" data-name="<?=$rslt['EmployeeName']?>">
                          <th><?=$count?></th>
                          <td>
                            <strong>Employee ID</strong> : <?=EmpCode("", $rslt['UID'])?> <br>
                            
                            <?=($rslt['EmployeeCity']!=0) ? '<strong>City</strong> : '.optionVal($rslt['EmployeeCity']).'<br>' : ' '?>
                            
                            <?=($rslt['EmployeeGender']!=0) ? '<strong>Gender</strong> : '.$rslt['EmployeeGender'].'<br>' : ' '?>
                            
                            <strong>Profile Score</strong> : <?=$ProfileScore?>%<br />
                            
                            <?php
                            if(count($education)>0 && $education['EducationFrom']!=''){ ?>
                                <strong>Highest Education </strong> :
                                <?=optionVal($education['EducationQualification'])?><br />
                            <?php }	?>
                            <?=($experience['ExperienceDesignation']!=0) ? '<strong>current designation</strong> : '.optionVal($experience['ExperienceDesignation']).'<br>' : ' '?>
                            
							<?=($GetEmployeeExpInYear!='') ? '<strong>Total Experience</strong> : '.$GetEmployeeExpInYear.'<br>' : ' '?></td>
                            
                          <td><a href="#InviteContent" data-toggle="modal" class="btn btn-minier btn-success" data-uid="<?=$rslt['UID']?>" title="Invite">Invite</a> 
                          <a href="<?=EmployeeProfileLink($rslt['UID'])?>" target="_blank" class="btn btn-minier btn-primary" data-uid="<?=$rslt['UID']?>" title="Invite">View Profile</a><?=($invite>0)?'<br>'.$invite.' Invitations Sent.':''?> </td>
                        </tr>
                        <? $count++;
                        }
                    /*} else {  ?>
                        <tr>
                          <th class="center" colspan="3"> <?=( count($_GET) > 1 ) ? 'No Records Found.!' : 'Please use Search filters.!' ?>
                          </th>
                        </tr>
                        <?php
                    }*/ ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th class="center" colspan="3"><div class="pull-right">
                              <? //( count($_GET) > 1 ) ? $pagination : '' ?>
                              <?=$pagination?>
                            </div></th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
      </div>
    </div>
    <?=GenModelBOX('InviteContent', 'invite-content.php')?> <?php
} ?>
<?php include('footer.php');?>
