<?php include('header.php');?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="#">Home</a> </li>
      <li class="active">Subscription</li>
    </ul>
    <!-- .breadcrumb --> 
	<div id="SubscriptionExpireStatus" class="pull-right"><?=$SubscriptionExpireStatus?></div>
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Subscription <small> <i class="icon-double-angle-right"></i> Subscription Details </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" action="" method="post">
      <div class="row">
        <div class="col-xs-12"> 
          <!-- PAGE CONTENT BEGINS -->
          <h4 class="header green">Subscriptions Details</h4>
          <?php
			$stmt = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employee' order by `PlanFee`");
			$PlanTitle = $PlanDays = $PlanFee = $PlanButton = '';
			$subid = GetData('EmployeeSubscription','employee','UID',$_SESSION['Employee']['UID']);
			while($rslt=fetch($stmt)){
				($subid==$rslt['UID'])? $PlanClass='class="success"' : $PlanClass='' ;
				$PlanTitle .= '<th width="16%" align="center" style="text-align:center" '.$PlanClass.'>'.ucwords($rslt['PlanTitle']).'</th>';
				$PlanDays .= '<td align="center" '.$PlanClass.'>'.round($rslt['PlanDays']/30, 0).' Months</td>';
				$PlanFee .= '<td align="center" '.$PlanClass.'>Rs. '.$rslt['PlanFee'].' / Month</td>';
				if($subid==$rslt['UID']){
					$PlanButton .= '<td align="center" '.$PlanClass.'><strong>Current Plan</strong></td>';
				} else {
					$PlanButton .= '<td align="center" '.$PlanClass.'><a class="btn btn-xs btn-danger" href="'.$path.'page/contact" target="_blank">Contact Us</a></td>';
				}
			}?>
			<table class="table table-striped table-bordered table-hover ">
			  <thead class="red">
				<tr>
				  <th></th>
				  <?=$PlanTitle?>
				</tr>
			  </thead>
			  <tbody>
				<tr>
				  <th>Time Duration</th>
				  <?=$PlanDays?>
				</tr>
				<?php
				$stmt = query("SELECT * FROM `subscription_accesstype` WHERE AccessTypeModule = 'Employee' order by `UID`");
				while($rslt=fetch($stmt)){?>
				<tr>
				  <th><?=$rslt['AccessTypeTitle']?><br /><small style="font-weight:normal;"><?=$rslt['AccessTypeDesc']?></small></th><?php
				  $stmtPlans = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employee' order by `PlanFee`");
				  while($rsltPlans = fetch($stmtPlans)){
					  ($subid==$rsltPlans['UID'])? $PlanClass='class="success"' : $PlanClass='' ;
					  
					  $stmtAccess = query("SELECT * FROM `subscription_access` WHERE `AccessSubID` = '".$rsltPlans['UID']."' and `AccessTypeKey` = '".$rslt['AccessTypeKey']."'");
					  $rsltAccess = fetch($stmtAccess);
					  
					  ($rsltAccess['AccessAllowed']==1) ? $access = '<i class="icon-ok bigger-110 green"></i>' : $access = '<i class="icon-remove bigger-110 red"></i>';
					  
					  ($rsltAccess['AccessDays'] > 0 && $rsltAccess['AccessAllowed']==1) ? $access = $rsltAccess['AccessDays'] . ' Days' : '';
					  
					  echo '<td align="center" '.$PlanClass.'>'.$access.'</td>';
				  }?>
				</tr>
				<?php
				}?>
                <tr>
				  <th>Monthly Charges</th>
				  <?=$PlanFee?>
				</tr>
			  </tbody>
			  <tfoot>
				<tr>
				  <td></td>
				  <?=$PlanButton?>
				</tr>
			  </tfoot>
			</table>
			
          <!-- PAGE CONTENT ENDS --> 
        </div>
        <!-- /.col --> 
      </div>
      <!-- /.row -->
    </form>
	
  </div>
  <!-- /.page-content --> 
</div>
<!-- /.main-content -->
<?php include('footer.php');?>
