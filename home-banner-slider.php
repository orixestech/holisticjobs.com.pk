<style>
.header-search-spotlight {
	background-color: #717171;
	padding: 30px;
}
.header-search-spotlight h2 {
	color: #FFF;
}
.header-search-spotlight input {
	height: 45px;
	padding: 8px 18px;
}
</style>
<div class="container">
  <div class="fullwidthbanner">
    <ul>
      <?php
	$stmt = query("SELECT * FROM `banners` WHERE 1");
	while($rslt = fetch($stmt)){ ?>
      <li data-fstransition="fade" data-transition="fade" data-slotamount="10" data-masterspeed="300"> <img src="<?=$path?>images/banners/<?=$rslt['BannerImage']?>" width="1024" alt="">
        <div class="caption title sfb" data-x="center" data-y="220" data-speed="400" data-start="800"  data-easing="easeOutExpo">
          <h2>
            <?=$rslt['BannerHeading']?>
          </h2>
        </div>
        <div class="caption text align-center sfb" data-x="center" data-y="300" data-speed="400" data-start="1200" data-easing="easeOutExpo">
          <p>
            <?=$rslt['BannerDesc']?>
          </p>
        </div>
        <div class="caption sfb" data-x="center" data-y="320" data-speed="400" data-start="1600" data-easing="easeOutExpo"></div>
      </li>
      <?php 
	} ?>
    </ul>
  </div>
</div>
<div class="container">
  <div class="header-search-spotlight" style="margin-top:19px"> 
    <!-- Form -->
    <h2>Find a Job</h2>
    
      <input type="text" name="Search" id="Search" value="" placeholder="job title, keywords or company name" class="six columns">
      <select name="JobCity" id="JobCity" placeholder="Search by city, province or region" class=" six columns">
    <option value="">Search By City</option>
    <?=formListOpt('city', $_SESSION['JobCity'])?>
  </select>
   
    <div class="two columns">
      <button onclick="SearchSubmit();"><i class="fa fa-search"></i></button>
    </div>
    <div class="clear clearfix"></div>
    <!-- Announce -->
    <div class="announce"> We've over <strong><?=total("SELECT * FROM `jobs` WHERE 1")?></strong> job offers for you! </div>
  </div>
</div>

<script type="application/javascript">
function SearchSubmit(){
	JobCity = $(".header-search-spotlight #JobCity ").val();
	Search = $(".header-search-spotlight #Search ").val();
	
	if(Search == '' && JobCity == ''){
		alert("Please enter your keyword or city for search...");
		return false;	
	}
	
	URL = '<?=$path?>jobs/list?Search=' + Search + '&JobCity=' + JobCity;
	window.location.href = URL;
}
</script>
