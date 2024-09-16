<?php include('header.php');
if($_GET['delete']=='true'){
	mysql_query( " DELETE FROM `admin_log` WHERE `logref` = 'employer' and `logrefid` = '".$_SESSION['EmployerUID']."' " );
}
?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Notifications</li>
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
            <h3 class="header smaller lighter blue">Notifications</h3>
            <?=$message?>
            <div class="table-header"> Results for "All Notifications" <span style="float:right; margin-right:8px;"><a href="notification.php?delete=true" class="white">Clear All Notifications</a></span></div>
            <div class="table-responsive">
              <table id="pages-view" class="table table-striped table-bordered table-hover ">
                <thead>
                  <tr>
                    <th>Notifications</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  	$query = '';
                    $stmt = mysql_query(" SELECT * FROM `admin_log` WHERE `logref` = 'employer' and `logrefid` = '".$_SESSION['EmployerUID']."'  order by `logdatetime` desc ");
                    while( $rslt = mysql_fetch_array($stmt) ){ ?>
					  <tr <?=($rslt['logstatus']==0)?' class="success" ':' class="warning" '?>>
						<td><?=$rslt['lognotes']?></td>
						<td class="hidden-480"><?=date('d M, Y h:i A',strtotime($rslt['logdatetime']))?></td>
					  </tr><?
					  if($rslt['logstatus']==0){
						  query("UPDATE `admin_log` SET `logstatus` = '1' WHERE `admin_log`.`logid` = '".$rslt['logid']."'; ");
					  }}?>
                </tbody>
              </table>
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
<?php include('footer.php');?>
