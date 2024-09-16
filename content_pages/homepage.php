<!-- Categories -->

<div class="container" style="display:none;">
  <div class="sixteen columns">
    <h3 class="margin-bottom-25">Popular Categories</h3>
    <ul id="popular-categories">
      <?php
	$stmt = query("SELECT `category`.*, ( SELECT count(`UID`) FROM `jobs` WHERE ".$DfaultJOBQuery." and `JobCategory` = `category`.`id` group by `JobCategory` ) as TOTCOUNT FROM `category` WHERE `catid` = 0 limit 10");
	while($rslt=fetch($stmt)){
		?>
      <li><a href="<?=$path?>jobs/list?JobCategory=<?=$rslt['id']?>"><img src="<?=$path?>images/category_images/<?=($rslt['image']=='')?'no-image.png':$rslt['image']?>" style="clear: both; margin: 0px auto; width: 150px; height: 40px;" alt="" />
        <?=$rslt['title']?>
        <?=($rslt['TOTCOUNT']>0)?' ( '. $rslt['TOTCOUNT'] .' ) ':''?>
        </a></li>
      <?
	}?>
    </ul>
    <div class="clearfix"></div>
    <div class="margin-top-30"></div>
    <a href="<?=$path?>page/categories" class="button centered">Browse All Categories</a>
    <div class="margin-bottom-50"></div>
  </div>
</div>

