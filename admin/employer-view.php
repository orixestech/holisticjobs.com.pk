<?php include('header.php');?>
<?php
if($_GET["delete"]=='true' && $_GET["id"]){
	$pageTitle = GetData('EmployerCompany','employer','UID',$_GET["id"]);
	mysql_query(" DELETE FROM  `employer` WHERE `employer`.`UID` = '".$_GET["id"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Employer [ '.$pageTitle.' ] Deleted...!');
		$message = Alert('success', 'Employer [ '.$pageTitle.' ] Deleted...!');
	}
}

if($_GET["status"]!='' && $_GET["id"] && ($_GET["status"]=='Active' || $_GET["status"]=='Block') ){
	$pageTitle = GetData('EmployerCompany','employer','UID',$_GET["id"]);
	$sql = " UPDATE  employer SET EmployerStatus = '".$_GET["status"]."' WHERE employer.`UID` = '".$_GET["id"]."' ";
	mysql_query($sql);
	$num = mysql_affected_rows();
	if($num){
		Track('Employer [ '.$pageTitle.' ] Status Change...!');
		$message = Alert('success', 'Employer [ '.$pageTitle.' ] Status Change...!');
	}
}

$QUERYSTRING = '';
$whereSQL = 'where 1 ';
if($_GET['city'] != ''){
	$whereSQL .= " and EmployerCity  = '".$_GET['city']."' ";
	$QUERYSTRING .= 'city='.$_GET['city'].'&';
}

if($_GET['empid'] != ''){
	$whereSQL .= " and UID  = '".$_GET['empid']."' ";
	$QUERYSTRING .= 'empid='.$_GET['empid'].'&';
}



?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Employer</li>
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
            <h3 class="header smaller lighter blue">Employer</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="col-xs-12">
              <div class="widget-box collapsed">
                <div class="widget-header">
                  <h4>Filter Records</h4>
				  <div class="widget-toolbar"> <a href="#" data-action="collapse"> <i class="1 icon-chevron-down bigger-125"></i> </a> </div>
                </div>
                <div class="widget-body">
                  <div class="widget-main">
                    <form class="form-inline" id="admin-meeting-filter-form">
                      <div class="col-xs-2">
                        <label>Area</label>
                        <select name="city" id="city" class="form-control col-xs-6 col-sm-6">
                          <?=formListOpt('city', $_GET['city'])?>
                        </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Company</label>
                        <select name="empid" id="empid" class="chosen-select-no-single col-xs-12 col-sm-12">
                          <option value=""> Please Select</option>
                          <?php
						$stmt = query("SELECT UID, EmployerCompany  FROM `employer` WHERE 1 ORDER BY `employer`.`EmployerCompany` ASC ");
						while($rslt = fetch($stmt)){?>
                          <option value="<?=$rslt['UID']?>">
                          <?=$rslt['EmployerCompany']?>
                          </option>
                          <?
						}?>
                        </select>
                      </div>
                      <div class="col-xs-2">
                        <button type="submit" style="margin-top:25px;" class="btn btn-info btn-sm"> <i class="icon-search bigger-110"></i> Search </button>
                      </div>
                    </form>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="table-header"> Results for "All Employers" <span style="float:right; margin-right:8px;"> <a href="<?=$path?>admin/employer.php" class="btn btn-success btn-sm">Add New</a> </span> </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover ">
                  <?php
				$limit = 15;
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('employer',$whereSQL . "  ORDER BY SystemDate DESC ",$limit,'employer-view.php','?'.$QUERYSTRING,$_REQUEST['pager']);
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
                  <thead>
                    <tr>
                      <th width="70">Sr. No</th>
                      <th width="15%">LOGO</th>
                      <th>Company Name</th>
                      <th>City</th>
                      <th width="15">Jobs</th>
                      <th width="85">Subscription Plan</th>
                      <th width="85">Status</th>
                      <th width="85">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
			  if($row>0){ 
				while( $rslt = mysql_fetch_array($rs_pages) ){ $totalJobs = total("SELECT * FROM `jobs` WHERE `JobEmployerID` = '".$rslt['UID']."' "); ($rslt['EmployerLogo']=='')?$rslt['EmployerLogo']='no-image.png':'';?>
                    <tr id="row_<?=$rslt['UID']?>">
                      <th><?=$count?></th>
                      <td><img src="<?=$path?>uploads/<?=$rslt['EmployerLogo']?>" style="max-width:150px; max-height:130px; min-height:75px;"></td>
                      <td><a href="<?=EmployerProfileLink($rslt['UID'])?>" target="_blank">
                        <?=$rslt['EmployerCompany']?>
                        </a>
						<br> Current Password : <strong style="color:#FF0000;"><?=PassWord($rslt['EmployerPassword'],'show')?></strong>
						</td>
                      <td><?=optionVal($rslt['EmployerCity'])?></td>
                      <td><a href="jobs-view.php?JobEmployerID=<?=$rslt['UID']?>">
                        <?=$totalJobs?>
                        </a></td>
                      <td><select name="EmployerSubscription" id="EmployerSubscription" onChange="UpdateSub(<?=$rslt['UID']?>, this.value)">
                          <?php
									$stmt2 = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employer' ORDER by PlanFee");
									while($rslt2=fetch($stmt2)){?>
                          <option value="<?=$rslt2['UID']?>" <?=($rslt['EmployerSubscription']==$rslt2['UID'])?'selected':''?>>
                          <?=$rslt2['PlanTitle']?>
                          for
                          <?=($rslt2['PlanFee']==0)?'Free':"Rs. ".$rslt2['PlanFee']?>
                          </option>
                          <?php
									}?>
                        </select>
                        <?php echo SubscriptionStatus('string', $rslt['EmployerSubscriptionExpire'] ); ?></td>
                      <td><div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['EmployerStatus']=='Active')?'success':'danger'?> dropdown-toggle">
                          <?=$rslt['EmployerStatus']?>
                          <i class="icon-angle-down icon-on-right"></i> </button>
                          <ul class="dropdown-menu">
                            <li><a href="employer-view.php?status=Active&id=<?=$rslt['UID']?>">Active</a></li>
                            <li><a href="employer-view.php?status=Block&id=<?=$rslt['UID']?>">Block</a></li>
                          </ul>
                        </div></td>
                      <td><?php
					$data = array(
								array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'employer.php?mode=update&code='.$rslt['UID'], 'js'=>' role="button" class="green" title="Edit Employer Details"'),
								array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'employer-view.php?delete=true&id='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete" '),
								array('title'=>'<i class="icon-zoom-in bigger-130"></i>', 'href'=>'#ViewEmployer', 'js'=>' role="button" class="blue" data-toggle="modal" title="View Employer Details" data-uid="'.$rslt["UID"].'" ')
							);
					echo TableActions($data);?>
                        <span id="SendLoginDetails" class=" pull-right">
                        <button onclick="SendLoginDetails('employer',<?=$rslt['UID']?>,'row_<?=$rslt['UID']?> span#SendLoginDetails')" class="btn btn-primary btn-xs" type="submit"> Send Password </button>
                        </span></td>
                    </tr>
                    <? $count++;
				  }
				} else {  ?>
                    <tr>
                      <th class="center" colspan="9">No Records Found.!</th>
                    </tr>
                    <?

				} ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="center" colspan="9"><div class="pull-right">
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
<script>

function UpdateSub(emp, plan){
	
	if( confirm('Are you sure to change plan?') ){
		alert(emp +' employer plan change to ' + plan);		
	}
	
}


function openStatusfaq(faq_id, status)
{
	form_data = "action=faq_status&faq_id="+faq_id+"&status="+status;
	//alert(form_data);
	
	$.ajax({
		cache: false, 
		type: "POST",
		url: "employee-ajax.php",
		data: form_data,
		dataType : 'json',
		async: false,
		success: function(data){
			//alert(data.status);
			
			if(data.status == 'success')
			{
				$("#ajax-result").html(data.data);
				
				updatefaqGrid();
			}
		},
		error: function(){
			//alert('Error Loading AJAX Request...!');
		}
	});
}


function deletefaq(id)
{
	//ajaxreq('employee-ajax.php', "action=delete&faq_id="+id, 'ajax-result');
	$("#row_"+id).remove();
}

</script>
<?=GenModelBOX('ViewEmployer', 'view-employer.php')?>
<?php include('footer.php');?>
