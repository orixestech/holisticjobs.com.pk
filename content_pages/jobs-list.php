<?php
$JobCity = optionVal($_GET['JobCity']);

if($_GET['clear']){
	if($_GET['clear']=='sort'){ unset($_SESSION['SORTJOB']); }
	if($_GET['clear']=='JobCity'){ unset($_SESSION['JobCity']); }
	if($_GET['clear']=='JobDepartment'){ unset($_SESSION['JobDepartment']); }
	if($_GET['clear']=='Search'){ unset($_SESSION['SearchKey']); }
	if($_GET['clear']=='JobDesignation'){ unset($_SESSION['JobDesignation']); }
	if($_GET['clear']=='JobEmployerID'){ unset($_SESSION['JobEmployerID']); }
	if($_GET['clear']=='JobSalary'){ unset($_SESSION['JobSalaryFrom']); unset($_SESSION['JobSalaryTo']);}
	if($_GET['clear']=='JobCategory'){ unset($_SESSION['JobCategory']); }
	if($_GET['clear']=='JobNature'){ unset($_SESSION['JobNature']); }
	if($_GET['clear']=='JobExperience'){ unset($_SESSION['JobExperience']); }
	if($_GET['clear']=='JobQualification'){ unset($_SESSION['JobQualification']); }
	if($_GET['clear']=='JobType'){ unset($_SESSION['JobType']); }
}

( $_GET['sort'] != '' ) ? $_SESSION['SORTJOB'] = $_GET['sort'] : '' ;
( $_GET['JobCity'] != '' && $JobCity!='Multiple Cities') ? $_SESSION['JobCity'] = $_GET['JobCity'] : '' ;
( $_GET['Search'] != '' ) ? $_SESSION['SearchKey'] = $_GET['Search'] : '' ;
( $_GET['JobDepartment'] != '' ) ? $_SESSION['JobDepartment'] = $_GET['JobDepartment'] : '' ;
( $_GET['JobDesignation'] != '' ) ? $_SESSION['JobDesignation'] = $_GET['JobDesignation'] : '' ;
( $_GET['JobEmployerID'] != '' ) ? $_SESSION['JobEmployerID'] = $_GET['JobEmployerID'] : '' ;
( $_GET['JobSalaryFrom'] != '' ) ? $_SESSION['JobSalaryFrom'] = $_GET['JobSalaryFrom'] : '' ;
( $_GET['JobSalaryTo'] != '' ) ? $_SESSION['JobSalaryTo'] = $_GET['JobSalaryTo'] : '' ;
( $_GET['JobCategory'] != '' ) ? $_SESSION['JobCategory'] = $_GET['JobCategory'] : '' ;
( $_GET['JobNature'] != '' ) ? $_SESSION['JobNature'] = $_GET['JobNature'] : '' ;
( $_GET['JobExperience'] != '' ) ? $_SESSION['JobExperience'] = $_GET['JobExperience'] : '' ;
( $_GET['JobQualification'] != '' ) ? $_SESSION['JobQualification'] = $_GET['JobQualification'] : '' ;
( $_GET['JobType'] != '' ) ? $_SESSION['JobType'] = $_GET['JobType'] : '' ;


$SORT = "ORDER BY `jobs`.`UID` DESC ";
$WHERE = "WHERE ".$DfaultJOBQuery;

if($_SESSION['SORTJOB'] != ''){
	($_SESSION['SORTJOB'] == 'newest') ? $SORT = "ORDER BY `SystemDate` DESC" : '' ;
	($_SESSION['SORTJOB'] == 'oldest') ? $SORT = "ORDER BY `SystemDate` " : '' ;
	($_SESSION['SORTJOB'] == 'expiry') ? $SORT = "ORDER BY `SystemDate` DESC" : '' ;
	($_SESSION['SORTJOB'] == 'highsallery') ? $SORT = "ORDER BY `JobSalaryTo` DESC" : '' ;
	($_SESSION['SORTJOB'] == 'lowsallery') ? $SORT = "ORDER BY `JobSalaryTo`" : '' ; 
}
if($_SESSION['JobCity'] != ''){
	//$WHERE .= " and JobCity = '".$_SESSION['JobCity']."' ";
	
	$WHERE .= " and UID in ( SELECT JobID FROM `jobs_extra` WHERE `InfoType` = 'JobCity' and `InfoTypeValue` = '".$JobCity."' ) ";
	$QueryString .= "JobCity=".$_SESSION['JobCity']."&";
}
if($_SESSION['JobDepartment'] != ''){
	$WHERE .= " and JobDepartment = '".$_SESSION['JobDepartment']."' ";
	$QueryString .= "JobDepartment=".$_SESSION['JobDepartment']."&";
}
if($_SESSION['SearchKey'] != ''){
	$WHERE .= " and ( JobTitle like '%".$_SESSION['SearchKey']."%' or `JobEmployerID` in ( SELECT `UID` FROM `employer` WHERE `EmployerCompany` like '%".$_SESSION['SearchKey']."%' ) ) "; /* or JobDescription like '%".$_SESSION['SearchKey']."%' */
	$QueryString .= "Search=".$_SESSION['SearchKey']."&";
}
if($_SESSION['JobDesignation'] != ''){
	$WHERE .= " and JobDesignation = '".$_SESSION['JobDesignation']."' ";
	$QueryString .= "JobDesignation=".$_SESSION['JobDesignation']."&";
}
if($_SESSION['JobEmployerID'] != ''){
	$WHERE .= " and JobEmployerID = '".$_SESSION['JobEmployerID']."' ";
	$QueryString .= "JobEmployerID=".$_SESSION['JobEmployerID']."&";
}
if($_SESSION['JobCategory'] != ''){
	$WHERE .= " and JobCategory = '".$_SESSION['JobCategory']."' ";
	$QueryString .= "JobCategory=".$_SESSION['JobCategory']."&";
}
if($_SESSION['JobNature'] != ''){
	$WHERE .= " and JobNature = '".$_SESSION['JobNature']."' ";
	$QueryString .= "JobNature=".$_SESSION['JobNature']."&";
}
if($_SESSION['JobExperience'] != ''){
	$WHERE .= " and JobExperience = '".$_SESSION['JobExperience']."' ";
	$QueryString .= "JobExperience=".$_SESSION['JobExperience']."&";
}
if($_SESSION['JobQualification'] != ''){
	$WHERE .= " and UID in ( SELECT distinct `JobID` FROM `jobs_extra` WHERE `InfoTypeValue` in ( SELECT `OptionDesc` FROM `optiondata` WHERE `OptionId` = '".$_SESSION['JobQualification']."'  ) )  ";
	$QueryString .= "JobQualification=".$_SESSION['JobQualification']."&";
}
if($_SESSION['JobSalaryFrom'] != ''){
	$WHERE .= " and ( JobSalaryFrom >= '".$_SESSION['JobSalaryFrom']."' or JobSalaryTo <= '".$_SESSION['JobSalaryTo']."') ";

	
}
if($_SESSION['JobType'] != ''){
	$WHERE .= " and JobType = '".$_SESSION['JobType']."' ";
	$QueryString .= "JobType=".$_SESSION['JobType']."&";
}

