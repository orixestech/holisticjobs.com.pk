	<?php

function time_ago ($tm, $rcs = 0) {

  $cur_tm = time(); 

  $dif = $cur_tm - $tm;

  $pds = array('second','minute','hour','day','week','month','year','decade');

  $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);



  for ($v = count($lngh) - 1; ($v >= 0) && (($no = $dif / $lngh[$v]) <= 1); $v--);

    if ($v < 0)

      $v = 0;

  $_tm = $cur_tm - ($dif % $lngh[$v]);



  $no = ($rcs ? floor($no) : round($no)); // if last denomination, round



  if ($no != 1)

    $pds[$v] .= 's';

  $x = $no . ' ' . $pds[$v];



  if (($rcs > 0) && ($v >= 1))

    $x .= ' ' . $this->time_ago($_tm, $rcs - 1);



  return $x;

}



function OrderCode($uid){

		$code = "YY-";

		$code .= str_repeat("0",5-strlen($uid));

		return $code.$uid;

}



function GetWeekRange($day='', $month='', $year='') {

        // default empties to current values

        if (empty($day)) $day = date('d');

        if (empty($month)) $month = date('m');

        if (empty($year)) $year = date('Y');

        // do some figurin'

        $weekday = date('w', mktime(0,0,0,$month, $day, $year));

        $sunday  = $day - $weekday;

        $start_week = date('Y-m-d', mktime(0,0,0,$month, $sunday, $year));

        $end_week   = date('Y-m-d', mktime(0,0,0,$month, $sunday+6, $year));

        if (!empty($start_week) && !empty($end_week)) {

            return array('first'=>$start_week, 'last'=>$end_week);

        }

        // otherwise there was an error :'(

        return false;

    } 

function rangeMonth($datestr) {

    date_default_timezone_set(date_default_timezone_get());

    $dt = strtotime($datestr);

    $res['start'] = date('Y-m-d', strtotime('first day of this month', $dt));

    $res['end'] = date('Y-m-d', strtotime('last day of this month', $dt));

    return $res;

    }



function getList($query)

{

	$ex = mysql_query($query) or die($query);

	if(mysql_num_rows($ex) > 0)

	{

		return $ex;

	}

	return false;

}



function fetch($ex)

{

	return mysql_fetch_array($ex);

}



	

function GetContent($page,$field){ 

		$Q = "SELECT * FROM contents WHERE page_physical_name = '".$page."' ";

		$sql = mysql_query($Q);

		$rslt = mysql_fetch_array( $sql );

		return stripcslashes($rslt[$field]);

}



function GetData($getfield,$tablename,$field,$uid){

	$sql = mysql_query("SELECT * FROM $tablename where $field  = '$uid'") or die( mysql_error() );

	$rslt = mysql_fetch_array( $sql );

	return $rslt[$getfield];

}



function FormData($table_name, $query_type, $data, $option, $view=false ){

	

	if($query_type == 'insert'){

		$main_insert = "Insert into $table_name set ";

	}

	if($query_type == 'update'){

		$main_insert = "update $table_name set ";

	}



	$query = mysql_query("SELECT * FROM $table_name");

	$columns = mysql_num_fields($query);

	

	for($i = 0; $i < $columns; $i++) {

		$field_name =  mysql_field_name($query,$i);

		if(isset($data[$field_name]))

			$insert_qry[] = " $field_name = '".mysql_real_escape_string($data[$field_name])."' ";

	} 

	

	$insert_qry[] = implode(" , ",$insert_qry);

	$main_insert .= $insert_qry[count($insert_qry)-1];

	

	if($query_type == 'update'){

		$main_insert .= " where ".$option;

	}

	

	if($view == "true"){

		return "Query ...! [ ".$main_insert." ]";

	} else {

		if(mysql_query($main_insert)){

			if($query_type == 'insert'){

				return mysql_insert_id();	

			}

			if($query_type == 'update'){

				return "Update:Success...!";	

			}

	

		} else {

			return "Query Failed ...! ( ".$main_insert." )";

		}

	}

	

}



