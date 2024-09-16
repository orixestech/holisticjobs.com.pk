<?php


include("includes/conn.php");
include("admin_theme_functions.php");

$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];
//print_r($_REQUEST);
if($_REQUEST["action"]=='dropdown_name_edit')
{
	$type = mysql_real_escape_string($_REQUEST["type"]);
	
	$dropdown_typeid = mysql_real_escape_string($_REQUEST["dropdown_id"]);
	$dropdown_oldid = mysql_real_escape_string($_REQUEST["TypeOptions"]);
	$dropdown_newid = mysql_real_escape_string($_REQUEST["OptionId"]);
	$dropdown_newvalue = mysql_real_escape_string($_REQUEST["dropdown_description"]);
	$dropdown_oldvalue = GetData("OptionDesc","optiondata","OptionId",$dropdown_oldid);
	
	$dropdown_typename = GetData("TypeFieldName","typedata","TypeId",$dropdown_typeid);
	
	$return = array();
	
	if($type=='Approve'){
		$return['message'] = 'New Dropdown Value Approved';
		$sql = " UPDATE `optiondata` SET `OptionDesc` = '".$dropdown_newvalue."', `OptionName` = '".post_slug( $dropdown_newvalue )."' , `Status` = '1' WHERE `optiondata`.`OptionId` = '".$dropdown_newid."' ;  ";
		query($sql);
	}
	
	if($type=='Replace'){
		$sql = array();
		switch($dropdown_typename){
			case "mother-language";
				$sql[] = "UPDATE `employee` SET EmployeeMotherLanguage = '".$dropdown_oldid."' WHERE EmployeeMotherLanguage = '".$dropdown_newid."';";
			break;
			case "designation";
				$sql[] = "Update `employee_experience` set `ExperienceDesignation` = '".$dropdown_oldid."' WHERE `ExperienceDesignation` = '".$dropdown_newid."';";
				$sql[] = "Update `jobs` set `JobExperienceDesignation` = '".$dropdown_oldid."' WHERE `JobExperienceDesignation` = '".$dropdown_newid."';";
				$sql[] = "Update `jobs` set `JobDesignation` = '".$dropdown_oldid."' WHERE `JobDesignation` = '".$dropdown_newid."';";
			break;
			case "experience";
				$sql[] = "Update `employee_experience` set `ExperienceYear` = '".$dropdown_oldid."' WHERE `ExperienceYear` = '".$dropdown_newid."';";
				$sql[] = "Update `jobs` set `JobExperience` = '".$dropdown_oldid."' WHERE `JobExperience` = '".$dropdown_newid."';";
				$sql[] = "Update `jobs` set `JobTotalExperience` = '".$dropdown_oldid."' WHERE `JobTotalExperience` = '".$dropdown_newid."';";
				
			break;
			case "city";
				$sql[] = "UPDATE `employee` SET EmployeeCity = '".$dropdown_oldid."' WHERE EmployeeCity = '".$dropdown_newid."';";
				$sql[] = "Update `jobs_extra` set `InfoTypeValue` = '".$dropdown_oldvalue."' WHERE `InfoTypeValue` = '".$dropdown_newvalue."' and `InfoType` = 'JobCity';";
			break;
			case "qualification";
				$sql[] = "Update `employee_education` set `EducationQualification` = '".$dropdown_oldid."' WHERE `EducationQualification` = '".$dropdown_newid."';";
				$sql[] = "Update `jobs_extra` set `InfoTypeValue` = '".$dropdown_oldvalue."' WHERE `InfoTypeValue` = '".$dropdown_newvalue."' and `InfoType` = 'JobQualification';";
				$sql[] = "Update `jobs_extra` set `InfoTypeValue` = '".$dropdown_oldvalue."' WHERE `InfoTypeValue` = '".$dropdown_newvalue."' and `InfoType` = 'JobAdditionalQualification';";
			break;
			case "skills";
				$sql[] = "Update `employee_extra` set `InfoTypeValue` = '".$dropdown_oldvalue."' WHERE `InfoTypeValue` = '".$dropdown_newvalue."' and `InfoType` = 'EmployeeSkills';";
				$sql[] = "Update `jobs_extra` set `InfoTypeValue` = '".$dropdown_oldvalue."' WHERE `InfoTypeValue` = '".$dropdown_newvalue."' and `InfoType` = 'JobSkills';";
			break;
			case "soft-skills";
				$sql[] = "Update `employee_extra` set `InfoTypeValue` = '".$dropdown_oldvalue."' WHERE `InfoTypeValue` = '".$dropdown_newvalue."' and `InfoType` = 'EmployeeSoftSkills';";
				$sql[] = "Update `jobs_extra` set `InfoTypeValue` = '".$dropdown_oldvalue."' WHERE `InfoTypeValue` = '".$dropdown_newvalue."' and `InfoType` = 'JobSoftSkills';";
			break;
			case "interests";
				$sql[] = "Update `employee_extra` set `InfoTypeValue` = '".$dropdown_oldvalue."' WHERE `InfoTypeValue` = '".$dropdown_newvalue."' and `InfoType` = 'EmployeeInterests';";
			break;
			case "departments";
				$sql[] = "UPDATE `jobs` SET `JobDepartment` = '".$dropdown_oldid."' WHERE `JobDepartment` = '".$dropdown_newid."';";
			break;
			case "job-type";
				$sql[] = "UPDATE `jobs` SET `JobType` = '".$dropdown_oldid."' WHERE `JobType` = '".$dropdown_newid."';";
			break;
		}
		$return['message'] = 'New Dropdown Value ('.$dropdown_newvalue.') Replaced to Old Value ('.$dropdown_oldvalue.')';
		//echo print_r($sql);
		foreach($sql as $s){
			query($s);			
		}
		
		$delete = " DELETE FROM `optiondata` WHERE `optiondata`.`OptionId` = '".$dropdown_newid."' ; ";
		query($delete);
		
	}
	
	$return['status'] = 'success';
	echo json_encode($return);
}

