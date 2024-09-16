<?php
$stmt = query("SELECT * FROM `banners` LIMIT 1");
$banner = fetch($stmt);?>
<style type="text/css">
.mobile-hide{ display: block; }
.desktop-hide{ display: none; }
@media only screen and (max-width: 765px) {
   .mobile-hide{ display: none !important; }
   .desktop-hide{ display: block !important; }
}
</style>
<div class="container desktop-hide" style="background: transparent url('<?=$path?>images/banners/<?=$banner['BannerImage']?>') no-repeat scroll top center ; min-height: 400px;">
    <div class="sixteen columns">
      <div class="search-container" style="padding: 30px 10px;"> <?php $searchColor = "#3B4693";?>
        <!-- Form -->
        <h2 style="color:<?=$searchColor?>; font-size:30px; margin-bottom:10px;">Find a Job</h2>
        <input type="text" name="Search" id="Search" value="" placeholder="job title, keywords or company name" class="six columns" style="margin-left:0px; width:95%">
        <select name="JobCity" id="JobCity" placeholder="Search by city, province or region" class=" six columns" style="width:95%">
          <option value="">Search By City</option>
          <?=formListOpt('city', $_SESSION['JobCity'])?>
        </select>
        <div class="two columns">
          <button onclick="SearchSubmit('desktop-hide');" style="padding:8px 18px;  width:95%"><i class="fa fa-search"></i></button>
        </div>
        <div class="clear clearfix"></div>
        <!-- Announce -->
        <div class="announce" style="color:<?=$searchColor?>; margin-top: 10px; font-size:20px;"> We've over <strong style="color:<?=$searchColor?>;">
          <?=total("SELECT * FROM `jobs` WHERE 1")?>
          </strong> job offers for you! </div>
      </div>
    </div>
  </div>
 
<div class="container mobile-hide " style="background: transparent url('<?=$path?>images/banners/<?=$banner['BannerImage']?>') no-repeat scroll top center / 100% auto; min-height: 400px;">
    <div class="sixteen columns">
      <div class="search-container" style="padding: 30px 10px;"> <?php $searchColor = "#3B4693";?>
        <!-- Form -->
        <h2 style="color:<?=$searchColor?>; font-size:30px; margin-bottom:10px;">Find a Job</h2>
        <input type="text" name="Search" id="Search" value="" placeholder="job title, keywords or company name" class="six columns" style="padding:11px 18px !important; margin-left:0px;">
        <select name="JobCity" id="JobCity" placeholder="Search by city, province or region" class=" six columns" style="padding:11px 18px !important;">
          <option value="">Search By City</option>
          <?=formListOpt('city', $_SESSION['JobCity'])?>
        </select>
        <div class="two columns">
          <button onclick="SearchSubmit('mobile-hide');" style="padding:8px 18px;"><i class="fa fa-search"></i></button>
        </div>
        <div class="clear clearfix"></div>
        <!-- Announce -->
        <div class="announce" style="color:<?=$searchColor?>; margin-top: 10px; font-size:20px; font-weight:bold"> We've over <strong style="color:<?=$searchColor?>;">
          <?=total("SELECT * FROM `jobs` WHERE 1")?>
          </strong> job offers for you! </div>
      </div>
    </div>
  </div>
  
<script type="application/javascript">
function SearchSubmit(section){
	JobCity = $("."+section+" .search-container #JobCity ").val();
	Search = $("."+section+" .search-container #Search ").val();
	
	if(Search == '' && JobCity == ''){
		alert("Please enter your keyword or city for search...");
		return false;	
	}
	
	URL = '<?=$path?>jobs/list?Search=' + Search + '&JobCity=' + JobCity;
	window.location.href = URL;
}
</script>
