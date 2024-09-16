<?php include('header.php');?>
<?php
if($_GET["status"] && $_GET["code"]){
	$pageTitle = GetData('JobTitle','jobs','UID',$_GET["code"]);
	mysql_query(" UPDATE `jobs` SET `JobStatus` = 'Un-Publish' WHERE `jobs`.`UID` = '".$_GET["code"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Job [ '.$pageTitle.' ] Has been Un-Published...!');
		$message = Alert('success', 'Job [ '.$pageTitle.' ] Has been Un-Published...!');
	}
}

if($_GET["delete"]=='true' && $_GET["id"]){
	$pageTitle = GetData('JobTitle','jobs','UID',$_GET["id"]);
	query(" DELETE FROM `jobs_extra` WHERE `JobID` = '".$_GET["id"]."' ");
	mysql_query(" DELETE FROM  `jobs` WHERE `UID` = '".$_GET["id"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Job Title [ '.$pageTitle.' ] Deleted...!');
		$message = Alert('success', 'Job Title [ '.$pageTitle.' ] Deleted...!');
	}
}



$QUERYSTRING = '';
$whereSQL = "where JobEmployerID = '".$_SESSION['EmployerUID']."' and JobLastDateApply >= '".date("Y-m-d")."' and JobStatus = 'Publish' ";

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
	$whereSQL .= " and ( JobTitle like '%".$_GET['JobTitle']."%' or JobDescription like '%".$_GET['JobTitle']."%') ";
	$QUERYSTRING .= 'JobTitle='.$_GET['JobTitle'].'&';
}
if($_GET['JobNature'] != ''){
	$whereSQL .= " and JobNature = '".$_GET['JobNature']."' ";
	$QUERYSTRING .= 'JobNature='.$_GET['JobNature'].'&';
}

?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Published Jobs</li>
    </ul>
    <div class="pull-right" id="SubscriptionExpireStatus"><?=$SubscriptionExpireStatus?></div>
    <!-- .breadcrumb -->
  </div>
  <div class="page-content">
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <div class="col-xs-12">
          <h3 class="header smaller lighter blue">Published Jobs</h3>
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
                      <label>Area</label>
                      <select name="JobCity" id="JobCity" class="form-control">
                        <option value=""> Please Select</option>
                        <?=formListOpt('city', 0)?>
                      </select>
                    </div>
                    
                    
                    <div class="col-xs-2 col-sm-2">
                      <label>Department</label>
                      <select name="JobDepartment" id="JobDepartment" class="form-control">
                        <option value=""> Please Select</option>
                          <?=formListOpt('departments', $PAGE['JobDepartment'])?>
                      </select>
                    </div>
                    <div class="col-xs-2 col-sm-2">
                      <label>Job nature </label>
                      <select name="JobNature" id="JobNature" class="form-control">
                        <option value=""> Please Select</option>
                        <?=formListOpt('job-nature', 0)?>
                      </select>
                    </div>
                    <div class="col-xs-1 col-sm-1">
                      <button type="submit" style="margin-top:24px;" class="btn btn-info btn-sm"> <i class="icon-search bigger-110"></i> Search </button>
                    </div>
                    <div class="col-xs-1 col-sm-1"> <a href="published-jobs.php" style="margin-top:24px;" class="btn btn-success btn-sm"> Clear </a> </div>
                  </form>
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-12">
            <div class="table-header"> Results for "All Jobs"  </div>
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover ">
                <?php
              $limit = 15;
              (!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
              $count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
              $paging = getPaging('jobs',$whereSQL . " ORDER BY `SystemDate` DESC ",$limit,'published-jobs.php','?'.$QUERYSTRING,$_REQUEST['pager']);
              //echo $paging[0];
              $rs_pages = mysql_query($paging[0]) or die($paging[0]);
              $row = mysql_num_rows( $rs_pages );
              $pagination = $paging[1];?>
                <thead>
                  <tr>
                    <th width="6%">Sr. No</th>
                    <th>Job Title</th>
                    <th>Vacancies</th>
                    <th>Location</th>
                    <th width="9%">Last Date</th>
                    <th  width="12%">Applications</th>
                    <th class="hidden-480" width="10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
            if($row>0){
              while( $rslt = mysql_fetch_array($rs_pages) ){
				  $totalApplicatoin = total("SELECT * FROM `jobs_apply` WHERE `JobID` = '".$rslt['UID']."' "); ?>
                  <tr>
                    <td><?=$count?></td>
                    <td><a href="<?=JobLink($rslt['UID'])?>" target="_blank">
                      <?=$rslt['JobTitle']?>
                      </a></td>
                    <td><?=($rslt['JobNumbOfVacancy']>0)?$rslt['JobNumbOfVacancy']:'-'?>
                    </td>
                    <td><?=JobExtra($rslt['UID'], 'JobCity', 'string')?></td>
                    <td><?=$rslt['JobLastDateApply']?></td>
                    <td><?php if ($totalApplicatoin > 0 ){ ?>
                      <a href="job-applications.php?uid=<?=$rslt["UID"]?>"><strong>
                      <?=$totalApplicatoin?>
                      </strong> Applications</a>
                      <?php } else { ?>
                      N/A
                      <?php }?>
                    </td>
                    <td><?php
                  $data = array(
				  			  array('title'=>'<i class="icon-envelope bigger-130"></i>', 'href'=>'published-jobs.php?status=Un-Publish&code='.$rslt['UID'], 'js'=>' role="button" class="red" title="Click there to Un-Publish this Job."'),
							  array('title'=>'<i class="icon-zoom-in bigger-130"></i>', 'href'=>'#ViewJob', 'js'=>' role="button" class="blue" data-toggle="modal" title="View Job" data-uid="'.$rslt["UID"].'" '),
                              array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'post-job.php?mode=update&code='.$rslt['UID'], 'js'=>' role="button" class="green" title="Edit Job Details"'),
							  array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'published-jobs.php?delete=true&id='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete" ')

                          );
                  echo TableActions($data);?></td>
                  </tr>
                  <? $count++;
                }
              } else {  ?>
                  <tr>
                    <th class="center" colspan="7">No Records Found.!</th>
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
          </div>
        </div>
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
