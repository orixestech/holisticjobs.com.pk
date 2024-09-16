<div class="widget">
  <h4><button class="button big margin-top-5">Sort by</button>
    <?=($_SESSION['SORTJOB']!='') ? '<a href="'.$path.'jobs/list?clear=sort"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a>' : ''?>
  </h4>
  <select data-placeholder="Choose Category" class="chosen-select-no-single" onchange="if (this.value) window.location.href=this.value">
    <option <?=($_SESSION['SORTJOB'] == 'newest')?'selected="selected"':''?> value="<?=$path?>jobs/list?sort=newest">Latest</option>
    <option <?=($_SESSION['SORTJOB'] == 'expiry')?'selected="selected"':''?> value="<?=$path?>jobs/list?sort=expiry">Expiring Soon</option>
  </select>
</div>
<div class="widget">
  <?=($_SESSION['JobCity']!='') ? '<a href="'.$path.'jobs/list?clear=JobCity"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a>' : ''?>
  <select name="JobCity" id="JobCity" placeholder="Search By City" class="chosen-select-no-single"  onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobCity='+this.value">
    <option value="">Search By City</option>
    <?=formListOpt('city', $_SESSION['JobCity'])?>
  </select>
</div>
<div class="widget">
  <?=($_SESSION['JobDesignation']!='') ? '<a href="'.$path.'jobs/list?clear=JobDesignation"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a>' : ''?>
  <select name="JobDesignation" id="JobDesignation" placeholder="Search By Designation" class="chosen-select-no-single"  onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobDesignation='+this.value">
      <option value="">Search By Designation</option>

    <?=formListOpt('designation', $_SESSION['JobDesignation'])?>
  </select>
</div>
<div class="widget">
  <?=($_SESSION['JobEmployerID']!='') ? '<a href="'.$path.'jobs/list?clear=JobEmployerID"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a>' : ''?>
  <select name="JobEmployerID" id="JobEmployerID" placeholder="Search By Company" class="chosen-select-no-single"  onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobEmployerID='+this.value">
      <option value="">Search By Company</option>
    <?php
	$stmt = query("SELECT UID, EmployerCompany  FROM `employer` WHERE 1 ORDER BY `employer`.`EmployerCompany` ASC ");
	while($rslt = fetch($stmt)){?>
    <option value="<?=$rslt['UID']?>" <?=($_SESSION['JobEmployerID']==$rslt['UID'])?'selected':''?> >
    <?=$rslt['EmployerCompany']?>
    </option>
    <?
	}?>
  </select>
</div>
<div class="widget">
  <?=($_SESSION['JobSalaryFrom']!='') ? '<div style="margin-left: 0px; float: left; width: 100%;"><a href="'.$path.'jobs/list?clear=JobSalary"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a></div>' : ''?>
   
  <div class="margin-bottom-10" style="margin-left: 0px; float: left; width: 45%;">
    <select name="JobSalaryFrom" id="JobSalaryFrom" class="chosen-select-no-single"  onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobSalaryFrom='+this.value">
		<option value="">Salary From</option>
      <?=SalleryDropdown($_SESSION['JobSalaryFrom'])?>
    </select>
  </div>
  <div class="margin-bottom-10" style="margin-left: 0px; width: 10%; float: left; text-align: center;"> _ </div>
  <div class="margin-bottom-10" style="margin-left: 0px; float: left; width: 45%;">
    <select name="JobSalaryTo" id="JobSalaryTo" class="chosen-select-no-single"   onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobSalaryTo='+this.value">
      <option value="">Salary To</option>
	  <?=SalleryDropdown($_SESSION['JobSalaryTo'])?>
    </select>
  </div>
</div>
<div class="widget">
  <?=($_SESSION['JobCategory']!='') ? '<a href="'.$path.'jobs/list?clear=JobCategory"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a>' : ''?>
  <select name="JobCategory" id="JobCategory" placeholder="Search By Department" class="chosen-select-no-single"  onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobCategory='+this.value">
      <option value="">Search By Department</option>
    <?php echo CategoryDropDown('Jobs',$_SESSION['JobCategory']);?>
  </select>
</div>
<div class="widget">
  <?=($_SESSION['JobNature']!='') ? '<a href="'.$path.'jobs/list?clear=JobNature"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a>' : ''?>
  <select name="JobNature" id="JobNature"  placeholder="Search By Nature" class="chosen-select-no-single"  onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobNature='+this.value">
    <option value="">Search By Nature</option>
    <option value="field">Field</option>
    <option value="office">Office</option>
    <option value="field-office">Field + Office</option>
    <option value="factory">Factory</option>
  </select>
</div>
<div class="widget">
  <?=($_SESSION['JobExperience']!='') ? '<a href="'.$path.'jobs/list?clear=JobExperience"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a>' : ''?>
  <select name="JobExperience" id="JobExperience" placeholder="Search By Experience" class="chosen-select-no-single"  onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobExperience='+this.value">
      <option value="">Search By Experience</option>
    <?=formListOpt('experience', $_SESSION['JobExperience'])?>
  </select>
</div>
<div class="widget">
  <?=($_SESSION['JobQualification']!='') ? '<a href="'.$path.'jobs/list?clear=JobQualification"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a>' : ''?>
  <select name="JobQualification" id="JobQualification" placeholder="Search By Qualification" class="chosen-select-no-single"  onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobQualification='+this.value">
      <option value="">Search By Qualification</option>
    <?=formListOpt('qualification', $_SESSION['JobQualification'])?>
  </select>
</div>
<div class="widget">
  <?=($_SESSION['JobType']!='') ? '<a href="'.$path.'jobs/list?clear=JobType"><button class="button pull-right small margin-bottom-5"><i class="fa fa-cut"></i></button></a>' : ''?>
  <select name="JobType" id="JobType" placeholder="Search By Type" class="chosen-select-no-single"  onchange="if (this.value) window.location.href='<?=$path?>jobs/list?JobType='+this.value">
      <option value="">Search By Type</option>
    <?=formListOpt('job-type', $_SESSION['JobType'])?>
  </select>
</div>
