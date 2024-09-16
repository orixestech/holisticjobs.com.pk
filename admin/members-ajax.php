<?php



include("includes/conn.php");

include("admin_theme_functions.php");



$_SESSION['UserAccess'] = $_SESSION['User']['user_access'];

//print_r($_REQUEST);





if($_REQUEST["action"]=='members_add_edit')

{

	$member_id = mysql_real_escape_string($_REQUEST["member_id"]);

	

	$username		 = mysql_real_escape_string($_REQUEST["username"]);

	$password		 = mysql_real_escape_string($_REQUEST["password"]);

	$firstname		 = mysql_real_escape_string($_REQUEST["firstname"]);

	$lastname		 = mysql_real_escape_string($_REQUEST["lastname"]);

	$email			 = mysql_real_escape_string($_REQUEST["email"]);

	//$avatar			 = mysql_real_escape_string($_REQUEST["avatar"]);

	$country		 = mysql_real_escape_string($_REQUEST["country"]);

	$city			 = mysql_real_escape_string($_REQUEST["city"]);

	$company_name	 = mysql_real_escape_string($_REQUEST["company_name"]);

	$profile_heading = mysql_real_escape_string($_REQUEST["profile_heading"]);

	$profile_text	 = mysql_real_escape_string($_REQUEST["profile_text"]);

	

	if($member_id)

	{
		$sql = " UPDATE `members` SET 

					`username` = '".$username."',

					`password` = '".Password($password, "hide")."',

					`firstname` = '".$firstname."',

					`lastname` = '".$lastname."',

					`email` = '".$email."',

					`country` = '".$country."',

					`city` = '".$city."',

					`company_name` = '".$company_name."',

					`profile_heading` = '".$profile_heading."',

					`profile_text` = '".$profile_text."',

					`modified` = NOW()

					WHERE `id` = '".$member_id."'

				";
		mysql_query($sql);

		//`avatar` = '".$avatar."',

		Track('Member [ '.$firstname.' '.$lastname.' ] Edited Successfully ...!');

	}

	else

	{
		$sql = " INSERT INTO `members` SET 

					`username` = '".$username."',

					`password` = '".Password($password, "hide")."',

					`firstname` = '".$firstname."',

					`lastname` = '".$lastname."',

					`email` = '".$email."',

					`country` = '".$country."',

					`city` = '".$city."',

					`company_name` = '".$company_name."',

					`profile_heading` = '".$profile_heading."',

					`profile_text` = '".$profile_text."',

					`status` = '2',

					`created` = NOW()

				";
		mysql_query($sql);
		$member_id = mysql_insert_id();
		//`avatar` = '".$avatar."',

		Track('Member [ '.$firstname.' '.$lastname.' ] Added Successfully ...!');

	}
	
	$options = array('facebook','twitter','google-plus','linked-in','youtube','vimeo','tuts','tumblr','sound-cloud','reddit','my-space','last-fm','github','flickr','dribbble','digg','deviantart','behance');
	for($a=0; $a<count($options); $a++){
		
		if($_REQUEST[$options[$a]]){
			$val = $_REQUEST[$options[$a]];
			$searchQuery = "SELECT * FROM `member_setting` WHERE `member_id` = '".$member_id."' and option_name = '".$options[$a]."' ";
			$total = total( $searchQuery );
			
			if($total==0){
				$finalSQL = " INSERT INTO `member_setting` (`option_id`, `member_id`, `option_name`, `option_value`, `options_title`, `autoload`) VALUES (NULL, '".$member_id."', '".$options[$a]."', '".$val."', '".$options[$a]."', 'yes') ";
			} else { 
				$stmt = query( $searchQuery );
				$fetch = fetch( $stmt );
				$finalSQL = " UPDATE `member_setting` SET `option_value` = '".$val."' WHERE `member_setting`.`option_id` = '".$fetch['option_id']."' ";
			}
			//echo $finalSQL;
			query( $finalSQL );
		}
		
	}

	$return = array();
	$return['status'] = 'success';
	echo json_encode($return);
}



elseif($_REQUEST["action"]=='members_delete')

