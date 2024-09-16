<?php include('header.php');?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Access Type</li>
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
                <h3 class="header smaller lighter blue">Employee Subscription Access Type</h3>
                <div id="ajax-result"> </div>
                <div class="col-xs-12">
                  <div class="table-header"> Results for "All Access Types"</div>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover ">
                      <?php
					$QUERYSTRING = '';
					$whereSQL = " where `AccessTypeModule` = 'Employee' ";
					$limit = 15;
					(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
					$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
					$paging = getPaging('subscription_accesstype',$whereSQL . " ORDER BY AccessTypeTitle ",$limit,'employee-access-type.php','?'.$QUERYSTRING,$_REQUEST['pager']);
					//echo $paging[0];
					$rs_pages = mysql_query($paging[0]) or die($paging[0]);
					$row = mysql_num_rows( $rs_pages );
					$pagination = $paging[1];?>
                      <thead>
                        <tr>
                          <th width="85">Sr. No</th>
                          <th width="250">Key</th>
                          <th width="250">Title</th>
                          <th>Description</th>
                          <th class="hidden-480" width="85">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
					  if($row>0){
						while( $rslt = mysql_fetch_array($rs_pages) ){?>
                        <tr>
                          <td><?=$count?></td>
                          <td><?=$rslt['AccessTypeKey']?></td>
                          <td><?=$rslt['AccessTypeTitle']?></td>
                          <td><?=$rslt['AccessTypeDesc']?></td>
                          <td><?php
							$data = array(
										array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'#EditAccessType', 'js'=>' role="button" class="green" data-toggle="modal" title="View Subscription" data-uid="'.$rslt["UID"].'" '),
										array('title'=>'<i class="icon-zoom-in bigger-130"></i>', 'href'=>'#ViewAccessType', 'js'=>' role="button" class="blue" data-toggle="modal" title="View Subscription" data-uid="'.$rslt["UID"].'" ')		
									);
							echo TableActions($data);?></td>
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
    <?=GenModelBOX('ViewAccessType', 'view-access-type.php')?>
    <?=GenModelBOX('EditAccessType', 'edit-access-type.php')?>
    <?php include('footer.php');?>
