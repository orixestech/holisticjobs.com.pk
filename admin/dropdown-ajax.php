<?php



include("includes/conn.php");

include("admin_theme_functions.php");



$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];

//print_r($_REQUEST);





if($_REQUEST["action"]=='dropdown_name_add_edit')

{

	$dropdown_id = mysql_real_escape_string($_REQUEST["dropdown_id"]);

	$dropdown_title = mysql_real_escape_string($_REQUEST["dropdown_title"]);

	$dropdown_description = mysql_real_escape_string($_REQUEST["dropdown_description"]);

	

	if($dropdown_id)

	{

		mysql_query(" UPDATE `typedata` SET 

					`TypeFieldName` = '".post_slug($dropdown_title)."',

					`TypeDesc` = '".$dropdown_description."'

					WHERE `TypeId` = '".$dropdown_id."'

				");

		

		Track('Dropdown [ '.$dropdown_title.' ] Edited Successfully ...!');

	}

	else

	{

		mysql_query(" INSERT INTO `typedata` SET 

					`TypeFieldName` = '".post_slug($dropdown_title)."',

					`TypeDesc` = '".$dropdown_description."'

				");

		

		Track('Dropdown [ '.$dropdown_title.' ] Added Successfully ...!');

	}

	

	$return = array();

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
		$paging = getPaging('typedata',' WHERE `TypeId` in ( SELECT distinct `OptionType` FROM `optiondata` WHERE `Status` = 1 ) ORDER BY TypeId DESC',1000,'dropdowns-view.php','?',$_REQUEST['pager']);
		$rs_pages = mysql_query($paging[0]) or die($paging[0]);
		$row = mysql_num_rows( $rs_pages );
		$pagination = $paging[1];?>
  <thead>
    <tr>
      <th width="17%"><input type="checkbox" onclick="$('.dropdowncheckbox').prop('checked', $(this).prop('checked') );" />
        Mark All</th>
      <th width="30%">Key</th>
      <th width="35%">Description</th>
      <th>Options</th>
      <th class="hidden-480" width="18%">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php

	  if($row>0){
	
		while( $rslt = mysql_fetch_array($rs_pages) ){
		$options = total("SELECT * FROM `optiondata` WHERE Status = 1 and `OptionType` = '".$rslt['TypeId']."' ");
	
	  ?>
    <tr id="ddn_<?=$rslt['TypeId']?>">
      <th><input type="checkbox" name="dropdowns[]" id="dropdown_name_<?=$rslt['TypeId']?>" class="dropdowncheckbox" value="<?=$rslt['TypeId']?>" /></th>
      <td><?=$rslt['TypeFieldName']?></td>
      <td><?=$rslt['TypeDesc']?></td>
      <td><?=$options?></td>
      <td><div class="visible-md visible-lg hidden-sm hidden-xs action-buttons"> <a class="green" href="javascript:void(0);" onclick="openEditDropdownName(<?=$rslt['TypeId']?>, '<?=$rslt['TypeFieldName']?>', '<?=$rslt['TypeDesc']?>');"> <i class="icon-pencil bigger-130"></i> </a> <a class="red hide" href="javascript:void(0);" onclick="if(confirm('Are you sure you want to delete this dropdown?')){deleteDropdownName(<?=$rslt['TypeId']?>, '<?=$rslt['TypeFieldName']?>', '<?=$rslt['TypeDesc']?>');}"> <i class="icon-trash bigger-130"></i> </a> <a class="blue" href="javascript:void(0);" onclick="updateDropdownOptionGrid(<?=$rslt['TypeId']?>);"> <i class="icon-zoom-in bigger-130"></i> </a> </div></td>
    </tr>
    <? }

		  } else {

		  ?>
    <tr>
      <th class="center" colspan="5">No Records Found.!</th>
    </tr>
    <?

		  }

		  ?>
  <tfoot>
    <tr>
      <th class="center" colspan="5"><div class="pull-right">
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



elseif($_REQUEST["action"]=='dropdown_option_view')

{

?>
<table class="table table-striped table-bordered table-hover ">
  <?php
		$paging = getPaging('optiondata',"WHERE Status = 1 and OptionType='{$_REQUEST['dropdown_id']}' ORDER BY OptionId DESC",1000,'dropdowns-view.php','?',$_REQUEST['pager2']);

		

		$rs_pages = mysql_query($paging[0]) or die($paging[0]);

		$row = mysql_num_rows( $rs_pages );

		$pagination = $paging[1];?>
  <thead>
    <tr>
      <th width="17%"><input type="checkbox" onclick="$('.dropdowncheckbox2').prop('checked', $(this).prop('checked') );" />
        Mark All</th>
      <th width="35%">Option Key</th>
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
      <th><input type="checkbox" name="dropdowns_options[]" id="dropdown_option_<?=$rslt['OptionId']?>" class="dropdowncheckbox2" value="<?=$rslt['OptionId']?>" /></th>
      <td><?=$rslt['OptionName']?></td>
      <td><?=$rslt['OptionDesc']?></td>
      <td><?php
				$data = array(
						'edit' => array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'javascript:void(0);', 'js'=>'onclick="openEditDropdownOption('.$rslt['OptionId'].', \''.$rslt['OptionType'].'\', \''.$rslt['OptionName'].'\', \''.$rslt['OptionDesc'].'\')"'),
						'delete' => array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0);', 'js'=>'onclick="if(confirm(\'Are you sure you want to delete this drop down option?\')){ deleteDropdownOption('.$rslt['OptionId'].', \''.$rslt['OptionType'].'\', \''.$rslt['OptionName'].'\', \''.$rslt['OptionDesc'].'\')}"')
						);

				echo TableAction($data);

				?>
      </td>
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

