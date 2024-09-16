<?php include('header.php');?>
    <link href="<?=$path?>css/paging.css" rel="stylesheet">
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">User Manual</li>
        </ul>
        <!-- .breadcrumb --> 
      </div>
      <div class="page-content">
        <div class="page-header">
          <h1>User Manual <small> <i class="icon-double-angle-right"></i>User Manual </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <div class="col-xs-3">
              <div class="widget-box">
                <div class="widget-header header-color-blue2">
                  <h4 class="lighter smaller"><strong>FAQs&nbsp;(Frequently Asked Questions)</strong></h4>
                </div>
                <div class="widget-body">
                  <div class="widget-main padding-8">
                    <div class="tree tree-selectable">
                      
                      <?php
					  $cnt = 0;
                      $stmt = query(" SELECT * FROM `category` WHERE `catid` = 0 and `type` = 'User-Manual-Employer' order by title; ");
					  while( $rslt = fetch($stmt) ){ if($cnt==0){ $LoadFirstCat = $rslt['id']; } $cnt++;
						  $subCatSql = "SELECT * FROM `category` WHERE `catid` = '".$rslt['id']."' and `type` = 'User-Manual-Employer' order by title;";
						  $subCat = total($subCatSql);
						  $totalitem = total( " SELECT * FROM `user_manual` WHERE `ManualCategory` in ( SELECT `id` FROM `category` WHERE `catid` = '".$rslt['id']."' or `id` = '".$rslt['id']."' ) " ); ?>
                          <div class="tree-folder" style="display: <?=($totalitem>0)?'block':'none'?>;">
                            <div class="tree-folder-header" <?=($subCat>0)?'onClick="ToggleCat(\'cat_'.$rslt['id'].'\',this);"':'onClick="LoadHelpDesk('.$rslt['id'].')"'?> > <?=($subCat>0)?'<i class="icon-minus"></i>':''?>
                              <div class="tree-folder-name"><strong><?=$rslt['title']?> (<?=$totalitem?>)</strong></div>
                            </div>
                            <?php if($subCat>0){ ?>
                                    <div class="tree-folder-content" style="display: block;" id="cat_<?=$rslt['id']?>"><?php
                                    $stmt1 = query($subCatSql);
									while( $rslt1 = fetch($stmt1) ){ 
										$totalitem1 = total( " SELECT * FROM `user_manual` WHERE `ManualCategory` in ( SELECT `id` FROM `category` WHERE `id` = '".$rslt1['id']."' ) " ); ?>
                                        <div class="tree-item" onClick="LoadHelpDesk(<?=$rslt1['id']?>)"> 
                                          <div class="tree-item-name"><?=$rslt1['title']?> (<?=$totalitem1?>)</div>
                                        </div>
									<?php
                                    }?>
                                    </div>
							<?php }?>
                          </div> <?
					  } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xs-9">
              <div class="widget-box" id="HelpDeskQuestions">
                
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
<script type="text/javascript">
	function ToggleCat(id, obj){
		divclass = $(' > i', obj).attr('class');
		if(divclass=='icon-minus'){
			$(' > i', obj).removeClass('icon-minus');
			$(' > i', obj).addClass('icon-plus');
		}
		if(divclass=='icon-plus'){
			$(' > i', obj).removeClass('icon-plus');
			$(' > i', obj).addClass('icon-minus');
			$('#HelpDeskQuestions .widget-header h4').html('Please select Sub Category');
			$('#HelpDeskQuestions .widget-main').html('Please select Sub Category');
		}
		$('#'+id).toggle('slow');
		
		
	}	
	
	function LoadHelpDesk(id){
		ajaxreq('ajaxpage.php','action=LoadHelpDesk&cid='+id,'HelpDeskQuestions');
	}
	
	LoadHelpDesk(<?=$LoadFirstCat?>);
</script>
    <?php include('footer.php');?>
