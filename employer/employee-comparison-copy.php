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
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
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
				<div class="col-xs-12">
                <div class="table-header"> Results for "All Employees" </div>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover ">
                    <?php
				$limit = 25;
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('employee',$whereSQL . "  ORDER BY `employee`.`EmployeeTotalExperience` DESC ",$limit,'employee-comparison.php','?',$_REQUEST['pager']);
				//echo $paging[0];
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
                    <thead>
                      <tr>
                        <th width="70">Sr. No</th>
                        <th width="25%">Personal Details</th>
                        <th>Experience</th>
						<th>Education</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
			  if($row>0){
				while( $rslt = mysql_fetch_array($rs_pages) ){
					$ProfileScore = round(ProfileScore($rslt['UID']),0); ?>
                      <tr>
                        <th><?=$count?></th>
						<td><strong>Name</strong> : *************** <br>
						
						<?=($rslt['EmployeeCity']!=0) ? '<strong>City</strong> : '.optionVal($rslt['EmployeeCity']).'<br>' : ' '?>
						
						<?=($rslt['EmployeeGender']!=0) ? '<strong>Gender</strong> : '.$rslt['EmployeeGender'].'<br>' : ' '?>
                        
                        <strong>Profile Score</strong> : <?=$ProfileScore?>%<br />
						</td>
							<td>
							<?=($rslt['EmployeeTotalExperience']!=0) ? '<strong>Total Experience in Pharma Industry</strong> : '.optionVal($rslt['EmployeeTotalExperience']).'<br><br>' : ' '?>
							
							<?php
							$exp = query("SELECT * FROM `employee_experience` WHERE `ExperienceEmployeeID` = '".$rslt['UID']."' ORDER BY `SystemDate` DESC");
							while( $experience = fetch($exp) ){?>
								<strong>* </strong> :
								Work as <?=optionVal($experience['ExperienceDesignation'])?> In <?=$experience['ExperienceEmployer']?>, Total <?=optionVal($experience['ExperienceYear'])?> Experience <br> <?php
							}?></td>
							<td><?php
							$edu = query("SELECT * FROM `employee_education` WHERE `EducationEmployeeID` = '".$rslt['UID']."' ORDER BY `employee_education`.`EducationTo` DESC");
							while( $education = fetch($edu) ){
								if(count($education)>0 && $education['EducationFrom']!=''){ ?>
									<strong>* </strong> :
									<?=optionVal($education['EducationQualification'])?>
									From
									<?=$education['EducationInstitute']?>
									In
									<?=date("M, Y", strtotime($education['EducationFrom']))?>
									-
									<?=date("M, Y", strtotime($education['EducationTo']))?>
									<br><?php
								}
							}?></td>
                      </tr>
					  
                      <? $count++;
				  }
				} else {  ?>
                      <tr>
                        <th class="center" colspan="4">No Records Found.!</th>
                      </tr>
                      <?
				} ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="center" colspan="4"><div class="pull-right">
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
    <?php include('footer.php');?>
