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



/*$pdf = array();
$pdf['EmpID'] = 7;
$pdf['PlanID'] = 2;
echo $filename = CreatePDF($pdf);*/



$QUERYSTRING = '';
$whereSQL = "WHERE `InvoiceUserType` = 'Employer' and `InvoiceUserID` = '".$_SESSION['EmployerUID']."' "; ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Invoices</li>
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
            <h3 class="header smaller lighter blue">Invoices</h3>
            <div id="ajax-result"> </div>
            <div class="col-xs-12">
              <div class="table-header"> Results for "All Invoices"</div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover ">
                  <?php
				$limit = 15;
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('invoices',$whereSQL . " ORDER BY SystemDate DESC ",$limit,'payment-history.php','?'.$QUERYSTRING,$_REQUEST['pager']);
				//echo $paging[0];
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
                  <thead>
                    <tr>
                      <th>Sr. No</th>
                      <th>Date</th>
                      <th>Ref.</th>
                      <th>Amount</th>
                      <th>Subscription Plan</th>
                      <th>Status</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
			  if($row>0){
				while( $rslt = mysql_fetch_array($rs_pages) ){
						$plan = GetData('PlanTitle','subscriptions','UID',$rslt['InvoicePlan']); ?>
                    <tr>
                      <td><?=$count?></td>
                      <td><?=date("d M, Y h:i A", strtotime($rslt['SystemDate']))?></td>
                      <td><?=Code('HJ', $rslt['UID'])?></td>
                      <td>Rs.
                        <?=$rslt['InvoiceFee']?></td>
                      <td><?=$plan?></td>
                      <td><span class="label label-<?=($rslt['InvoiceStatus']=='Paid')?'success':'danger'?> arrowed-in arrowed-in-right">
                        <?=$rslt['InvoiceStatus']?>
                        </span></td>
                      <td><a class="btn btn-primary btn-xs" href="<?=$path?>#UpdateInvoice" data-toggle="modal" role="button"> Update </a> <a class="btn btn-success btn-xs" href="<?=$path?>invoices/<?=$rslt['InvoiceFilename']?>" target="_blank" role="button"> Download </a> </td>
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
<?php include('footer.php');?>
<div id="UpdateInvoice" class="modal">
  <div class="modal-dialog" style="width:65%">
    <div class="modal-content">
      <form role="form" id="UpdateInvoiceForm">
        <input type="hidden" name="uid" value="60">
        <div class="modal-header no-padding">
          <div class="table-header">Update Invoice</div>
        </div>
        <div class="modal-body form-horizontal">
          <h5 id="Ajax-Result"></h5>
          <div class="modal-body form-horizontal">
            <div class="form-group">
              <label class="col-xs-3 col-sm-3 control-label no-padding-right"> Payment Date:* </label>
              <div class="row">
                <div class="col-xs-4 col-sm-4">
                  <div class="input-group">
                    <input class="form-control validate[required,custom[date]] date-picker" data-format="Y-m-d"  name="PaymentDate" id="PaymentDate" type="text" value="2015-12-01">
                    <span class="input-group-addon"> <i class="icon-calendar bigger-110"></i> </span> </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Payment Mode:* </label>
              <div class="col-xs-9 col-sm-9">
                <select id="PaymentMode" class="col-xs-9 col-sm-9" name="PaymentMode">
                  <option value="1">Cheque</option>
                  <option value="0">Deposit</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Cheque/Receipt No:* </label>
              <div class="col-xs-9 col-sm-9">
                <input name="ReceiptNo" id="ReceiptNo" type="text" placeholder="" class="col-xs-9 col-sm-9 validate[required]" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Remarks:* </label>
              <div class="col-xs-9 col-sm-9">
                <textarea name="remarks" id="remarks" class="col-xs-9 col-sm-9" placeholder=""></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer no-margin-top">
          <button class="btn btn-danger" data-dismiss="modal"> Cancel </button>
          <button type="button" class="btn btn-primary"> Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
