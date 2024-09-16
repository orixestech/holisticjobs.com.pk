<?php
@session_start();
if(@$_SESSION['AdminUserLogged'] != 1){
	echo '<meta http-equiv="refresh" content="0;URL=\'login.php\'" />';exit;
}

if(isset($_GET['logout']) && $_GET['logout']=='true'){
	$_SESSION['AdminUserLogged'] = $_SESSION['User'] = '';	
}



?>
<?php include("header.php"); ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <script type="text/javascript">
		try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="#">Home</a> </li>
      <li class="active">Dashboard</li>
    </ul>
    <!-- .breadcrumb -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1> Dashboard <small> <i class="icon-double-angle-right"></i> overview &amp; stats </small> 
	  <div class="pull-right col-md-2" style="width:9%">
      <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown"> Last 2 Weeks <i class="icon-angle-down icon-on-right bigger-110"></i> </button>
      <ul class="dropdown-menu pull-right dropdown-125 dropdown-lighter dropdown-caret">
        <li<?=($page=='index2.php')?' class="active"':''?>> <a href="index2.php" <?=($page=='index2.php')?' class="blue"':''?>>
          <?=($page=='index2.php')?'<i class="icon-caret-right bigger-110"></i> ':''?>
          Last 2 Weeks</a> </li>
        <li> <a href="#"> <i class="icon-caret-right bigger-110 invisible"></i> Current Month </a> </li>
        <li> <a href="#"> <i class="icon-caret-right bigger-110 invisible"></i> Last 3 Month </a> </li>
        <li> <a href="#"> <i class="icon-caret-right bigger-110 invisible"></i> Last 6 Month </a> </li>
        <li> <a href="#"> <i class="icon-caret-right bigger-110 invisible"></i> 1 Year </a> </li>
        <li> <a href="#"> <i class="icon-caret-right bigger-110 invisible"></i> Custom Date (from & to) </a> </li>
      </ul>
    </div>
    <div class="pull-right col-md-4">
      <input type="text" class="date-picker col-md-4" placeholder="From" style="margin-right: 7px;" />
      <input type="text" class="date-picker col-md-4" placeholder="To" style="margin-right: 7px;" />
      <button class="btn btn-xs btn-success col-md-2" data-toggle="button" type="button">Submit</button>
    </div></h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="alert alert-block alert-success">
          <button type="button" class="close" data-dismiss="alert"> <i class="icon-remove"></i> </button>
          <i class="icon-ok green"></i> Welcome to the Admin Panel of <strong class="green"> Holistic Jobs </strong> <small>(A Project of Holistic Solutions (PVT) LTD)</small></div>
        <div class="row">
          <div class="space-6"></div>
          <h4 class="lighter blue pull-left"> &nbsp; <i class="icon-star orange"></i> Overall Statistics</h4>
          <div class="col-sm-12 infobox-container">
            <div class="infobox infobox-green infobox-large infobox-dark">
              <div class="infobox-icon"> <i class="icon-group"></i> </div>
              <div class="infobox-data">
                <div class="infobox-content" align="center">Employer Signups</div>
                <div class="infobox-data-number infobox-grey2" align="center"> <strong>500</strong></div>
              </div>
            </div>
            <div class="infobox infobox-red infobox-large infobox-dark">
              <div class="infobox-icon"> <i class="icon-user"></i> </div>
              <div class="infobox-data">
                <div class="infobox-content" align="center">Employee Signups </div>
                <div class="infobox-data-number infobox-grey2" align="center"> <strong>1437</strong></div>
              </div>
            </div>
            <div class="infobox infobox-blue infobox-large infobox-dark">
              <div class="infobox-icon"> <i class="icon-briefcase"></i> </div>
              <div class="infobox-data">
                <div class="infobox-content" align="center">Jobs Posted</div>
                <div class="infobox-data-number infobox-grey2" align="center"> <strong>1248</strong></div>
              </div>
            </div>
            <div class="hr hr32 hr-dotted"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <h3 class="header smaller lighter blue">&nbsp; <i class="icon-star orange"></i> Jobs Posted</h3>
            <div class="well">
              <h4 class="green smaller lighter"></h4>
			   Jobs Posted Graph as per day on any date range. 
            </div>
          </div>
		  <div class="col-sm-6">
            <h3 class="header smaller lighter blue">&nbsp; <i class="icon-star orange"></i> User activity </h3>
            <div class="well">
              <h4 class="green smaller lighter"></h4>
			 User activity Graph as per day on any date range.
            </div>
          </div>
        </div>
		<div class="row">
          <div class="col-sm-6">
            <h3 class="header smaller lighter blue">&nbsp; <i class="icon-star orange"></i> Job Applications</h3>
            <div class="well">
              <h4 class="green smaller lighter"></h4>
			  Job Applications Graph as per day on any date range.
            </div>
          </div>
		  <div class="col-sm-6">
            <h3 class="header smaller lighter blue">&nbsp; <i class="icon-star orange"></i> Invitations stats </h3>
            <div class="well">
              <h4 class="green smaller lighter"></h4>
			  Invitations stats Graph as per day on any date range by employer.
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
<!-- page specific plugin scripts -->
<!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
		<![endif]-->
