<?php include("admin/includes/conn.php"); include("site_theme_functions.php"); $SessionID = $_SESSION["sessid"]; // print_r( $_REQUEST );
$limit = 9999;
(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
$paging = getPaging('employee',$whereSQL . "  ORDER BY `employee`.`LastLoginDateTime` DESC ",$limit,'employee-view.php','?'.$QUERYSTRING,$_REQUEST['pager']);
//echo $paging[0];
$rs_pages = mysql_query($paging[0]) or die($paging[0]);
$row = mysql_num_rows( $rs_pages );
$pagination = $paging[1];
?>
<style>
* { font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; text-align:left; }
table{ width:100%; border:1px solid black;}
th{ text-align:left; border:1px solid red;}
td{ text-align:left; border:1px solid black;}
</style>
<table>
  <thead>
    <tr>
      <th width="70">Sr. No</th>
      <th>Fullname</th>
      <th>Email</th>
      <th>Password</th>
      <th>CV Download</th>
      <th>Profile Score</th>
      <th>City</th>
    </tr>
  </thead>
  <tbody>
    <?php
while( $rslt = mysql_fetch_array($rs_pages) ){
	$CV = fetch( query( "SELECT * FROM `employee_resume` WHERE `ResumeEmployeeID` = '".$rslt['UID']."' order by `SystemDate` desc limit 1 " ) );
	$ProfileScore = round(ProfileScore($rslt['UID']),0); ?>
    <tr id="row_<?=$rslt['UID']?>">
      <td><?=$count?></td>
      <td><?=optionVal($rslt['EmployeeTitle'])?>
        &nbsp;
        <?=$rslt['EmployeeName']?></td>
      <td><?=$rslt['EmployeeEmail']?></td>
      <td>'<?=PassWord($rslt['EmployeePassword'],'show')?></td>
      <td><?=( ( empty ($CV['ResumeFilename']) ) ? '-' : 'http://www.holisticjobs.com.pk/uploads/'.$CV['ResumeFilename'] )?></td>
      <td><?=$ProfileScore?>
        %</td>
      <td><?=optionVal($rslt['EmployeeCity'])?></td>
    </tr>
    <? $count++;
}
?>
  </tbody>
</table>
