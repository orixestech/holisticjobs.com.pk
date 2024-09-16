<?php include('header.php');?>
<?php
if($_GET["delete"]=='true' && $_GET["id"]){
	/*$pageTitle = GetData('JobTitle','jobs','UID',$_GET["id"]);
	mysql_query(" DELETE FROM  `jobs` WHERE `UID` = '".$_GET["id"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Job Title [ '.$pageTitle.' ] Deleted...!');
		$message = Alert('success', 'Job Title [ '.$pageTitle.' ] Deleted...!');
	}*/
}

$QUERYSTRING = '';
$whereSQL = 'where 1 ';
if($_GET['key'] != ''){
	$_GET['key'] = trim($_GET['key']);
	$whereSQL .= " and ( Name like '%".$_GET['key']."%' ) ";
	$QUERYSTRING .= 'key='.$_GET['key'].'&';
}

if($_GET['comp'] != ''){
	$_GET['comp'] = trim($_GET['comp']);
	$whereSQL .= " and ( IntrestedCompany like '%".$_GET['comp']."%'  ) ";
	$QUERYSTRING .= 'comp='.$_GET['comp'].'&';
}

if($_GET['city'] != ''){
	$_GET['city'] = trim($_GET['city']);
	$whereSQL .= " and ( IntrestedArea like '%".$_GET['city']."%'  ) ";
	$QUERYSTRING .= 'city='.$_GET['city'].'&';
}

if($_GET['experience'] != ''){
	$_GET['experience'] = trim($_GET['experience']);
	$whereSQL .= " and ( SpecialtyExperience like '%".$_GET['experience']."%'  ) ";
	$QUERYSTRING .= 'experience='.$_GET['experience'].'&';
}

if($_GET['designation'] != ''){
	$_GET['designation'] = trim($_GET['designation']);
	$whereSQL .= " and ( IntrestedDesignation like '%".$_GET['designation']."%'  ) ";
	$QUERYSTRING .= 'designation='.$_GET['designation'].'&';
}

if($_GET['skill'] != ''){
	$_GET['skill'] = trim($_GET['skill']);
	$whereSQL .= " and ( TopTechnicalSkills like '%".$_GET['skill']."%' or TopSoftSkills like '%".$_GET['skill']."%' ) ";
	$QUERYSTRING .= 'skill='.$_GET['skill'].'&';
}



?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Careers</li>
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
            <h3 class="header smaller lighter blue">Careers</h3>
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
                        <label> Keyword </label>
                        <input class="form-control col-xs-12 col-sm-12" type="text" name="key" id="key"/>
                      </div>
					  <div class="col-xs-3 col-sm-3">
                        <label> Company </label>
                        <input class="form-control col-xs-12 col-sm-12" type="text" name="comp" id="comp"/>
                      </div>
					  <div class="col-xs-3 col-sm-3">
                        <label> Area </label>
                        <input class="form-control col-xs-12 col-sm-12" type="text" name="city" id="city"/>
                      </div>
					  <div class="col-xs-3 col-sm-3">
                        <label> Experience Year </label>
                        <input class="form-control col-xs-12 col-sm-12" type="text" name="experience" id="experience"/>
                      </div>
					  <div class="col-xs-3 col-sm-3">
                        <label> Skills </label>
                        <input class="form-control col-xs-12 col-sm-12" type="text" name="skill" id="skill"/>
                      </div>
					  <div class="col-xs-3 col-sm-3">
                        <label> Designation </label>
                        <input class="form-control col-xs-12 col-sm-12" type="text" name="designation" id="designation"/>
                      </div>
                      <div class="col-xs-1 col-sm-1">
                        <button type="submit" style="margin-top:24px;" class="btn btn-info btn-sm"> <i class="icon-search bigger-110"></i> Search </button>
                      </div>
                      <div class="col-xs-1 col-sm-1"> <a href="careers-view.php" style="margin-top:24px;" class="btn btn-success btn-sm"> Clear </a> </div>
                    </form>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="table-header"> Results for "All Careers" </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover ">
                  <?php
				$limit = 15;
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('careers',$whereSQL . " ORDER BY `SystemDate` DESC ",$limit,'careers-view.php','?'.$QUERYSTRING,$_REQUEST['pager']);
				//echo $paging[0];
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
                  <thead>
                    <tr>
                      <th>Sr. No</th>
                      <th width="25%">Details</th>
                      <th width="25%">Intrest</th>
                      <th>Current Status</th>
                      <th>Specialty Experience</th>
                      <th class="hidden-480" width="85">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
			  if($row>0){
				while( $rslt = mysql_fetch_array($rs_pages) ){ ?>
                    <tr id="row_<?=$rslt['fq_id']?>">
                      <td><?=$count?></td>
                      <td><strong>Name</strong> :
                        <?=$rslt['Name']?>
                        <br>
                        <strong>Email</strong> :
                        <?=$rslt['Email']?>
                        <br>
                        <strong>Contact #</strong> :
                        <?=$rslt['ContactNo']?></td>
                      <td><strong>Area</strong> :
                        <?=$rslt['IntrestedArea']?>
                        <br>
                        <strong>Designation</strong> :
                        <?=$rslt['IntrestedDesignation']?>
                        <br>
                        <strong>Company</strong> :
                        <?=$rslt['IntrestedCompany']?></td>
                      <td><?=ucwords($rslt['CurrentStatus'])?></td>
                      <td><?=ucwords($rslt['SpecialtyExperience'])?></td>
                      <td><a href="<?=$path.'uploads/'.$rslt['UploadCV']?>">Download</a> </td>
                    </tr>
                    <? $count++;
				  }
				} else {  ?>
                    <tr>
                      <th class="center" colspan="10">No Records Found.!</th>
                    </tr>
                    <?
				  } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="center" colspan="10"><div class="pull-right">
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
<?php include('footer.php');?>
