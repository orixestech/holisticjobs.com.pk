<?php include('header.php');?>
    <?php
if($_GET["delete"]=='true' && $_GET["id"]){
	$pageTitle = GetData('EmployeeName','employee','UID',$_GET["id"]);
	mysql_query(" DELETE FROM  employee WHERE employee.`UID` = '".$_GET["id"]."' ");
	$num = mysql_affected_rows();
	if($num){
		Track('Employee [ '.$pageTitle.' ] Deleted...!');
		$message = Alert('success', 'Employee [ '.$pageTitle.' ] Deleted...!');
	}
}

if($_GET["status"]!='' && $_GET["id"] && ($_GET["status"]=='Active' || $_GET["status"]=='Block') ){
	$pageTitle = GetData('EmployeeName','employee','UID',$_GET["id"]);
	$sql = " UPDATE  employee SET EmployeeStatus = '".$_GET["status"]."' WHERE employee.`UID` = '".$_GET["id"]."' ";
	mysql_query($sql);
	$num = mysql_affected_rows();
	if($num){
		Track('Employee [ '.$pageTitle.' ] Status Change...!');
		$message = Alert('success', 'Employee [ '.$pageTitle.' ] Status Change...!');
	}
}

$QUERYSTRING = '';
$whereSQL = 'where 1 ';
if($_GET['keyword'] != ''){
	$whereSQL .= " and ( EmployeeName like '%".$_GET['keyword']."%' or EmployeeEmail like '%".$_GET['keyword']."%' ) " ;
	$QUERYSTRING .= 'keyword='.$_GET['keyword'].'&';
}

if($_GET['gender'] != ''){
	$whereSQL .= " and ( EmployeeGender = '".$_GET['gender']."' ) ";
	$QUERYSTRING .= 'gender='.$_GET['gender'].'&';
}

if($_GET['city'] != ''){
	$whereSQL .= " and EmployeeCity  = '".$_GET['city']."' ";
	$QUERYSTRING .= 'city='.$_GET['city'].'&';
}

if($_GET['experience'] != ''){
	$whereSQL .= " and EmployeeTotalExperience = '".$_GET['experience']."' ";
	$QUERYSTRING .= 'experience='.$_GET['experience'].'&';
}

if($_GET['qualification'] != ''){
	$whereSQL .= " and EmployeeQualification = '".$_GET['qualification']."' ";
	$QUERYSTRING .= 'qualification='.$_GET['qualification'].'&';
}