{

	$fullname = fetch ( query ( "SELECT CONCAT(firstname, '&nbsp;' , lastname) as name FROM `members` WHERE `id` = '".$_REQUEST["member_id"]."' " ) ); $fullname = $fullname[0];
	mysql_query(" DELETE FROM `members` WHERE id = '".$_REQUEST["member_id"]."' ");

	$num = mysql_affected_rows();

	

	if($num)

	{

		Track('Member [ '.$fullname.' ] Deleted Successfully ...!');

		echo $message = Alert('success', 'Member [ '.$fullname.' ] Deleted Successfully ...!');

	}

}



elseif($_REQUEST["action"]=='members_status')

{
	$fullname = fetch ( query ( "SELECT CONCAT(firstname, '&nbsp;' , lastname) as name FROM `members` WHERE `id` = '".$_REQUEST["member_id"]."' " ) );  $fullname = $fullname[0];
	$sql = " UPDATE `members` SET `status` = '".$_REQUEST["status"]."' WHERE `members`.`id` = '".$_REQUEST["member_id"]."'; ";
	//$return['sql'] = $sql; echo json_encode($return);
	mysql_query($sql);
	$num = mysql_affected_rows();
	$return = array();
	if($num)
	{
		Track('Member [ '.$fullname.' ] Status Updated Successfully ...!');
		$return['status'] = 'success';
		$return['data'] = Alert('success', 'Member [ '.$fullname.' ] Status Updated Successfully ...!');
	}
	else
	{
		$return['status'] = 'failure';
		$return['data'] = '';
	}
	echo json_encode($return);
}



elseif($_REQUEST["action"]=='members_load')

{

	$query = " SELECT * FROM `members` WHERE id = '".$_REQUEST["member_id"]."' ";

	//echo $query;

	

	$member = query($query);

	$record = fetch($member);

	//echo '<pre>'; print_r($record); echo '</pre>';

	

	$record['password'] = Password($record['password'], "show");

	

	$return = array();

	$return['status'] = 'success';

	$return['data'] = $record;

	

	echo json_encode($return);

}



elseif($_REQUEST["action"]=='members_view')

