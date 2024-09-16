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
          <li><a href="<?=$path?>">Home</a></li>
          <li>
            <?=$CONTENT['content_title']?>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>
<!-- Content
================================================== -->
<div id="categories">
  <?php
	$stmt = query( " SELECT * FROM `category` WHERE `catid` = 0 and `type` = 'Jobs' order by `title` " );
	while( $rslt = fetch( $stmt ) ){ ?>
  <div class="categories-group">
    <div class="container">
      <div class="four columns"> <a href="<?=$path?>jobs/list?JobCategory=<?=$rslt['id']?>">
        <h4>
          <?=$rslt['title']?>
        </h4>
        </a> </div>
      <div class="twelve columns">
        <?php
		  $stmt1 = query( " SELECT * FROM `category` WHERE `catid` = '".$rslt['id']."'  and `type` = 'Jobs' order by `title` " );
		  $totalSub = mysql_num_rows( $stmt1 );
		  //echo "total : " . round($totalSub/3, 0);
          while( $rslt1 = fetch( $stmt1 ) ){
				echo '<div class="four columns" style="margin:0; padding:2px;"><ul><li><a href="'.$path.'jobs/list?JobCategory='.$rslt1['id'].'">'.$rslt1['title'].'</a></li></ul></div>';			
		  } ?>
      </div>
    </div>
  </div>
  <?php 
	} ?>
</div>