elseif($_REQUEST["action"]=='get_dropdown_name')
{
	$dropdown_title = GetData('TypeFieldName', 'typedata', 'TypeId', $_REQUEST["dropdown_id"]);
	echo 'for ' . $dropdown_title;
}

elseif($_REQUEST["action"]=='dropdown_name_delete')
{
	$dropdown_title = GetData('TypeFieldName', 'typedata', 'TypeId', $_REQUEST["dropdown_id"]);
	
	mysql_query(" DELETE FROM `typedata` WHERE TypeId = '".$_REQUEST["dropdown_id"]."' ");
	$num = mysql_affected_rows();
	
	if($num)
	{
		Track('Dropdown [ '.$dropdown_title.' ] Deleted Successfully ...!');
		echo $message = Alert('success', 'Dropdown [ '.$dropdown_title.' ] Deleted Successfully ...!');
	}
}

elseif($_REQUEST["action"]=='dropdown_name_view')
{
?>

<table class="table table-striped table-bordered table-hover ">
  <?php
	$paging = getPaging('typedata',' WHERE `TypeId` in ( SELECT distinct `OptionType` FROM `optiondata` WHERE `Status` = 0 ) ORDER BY TypeId DESC',1000,'dropdowns-view.php','?',$_REQUEST['pager']);
	$rs_pages = mysql_query($paging[0]) or die($paging[0]);
	$row = mysql_num_rows( $rs_pages );
	$pagination = $paging[1];?>
  <thead>
    <tr>
      <th width="35%">Dropdown</th>
      <th>Options</th>
      <th class="hidden-480" width="18%">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
	  if($row>0){
		while( $rslt = mysql_fetch_array($rs_pages) ){
		$options = total("SELECT * FROM `optiondata` WHERE `Status` = 0 and `OptionType` = '".$rslt['TypeId']."' ");
	  ?>
    <tr id="ddn_<?=$rslt['TypeId']?>">
      <td><?=$rslt['TypeDesc']?></td>
      <td><?=$options?></td>
      <td><div class="visible-md visible-lg hidden-sm hidden-xs action-buttons"><a class="blue" href="javascript:void(0);" onclick="updateDropdownOptionGrid(<?=$rslt['TypeId']?>);"> <i class="icon-zoom-in bigger-130"></i> </a> </div></td>
    </tr>
    <? }
		  } else {
		  ?>
    <tr>
      <th class="center" colspan="4">No Records Found.!</th>
    </tr>
    <?
		  }
		  ?>
  <tfoot>
    <tr>
      <th class="center" colspan="4"><div class="pull-right">
          <?=$pagination?>
        </div></th>
    </tr>
  </tfoot>
    </tbody>
  
</table>
<?php
}


