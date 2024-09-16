<?php
class EmagineIT
{

	function paypal_info($id)
	 {
	  $s1_paypal_business_link = "select * from paypalinfo_tbl_cb where 1 and id = '".$id."'";
	  $q1_paypal_business_link = mysql_query($s1_paypal_business_link) or die($s1_paypal_business_link);
	  $r1_paypal_business_link = mysql_fetch_array($q1_paypal_business_link);
	  return $r1_paypal_business_link["txt_value"];
	 }
	function display_page($physical_name, $conn , $where = NULL )
	{
	  $sql = "SELECT * FROM contents WHERE page_physical_name='".$physical_name."' and store_id = '".$_SESSION["storeid"]."' ".$where;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);

	  return $row;
	}
	function display_page2($physical_name, $conn , $where = NULL )
	{
	  $sql = "SELECT * FROM contents WHERE page_physical_name ='".$physical_name."' and status = 1  and store_id = '".$_SESSION["storeid"]."' ".$where;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);

	  return $row;
	}
	
	function display_testimonial_main_info()
	{
	  $sql = "SELECT * FROM testimonials WHERE 1 and status = 1 and main = 1";
	  $res = mysql_query($sql) or die(mysql_error());
	  $row = mysql_fetch_array($res);
	  return $row;
	}
	function display_quote_main_info()
	{
	  $sql = "SELECT * FROM quotes WHERE 1 and status = 1 and main = 1";
	  $res = mysql_query($sql) or die(mysql_error());
	  $row = mysql_fetch_array($res);
	  return $row;
	}
	function display_news_main_info()
	{
	  $sql = "SELECT * FROM news WHERE 1 and nstatus = 1 and main = 1";
	  $res = mysql_query($sql) or die(mysql_error());
	  $row = mysql_fetch_array($res);
	  return $row;
	}
	function display_recommendedbooks_main_info()
	{
	  $sql = "SELECT * FROM recommended_books WHERE 1 and status = 1 and main = 1";
	  $res = mysql_query($sql) or die(mysql_error());
	 // $row = mysql_fetch_array($res);
	  return $res;
	}
	function display_leftside_personal()
	{
	  $sql = "SELECT * FROM management_team WHERE main = '1' and status = 1 ".$where;
	  $res = mysql_query($sql) or die($sql);
	  $row = mysql_fetch_array($res);
	  return $row;
	}
	function display_management_team($type)
	{
	  $sql = "SELECT * FROM management_team WHERE status = 1 and type = '".$type."' order by orderid";
	  $res = mysql_query($sql) or die($sql);
	  return $res;
	}
	function display_major_investors()
	{
	  $sql = "SELECT * FROM portfolio_flash_work WHERE port_status = 1 order by orderid";
	  $res = mysql_query($sql) or die($sql);
	  return $res;
	}
	function display_contact_info()
	{
	  $sql = "SELECT * FROM contact_info left outer join countries on countries_id = contact_country  WHERE 1";
	  $res = mysql_query($sql) or die(mysql_error());
	  $row = mysql_fetch_array($res);
	  return $row;
	}
	function adminMailBox( $id ){
		$query = "SELECT * FROM email_tbl_cb WHERE id = '$id'";
		$result = mysql_query($query);
		$mail = mysql_fetch_array($result);
		return $mail;
	}
	function display_password_protected($physical_name, $conn , $where = NULL )
	{
	  $sql = "SELECT * FROM contents WHERE page_physical_name='".$physical_name."' and password_protected = '1' ".$where;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_num_rows($res);
	  return $row;
	}
	function display_page_type($physical_name, $conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM news WHERE page_physical_name ='".$physical_name."' and nstatus = 1 and ntype = '".$type."' ".$where;
//	  print $sql;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);
//	  echo "TEST:- ".$row['cont_header'];
	  return $row;
	}
	
	function display_page_fundaccess($physical_name, $conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM funds_access WHERE page_physical_name ='".$physical_name."' and fundaccess_status = 1 ".$where;
//	  print $sql;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);
//	  echo "TEST:- ".$row['cont_header'];
	  return $row;
	}
	function display_page_portfolio($physical_name, $conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM funds_access WHERE page_physical_name ='".$physical_name."' and fundaccess_status = 1 ".$where;
//	  print $sql;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);
//	  echo "TEST:- ".$row['cont_header'];
	  return $row;
	}
	function display_page_funds($physical_name, $conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM property WHERE page_physical_name ='".$physical_name."' and status = 1 ".$where;
//	  print $sql;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);
//	  echo "TEST:- ".$row['cont_header'];
	  return $row;
	}
	function display_page_album($physical_name, $conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM albums WHERE page_physical_name ='".$physical_name."' and album_status = 1 ".$where;
//	  print $sql;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);
//	  echo "TEST:- ".$row['cont_header'];
	  return $row;
	}
	function display_page_type_active($conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM news WHERE ntype = '".$type."' and nstatus = 1 ".$where;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  return $res;
	}
	function display_main_type($conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM news WHERE ntype = '".$type."' and nstatus = 1 and main = 1 ".$where;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);
	  return $row;
	}
	function display_main_comment($conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM visitor_comments WHERE cmt_status = 1 order by cmt_id desc LIMIT 0,1";
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);
	  return $row;
	}
	function display_main_image_type($conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM header_images WHERE header_picture_type = '".$type."' and header_mv_id in (select mv_id from mv) and header_status = 1 and main = 1 order by rand() LIMIT 0,1";
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);
	  return $row["header_bigimage"];
	}
	function display_main_image_type_size($image)
	{
	  list($width, $height, $type, $attr) = getimagesize("images/gallery/thumbs/".$image);
	  if($height > $width)
	  {
	  	$height = "3";
	  }
	  else
	  {
	  	$height = "40";
	  }
	  return $height;
	}
	function display_main_video_type($conn , $where = NULL )
	{
	  $sql = "SELECT * FROM header_video WHERE header_status = 1 and main = 1 ".$where;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  $row = mysql_fetch_array($res);
	  return $row["header_bigimage"];
	}
	function display_page_physical_name($conn , $type , $where = NULL )
	{
	  $sql = "SELECT * FROM news WHERE ntype = '".$type."' and nstatus = 1 ".$where;
//	  print $sql;
	  $res = mysql_query($sql, $conn) or die(mysql_error());
	  while($row = mysql_fetch_array($res))
	  {
		$page_title = $row["page_physical_name"];
		break;
	  }
	  //echo $page_title;
	  return $page_title;
	}
	function encrypt($plain_text) {
	$key = 'khurram';
	$plain_text = trim($plain_text);
	$iv = substr(md5($key), 0,mcrypt_get_iv_size (MCRYPT_CAST_256,MCRYPT_MODE_CFB));
	$c_t = mcrypt_cfb (MCRYPT_CAST_256, $key, $plain_text, MCRYPT_ENCRYPT, $iv);
	return trim(chop(base64_encode($c_t)));
	}
	
	function decrypt($c_t) {
	$key = 'khurram';
	$c_t =  trim(chop(base64_decode($c_t)));
	$iv = substr(md5($key), 0,mcrypt_get_iv_size (MCRYPT_CAST_256,MCRYPT_MODE_CFB));
	$p_t = mcrypt_cfb (MCRYPT_CAST_256, $key, $c_t, MCRYPT_DECRYPT, $iv);
	return trim(chop($p_t));
	}
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	
	  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
	
	  switch ($theType) {
		case "text":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;    
		case "long":
		case "int":
		  $theValue = ($theValue != "") ? intval($theValue) : "NULL";
		  break;
		case "double":
		  $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
		  break;
		case "date":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;
		case "defined":
		  $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
		  break;
	  }
	  return $theValue;
	}    
	function display_homepage_images($path,$page_name)
	{
		$i = 1;
		$images = '';
	  $sql = "SELECT * FROM homepage_images WHERE 1 and home_status = 1 and page_name like '".$page_name."' order by orderid";
	  $res = mysql_query($sql) or die(mysql_error());
	  while($row = mysql_fetch_array($res))
	  {
	  	if($i == 1)
		{
			$images .= "\"".$path."images/".$row["home_image"]."\"";
		}
		else
		{
			$images .= " ,\"".$path."images/".$row["home_image"]."\"";
		}
		$i ++;
	  }
	  return $images;
	}  
	function display_homepage_first_image($path,$page_name)
	{
	  $sql = "SELECT * FROM homepage_images WHERE 1 and home_status = 1 and page_name like '".$page_name."' order by orderid LIMIT 0,1";
	  $res = mysql_query($sql) or die(mysql_error());
	  $row = mysql_fetch_array($res);
	  $image = $path."images/".$row["home_image"];
	  return $image;
	}
}