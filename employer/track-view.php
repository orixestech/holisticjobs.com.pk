<?php include('header.php');
if($_GET['delete']=='true'){
	//mysql_query( 'TRUNCATE admin_log' );
}
?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">System Logs</li>
    </ul>
    <!-- .breadcrumb -->
	<div id="SubscriptionExpireStatus" class="pull-right"><?=$SubscriptionExpireStatus?></div>
  </div>
  <div class="page-content">
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
          <div class="col-xs-12">
            <h3 class="header smaller lighter blue">System Logs</h3>
            <?=$message?>
            <div class="table-header"> Results for "All System Logs" <span style="float:right; margin-right:8px;"><a href="track-view.php?delete=true" class="white">Clear All System Log</a></span></div>
            <div class="table-responsive">
              <table id="pages-view" class="table table-striped table-bordered table-hover ">
                <thead>
                  <tr>
                    <th>IP Address</th>
                    <th>Notes</th>
                    <th>Last Modify</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $stmt = mysql_query(" SELECT * FROM `admin_log` WHERE 1 order by `logdatetime` desc ");
                    while( $rslt = mysql_fetch_array($stmt) ){ ?>
                  <tr>
                    <td><?=$rslt['logip']?></td>
                    <td><?=$rslt['lognotes']?></td>
                    <td class="hidden-480"><?=date('d M, Y h:i A',strtotime($rslt['logdatetime']))?></td>
                  </tr>
                  <? } ?>
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
<script>
var oTable1 = $('table#pages-view').dataTable(  );
</script>
<?php include('footer.php');?>