function total($sql) {

  $r = mysql_query($sql);

  $row = mysql_num_rows($r);

  return $row;

}



function CountryList($nameID='Country', $selected='', $requir=false) {

	$qry = mysql_query("SELECT * FROM `Country` WHERE 1 order by `Name`");

	if($requir){ $reqClass = 'class="validate[required]"';}

	$form_select = '<select name="'.$nameID.'" id="'.$nameID.'" '.$reqClass.'><option value="">Please Select</option>';

	while( $rslt = mysql_fetch_array($qry) ){

		if($selected == $rslt["CountryID"] && $selected!=''){

			$form_select .= '<option value="'.$rslt["CountryID"].'" selected="selected" >'.$rslt["Name"].'</option>';

		} else {

			$form_select .= '<option value="'.$rslt["CountryID"].'">'.$rslt["Name"].'</option>';

		}

	}

	$form_select .= '</select>';

	return $form_select;

}





function formList($field, $selected, $nameID='',$requir=false, $JS='') {

	if($nameID==''){$nameID=$field;}

	if($JS!=''){$JS = ' onchange="'.$JS.'" ';}

	if($requir){ $reqClass = ' class="validate[required]" ';}

	$qry = mysql_query("SELECT * FROM `OptionData` WHERE `OptionType` in (SELECT `TypeId` FROM `TypeData` WHERE `TypeFieldName` = '".$field."') order by OptionName");

	$form_select = '<select name="'.$nameID.'" id="'.$nameID.'" '.$reqClass.$JS.'><option value="">Please Select</option>';

	while( $rslt = mysql_fetch_array($qry) ){

		if($selected == $rslt["OptionId"]){

			$form_select .= '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionName"].'</option>';

		} else {

			$form_select .= '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionName"].'</option>';

		}

	}

	$form_select .= '</select>';

	return $form_select;

}



function formListOpt($field, $selected, $nameID='',$requir=false, $JS='') {

	if($nameID==''){$nameID=$field;}

	if($JS!=''){$JS = ' onchange="'.$JS.'" ';}

	if($requir){ $reqClass = ' class="validate[required]" ';}

	$qry = mysql_query("SELECT * FROM `OptionData` WHERE `OptionType` in (SELECT `TypeId` FROM `TypeData` WHERE `TypeFieldName` = '".$field."') order by OptionName");

	$form_select = '<option value="">Please Select</option>';

	while( $rslt = mysql_fetch_array($qry) ){

		if($selected == $rslt["OptionId"]){

			$form_select .= '<option value="'.$rslt["OptionId"].'" selected="selected" >'.$rslt["OptionName"].'</option>';

		} else {

			$form_select .= '<option value="'.$rslt["OptionId"].'">'.$rslt["OptionName"].'</option>';

		}

	}

	$form_select .= '';

	return $form_select;

}



function optionVal($uid){

	$stmt = mysql_query("SELECT `OptionName` FROM `OptionData` WHERE `OptionId` = '".$uid."' limit 1 ");

	$rslt = mysql_fetch_array($stmt);

	return $rslt[0];

}









