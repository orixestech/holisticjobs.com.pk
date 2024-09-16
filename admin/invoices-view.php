<?php include('header.php');?>
<?php
if($_GET["delete"]=='true' && $_GET["id"]){
	/*$pageTitle = GetData('JobTitle','jobs','UID',$_GET["id"]);
	mysql_query(" DELETE FROM  `jobs` WHERE `UID` = '".$_GET["id"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Job Title [ '.$pageTitle.' ] Deleted...!');
		$message = Alert('success', 'Job Title [ '.$pageTitle.' ] Deleted...!');
	}*/
}

if($_GET["tempinvoice"]=='true'){
	$pdf = array();
	$emp = fetch( query( " SELECT `UID` FROM `employer` WHERE 1 order by rand() limit 1 " ) );
	$pdf['EmpID'] = $emp[0];
	
	$plan = fetch( query( " SELECT `UID` FROM `subscriptions` WHERE 1 order by rand() limit 1 " ) );
	$pdf['PlanID'] = $plan[0];
	
	$filename = CreatePDF($pdf);
	echo '<meta http-equiv="refresh" content="0;URL=\'invoices-view.php\'" />';exit;
}




$QUERYSTRING = '';
$whereSQL = "where 1 "; ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Invoices</li>
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
            <h3 class="header smaller lighter blue">Invoices</h3>
            <div id="ajax-result"> </div>
            <div class="col-xs-12">
              <div class="table-header"> Results for "All Invoices" <span style="float:right; margin-right:8px;"> <a class="btn btn-danger btn-sm" href="<?=$path?>admin/invoices-view.php?tempinvoice=true">Generate Temp Invoice For Testing</a> </span></div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover ">
                  <?php
				$limit = 15;
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('invoices',$whereSQL . " ORDER BY SystemDate DESC ",$limit,'invoices-view.php','?'.$QUERYSTRING,$_REQUEST['pager']);
				//echo $paging[0];
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
                  <thead>
                    <tr>
                      <th>Sr. No</th>
                      <th>Date</th>
                      <th>Ref.</th>
                      <th>Account Type</th>
                      <th>User Account</th>
                      <th>Amount</th>
                      <th>Subscription Plan</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
			  if($row>0){
				while( $rslt = mysql_fetch_array($rs_pages) ){
						$employer = GetData('EmployerCompany','employer','UID',$rslt['InvoiceUserID']);
						$plan = GetData('PlanTitle','subscriptions','UID',$rslt['InvoicePlan']); ?>
                    <tr>
                      <td><?=$count?></td>
                      <td><?=date("d M, Y h:i A", strtotime($rslt['SystemDate']))?></td>
                      <td><?=Code('HJ', $rslt['UID'])?></td>
                      <td><?=$rslt['InvoiceUserType']?></td>
                      <td><?=$employer?></td>
                      <td>Rs.
                        <?=$rslt['InvoiceFee']?></td>
                      <td><?=$plan?></td>
                      <td><span class="label label-<?=($rslt['InvoiceStatus']=='Paid')?'success':'danger'?> arrowed-in arrowed-in-right">
                        <?=$rslt['InvoiceStatus']?>
                        </span></td>
                      <td><a class="btn btn-primary btn-xs" href="#UpdateInvoice" data-toggle="modal" role="button"> Update </a> <a class="btn btn-success btn-xs" href="<?=$path?>invoices/<?=$rslt['InvoiceFilename']?>" target="_blank" role="button"> Download </a> </td>
                    </tr>
                    <? $count++;
				  }
				} else {  ?>
                    <tr>
                      <th class="center" colspan="8">No Records Found.!</th>
                    </tr>
                    <?
				  } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="center" colspan="8"><div class="pull-right">
                          <?=$pagination?>
                        </div></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
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
<!-- /.main-content -->
<?=GenModelBOX('UpdateInvoice', 'update-invoice.php')?>
<?php include('footer.php');?>
