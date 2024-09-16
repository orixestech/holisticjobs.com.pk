<?php include('header.php');?>
<?php
if($_GET["delete"]=='true' && $_GET["id"]){
	$pageTitle = GetData('JobTitle','jobs','UID',$_GET["id"]);
	mysql_query(" DELETE FROM  `jobs` WHERE `UID` = '".$_GET["id"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Job Title [ '.$pageTitle.' ] Deleted...!');
		$message = Alert('success', 'Job Title [ '.$pageTitle.' ] Deleted...!');
	}
}


$QUERYSTRING = '';
$whereSQL = 'where 1 ';
if($_GET['JobCity'] != ''){
	$JobCity = optionVal($_GET['JobCity']);
	$whereSQL .= " and UID in ( SELECT JobID FROM `jobs_extra` WHERE `InfoType` = 'JobCity' and `InfoTypeValue` = '".$JobCity."' ) ";
	$QUERYSTRING .= 'JobCity='.$_GET['JobCity'].'&';
}
if($_GET['JobDepartment'] != ''){
	$whereSQL .= " and JobDepartment = '".$_GET['JobDepartment']."' ";
	$QUERYSTRING .= 'JobDepartment='.$_GET['JobDepartment'].'&';
}
if($_GET['JobTitle'] != ''){
	$whereSQL .= " and ( JobTitle like '%".$_GET['JobTitle']."%') ";
	$QUERYSTRING .= 'JobTitle='.$_GET['JobTitle'].'&';
}
if($_GET['JobDesignation'] != ''){
	$whereSQL .= " and JobDesignation = '".$_GET['JobDesignation']."' ";
	$QUERYSTRING .= 'JobDesignation='.$_GET['JobDesignation'].'&';
}
if($_GET['JobEmployerID'] != ''){
	$whereSQL .= " and JobEmployerID = '".$_GET['JobEmployerID']."' ";
	$QUERYSTRING .= 'JobEmployerID='.$_GET['JobEmployerID'].'&';
}
if($_GET['JobCategory'] != ''){
	$whereSQL .= " and JobCategory = '".$_GET['JobCategory']."' ";
	$QUERYSTRING .= 'JobCategory='.$_GET['JobCategory'].'&';
}
if($_GET['JobNature'] != ''){
	$whereSQL .= " and JobNature = '".$_GET['JobNature']."' ";
	$QUERYSTRING .= 'JobNature='.$_GET['JobNature'].'&';
}
if($_GET['JobExperience'] != ''){
	$whereSQL .= " and JobExperience = '".$_GET['JobExperience']."' ";
	$QUERYSTRING .= 'JobExperience='.$_GET['JobExperience'].'&';
}
if($_GET['JobQualification'] != ''){
	$whereSQL .= " and JobQualification = '".$_GET['JobQualification']."' ";
	$QUERYSTRING .= 'JobQualification='.$_GET['JobQualification'].'&';
}
if($_GET['JobType'] != ''){
	$whereSQL .= " and JobType = '".$_GET['JobType']."' ";
	$QUERYSTRING .= 'JobType='.$_GET['JobType'].'&';
}

if($_GET['JobApplication'] == 'true'){
	$whereSQL .= " and ( ( SELECT COUNT(jobs_apply.`UID`) FROM `jobs_apply` WHERE `JobID` = jobs.UID ) != 0 ) ";
	$QUERYSTRING .= 'JobApplication='.$_GET['JobApplication'].'&';
}

if($_GET['JobApplication'] == 'false'){
	$whereSQL .= " and ( ( SELECT COUNT(jobs_apply.`UID`) FROM `jobs_apply` WHERE `JobID` = jobs.UID ) = 0 ) ";
	$QUERYSTRING .= 'JobApplication='.$_GET['JobApplication'].'&';
} 

if($_GET['ExpiredJobs'] == 'on'){
	$whereSQL .= " and  JobLastDateApply < '".date("Y-m-d")."' ";
	$QUERYSTRING .= 'ExpiredJobs='.$_GET['ExpiredJobs'].'&';
} 




?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Jobs</li>
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
            <h3 class="header smaller lighter blue">Jobs</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="col-xs-12">
              <div class="widget-box collapsed">
                <div class="widget-header">
                  <h4>Filter Records</h4>
                  <div class="widget-toolbar"> <a href="#" data-action="collapse"> <i class="1 icon-chevron-down bigger-125"></i> </a> </div>
                </div>
                <div class="widget-body">
                  <div class="widget-main">
                    <form class="form-inline" id="admin-meeting-filter-form" method="get">
                      <div class="col-xs-3 col-sm-3">
                        <label> Job Title</label>
                        <input class="form-control " type="text" name="JobTitle" id="JobTitle"/>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Company</label>
                        <select name="JobEmployerID" id="JobEmployerID" class="chosen-select-no-single col-xs-12 col-sm-12">
                          <option value=""> Please Select</option>
                          <?php
						$stmt = query("SELECT UID, EmployerCompany  FROM `employer` WHERE 1 ORDER BY `employer`.`EmployerCompany` ASC ");
						while($rslt = fetch($stmt)){?>
                          <option value="<?=$rslt['UID']?>">
                          <?=$rslt['EmployerCompany']?>
                          </option>
                          <?
						}?>
                        </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Area</label>
                        <select name="JobCity" id="JobCity" class="form-control">
                          <option value=""> Please Select</option>
                          <?=formListOpt('city', 0)?>
                        </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Category</label>
                        <select name="JobCategory" id="JobCategory" class="form-control">
                          <option value=""> Please Select</option>
                          <?php echo CategoryDropDown('Jobs',0);?>
                        </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Job priority</label>
                        <select name="JobPriority" id="JobPriority" class="form-control">
                          <option value=""> Please Select</option>
                          <?=formListOpt('job-priority', 0)?>
                        </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Job type</label>
                        <select name="JobType" id="JobType" class="form-control">
                          <option value=""> Please Select</option>
                          <?=formListOpt('job-type', 0)?>
                        </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Job nature </label>
                        <select name="JobNature" id="JobNature" class="form-control">
                          <option value=""> Please Select</option>
                          <?=formListOpt('job-nature', 0)?>
                        </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Job Applications </label>
                        <select name="JobApplication" id="JobApplication" class="form-control">
                          <option value=""> Please Select</option>
                          <option value="true"> Having Application</option>
                          <option value="false"> Not Having Application</option>
                        </select>
                      </div>
					  <div class="col-xs-2">
                    <label>Expired Jobs</label>
                    <br />
                    <input class="ace" type="checkbox" name="ExpiredJobs"/>
                    <span class="lbl"></span> </div>
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
            <div class="col-xs-12">
              <div class="table-header"> Results for "All Jobs" <span style="float:right; margin-right:8px;"> <a href="<?=$path?>admin/job.php" class="btn btn-success btn-sm">Add New</a> </span> </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover ">
                  <?php
				$limit = 15;
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('jobs',$whereSQL . " ORDER BY UID DESC ",$limit,'jobs-view.php','?'.$QUERYSTRING,$_REQUEST['pager']);
				//echo $paging[0];
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
                  <thead>
                    <tr>
                      <th width="6%">Sr. No</th>
                      <th>Job Title</th>
                      <th>Company</th>
                      <th>Designation</th>
                      <th width="5%">Area</th>
                      <th width="7%">Priority</th>
                      <th width="10%">Last Date</th>
                      <th width="10%">Applications</th>
                      <th width="8%">Action</th>
					  <!--<th width="10%">Share</th>-->
                    </tr>
                  </thead>
                  <tbody>
                    <?php
			  if($row>0){
				while( $rslt = mysql_fetch_array($rs_pages) ){
				$totalApplicatoin = total("SELECT * FROM `jobs_apply` WHERE `JobID` = '".$rslt['UID']."' "); ?>
                    <tr id="row_<?=$rslt['UID']?>">
                      <td><?=$count?></td>
                      <td><a href="<?=JobLink($rslt['UID'])?>" target="_blank"><?=$rslt['JobTitle']?></a></td>
                      <td><?=GetEmployer('EmployerCompany', $rslt['JobEmployerID'])?></td>
                      <td><?=optionVal($rslt['JobDesignation'])?></td>
                      <td><?=JobExtra($rslt['UID'], 'JobCity', 'string')?></td>
                      <td><?=ucwords($rslt['JobPriority'])?></td>
                      <td><?=date("d M, Y", strtotime($rslt['JobLastDateApply']))?></td>
					  <td> 
					<?php if ($totalApplicatoin > 0 ){ ?>
                    		<a href="#ViewApplications" data-toggle="modal" data-uid="<?=$rslt["UID"]?>"><strong><?=$totalApplicatoin?></strong> Applications</a>
					<?php } else { ?>
                    		N/A
					<?php }?> 
					</td>                      
					<td><?php
					$data = array(
								array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'job.php?mode=update&code='.$rslt['UID'], 'js'=>' role="button" class="green" title="Edit Job Details"'),
								array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'jobs-view.php?delete=true&id='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete" '),
								array('title'=>'<i class="icon-zoom-in bigger-130"></i>', 'href'=>'#ViewJob', 'js'=>' role="button" class="blue" data-toggle="modal" title="View Job Details" data-uid="'.$rslt["UID"].'" ')
							);
					echo TableActions($data);?>
                      </td>
					  <!--<td><div style="margin-top:5px;"></div>
                    <div class="fb-share-button" data-href="<?=JobLink($rslt['UID'])?>" data-layout="button_count"></div>
                    <div style="margin-top:5px;"></div>
                    <script type="IN/Share" data-url="<?=JobLink($rslt['UID'])?>" data-counter="right"></script></td>-->
                    </tr>
                    <? $count++;
				  }
				} else {  ?>
                    <tr>
                      <th class="center" colspan="9">No Records Found.!</th>
                    </tr>
                    <?
				  } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="center" colspan="9"><div class="pull-right">
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
<?=GenModelBOX('ViewJob', 'view-job.php')?>
<?=GenModelBOX('ViewApplications', 'view-applications.php')?>
<?=GenModelBOX('EmailContent', 'email-content.php')?>

<?php include('footer.php');?>
