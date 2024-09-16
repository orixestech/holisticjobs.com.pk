<?php include('header.php');?>
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Employer User Manual</li>
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
                <h3 class="header smaller lighter blue">Employer User Manual</h3>
                <div id="ajax-result">
                  <?=$message?>
                </div>
                <div class="col-xs-12">
                  <div class="table-header"> Results for "All User Manuals for Employer"</div>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover ">
                      <?php
				if($_GET["delete"]=='true' && $_GET["id"]){
					$pageTitle = GetData('ManualQuestion','user_manual','UID',$_GET["id"]);
					mysql_query(" DELETE FROM  `user_manual` WHERE UID = '".$_GET["id"]."' ");
					$num = mysql_affected_rows();
					if($num){
						Track('User Manual Question [ '.$pageTitle.' ] Deleted...!');
						$message = Alert('success', 'User Manual Question [ '.$pageTitle.' ] Deleted...!');
					}
				}
				$limit = 15;
				(!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
				$whereSQL = "WHERE `category`.`type` = 'User-Manual-Employer' ";
				$count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
				$innerJOIN = " INNER JOIN `category` ON (`user_manual`.`ManualCategory` = `category`.`id`) ";
				$paging = getPaging('`user_manual`',$innerJOIN . $whereSQL . " ORDER BY `SystemDate` DESC ",$limit,'user-manual-view.php','?'.$QUERYSTRING,$_REQUEST['pager']);
				//echo $paging[0];
				$rs_pages = mysql_query($paging[0]) or die($paging[0]);
				$row = mysql_num_rows( $rs_pages );
				$pagination = $paging[1];?>
                      <thead>
                        <tr>
                          <th width="6%">Sr. No</th>
                          <th width="20%">Category</th>
                          <th>Question/Answer</th>
                          <th width="13%">Create Date</th>
                          <th width="5%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                  while( $rslt = mysql_fetch_array($rs_pages) ){ $cnt++; 
                  $categoryPrint = $rslt['title'];?>
                        <tr>
                          <td><?=$count?></td>
                          <td><strong>
                            <?=$categoryPrint?>
                            </strong></td>
                          <td><strong>
                            <?=$rslt['ManualQuestion']?>
                            </strong><br />
                            <?=$rslt['ManualAnswer']?></td>
                          <td><?=date("d M, Y", strtotime($rslt['SystemDate']))?></td>
                          <td><?php
                       $data = array(
                        array('title'=>'<i class="icon-pencil bigger-130"></i>', 'href'=>'user-manual-employer.php?mode=update&code='.$rslt['UID'], 'js'=>' role="button" class="green" title="Edit Question"'),
                        array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'user-manual-view.php?delete=true&id='.$rslt['UID'], 'js'=>' class="red"  title="Delete Question" ')
                      );
                      echo TableActions($data);?>
                          </td>
                        </tr>
                        <? $count++;
                  } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th class="center" colspan="5"><div class="pull-right">
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