?>
    <link href="<?=$path?>css/paging.css" rel="stylesheet">
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Employees</li>
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
                <h3 class="header smaller lighter blue">Employees</h3>
				
                <div id="ajax-result">
                  <?=$message?>
                </div>
				<div class="col-xs-12">
              <div class="widget-box collapsed"> <!-- -->
                <div class="widget-header">
                  <h4>Filter Records</h4>
                  <div class="widget-toolbar"> <a href="#" data-action="collapse"> <i class="1 icon-chevron-down bigger-125"></i> </a> </div>
                </div>
                <div class="widget-body">
                  <div class="widget-main">
                    <form class="form-inline" id="employee-view-filter-form" method="get">
                      <div class="col-xs-3 col-sm-3">
                        <label>KeyWords</label>
                        <input class="form-control " type="text" name="keyword" id="keyword"/>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Gender</label>
                        <select name="gender" id="gender" class="chosen-select-no-single col-xs-12 col-sm-12">
                          <option value=""> Please Select</option>
						  <option value="Male">Male</option>
                 		 <option value="Female">Female</option>
                          </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>City</label>
                        <select name="city" id="city" class="form-control">
                          <option value=""> Please Select</option>
                          <?=formListOpt('city', 0)?>
                        </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Experience Year</label>
                        <select name="experience" id="experience" class="form-control">
                          <option value=""> Please Select</option>
                          <?=formListOpt('experience', 0)?>
                        </select>
                      </div>
                      <div class="col-xs-2 col-sm-2">
                        <label>Qualification</label>
                        <select name="qualification" id="qualification" class="form-control">
                          <option value=""> Please Select</option>
                          <?=formListOpt('qualification', 0)?>
                        </select>
                      </div>
                      <div class="col-xs-1 col-sm-1">
                        <button type="submit" style="margin-top:24px;" class="btn btn-info btn-sm"> <i class="icon-search bigger-110"></i> Search </button>
                      </div>
                      <div class="col-xs-1 col-sm-1"> <a href="employee-view.php" style="margin-top:24px;" class="btn btn-success btn-sm"> Clear </a> </div>
                    </form>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
            </div>
				<div class="col-xs-12">
                <div class="table-header"> Results for "All Employees" <span style="float:right; margin-right:8px;"> <a href="<?=$path?>admin/employee.php" class="btn btn-success btn-sm">Add New</a> 
                  <!--<button type="button" class="btn btn-success btn-sm" onclick="openAddfaq();">Add New</button>--> 
                  </span> </div>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover ">
                    <?php
				$limit = 25;
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$paging = getPaging('employee',$whereSQL . "  ORDER BY `employee`.`LastLoginDateTime` DESC ",$limit,'employee-view.php','?'.$QUERYSTRING,$_REQUEST['pager']);
				//echo $paging[0];
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
                    <thead>
                      <tr>
                        <th width="70">Sr. No</th>
                        <th width="5%">Profile</th>
                        <th>Basic Details</th>
                        <th>Application</th>
						<th width="85">Subscription Plan</th>
                        <th width="85">Staus</th>
                        <th width="85">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
			  if($row>0){
				while( $rslt = mysql_fetch_array($rs_pages) ){
					$totalApplication = total("SELECT * FROM `jobs_apply` WHERE `EmployeeID` =  '".$rslt['UID']."' ");
					($rslt['EmployeeLogo']=='')?$rslt['EmployeeLogo']='no-image.png':'';
					$ProfileScore = round(ProfileScore($rslt['UID']),0); ?>
                      <tr id="row_<?=$rslt['UID']?>">
                        <th><?=$count?></th>
                        <td><img src="../image.php?image=<?=$path?>uploads/<?=$rslt['EmployeeLogo']?>" style="max-width:150px; max-height:130px;"></td>
                        <td><strong>Last Login Date</strong> : <?php if($rslt['LastLoginDateTime']) echo date("d M, Y", strtotime($rslt['LastLoginDateTime']))?><br>
							<strong>Name</strong> : <?=optionVal($rslt['EmployeeTitle'])?>&nbsp;<?=$rslt['EmployeeName']?> <br>
							<strong>Email</strong> : <?=$rslt['EmployeeEmail']?> <br>
							<strong>Mobile</strong> : <?=$rslt['EmployeeMobile']?> <br>
							<strong>City</strong> : <?=optionVal($rslt['EmployeeCity'])?><br>
							<strong>Profile Score</strong> : <?=$ProfileScore?>%</td>
                        <td><a href="#ViewEmployeeApplications" data-toggle="modal" data-uid="<?=$rslt["UID"]?>">
                        <?=$totalApplication?>
                        </a></td>
                        <td id="subscription"><select name="EmployerSubscription" id="EmployerSubscription" onChange="UpdateSub(<?=$rslt['UID']?>, this.value)">
									<option value="">Please Select</option>
                                      <?php
									$stmt2 = query("SELECT * FROM `subscriptions` WHERE PlanModule = 'Employee' ORDER by PlanFee");
									while($rslt2=fetch($stmt2)){?>
                                      <option value="<?=$rslt2['UID']?>" <?=($rslt['EmployeeSubscription']==$rslt2['UID'])?'selected':''?>>
                                    <?=$rslt2['PlanTitle']?>
                                    for
                                    <?=($rslt2['PlanFee']==0)?'Free':"Rs. ".$rslt2['PlanFee']?>
                                    </option>
                                      <?php
									}?>
                                    </select>
                            <?php echo SubscriptionStatus('string', $rslt['EmployeeSubscriptionExpire'] ); ?></td>
							<td><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['EmployeeStatus']=='Active')?'success':'danger'?> dropdown-toggle">
                            <?=$rslt['EmployeeStatus']?>
                            <i class="icon-angle-down icon-on-right"></i> </button>
                            <ul class="dropdown-menu">
                              <li><a href="employee-view.php?status=Active&id=<?=$rslt['UID']?>">Active</a></li>
                              <li><a href="employee-view.php?status=Block&id=<?=$rslt['UID']?>">Block</a></li>
                            </ul>
                          </div></td>
                        <td><?php
						$data = array(
									array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'employee.php?mode=update&code='.$rslt['UID'], 'js'=>' role="button" class="green" title="Edit Employee Details"'),
									array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'employee-view.php?delete=true&id='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete" '),
									array('title'=>'<i class="icon-zoom-in bigger-130"></i>', 'href'=>'#ViewEmployee', 'js'=>' role="button" class="blue" data-toggle="modal" title="View Employee Details" data-uid="'.$rslt["UID"].'" ')
								);
						echo TableActions($data); 
				?><span class=" pull-right" id="SendLoginDetails">
                                    <button type="submit" class="btn btn-primary btn-xs" onclick="SendLoginDetails('employee',<?=$rslt["UID"]?>,'row_<?=$rslt["UID"]?> span#SendLoginDetails')"> Send Password </button>
                                    </span></td>
                      </tr>
                      <? $count++;
				  }
				} else {  ?>
                      <tr>
                        <th class="center" colspan="10">No Records Found.!</th>
                      </tr>
                      <?
				} ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="center" colspan="10"><div class="pull-right">
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
		if(plan==''){
			alert('Please select valid plan.');		
		} else {
			ajaxreq('ajaxpage.php', "action=changesubs&module=employee&uid="+emp+"&plan="+plan, "row_"+emp+" #subscription");
		}
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
    <?=GenModelBOX('ViewEmployee', 'view-employee.php')?>
	<?=GenModelBOX('ViewEmployeeApplications', 'view-employee-applications.php')?>
    <?php include('footer.php');?>
