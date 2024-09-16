<?php include('header.php');?>
    <?php
$QUERYSTRING = '';
$whereSQL = 'where 1 ';
if($_GET['keyword'] != ''){
	$whereSQL .= " and ( EmployeeName like '%".$_GET['keyword']."%' or EmployeeEmail like '%".$_GET['keyword']."%' ) " ;
	$QUERYSTRING .= 'keyword='.$_GET['keyword'].'&';
}

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

if($_GET['qualification'] != ''){
	$whereSQL .= " and EmployeeQualification = '".$_GET['qualification']."' ";
	$QUERYSTRING .= 'qualification='.$_GET['qualification'].'&';
}


?>
    <link href="<?=$path?>css/paging.css" rel="stylesheet">
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php"> Home</a> </li>
          <li class="active">Employee Comparison</li>
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
                <h3 class="header smaller lighter blue">Employee Comparison</h3>
				<div id="ajax-result">
                  <?=$message?>
                </div>
				<!--<div class="col-xs-12">
              <div class="widget-box collapsed">
                <div class="widget-header">
                  <h4>Filter Records</h4>
                  <div class="widget-toolbar"> <a href="#" data-action="collapse"> <i class="1 icon-chevron-down bigger-125"></i> </a> </div>
                </div>
                <div class="widget-body">
                  <div class="widget-main">
                    <form class="form-inline" id="employee-comparison-filter-form" method="get">
                      <div class="col-xs-3 col-sm-3">
                        <label>KeyWords</label>
                        <input class="form-control " type="text" name="keyword" id="keyword"/>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Gender</label>
                        <select name="gender" id="gender" class="chosen-select-no-single col-xs-12 col-sm-12">
                          <option value=""> Please Select</option>
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
                          <?=formListOpt('experience', 0)?>
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
                      <div class="col-xs-1 col-sm-1"> <a href="employee-Comparison.php" style="margin-top:24px;" class="btn btn-success btn-sm"> Clear </a> </div>
                    </form>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
            </div>-->
				<div class="col-xs-12">
                <div class="table-header"> Results for "All Employees" </div>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover ">
                    <?php
				$sql = " SELECT `employee`.* from `jobs_apply` INNER JOIN `employee` ON (`jobs_apply`.`EmployeeID` = `employee`.`UID`) WHERE (`jobs_apply`.`JobID` = '".$_GET["uid"]."' AND `jobs_apply`.`ApplicationStatus` != 'Ignore') ORDER BY `jobs_apply`.`SystemDate` DESC LIMIT 0, 25  ";
				$rs_pages = mysql_query($sql) or die($sql);
				$row = mysql_num_rows( $rs_pages ); ?>
                    <thead>
                      <tr>
                        <th width="6%">Sr. No</th>
                        <th>Personal Details</th>
                        <th width="20%">Total Experience</th>
						
						<th width="20%">Companies Worked with</th>
                        <th>Highest Education</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
			  if($row>0){
				while( $rslt = mysql_fetch_array($rs_pages) ){ $count++;
					$ProfileScore = round(ProfileScore($rslt['UID']),0);
					
					$EduStmt = query(" SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$rslt['UID']."' ORDER BY `EducationTo` DESC limit 1 ");
					$Education = fetch( $EduStmt );?>
                      <tr id="row_<?=$rslt['UID']?>">
                        <th><?=$count?></th>
                        <td>
						<strong>Name</strong> : <?=optionVal($rslt['EmployeeTitle'])?> &nbsp; <?=$rslt['EmployeeName']?> <br>
						
						<?=($rslt['EmployeeCity']!=0) ? '<strong>City</strong> : '.optionVal($rslt['EmployeeCity']).'<br>' : ' '?>
						
						<?=($rslt['EmployeeGender']!=0) ? '<strong>Gender</strong> : '.$rslt['EmployeeGender'].'<br>' : ' '?>
						<strong> Age:</strong> <?=($rslt['EmployeeDOB']=='')?"N/A":dob($rslt['EmployeeDOB'])?> <br />
                         <strong>Profile Score</strong> : <?=$ProfileScore?>%<br />
						</td>
						<td><?=GetEmployeeExpInYear( $rslt['UID'] )?> <?php //optionVal($rslt['EmployeeTotalExperience'])?></td>
						
						<td><?=total("SELECT * FROM `employee_experience` WHERE `ExperienceEmployeeID` = '".$rslt['UID']."' ")?></td>
                        <td><?php
						if($Education['EducationQualification']>0){ ?>
							<?=optionVal($Education['EducationQualification'])?> in <?=date("Y", strtotime($Education['EducationTo']))?> from <?=$Education['EducationInstitute']?><?php
						} ?> </td>
                      </tr>
                      <? 
				  }
				} else {  ?>
                      <tr>
                        <th class="center" colspan="4">No Records Found.!</th>
                      </tr>
                      <?
				} ?>
                    </tbody>
                    
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
    <?php include('footer.php');?>