<!-- TOP PHARMACEUTICAL COMPANIES TO WORK WITH -->
<div class="infobox" style="background-color:#FBFBFB !important;">
  <div class="container">
    <div class="sixteen columns">
      <h5 class="clients-headline" style="color:#F00;">TOP COMPANIES TO WORK WITH </h5>
      <div class="clearfix"><br />
      </div>
      <ul id="popular-comp">
        <?php
			$stmt = query(" SELECT DISTINCT `employer`.`UID` , `employer`.`EmployerLogo` , COUNT(`jobs`.`UID`) AS TotalJobs FROM `jobs` 
							INNER JOIN `employer` ON (`jobs`.`JobEmployerID` = `employer`.`UID`) 
							WHERE (`jobs`.`JobStatus` = 'Publish' AND `employer`.`EmployerStatus` = 'Active') 
							GROUP BY `employer`.`UID` ORDER BY `TotalJobs` DESC  "); /*  */
							
			$stmt = query(" SELECT DISTINCT `employer`.`UID` , `employer`.`EmployerLogo`  FROM `employer` WHERE EmployerLogo !='' AND `employer`.`EmployerStatus` = 'Active' ORDER BY RAND() ");
			while($rslt=fetch($stmt)){
				$EmployerLogo = ($rslt['EmployerLogo']!='') ? $path . 'uploads/' . $rslt['EmployerLogo'] : $path . 'uploads/no-image.png'; ?>
        <li class="columns"  style="margin: 3px; border: 1px solid #e0e0e0; padding: 6px; width:127px; text-align:center;"><a href="<?=EmployerProfileLink($rslt['UID'])?>" style="margin: 0px auto; padding: 2px; float: none;"><img alt="" src="<?=$EmployerLogo?>" style="background: transparent none repeat scroll 0% 0%; height: 50px; max-width: 125px;" align="middle"></a></li>
        <?
			}?>
      </ul>
    </div>
  </div>
</div>
<div class="clearfix"></div>

<!-- Premium Jobs -->
<div class="container" style="display:none;">
  <h3 class="margin-bottom-25">Premium Jobs <span> <a href="<?=$path?>jobs/list?JobPriority=premium" class="button pull-right"><i class="fa fa-plus-circle"></i> Show More Jobs</a></span></h3>
  <div class=" eight columns">
    <ul class="job-list">
      <?php
	$skip = '0';
	$sql = "SELECT * FROM `jobs` WHERE ".$DfaultJOBQuery." and `JobPriority` = 'premium' and `UID` not in (".$skip.") ORDER BY `jobs`.`UID` DESC  limit 3";
	$stmt = query($sql);
	while($rslt=fetch($stmt)){
		$skip .= ', ' . $rslt['UID'];
		( GetEmployer('EmployerLogo', $rslt['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $rslt['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';
		echo JobBox($rslt['UID']);
	} ?>
    </ul>
  </div>
  <div class=" eight columns">
    <ul class="job-list">
      <?php
	$sql = "SELECT * FROM `jobs` WHERE ".$DfaultJOBQuery." and `JobPriority` = 'premium' and `UID` not in (".$skip.") ORDER BY `jobs`.`UID` DESC  limit 3";
	$stmt = mysql_query($sql);
	while($rslt=mysql_fetch_array($stmt)){ ( GetEmployer('EmployerLogo', $rslt['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $rslt['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';
		echo JobBox($rslt['UID']);
	} ?>
    </ul>
  </div>
  <div class="margin-bottom-55"></div>
</div>

<!-- Top Jobs -->
<div class="clearfix"></div>
<div class="container" style="display:none;"> 
  <!-- Recent Jobs -->
  <h3 class="margin-bottom-25">Top Jobs <span> <a href="<?=$path?>jobs/list?JobPriority=top" class="button pull-right"><i class="fa fa-plus-circle"></i> Show More Jobs</a></span></h3>
  <div class=" eight columns">
    <ul class="job-list">
      <?php
	$skip = '0';
	$sql = "SELECT * FROM `jobs` WHERE ".$DfaultJOBQuery." and `JobPriority` = 'top' and `UID` not in (".$skip.") ORDER BY `jobs`.`UID` DESC  limit 3";
	$stmt = query($sql);
	while($rslt=fetch($stmt)){
		$skip .= ', ' . $rslt['UID'];
		( GetEmployer('EmployerLogo', $rslt['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $rslt['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';
		echo JobBox($rslt['UID']);
	} ?>
    </ul>
  </div>
  <div class=" eight columns">
    <ul class="job-list">
      <?php
	$sql = "SELECT * FROM `jobs` WHERE ".$DfaultJOBQuery." and `JobPriority` = 'top' and `UID` not in (".$skip.") ORDER BY `jobs`.`UID` DESC  limit 3";
	$stmt = mysql_query($sql);
	while($rslt=mysql_fetch_array($stmt)){ 
		( GetEmployer('EmployerLogo', $rslt['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $rslt['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';
		echo JobBox($rslt['UID']);
	} ?>
    </ul>
  </div>
  <div class="margin-bottom-55"></div>
</div>

<!-- Recent Jobs -->
<div class="clearfix"></div>
<div class="container">
  <div class="eleven columns">
    <div class="padding-right">
      <h3 class="margin-bottom-25">Recent Jobs</h3>
      <ul class="job-list">
        <?php
		$sql = "SELECT * FROM `jobs` WHERE ".$DfaultJOBQuery." ORDER BY `jobs`.`UID` DESC  limit 15";
		$stmt = query($sql);
		while($rslt=fetch($stmt)){ 
			( GetEmployer('EmployerLogo', $rslt['JobEmployerID']) != '' ) ? $EmployerLogo = $path . 'uploads/' . GetEmployer('EmployerLogo', $rslt['JobEmployerID']) : $EmployerLogo = $path . 'uploads/no-image.png';
			echo JobBox($rslt['UID']);
		} ?>
      </ul>
      <a href="<?=$path?>jobs/list?sort=newest" class="button centered"><i class="fa fa-plus-circle"></i> Show All Jobs</a>
      <div class="margin-bottom-55"></div>
    </div>
  </div>
  <!-- Job Spotlight -->
  <div class="five columns"> <a href="#"><img src="<?=$path?>images/navigator.png" alt="Holistic Jobs" style="width:100%;" /></a> 
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
    <!-- Holistic Jobs --> 
    <ins class="adsbygoogle"
         style="display:inline-block;width:300px;height:600px"
         data-ad-client="ca-pub-7384521703100350"
         data-ad-slot="4547416629"></ins> 
    <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
    </script> 
    <!--
	<h3 class="margin-bottom-5">Upload your CV</h3>
    <div class="clearfix"></div>
	
    <div id="job-spotlight" class="showbiz-container">
      <div class="showbiz" data-left="#showbiz_left_1" data-right="#showbiz_right_1" data-play="#showbiz_play_1" >
        <div class="overflowholder">
          <div class="job-spotlight">
            <p style="text-align:justify;">Did not find a job of your interest? Kindly upload your CV and mention your company, designation and area where you would like to work in future. You will be contacted personally as soon as your interest matches or we find you fit for a job that you have not applied for.</p>
            <br />
            <a href="page/upload-cv" class="button big margin-top-5">Upload CV <i class="fa fa-arrow-circle-right"></i></a> </div>
          <div class="divider margin-top-0 padding-reset"></div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="clearfix"></div>
    </div>
	--> 
  </div>
</div>
</div>
<!-- Testimonials -->
<div id="testimonials"> 
  <!-- Slider -->
  <div class="container">
    <div class="sixteen columns">
      <div class="testimonials-slider">
        <ul class="slides">
          <li>
            <p>Training on Managerial Decision Making was one of the best trainings of its kind. The presenter was well prepared and has a deep knowledge  and experience about the topic as a whole. All the steps were well elaborated with live examples and case scenarios. <span>Yasir Quddus</span><span>SMIO, Novartis, Islamabad</span></p>
          </li>
          <li>
            <p>Training was very productive and useful where I got the opportunity to learn so many new things about Managerial Decision Making. It would definitely bring great success to my corporate. <span>Kahlid Mehmood</span><span>CEO Pharmion (Pvt.) Ltd. Islamabad</span></p>
          </li>
          <li>
            <p>Training on Sales Call is a very good initiative and this training was very relevant to what we face in the field. Overall environment of training and training room were excellent. <span>Naeem Umer</span><span>Sales Manager, Premier Health Services, Islamabad</span></p>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- Infobox --> 
<!-- Recent Posts --> 
