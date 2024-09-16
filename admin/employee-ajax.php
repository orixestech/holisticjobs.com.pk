<?php



include("includes/conn.php");

include("admin_theme_functions.php");



$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];

//print_r($_REQUEST);





if($_REQUEST["action"]=='faq_add_edit')

{

	$faq_id = mysql_real_escape_string($_REQUEST["faq_id"]);
	$fq_qs		 = mysql_real_escape_string($_REQUEST["fq_qs"]);
	$fq_cat		 = mysql_real_escape_string($_REQUEST["fq_cat"]);
	$fq_ans		 = mysql_real_escape_string($_REQUEST["fq_ans"]);
	if($faq_id)

	{

		mysql_query(" UPDATE `faqs` SET 
					`fq_qs` = '".$fq_qs."',
					`fq_ans` = '".$fq_ans."',
					`fq_cat` = '".$fq_cat."',
					`fq_modifydate` = NOW()
					WHERE `fq_id` = '".$faq_id."'

				");

	}

	else

	{

		mysql_query(" INSERT INTO `faqs` SET 

					`fq_qs` = '".$fq_qs."',
					`fq_ans` = '".$fq_ans."',
					`fq_cat` = '".$fq_cat."',
					`status` = '1',

					`fq_createdate` = NOW()

				");

	}

	

	$return = array();

	$return['status'] = 'success';

	

	echo json_encode($return);

}



elseif($_REQUEST["action"]=='faq_delete')

{
	mysql_query(" DELETE FROM `faqs` WHERE fq_id = '".$_REQUEST["faq_id"]."' ");
	$num = mysql_affected_rows();
	if($num)
	{
		echo $message = Alert('success', 'Help & Support Content Deleted Successfully ...!');
	}
}



elseif($_REQUEST["action"]=='faq_status')

{
	mysql_query(" UPDATE `faqs` SET `fq_status`= '".$_REQUEST["status"]."' WHERE fq_id = '".$_REQUEST["faq_id"]."' ");
	$num = mysql_affected_rows();
	$return = array();
	if($num)
	{
		$return['status'] = 'success';
		$return['data'] = Alert('success', 'Help & Support Content  Status Updated Successfully ...!');
	}
	else
	{
		$return['status'] = 'failure';
		$return['data'] = '';
	}
	echo json_encode($return);
}



elseif($_REQUEST["action"]=='faq_load')
{
	$query = " SELECT * FROM `faqs` WHERE fq_id = '".$_REQUEST["faq_id"]."' ";
	//echo $query;
	$faq = query($query);
	$record = fetch($faq);
	$return = array();
	$return['status'] = 'success';
	$return['data'] = $record;
	echo json_encode($return);

}



elseif($_REQUEST["action"]=='faq_view')

{

?>

<table class="table table-striped table-bordered table-hover ">
  <?php
	$paging = getPaging('faqs'," LEFT JOIN  `category` ON (`category`.`id` = `faqs`.`fq_cat`) WHERE (`category`.`type` = 'Support' ) ORDER BY `category`.`title`, `faqs`.`fq_modifydate` DESC ",50,'faq-view.php','?',$_REQUEST['pager']);
	$rs_pages = mysql_query($paging[0]) or die($paging[0]);
	$row = mysql_num_rows( $rs_pages );
	$pagination = $paging[1];?>
  <thead>
    <tr>
      <th width="85"> <input type="checkbox" onclick="$('.faqcheckbox').prop('checked', $(this).prop('checked') );" />
        Mark All</th>
      <th width="15%">Category</th>
      <th>Heading</th>
      <th>Description</th>
      <th width="10%">Date</th>
      <th width="85">Status</th>
      <th class="hidden-480" width="85">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
  if($row>0){
	while( $rslt = mysql_fetch_array($rs_pages) ){
  ?>
    <tr id="row_<?=$rslt['fq_id']?>">
      <th><input type="checkbox" name="faq[]" id="faq_<?=$rslt['fq_id']?>" class="faqcheckbox" value="<?=$rslt['fq_id']?>" /></th>
      <td><?=$rslt['title']?></td>
      <td><?=$rslt['fq_qs']?></td>
      <td><?=$rslt['fq_ans']?></td>
      <td><?php if($rslt['fq_modifydate']) echo date("d-m-Y h:i:s A", strtotime($rslt['fq_modifydate']))?></td>
      <td><div class="btn-group">
          <button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['fq_status']==1)?'success':'danger'?> dropdown-toggle">
          <?=($rslt['fq_status']==1)?' Active ':' Block '?>
          <i class="icon-angle-down icon-on-right"></i> </button>
          <ul class="dropdown-menu">
            <li><a href="javascript:void(0);" onclick="openStatusfaq(<?=$rslt['fq_id']?>, 1);">Active</a></li>
            <li><a href="javascript:void(0);" onclick="openStatusfaq(<?=$rslt['fq_id']?>, 0);">Block</a></li>
          </ul>
        </div></td>
      <td><?php
		$data = array(
				'edit' => array('href'=>'javascript:void(0);', 'js'=>'onclick="openEditfaq('.$rslt['fq_id'].')"'),
				'delete' => array('href'=>'javascript:void(0);', 'js'=>'onclick="if(confirm(\'Are you sure you want to delete this Support Content?\')){deletefaq('.$rslt['fq_id'].')}"')
				);
		echo TableAction($data); ?>
      </td>
    </tr>
    <? }
	} else {  ?>
    <tr>
      <th class="center" colspan="8">No Records Found.!</th>
    </tr>
    <?

		  }

		  ?>
  <tfoot>
    <tr>
      <th class="center" colspan="8"><div class="pull-right">
          <?=$pagination?>
        </div></th>
    </tr>
  </tfoot>
  </tbody>
  
</table>
<?php

}



elseif($_REQUEST["action"]=='faq_dashboard_view')

{

	$no_of_records = 12;

	

	/**

	*

	* @param: Db table

	* @param: Everything after FROM clause.

	* @param: No. of per page records.

	* @param: Paging URL.

	* @param: Query string of paging URL.

	* @param: current page pager count.

	*/

	$paging = getPaging('faq',' ORDER BY created DESC',$no_of_records,'index.php','?',1);

	//print_r($paging);

	

	$rs_pages = mysql_query($paging[0]) or die($paging[0]);

	$row = mysql_num_rows( $rs_pages );

	//$pagination = $paging[1];

	

	if($row>0){

		while( $rslt = mysql_fetch_array($rs_pages) ){

?>
<div class="itemdiv faqdiv">
  <div class="user"> <img alt="<?=$rslt['firstname'].' '.$rslt['lastname']?> avatar" src="<?php if($rslt['avatar']) echo faq_IMAGE_DIRECTORY.$rslt['avatar']; else echo "../images/avatar.png"; ?>" /> </div>
  <div class="body">
    <div class="name"> <a href="#">
      <?=$rslt['firstname'].' '.$rslt['lastname']?>
      </a> </div>
    <div class="time"> <i class="icon-time"></i> <span class="green"><?php echo time_ago_calculator($rslt['created']); ?></span> </div>
    <?php if($rslt['status'] == 2) :?>
    <div> <span class="label label-warning label-sm">pending</span>
      <div class="inline position-relative">
        <button class="btn btn-minier bigger btn-yellow btn-no-border dropdown-toggle" data-toggle="dropdown"> <i class="icon-angle-down icon-only bigger-120"></i> </button>
        <ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
          <li> <a href="javascript:void(0);" onclick="openStatusfaq(<?=$rslt['id']?>, 1);" class="tooltip-success" data-rel="tooltip" title="Approve"> <span class="green"> <i class="icon-ok bigger-110"></i> </span> </a> </li>
          <li> <a href="javascript:void(0);" onclick="openStatusfaq(<?=$rslt['id']?>, 0);" class="tooltip-warning" data-rel="tooltip" title="Reject"> <span class="orange"> <i class="icon-remove bigger-110"></i> </span> </a> </li>
          <li> <a href="javascript:void(0);" onclick="if(confirm('Are you sure you want to delete this faq?')){deletefaq(<?=$rslt['id']?>)}" class="tooltip-error" data-rel="tooltip" title="Delete"> <span class="red"> <i class="icon-trash bigger-110"></i> </span> </a> </li>
        </ul>
      </div>
    </div>
    <?php elseif($rslt['status'] == 1) :?>
    <div> <span class="label label-success label-sm arrowed-in">approved</span> </div>
    <?php elseif($rslt['status'] == 0) :?>
    <div> <span class="label label-danger label-sm">blocked</span> </div>
    <?php endif; ?>
  </div>
</div>
<?php

		}

	}

}



elseif($_REQUEST["action"]=='faq_dashboard_messages')

{

	$no_of_records = 5;

	$max_message_lenght = 140;

	

	$query = "

	SELECT CONCAT(m.`firstname`, ' ' ,m.`lastname`) faq_name, m.avatar, mm.*

	FROM faq m INNER JOIN faq_messages mm ON m.`fq_id`=mm.`from_id`

	WHERE mm.`fq_id` = (SELECT MAX(mm2.id) FROM faq_messages mm2

	WHERE mm2.`from_id` = m.`fq_id`)

	ORDER BY mm.`fq_id` DESC

	LIMIT $no_of_records;

	";

	//echo $query;

	

	$rs_pages = query($query);

	//echo '<pre>'; print_r($rs_pages); echo '</pre>';

	

	while( $rslt = fetch($rs_pages) ){

		//echo '<pre>'; print_r($rslt); echo '</pre>';

?>
<div class="itemdiv dialogdiv">
  <div class="user"> <img alt="<?=$rslt['faq_name']?> avatar" src="<?php if($rslt['avatar']) echo faq_IMAGE_DIRECTORY.$rslt['avatar']; else echo "../images/avatar.png"; ?>" /> </div>
  <div class="body">
    <div class="time"> <i class="icon-time"></i> <span class="green"><?php echo time_ago_calculator($rslt['created']); ?></span> </div>
    <div class="name"> <a href="#">
      <?=$rslt['faq_name']?>
      </a> </div>
    <div class="text">
      <?php 

		if(strlen($rslt['message']) > $max_message_lenght)

		{

			echo substr($rslt['message'], 0, strrpos(substr($rslt['message'], 0, $max_message_lenght), ' '));

			echo "...";

		}

		else

		{

			echo $rslt['message'];

		}

		?>
    </div>
    <div class="tools"> <a href="#" class="btn btn-minier btn-info"> <i class="icon-only icon-share-alt"></i> </a> </div>
  </div>
</div>
<?php

	}

}



