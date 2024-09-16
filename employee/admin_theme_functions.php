<?php

function video_link($id){
	global $path;
	$stmt = mysql_query("SELECT * FROM `videos` WHERE `video_id` = '".$id."' ");
	$rslt = mysql_fetch_array($stmt);
	$link = $rslt['title'];

	$link = str_replace("/","-",$link);
	$link = str_replace(" ","-",$link);
	$link = str_replace("'","",$link);
	$link = str_replace(",","",$link);
	$link = str_replace("--","-",$link);
	$link = str_replace("--","-",$link);
	$link = str_replace("--","-",$link);
	$link = str_replace("--","-",$link);
	$link = strtolower($link);
	
	$link = 'video-'.$rslt['video_id'].'-'.$link; //.'.htm';
	return $path.$link;
}

function TotalOrg(){
	$stmt = mysql_query("SELECT * FROM `organizations` WHERE `org_userid` = '".$_SESSION['User']['ID']."' ");
	return $UserClasses = mysql_num_rows($stmt);
	
}


function Track($msg){
	@mysql_query(" INSERT INTO `admin_log` (`logid`, `logdatetime`, `lognotes`, `logstatus`, `logip`) VALUES (NULL, CURRENT_TIMESTAMP, '".$msg."', '0', '".$_SERVER['REMOTE_ADDR']."'); ");
	
}

function Alert($type, $message){
	
	if($type=='error'){
		return '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Error! </strong>'.$message.'<br></div>';
	}
	if($type=='success'){
		return '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>'.$message.'<br></div>';
	}
	
}


function TableAction($data){
	
	$editHREF = $data['edit']['href'];
	$deleteHREF = $data['delete']['href'];
	
	$html = '<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons"><a class="green" href="'.$editHREF.'" '.$data['edit']['js'].'> <i class="icon-pencil bigger-130"></i> </a> <a class="red" href="'.$deleteHREF.'" '.$data['delete']['js'].'> <i class="icon-trash bigger-130"></i> </a> </div>
			<div class="visible-xs visible-sm hidden-md hidden-lg">
			  <div class="inline position-relative">
				<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown"> <i class="icon-caret-down icon-only bigger-120"></i> </button>
				<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
				  <li> <a href="'.$editHREF.'" '.$data['edit']['js'].' class="tooltip-success" data-rel="tooltip" title="Edit"> <span class="green"> <i class="icon-edit bigger-120"></i> </span> </a> </li>
				  <li> <a href="'.$deleteHREF.'" '.$data['delete']['js'].' class="tooltip-error" data-rel="tooltip" title="Delete"> <span class="red"> <i class="icon-trash bigger-120"></i> </span> </a> </li>
				</ul>
			  </div>
			</div>';
	return $html;
	
}

function TableActions($data, $type='button'){
	$editHREF = $data['edit']['href'];
	$deleteHREF = $data['delete']['href'];
	if($type=='button'){
		$html = '<div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">';
		foreach($data as $val){
			$html .='<a href="'.$val['href'].'" '.$val['js'].'> '.$val['title'].' </a>';
		}
		$html .='</div>';
	}
	
	if($type=='menu'){
		$html = '
		<div class="btn-toolbar">
			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"> Actions <span class="icon-caret-down icon-on-right"></span> </button>
					<ul class="dropdown-menu pull-left">';
		foreach($data as $val){
			$html .='<li><a href="'.$val['href'].'" '.$val['js'].'> '.$val['title'].' </a></li>';
		}
		$html .='</ul>
			</div>
		</div>';
	}
	
	return $html;
}



?>