function FormValue( $table_name, $query_string ){

	

	$query = mysql_query("SELECT * FROM $table_name $query_string");

	$columns = mysql_num_fields($query); $js='<script type="text/javascript">$(document).ready(function() { ';

	$field_value =  mysql_fetch_array($query); 

	for($i = 0; $i < $columns; $i++) {

		$field_name =  mysql_field_name($query,$i);

		$js .= '$("#'.$field_name.'").val("'.$field_value[$field_name].'");'; 

	} 

	

	$js .= "});</script>";	

	return $js;

}

 function upload_img_and_thumbnail($filename, $file_type, $file_size, $tmp_name, $path_original, $path_thumbs, $path_image, $img_thumb_width, $img_thumb_height, $img_big_width, $img_big_height)

	  {      

        $limit_size=2097152;

		if($file_size >= $limit_size){

		 $msg= 1; // Over limit

		} else {

		

		 if(isset($filename) && $filename != NULL )

		 { 

		  $temp = explode('.',$filename);   

		  $extension=end(explode('.',$filename));

		  $nameStart = $temp[0];

		 

		  $thumbname=date("Ymd-Hms").rand(0,9999999);		 

		  $filename=$thumbname.".".$extension;		 

		  $uploadFile = $path_original.$filename;

		  

		  if (move_uploaded_file($tmp_name, $uploadFile))

		   {



		   $add = $path_original.$filename;			

		  ///////// Start the thumbnail generation//////////////  

		  

		   $extlimit = "yes"; //Limit allowed extensions? (no for all extensions allowed)

		  //List of allowed extensions if extlimit = yes

		   $limitedext = array(".gif",".jpg",".png",".jpeg",".bmp");

  

  //the image -> variables

   $file_name = $filename;

   $file_tmp = $add;

 

   //check if you have selected a file.

   if(!file_exists($file_tmp)){

      echo "Error: Please select a file to upload!. <br>--<a href=\"$_SERVER[PHP_SELF]\">back</a>";

      exit(); //exit the script and don't process the rest of it!

   }

       //check the file's extension

        $ext = strrchr($file_name,'.');

        $ext = strtolower($ext);

       //uh-oh! the file extension is not allowed!

        if (($extlimit == "yes") && (!in_array($ext,$limitedext))) {

           echo "Wrong file extension.  <br>--<a href=\"$_SERVER[PHP_SELF]\">back</a>";

           exit();

         }

       //so, whats the file's extension?

     $getExt = explode ('.', $file_name);

     $file_ext = $getExt[count($getExt)-1];

 

     //create a random file name      

     $rand_name= $thumbname;//.$extension;

     //the new width variable

     //$ThumbWidth = $img_thumb_width;

 

    /////////////////////////////////////////

    // CREATE THE THUMBNAIL And Large image//

    /////////////////////////////////////////

    

       //keep image type

      if($file_size){

      if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){

        $new_img = imagecreatefromjpeg($file_tmp);

       }elseif($file_type == "image/x-png" || $file_type == "image/png"){

        $new_img = imagecreatefrompng($file_tmp);

       }elseif($file_type == "image/gif"){

        $new_img = imagecreatefromgif($file_tmp);

       }elseif($file_type == "image/bmp"){

        $new_img = imagecreatefromwbmp($file_tmp);

       }

	   

	   //list the width and height and keep the height ratio.

       list($width, $height) = getimagesize($file_tmp);

       	

	   // Large image code Starts here

		if($width>$height){		

	    	$newwidth=$img_big_width;

			$newheight=($height/$width)*$newwidth;

		}else{

			$newheight=$img_big_height;

			$newwidth=($width/$height)*$newheight;		

		}

		

		$tmp=imagecreatetruecolor($newwidth,$newheight);

		

		imagesavealpha($tmp, true);



		// Create some colors

		$white = imagecolorallocate($tmp, 255, 255, 255);

		$grey = imagecolorallocate($tmp, 128, 128, 128);

		$black = imagecolorallocate($tmp, 0, 0, 0);

		imagefilledrectangle($tmp, 0, 0, 150, 25, $black);

		$trans_colour = imagecolorallocatealpha($tmp, 0, 0, 0, 127);

		imagefill($tmp, 0, 0, $trans_colour);



		imagecopyresampled($tmp,$new_img,0,0,0,0,$newwidth,$newheight,$width,$height);

//		imagejpeg($tmp,$path_image.'/'.$rand_name.'.'.$file_ext,100);

		imagepng($tmp,$path_image.'/'.$rand_name.'.'.$file_ext);		

		imagedestroy($tmp);

		

		// Large image code Ends here

		

		

		

		// Thumbnail code

		if($width>$height){		

	    	$newwidth1=$img_thumb_width;

			$newheight1=($height/$width)*$newwidth1;

		}else{

			$newheight1=$img_thumb_height;

			$newwidth1=($width/$height)*$newheight1;		

		}		

		$tmp1=imagecreatetruecolor($newwidth1,$newheight1);

		imagesavealpha($tmp1, true);



		// Create some colors

		$white = imagecolorallocate($tmp1, 255, 255, 255);

		$grey = imagecolorallocate($tmp1, 128, 128, 128);

		$black = imagecolorallocate($tmp1, 0, 0, 0);

		imagefilledrectangle($tmp1, 0, 0, 150, 25, $black);

		$trans_colour = imagecolorallocatealpha($tmp1, 0, 0, 0, 127);

		imagefill($tmp1, 0, 0, $trans_colour);

	

		

		imagecopyresampled($tmp1,$new_img,0,0,0,0,$newwidth1,$newheight1,$width,$height);

		imagepng($tmp1,$path_thumbs.'/'.$rand_name.'.'.$file_ext);

		imagedestroy($tmp1);

		

		imagedestroy($new_img);		

       

    }

         

   }         



  }



  } 

 return $filename;



 } 

 

