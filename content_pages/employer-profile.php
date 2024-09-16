<?php 
$sql = " SELECT * FROM `employer` WHERE `UID` =  '".$_GET["empid"]."' ";
$stmt = query($sql);
$EMPLOYER = fetch($stmt);

$EmployerLogo = ($EMPLOYER['EmployerLogo']!='') ? $path . 'uploads/' . $EMPLOYER['EmployerLogo'] : $path . 'uploads/no-image.png';
$EmployerCover =  ($EMPLOYER['EmployerCover']!='') ? $path . 'uploads/' . $EMPLOYER['EmployerCover'] : $path . 'uploads/employer-no-image.jpg';
?>
<?php if($EMPLOYER['EmployerCover']!=''){?>
<div class="fullwidthbanner-container" style="max-height:350px !important">
  <div class="container">
    <div class="cover-head ">
      <?=$EMPLOYER['EmployerCompany']?>
      @ Holistic Jobs</div>
  </div>
  <img src="<?=$EmployerCover?>" style="width:100%;"/> </div>
<?php } else { ?>
<div id="titlebar">
  <div class="container">
    <div class="sixteen columns">
      <h2>Company Profile </h2>
    </div>
  </div>
</div>
<?php } ?>
<!-- Content
================================================== -->
<div class="container">
  <div class="clearfix margin-bottom-55"></div>
  <!-- Recent Jobs -->
  <div class="eleven columns">
    <div class="padding-right"> 
      <!-- Company Info -->
      <div class="company-info"> <img src="<?=$EmployerLogo?>" style="max-width:300px; height:110px;" alt="">
        <div class="content">
          <h4>
            <?=$EMPLOYER['EmployerCompany']?>
          </h4>
          <?php if($EMPLOYER['EmployerWeb']!=''){?>
          <span><a href="<?=$EMPLOYER['EmployerWeb']?>"><i class="fa fa-link"></i>
          <?=$EMPLOYER['EmployerWeb']?>
          </a></span>
          <?php }?>
        </div>
        <div class="clearfix"></div>
      </div>
      <?php if($EMPLOYER['EmployerAboutContent']!=''){ ?>
      <h2 class="margin-bottom-10">About Company</h2>
      <p class="margin-reset">
        <?=ApplyTheme($EMPLOYER['EmployerAboutContent'])?>
      </p>
      <br class="margin-bottom-10">
      <?php } ?>
      <?php if($EMPLOYER['EmployerAchieveContent']!=''){ ?>
      <h2 class="margin-bottom-10">Key Achivements</h2>
      <p class="margin-reset">
        <?=ApplyTheme($EMPLOYER['EmployerAchieveContent'])?>
      </p>
      <br class="margin-bottom-10">
      <?php } ?>
    </div>
  </div>
  <!-- Widgets -->
  <div class="five columns"> 
    <!-- Sort by -->
    <div class="widget">
      <h4>Information</h4>
      <div class="job-overview">
        <ul>
          <?php if($EMPLOYER['EmployerAddress']!=''){ ?>
          <li> <i class="fa fa-map-marker"></i>
            <div> <strong>Location:</strong> <span>
              <?=$EMPLOYER['EmployerAddress']?>
              </span> </div>
          </li>
          <?php } ?>
          <?php if($EMPLOYER['EmployerLandLine']!=''){ ?>
          <li> <i class="fa fa-phone"></i>
            <div> <strong>Land Line Number:</strong> <span>
              <?=$EMPLOYER['EmployerLandLine']?>
              </span> </div>
          </li>
          <?php } ?>
          <?php if($EMPLOYER['EmployerFax']!=''){ ?>
          <li> <i class="fa fa-fax"></i>
            <div> <strong>Fax Number:</strong> <span>
              <?=$EMPLOYER['EmployerFax']?>
              </span> </div>
          </li>
          <?php } ?>
          <?php if($EMPLOYER['EmployerState']!=''){ ?>
          <li> <i class="fa fa-flag"></i>
            <div> <strong>Province or State:</strong> <span>
              <?=optionVal($EMPLOYER['EmployerState'])?>
              </span> </div>
          </li>
          <?php } ?>
          <?php if($EMPLOYER['EmployerCity']!=''){ ?>
          <li> <i class="fa fa-building"></i>
            <div> <strong>City:</strong> <span>
              <?=optionVal($EMPLOYER['EmployerCity'])?>
              </span> </div>
          </li>
          <?php } ?>
          <?php if($EMPLOYER['EmployerZipCode']!=''){ ?>
          <li> <i class="fa fa-envelope"></i>
            <div> <strong>Zip Code:</strong> <span>
              <?=$EMPLOYER['EmployerZipCode']?>
              </span> </div>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
  <!-- Widgets / End -->
  <div class="clearfix margin-bottom-55"></div>
  <?php
  $skip = '0';
  $sql = "SELECT * FROM `jobs` WHERE ".$DfaultJOBQuery." and JobEmployerID = '".$_REQUEST['empid']."' and `UID` not in (".$skip.") order by JobLastDateApply  ASC limit 4";
  if(total($sql)>0){ ?>
  <div class="container">
    <h3 class="margin-bottom-25">Posted Jobs <span> <a href="<?=$path?>jobs/list?JobEmployerID=<?=$_REQUEST['empid']?>" class="button pull-right"><i class="fa fa-plus-circle"></i> Show More Jobs</a></span></h3>
    <div class=" eight columns">
      <ul class="job-list">
        <?php 
        $stmt = query($sql);
        while($rslt=fetch($stmt)){
            $skip .= ', ' . $rslt['UID'];
            ( GetEmployer('EmployerLogo', $rslt['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $rslt['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';?>
        <li> <a href="<?=JobLink($rslt['UID'])?>" title="Job Details">
          <? //GetEmployer('EmployerCompany', $rslt['JobEmployerID'])?>
          <div class="job-list-content">
            <h4>
              <?=$rslt['JobTitle']?>
              <?=($rslt['JobNature']=='office')?' <span class="office"> Office </span> ':''?>
              <?=($rslt['JobNature']=='field')?' <span class="field"> Field </span> ':''?>
              <?=($rslt['JobNature']=='field-office')?' <span class="part-time"> Field + Office </span> ':''?>
              <span style="text-align: right; float: right; color: rgb(255, 0, 0); font-weight: bold; margin: 0px;">Last Date :
              <?=date("d M, Y",strtotime($rslt['JobLastDateApply']))?>
              </span> </h4>
            <div class="job-icons"> <span><i class="fa fa-building-o"></i>
              <?=GetEmployer('EmployerCompany', $rslt['JobEmployerID'])?>
              </span> <span><i class="fa fa-map-marker"></i>
              <?=JobExtra($rslt['UID'], 'JobCity', 'string');?>
              </span> <span><i class="fa fa-briefcase"></i>
              <?=GetCategory($rslt['JobCategory'])?>
              </span> </div>
          </div>
          </a>
          <div class="clearfix"></div>
        </li>
        <?php } ?>
      </ul>
    </div>
    <div class=" eight columns">
      <ul class="job-list">
        <?php
        $sql = "SELECT * FROM `jobs` WHERE ".$DfaultJOBQuery." and JobEmployerID = '".$_REQUEST['empid']."' and `UID` not in (".$skip.") ORDER BY `jobs`.`SystemDate` DESC  limit 4";
        $stmt = mysql_query($sql);
        while($rslt=mysql_fetch_array($stmt)){ ( GetEmployer('EmployerLogo', $rslt['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $rslt['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';?>
        <li> <a href="<?=JobLink($rslt['UID'])?>" title="Job Details">
          <? //GetEmployer('EmployerCompany', $rslt['JobEmployerID'])?>
          <div class="job-list-content">
            <h4>
              <?=$rslt['JobTitle']?>
              <?=($rslt['JobNature']=='office')?' <span class="office"> Office </span> ':''?>
              <?=($rslt['JobNature']=='field')?' <span class="field"> Field </span> ':''?>
              <?=($rslt['JobNature']=='field-office')?' <span class="part-time"> Field + Office </span> ':''?>
              <span style="text-align: right; float: right; color: rgb(255, 0, 0); font-weight: bold; margin: 0px;">Last Date :
              <?=date("d M, Y",strtotime($rslt['JobLastDateApply']))?>
              </span> </h4>
            <div class="job-icons"> <span><i class="fa fa-building-o"></i>
              <?=GetEmployer('EmployerCompany', $rslt['JobEmployerID'])?>
              </span> <span><i class="fa fa-map-marker"></i>
              <?=JobExtra($rslt['UID'], 'JobCity', 'string');?>
              </span> <span><i class="fa fa-briefcase"></i>
              <?=GetCategory($rslt['JobCategory'])?>
              </span> </div>
          </div>
          </a>
          <div class="clearfix"></div>
        </li>
        <?php } ?>
      </ul>
    </div>
    <div class="margin-bottom-55"></div>
  </div>
  <?php
  }?>
</div>
