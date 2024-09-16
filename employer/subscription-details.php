<?php include('header.php');

if($_POST){
	$valid = total(" SELECT * FROM `employer` WHERE `UID` = '".$_SESSION['Employer']['UID']."' and `EmployerPassword` = '".PassWord($_POST['current_pass'],'hide')."' ");
	if($valid == 1){
		
		if($_POST['new_pass'] == $_POST['retype_new_pass']){
			$logout = 0;
			if($_POST['new_pass']!='') {
				$logout = 1;
				$users['EmployerPassword'] = PassWord($_POST['new_pass'],'hide');
			}
			
			$run = FormData('employer', 'update', $users, " `UID` = '".$_SESSION['Employer']['UID']."' ", $view=false );
			$FormMessge = Alert('success', 'New Password successfully changed, Please relogin...!');
			echo "<script language='javascript'>window.location = 'index.php?logout=true';</script>";exit;
			
		} else {
			$FormMessge = Alert('error', 'New password does not match with re-type new password...!');
		}
	} else {
		$FormMessge = Alert('error', 'Invalid current password...!'); 	
	}
} ?>

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
			$stmt = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employer' order by `PlanFee`");
			$PlanTitle = $PlanDays = $PlanFee = $PlanButton = '';
			$subid = GetData('EmployerSubscription','employer','UID',$_SESSION['Employer']['UID']);
			while($rslt=fetch($stmt)){
				($subid==$rslt['UID'])? $PlanClass='class="success"' : $PlanClass='' ;
				$PlanTitle .= '<th width="12%" align="center" style="text-align:center" '.$PlanClass.'>'.ucwords($rslt['PlanTitle']).'</th>';
				$PlanDays .= '<td align="center" '.$PlanClass.'>'.round($rslt['PlanDays']/30, 0).' Months</td>';
				$PlanFee .= '<td align="center" '.$PlanClass.'>Rs. '.$rslt['PlanFee'].'</td>';
				if($subid==$rslt['UID']){
					$PlanButton .= '<td align="center" '.$PlanClass.'><strong>Current Plan</strong></td>';
				} else {
					$PlanButton .= '<td align="center" '.$PlanClass.'><a class="button" href="'.$path.'update-subscription.php?plan='.$rslt['UID'].'"><button class="btn btn-xs btn-danger">Subscribe</button></a></td>';
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
				<tr class="hide">
				  <th>Time Duration</th>
				  <?=$PlanDays?>
				</tr>
				<?php
				$stmt = query("SELECT * FROM `subscription_accesstype` WHERE AccessTypeModule = 'Employer' order by `UID`");
				while($rslt=fetch($stmt)){?>
				<tr>
				  <th><?=$rslt['AccessTypeTitle']?><br /><small style="font-weight:normal;"><?=$rslt['AccessTypeDesc']?></small></th><?php
				  $stmtPlans = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employer' order by `PlanFee`");
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
                <tr class="hide">
				  <th>Charges</th>
				  <?=$PlanFee?>
				</tr>
			  </tbody>
			  <tfoot class="hide">
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
	<span class="red"><strong>Services not offered in the launched plan will be included in upcoming advanced subscription plans. Their details will be disclosed later.</strong></span>
  </div>
  <!-- /.page-content --> 
</div>
<!-- /.main-content -->
<?php include('footer.php');?>