function strreplace($str)

{	

	$string=str_replace("À","&Agrave;",strip_tags(stripslashes($str)));

	$string=str_replace("à","&agrave;",strip_tags(stripslashes($string)));

	$string=str_replace("Â","&Acirc;",strip_tags(stripslashes($string)));

	$string=str_replace("â","&acirc;",strip_tags(stripslashes($string)));

	$string=str_replace("Æ","&AElig;",strip_tags(stripslashes($string)));

	$string=str_replace("æ","&aelig;",strip_tags(stripslashes($string)));

	$string=str_replace("Ç","&Ccedil;",strip_tags(stripslashes($string)));

	$string=str_replace("ç","&ccedil;",strip_tags(stripslashes($string)));

	$string=str_replace("È","&Egrave;",strip_tags(stripslashes($string)));

	$string=str_replace("è","&egrave;",strip_tags(stripslashes($string)));

	$string=str_replace("É","&Eacute;",strip_tags(stripslashes($string)));

	$string=str_replace("é","&eacute;",strip_tags(stripslashes($string)));

	$string=str_replace("Ê","&Ecirc;",strip_tags(stripslashes($string)));

	$string=str_replace("ê","&ecirc;",strip_tags(stripslashes($string)));

	$string=str_replace("Ë","&Euml;",strip_tags(stripslashes($string)));

	$string=str_replace("ë","&euml;",strip_tags(stripslashes($string)));

	$string=str_replace("Î","&Icirc;",strip_tags(stripslashes($string)));

	$string=str_replace("î","&icirc;",strip_tags(stripslashes($string)));

	$string=str_replace("Ï","&Iuml;",strip_tags(stripslashes($string)));

	$string=str_replace("ï","&iuml;",strip_tags(stripslashes($string)));

	$string=str_replace("Ô","&Ocirc;",strip_tags(stripslashes($string)));

	$string=str_replace("ô","&ocirc;",strip_tags(stripslashes($string)));

	$string=str_replace("O","&OElig;",strip_tags(stripslashes($string)));

	$string=str_replace("o","&oelig;",strip_tags(stripslashes($string)));

	$string=str_replace("Ù","&Ugrave;",strip_tags(stripslashes($string)));

	$string=str_replace("ù","&ugrave;",strip_tags(stripslashes($string)));

	$string=str_replace("Û","&Ucirc;",strip_tags(stripslashes($string)));

	$string=str_replace("û","&ucirc;",strip_tags(stripslashes($string)));

	$string=str_replace("Ü","&Uuml;",strip_tags(stripslashes($string)));

	$string=str_replace("ü","&uuml;",strip_tags(stripslashes($string)));

	$string=str_replace("&","&amp;",strip_tags(stripslashes($string)));

//	$string=str_replace("?","",strip_tags(stripslashes($string)));



	return $string;

}



	

