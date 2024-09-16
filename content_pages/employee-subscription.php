<!-- Titlebar
================================================== -->

<div id="titlebar" class="single">
  <div class="container">
    <div class="sixteen columns">
      <h2>
        <?=$CONTENT['content_title']?>
      </h2>
      <nav id="breadcrumbs">
        <ul>
          <li>You are here:</li>
          <li><a href="#">Home</a></li>
          <li>
            <?=$CONTENT['content_title']?>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>
<!-- Pricing Tables
================================================== --> 
<!-- Container / Start -->
<div class="container">
  <div class="sixteen columns">
    <p class="margin-reset">
      <?=$CONTENT['content_desc']?>
    </p>
    <div class="margin-bottom-20"></div>
  </div>
  <div class="sixteen columns">
    <h3 class="margin-bottom-20">Our Plans</h3>
    <?php
    $stmt = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employee' order by `UID`");
	$PlanTitle = $PlanDays = $PlanFee = '';
	while($rslt=fetch($stmt)){
		$PlanTitle .= '<th width="12%" align="center" style="text-align:center">'.ucwords($rslt['PlanTitle']).'</th>';
		$PlanDays .= '<td align="center">'.round($rslt['PlanDays']/30, 0).' Months</td>';
		$PlanFee .= '<td align="center" '.$PlanClass.'>Rs. '.$rslt['PlanFee'].' / Month</td>';
		//$PlanButton .= '<td align="center"><a class="button" href="'.$path.'page/subscription-signup?plan='.$rslt['UID'].'"><i class="fa fa-shopping-cart"></i> Subscribe</a></td>';
		
		if($rslt['PlanFee'] == 0){
			$PlanButton .= '<td align="center" style="padding:5px;"><a class="button green" href="'.$path.'page/employee-signup?plan='.$rslt['UID'].'">Subscribe</a></td>';
		} else {
			$PlanButton .= '<td align="center" style="padding:5px;"><a class="button" href="'.$path.'page/contact">Contact us</a></td>';
		}
	}?>
    <table class="manage-table  responsive-table stacktable large-only" style="width:100%;">
      <thead class="red">
        <tr>
          <th></th>
          <?=$PlanTitle?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong style="color:#f00;">Time Duration.</strong></td>
          <?=$PlanDays?>
        </tr>
        <?php
        $stmt = query("SELECT * FROM `subscription_accesstype` WHERE  AccessTypeModule = 'Employee' order by `UID`");
		while($rslt=fetch($stmt)){?>
        <tr>
          <td><strong style="color:#000;"><?=$rslt['AccessTypeTitle']?></strong><p><?=$rslt['AccessTypeDesc']?></p></td><?php
          $stmtPlans = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employee' order by `UID`");
		  while($rsltPlans = fetch($stmtPlans)){
			  $stmtAccess = query("SELECT * FROM `subscription_access` WHERE `AccessSubID` = '".$rsltPlans['UID']."' and `AccessTypeKey` = '".$rslt['AccessTypeKey']."'");
			  $rsltAccess = fetch($stmtAccess);
			  
			  ($rsltAccess['AccessAllowed']==1) ? $access = '<i class="fa fa-check green"></i>' : $access = '<i class="fa fa-close red"></i>';
			  
			  ($rsltAccess['AccessDays'] > 0 && $rsltAccess['AccessAllowed']==1) ? $access = $rsltAccess['AccessDays'] . ' Days' : '';
			  
			  echo '<td align="center">'.$access.'</td>';
		  }?>
        </tr>
        <?php
        }?>
		<tr>
          <td><strong style="color:#f00;">Monthly Charges.</strong></td>
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
  </div>
</div>
<!-- Container / End -->
<div class="margin-bottom-50"></div>