unset($_SESSION['SORTJOB']);
unset($_SESSION['JobCity']);
unset($_SESSION['JobDepartment']);
unset($_SESSION['SearchKey']);
unset($_SESSION['JobDesignation']);
unset($_SESSION['JobEmployerID']);

if($_GET['JobSalaryFrom']!='' || $_GET['JobSalaryTo'] !=''){
		$QueryString .= "JobSalaryFrom=".$_SESSION['JobSalaryFrom']."&JobSalaryTo=".$_SESSION['JobSalaryTo']."&";	
} else {
	unset($_SESSION['JobSalaryFrom']);
	unset($_SESSION['JobSalaryTo']);
}

unset($_SESSION['JobCategory']);
unset($_SESSION['JobNature']);
unset($_SESSION['JobExperience']);
unset($_SESSION['JobQualification']); 
unset($_SESSION['JobType']);


?>
<link href="<?=$path?>css/paging.css" rel="stylesheet">
<div id="titlebar">
  <div class="container">
    <div class="ten columns">
      <h2>All Jobs</h2>
    </div>
    <div class="six columns"> </div>
  </div>
</div>
<div class="container">
  <!-- Recent Jobs -->
  <div class="eleven columns">
    <div class="padding-right">
      <form action="<?=$path?>jobs/list" method="get" class="list-search">
        <?=($_SESSION['SearchKey']!='') ? '<a href="'.$path.'jobs/list?clear=Search" style="padding: 3px; margin: 0px 0px 0px 14px;"><i class="fa fa-cut"></i> Clear </a><br />' : ''?>
        <button onclick="if (this.value) window.location.href='<?=$path?>jobs/list?Search='+$('form.list-search #Search').val()" style="top: -6px; height: 49px;"><i class="fa fa-search"></i></button>
        <input type="text" id="Search" name="Search" placeholder="job title, keywords or company name" value="<?=$_SESSION['SearchKey']?>"/>
        <div class="clearfix"></div>
      </form>
      <div class="clearfix"></div>
      <ul class="job-list full">
        <?php

		$limit = 10;
		(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
		$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
		$paging = getPaging('jobs'," $WHERE $SORT ",$limit, $path . 'jobs/list','?'.$QueryString ,$_REQUEST['pager']);
		$rs_pages = mysql_query($paging[0]) or die($paging[0]);
		//echo $paging[0];
		$row = mysql_num_rows( $rs_pages );
		if( $row > 0 ){
			$pagination = $paging[1];
			while($rslt=fetch($rs_pages)){
				( GetEmployer('EmployerLogo', $rslt['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $rslt['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';
				echo JobBox($rslt['UID']);
			}
		} else {
			echo '<strong>No results found for your search criteria.</strong>';	
		} ?>
      </ul>
      <div class="clearfix"></div>
      <div class="pagination-container">
        <?=$pagination?>
      </div>
    </div>
  </div>
  <!-- Widgets -->
  <div class="five columns">
    <?php include("search-navigation.php");?>
  </div>
  <!-- Widgets / End -->
</div>
<div class="margin-bottom-25"></div>
<script type="application/javascript">
	setTimeout(function() { 
		var searchTerm = '<?=$_GET['Search']?>';
		// remove any old highlighted terms
		$('body').removeHighlight();
		// disable highlighting if empty
		if ( searchTerm ) {
			// highlight the new term
			$('body').highlight( searchTerm );
			//blinkHighlight();
		}
	}, 1000 );
	
</script>