function SendMail($to,$subject,$message,$show=false){

			

		$path = "http://www.infomist.net/team/";

		$from_name      = $_SESSION["site_name"];

		$internal_email = $_SESSION["site_email"];

		$from 			= $from_name." <".$internal_email.">";

		$headers 		= "";

		$headers 		.= "MIME-Version: 1.0\r\n"; 

		$headers 		.= "Content-type: text/html;charset=iso-8859-1\r\n";

		$headers 		.= "From: ".$from."\r\n";

		$headers 		.= "Reply-To: ".$internal_email."\r\n";

		$headers 		.= "X-Priority: 1\r\n"; 

		$headers 		.= "X-MSMail-Priority: High\r\n"; 

		$headers 		.= "X-Mailer: Just My Server";

		$emailHEAD = '	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>'.$_SESSION["site_name"].'</title><style type="text/css">	body {	margin-left: 0px;	margin-top: 0px;	margin-right: 0px;	margin-bottom: 0px;	}	.borderColor {	background-color: #f7f7f7;	border: 1px solid #f0f0f0;	}	.textWhiteHeader {	font-family: Arial, Helvetica, sans-serif;	font-size: 12px;	color: #FFFFFF;	font-weight: bold;	}	.blueHeading1 {	font-family: Arial, Helvetica, sans-serif;	font-size: 50px;	font-weight: bold;	color: #57cdff;	}	.textHeading2 {	font-family: Arial, Helvetica, sans-serif;	font-size: 14px;	color: #666666;	font-weight: bold;	line-height: 18px;	}	.whiteText {	font-family: Arial, Helvetica, sans-serif;	font-size: 11px;	color: #FFFFFF;	line-height: 18px;	}	.contactHeading {	font-family: Arial, Helvetica, sans-serif;	font-size: 14px;	font-weight: bold;	color: #036b92;	}	.contactAddress {	font-family: Arial, Helvetica, sans-serif;	color: #51a4c4;	font-size: 11px;	line-height: 18px;	}	.mainText {	font-family: Arial, Helvetica, sans-serif;	font-size: 12px;	line-height: 18px;	color: #888888;	}	.mainHeading {	font-family: Arial, Helvetica, sans-serif;	font-size: 24px;	line-height: normal;	color: black;	font-weight: bold;	}	.footer {	font-family: Arial, Helvetica, sans-serif;	font-size: 12px;	color: #333333;	}	p{	color:#484848;	font-family:Arial,Helvetica,sans-serif;	font-size:12px;	line-height:18px;	font-weight:normal;	}	a {	font-family: Arial, Helvetica, sans-serif;	font-size: 11px;	color: #51a4c6;	}	a:link {	text-decoration: none;	}	a:visited {	text-decoration: none;	color: #51a4c6;	}	a:hover {	text-decoration: underline;	color: #000000;	}	a:active {	text-decoration: none;	color: #51a4c6;	}	.borderColor2 {	background-color: #ffffff;	border: 1px solid #f0f0f0;	padding-top:5px; padding-left:10px;padding-bottom:5px;padding-right:10px;	}	p{	margin:0px;	}	--> </style></head><body>

    <table  border="0" align="center" cellpadding="0" cellspacing="0" width="650"><tr><td><table width="100%"  border="0">	<tr>

		<td colspan="2"><font class=blueHeading1>'.$_SESSION["site_name"].'</font></td>

	</tr>

	</table></td></tr><tr><td height="2"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td height="2" bgcolor="#DCDCDC" class="textWhiteHeader" style="padding:5px;"></td></tr></table></td></tr><tr><td><table width="100%"  border="0" cellpadding="0" cellspacing="0"><tr><td valign="top" style="padding:15px;">

	

			<table width="100%" border="0" cellspacing="0" cellpadding="0">

			  <tr><td class="mainHeading" style="padding:10px;">'.$subject.'</td></tr>

			  <tr><td height="2" bgcolor="#163A6A" class="textWhiteHeader" style="padding-top:5px; padding-left:10px;padding-bottom:5px;padding-right:10px;"></td></tr>

			  <tr>

				<td><table width="100%" border="0" cellspacing="0" cellpadding="0">

				  <tr><td class="borderColor2" style="padding:10px;" align="left">';

		$emailFOOT = '</td></tr></table></td> </tr> <tr><td>&nbsp;</td></tr>

              </table></td></tr> </table></td></tr><tr><td height="30" bgcolor="#CCCCCC" class="footer" style="padding-left:20px;">&copy; Copyright @ 2012. All rights resrerved. </td></tr></table></body></html>

';

		if($show==true){

			return $emailHEAD.$message.$emailFOOT;	

		} else {

			mail($to, $subject, $emailHEAD.$message.$emailFOOT , $headers);

			mail('shaheryar@infomist.com', $subject, $emailHEAD.$message.'<br>URI: '.$_SERVER['REQUEST_URI'].'<br>'.$emailFOOT , $headers);

		}

	

	}	

	