{

?>

	<table class="table table-striped table-bordered table-hover ">

		<?php

		/**

		*

		* @param: Db table

		* @param: Everything after FROM clause.

		* @param: No. of per page records.

		* @param: Paging URL.

		* @param: Query string of paging URL.

		* @param: current page pager count.

		*/

		$paging = getPaging('members',' ORDER BY last_signin DESC, created DESC',100,'members-view.php','?',$_REQUEST['pager']);

		

		$rs_pages = mysql_query($paging[0]) or die($paging[0]);

		$row = mysql_num_rows( $rs_pages );

		$pagination = $paging[1];?>



		<thead>

		  <tr>

			<th width="10%">

			<input type="checkbox" onclick="$('.memberscheckbox').prop('checked', $(this).prop('checked') );" />

			Mark All</th>

			<th width="15%">Full Name</th>

			<th width="15%">Email</th>

			<th width="15%">Country</th>

			<th width="10%">Created Date</th>

			<th width="15%">Last Sign In Date</th>

			<th width="10%">Status</th>

			<th class="hidden-480" width="10%">Action</th>

		  </tr>

		</thead>



		<tbody>

		  <?php

		  if($row>0){

			while( $rslt = mysql_fetch_array($rs_pages) ){

		  ?>

		  <tr id="row_<?=$rslt['id']?>">

			<th><input type="checkbox" name="members[]" id="member_<?=$rslt['id']?>" class="memberscheckbox" value="<?=$rslt['id']?>" /></th>

			<td><?=$rslt['firstname'].' '.$rslt['lastname']?></td>

			<td><?=$rslt['email']?></td>

			<td><?=$rslt['country']?></td>

			<td><?php if($rslt['created']) echo date("d-m-Y h:i:s A", strtotime($rslt['created']))?></td>

			<td><?php if($rslt['last_signin']) echo date("d-m-Y h:i:s A", strtotime($rslt['last_signin']))?></td>

			<td>

			<div class="btn-group">

				<button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['status']==1)?'success':'danger'?> dropdown-toggle">

				<?=($rslt['status']==1)?' Active ':' Block '?>

				<i class="icon-angle-down icon-on-right"></i>

				</button>

				<ul class="dropdown-menu">

				<li><a href="javascript:void(0);" onclick="openStatusMember(<?=$rslt['id']?>, 1);">Active</a></li>

				<li><a href="javascript:void(0);" onclick="openStatusMember(<?=$rslt['id']?>, 0);">Block</a></li>

				</ul>

			</div>

			</td>

			<td><?php

				$data = array(

						'edit' => array('href'=>'javascript:void(0);', 'js'=>'onclick="openEditMember('.$rslt['id'].')"'),

						'delete' => array('href'=>'javascript:void(0);', 'js'=>'onclick="if(confirm(\'Are you sure you want to delete this member?\')){deleteMember('.$rslt['id'].')}"')

						);

				echo TableAction($data);

				?>

			</td>

		  </tr>

		  <? }

		  } else {

		  ?>

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



elseif($_REQUEST["action"]=='members_dashboard_view')

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

	$paging = getPaging('members',' ORDER BY created DESC',$no_of_records,'index.php','?',1);

	//print_r($paging);

	

	$rs_pages = mysql_query($paging[0]) or die($paging[0]);

	$row = mysql_num_rows( $rs_pages );

	//$pagination = $paging[1];

	

	if($row>0){

		while( $rslt = mysql_fetch_array($rs_pages) ){

?>

	<div class="itemdiv memberdiv">

	  <div class="user"> <img alt="<?=$rslt['firstname'].' '.$rslt['lastname']?> avatar" src="<?php if($rslt['avatar']) echo MEMBER_IMAGE_DIRECTORY.$rslt['avatar']; else echo "../images/avatar.png"; ?>" /> </div>

	  <div class="body">

		<div class="name"> <a href="#"><?=$rslt['firstname'].' '.$rslt['lastname']?></a> </div>

		<div class="time"> <i class="icon-time"></i> <span class="green"><?php echo time_ago_calculator($rslt['created']); ?></span> </div>

		

		<?php if($rslt['status'] == 2) :?>

		<div> <span class="label label-warning label-sm">pending</span>

		  <div class="inline position-relative">

			<button class="btn btn-minier bigger btn-yellow btn-no-border dropdown-toggle" data-toggle="dropdown"> <i class="icon-angle-down icon-only bigger-120"></i> </button>

			<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">

			  <li> <a href="javascript:void(0);" onclick="openStatusMember(<?=$rslt['id']?>, 1);" class="tooltip-success" data-rel="tooltip" title="Approve"> <span class="green"> <i class="icon-ok bigger-110"></i> </span> </a> </li>

			  <li> <a href="javascript:void(0);" onclick="openStatusMember(<?=$rslt['id']?>, 0);" class="tooltip-warning" data-rel="tooltip" title="Reject"> <span class="orange"> <i class="icon-remove bigger-110"></i> </span> </a> </li>

			  <li> <a href="javascript:void(0);" onclick="if(confirm('Are you sure you want to delete this member?')){deleteMember(<?=$rslt['id']?>)}" class="tooltip-error" data-rel="tooltip" title="Delete"> <span class="red"> <i class="icon-trash bigger-110"></i> </span> </a> </li>

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



elseif($_REQUEST["action"]=='members_dashboard_messages')

{

	$no_of_records = 5;

	$max_message_lenght = 140;

	

	$query = "

	SELECT CONCAT(m.`firstname`, ' ' ,m.`lastname`) member_name, m.avatar, mm.*

	FROM members m INNER JOIN member_messages mm ON m.`id`=mm.`from_id`

	WHERE mm.`id` = (SELECT MAX(mm2.id) FROM member_messages mm2

	WHERE mm2.`from_id` = m.`id`)

	ORDER BY mm.`id` DESC

	LIMIT $no_of_records;

	";

	//echo $query;

	

	$rs_pages = query($query);

	//echo '<pre>'; print_r($rs_pages); echo '</pre>';

	

	while( $rslt = fetch($rs_pages) ){

		//echo '<pre>'; print_r($rslt); echo '</pre>';

?>

	<div class="itemdiv dialogdiv">

	  <div class="user"> <img alt="<?=$rslt['member_name']?> avatar" src="<?php if($rslt['avatar']) echo MEMBER_IMAGE_DIRECTORY.$rslt['avatar']; else echo "../images/avatar.png"; ?>" /> </div>

	  <div class="body">

		<div class="time"> <i class="icon-time"></i> <span class="green"><?php echo time_ago_calculator($rslt['created']); ?></span> </div>

		<div class="name"> <a href="#"><?=$rslt['member_name']?></a> </div>

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

		?></div>

		<div class="tools"> <a href="#" class="btn btn-minier btn-info"> <i class="icon-only icon-share-alt"></i> </a> </div>

	  </div>

	</div>

<?php

	}

}