//Dropdown Options
elseif($_REQUEST["action"]=='dropdown_option_add_edit')
{
	$dropdown_option_id = mysql_real_escape_string($_REQUEST["dropdown_option_id"]);
	$dropdown_option_type = mysql_real_escape_string($_REQUEST["dropdown_option_type"]);
	$dropdown_option_title = mysql_real_escape_string($_REQUEST["dropdown_option_title"]);
	$dropdown_option_description = mysql_real_escape_string($_REQUEST["dropdown_option_description"]);
	
	if($dropdown_option_id)
	{
		mysql_query(" UPDATE `optiondata` SET 
					`OptionType` = '".$dropdown_option_type."',
					`OptionName` = '".post_slug($dropdown_option_title)."',
					`OptionDesc` = '".$dropdown_option_description."'
					WHERE `OptionId` = '".$dropdown_option_id."'
				");
		
		Track('Dropdown Option [ '.$dropdown_option_title.' ] Edited Successfully ...!');
	}
	else
	{
		mysql_query(" INSERT INTO `optiondata` SET 
					`OptionType` = '".$dropdown_option_type."',
					`OptionName` = '".post_slug($dropdown_option_title)."',
					`OptionDesc` = '".$dropdown_option_description."'
				");
		
		Track('Dropdown Option [ '.$dropdown_option_title.' ] Added Successfully ...!');
	}
	
	$return = array();
	$return['status'] = 'success';
	
	echo json_encode($return);
}

elseif($_REQUEST["action"]=='dropdown_option_delete')
{
	$dropdown_option_title = GetData('OptionName', 'optiondata', 'OptionId', $_REQUEST["dropdown_option_id"]);
	
	mysql_query(" DELETE FROM `optiondata` WHERE OptionId = '".$_REQUEST["dropdown_option_id"]."' ");
	$num = mysql_affected_rows();
	
	if($num)
	{
		Track('Dropdown Option [ '.$dropdown_option_title.' ] Deleted Successfully ...!');
		echo $message = Alert('success', 'Dropdown Option [ '.$dropdown_option_title.' ] Deleted Successfully ...!');
	}
}
elseif($_REQUEST["action"]=='load_type_dropdown')
{
	echo '<option>Please Select</option>';
	$stmt = query(" SELECT * FROM `optiondata` WHERE Status = 1 and `OptionType` = '".$_REQUEST['dropdown_id']."' ORDER BY `OptionDesc` ");
	while( $rslt = fetch($stmt) ){
		echo '<option value="'.$rslt['OptionId'].'">'.$rslt['OptionDesc'].'</option>';
	}
}
elseif($_REQUEST["action"]=='dropdown_option_view')
{
?>
<table class="table table-striped table-bordered table-hover ">
  <?php
		$paging = getPaging('optiondata',"WHERE Status = 0 and OptionType='{$_REQUEST['dropdown_id']}' ORDER BY OptionId DESC",1000,'other-dropdowns.php','?',$_REQUEST['pager2']);
		$rs_pages = mysql_query($paging[0]) or die($paging[0]);
		$row = mysql_num_rows( $rs_pages );
		$pagination = $paging[1];?>
  <thead>
    <tr>
      <th width="35%">Option Value</th>
      <th class="hidden-480" width="13%">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
		  if($row>0){
			while( $rslt = mysql_fetch_array($rs_pages) ){
		  ?>
    <tr id="ddo_<?=$rslt['OptionId']?>">
      <td><?=$rslt['OptionDesc']?></td>
      <td><div class="visible-md visible-lg hidden-sm hidden-xs action-buttons"> <a class="green" href="javascript:void(0);" onclick="openEditDropdownName(<?=$_REQUEST['dropdown_id']?>, <?=$rslt['OptionId']?>, '<?=$rslt['OptionDesc']?>');"> <i class="icon-pencil bigger-130"></i> </a> </div></td>
    </tr>
    <? }
		  } else {
		  ?>
    <tr>
      <th class="center" colspan="4">No Records Found.!</th>
    </tr>
    <?
		  }
		  ?>
  <tfoot>
    <tr>
      <th class="center" colspan="4"><div class="pull-right">
          <?=$pagination?>
        </div></th>
    </tr>
  </tfoot>
    </tbody>
  
</table>
<?php
}