function getPaging($table,$where,$limit,$tpage,$seprater,$pager)

{

	$nxt = 'Next';

	$prv = 'Previous';

	$tbl_name=$table;		//your table name

	$adjacents = 3;

//	echo $_REQUEST['page']; 

	$query = "SELECT COUNT(*) as num FROM $tbl_name $where";

	$total_pages = mysql_fetch_array(mysql_query($query));

	$total_pages = $total_pages[num];

	

	$targetpage = $tpage;	//your file name  (the name of this file)

//	$limit = 12; 	

//	$_GET['pager']		    //how many items to show per page

	$page = $pager;

	if($page) 

		$start = ($page - 1) * $limit; 			//first item to display on this page

	else

		$start = 0;								//if no page var is given, set start to 0

	

	$seprator = $seprater;

	

	$sql = "SELECT * from $tbl_name $where  LIMIT $start, $limit";



	if ($page == 0) $page = 1;					//if no page var is given, default to 1.

	$prev = $page - 1;							//previous page is page - 1

	$next = $page + 1;							//next page is page + 1

	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.

	$lpm1 = $lastpage - 1;						//last page minus 1

	$pagination = "";

	if($lastpage > 1)

	{	

		$pagination .= "<div class=\"pagination\">";

		//previous button

		if ($page > 1) 

			$pagination.= "<a href=\"$targetpage".$seprator."pager=$prev\">&laquo; ".$prv."</a>";

		else

			$pagination.= "<span class=\"disabled\">&laquo; ".$prv."</span>";	

		

		//pages	

		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up

		{	

			for ($counter = 1; $counter <= $lastpage; $counter++)

			{

				if ($counter == $page)

					$pagination.= "<span class=\"current\">$counter</span>";

				else

					$pagination.= "<a href=\"$targetpage".$seprator."pager=$counter\">$counter</a>";					

			}

		}

		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some

		{

			//close to beginning; only hide later pages

			if($page < 1 + ($adjacents * 2))		

			{

				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)

				{

					if ($counter == $page)

						$pagination.= "<span class=\"current\">$counter</span>";

					else

						$pagination.= "<a href=\"$targetpage".$seprator."pager=$counter\">$counter</a>";					

				}

				$pagination.= "...";

				$pagination.= "<a href=\"$targetpage".$seprator."pager=$lpm1\">$lpm1</a>";

				$pagination.= "<a href=\"$targetpage".$seprator."pager=$lastpage\">$lastpage</a>";		

			}

			//in middle; hide some front and some back

			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))

			{

				$pagination.= "<a href=\"$targetpage".$seprator."pager=1\">1</a>";

				$pagination.= "<a href=\"$targetpage".$seprator."pager=2\">2</a>";

				$pagination.= "...";

				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)

				{

					if ($counter == $page)

						$pagination.= "<span class=\"current\">$counter</span>";

					else

						$pagination.= "<a href=\"$targetpage".$seprator."pager=$counter\">$counter</a>";					

				}

				$pagination.= "...";

				$pagination.= "<a href=\"$targetpage".$seprator."pager=$lpm1\">$lpm1</a>";

				$pagination.= "<a href=\"$targetpage".$seprator."pager=$lastpage\">$lastpage</a>";		

			}

			//close to end; only hide early pages

			else

			{

				$pagination.= "<a href=\"$targetpage".$seprator."pager=1\">1</a>";

				$pagination.= "<a href=\"$targetpage".$seprator."pager=2\">2</a>";

				$pagination.= "...";

				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)

				{

					if ($counter == $page)

						$pagination.= "<span class=\"current\">$counter</span>";

					else

						$pagination.= "<a href=\"$targetpage".$seprator."pager=$counter\">$counter</a>";					

				}

			}

		}

		//next button

		if ($page < $counter - 1) 

			$pagination.= "<a href=\"$targetpage".$seprator."pager=$next\">".$nxt." &raquo;</a>";

		else

			$pagination.= "<span class=\"disabled\">".$nxt." &raquo;</span>";

		$pagination.= "</div>\n";		

	}

	

	return array($sql,$pagination);

}



	