<script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="assets/js/jquery.slimscroll.min.js"></script>
<script src="assets/js/jquery.easy-pie-chart.min.js"></script>
<script src="assets/js/jquery.sparkline.min.js"></script>
<script src="assets/js/flot/jquery.flot.min.js"></script>
<script src="assets/js/flot/jquery.flot.pie.min.js"></script>
<script src="assets/js/flot/jquery.flot.resize.min.js"></script>
<script src="assets/js/jquery.inputlimiter.1.3.1.min.js"></script>
<script src="assets/js/jquery.maskedinput.min.js"></script>
<script type="text/javascript">
			jQuery(function($) {
			
				
				
				$('.easy-pie-chart.percentage').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
					var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
					var size = parseInt($(this).data('size')) || 50;
					$(this).easyPieChart({
						barColor: barColor,
						trackColor: trackColor,
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: parseInt(size/10),
						animate: /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()) ? false : 1000,
						size: size
					});
				})
			
				$('.sparkline').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
					$(this).sparkline('html', {tagValuesAttribute:'data-values', type: 'bar', barColor: barColor , chartRangeMin:$(this).data('min') || 0} );
				});
			
			
			
			
			  var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
			  var data = [
				{ label: "social networks",  data: 38.7, color: "#68BC31"},
				{ label: "search engines",  data: 24.5, color: "#2091CF"},
				{ label: "ad campaigns",  data: 8.2, color: "#AF4E96"},
				{ label: "direct traffic",  data: 18.6, color: "#DA5430"},
				{ label: "other",  data: 10, color: "#FEE074"}
			  ]
			  function drawPieChart(placeholder, data, position) {
			 	  $.plot(placeholder, data, {
					series: {
						pie: {
							show: true,
							tilt:0.8,
							highlight: {
								opacity: 0.25
							},
							stroke: {
								color: '#fff',
								width: 2
							},
							startAngle: 2
						}
					},
					legend: {
						show: true,
						position: position || "ne", 
						labelBoxBorderColor: null,
						margin:[-30,15]
					}
					,
					grid: {
						hoverable: true,
						clickable: true
					}
				 })
			 }
			 drawPieChart(placeholder, data);
			
			 /**
			 we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
			 so that's not needed actually.
			 */
			 placeholder.data('chart', data);
			 placeholder.data('draw', drawPieChart);
			
			
			
			  var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
			  var previousPoint = null;
			
			  placeholder.on('plothover', function (event, pos, item) {
				if(item) {
					if (previousPoint != item.seriesIndex) {
						previousPoint = item.seriesIndex;
						var tip = item.series['label'] + " : " + item.series['percent']+'%';
						$tooltip.show().children(0).text(tip);
					}
					$tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
				} else {
					$tooltip.hide();
					previousPoint = null;
				}
				
			 });
			
			
			
			
			
			
				var d1 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d1.push([i, Math.sin(i)]);
				}
			
				var d2 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d2.push([i, Math.cos(i)]);
				}
			
				var d3 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.2) {
					d3.push([i, Math.tan(i)]);
				}
				
			
				var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
				$.plot("#sales-charts", [
					{ label: "Domains", data: d1 },
					{ label: "Hosting", data: d2 },
					{ label: "Services", data: d3 }
				], {
					hoverable: true,
					shadowSize: 0,
					series: {
						lines: { show: true },
						points: { show: true }
					},
					xaxis: {
						tickLength: 0
					},
					yaxis: {
						ticks: 10,
						min: -2,
						max: 2,
						tickDecimals: 3
					},
					grid: {
						backgroundColor: { colors: [ "#fff", "#fff" ] },
						borderWidth: 1,
						borderColor:'#555'
					}
				});
			
			
				$('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('.tab-content')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			
			
				$('.dialogs,.comments').slimScroll({
					height: '342px'
			    });
				
				
				//Android's default browser somehow is confused when tapping on label which will lead to dragging the task
				//so disable dragging when clicking on label
				var agent = navigator.userAgent.toLowerCase();
				if("ontouchstart" in document && /applewebkit/.test(agent) && /android/.test(agent))
				  $('#tasks').on('touchstart', function(e){
					var li = $(e.target).closest('#tasks li');
					if(li.length == 0)return;
					var label = li.find('label.inline').get(0);
					if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
				});
			
				$('#tasks').sortable({
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'draggable-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer',
					stop: function( event, ui ) {//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
						$(ui.item).css('z-index', 'auto');
					}
					}
				);
				$('#tasks').disableSelection();
				$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
					if(this.checked) $(this).closest('li').addClass('selected');
					else $(this).closest('li').removeClass('selected');
				});
				
			
			});



</script>
<?php include("footer.php"); ?>