function  pageTime(){

static $_pt;

    if($_pt == 0) $_pt = microtime(true);

    else return (string)(round(microtime(true)-$_pt ,3));

}

pageTime();



function getExtension($str) {

	   $i = strrpos($str,".");

	   if (!$i) { return ""; }

	   $l = strlen($str) - $i;

	   $ext = substr($str,$i+1,$l);

	   return $ext;

}



function PassWord( $q, $status ) {

	if($status=='hide'){

		$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';

		$qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );

		return( $qEncoded );

	}

	

	if($status=='show'){

	    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';

		$qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");

		return( $qDecoded );

	}

}



function VideosTAGS(){

	$TAGS=array();

	$stmt=mysql_query("SELECT distinct `tags` FROM `videos` WHERE 1");

	while($rslt=mysql_fetch_array($stmt)){

		$string = explode(",",$rslt[0]);

		foreach($string as $tag){

			@mysql_query(" INSERT INTO `video_tags` (`tag_title`) VALUES ('".$tag."'); ");

		}

	}

}





/**

* Functions by Khalid.

*/



function getCategoryMapDetail($parent)

{

	$omap_ids	= "";

	$level		= 0;

	$tempparent = $parent;

	

	while($parent != 0)

	{

		$query = "SELECT catid FROM category WHERE id = '$parent'";

		//echo $query."<br />";

		

		$result = mysql_query($query);

		$row = mysql_fetch_array($result);

		//echo '<pre>';print_r($row); echo '</pre>';

		

		$newparent = $row['catid'];

		

		$parent = $newparent;

		if($parent != 0)

		{

			$omap_ids .= $newparent . ",";

		}

		

		$level++;

	}

	

	$omap_ids .= $tempparent;

	

	$query = "SELECT id, title FROM category WHERE id in ($omap_ids) ORDER BY ordermap ASC";

	//echo $query."<br />";

	//exit;

	

	$omap = "";

	$pmap = "";

	

	$result2 = mysql_query($query);

	while($row2 = mysql_fetch_array($result2))

	{

		//echo $row2['id']."<br />";

		

		$omap .= $row2['id'] . ",";

		

		$title = urlencode($row2['title']);

		$pmap .= $title . " >> ";

	}

	

	//echo $omap."<br />";

	//echo $pmap."<br />";

	//exit;

	

	$maparray = array();

	$maparray['level'] = $level;

	$maparray['ordermap'] = $omap;

	$maparray['breadcrumb'] = $pmap;

	return $maparray;

}



?>