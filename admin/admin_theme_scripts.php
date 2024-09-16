<?php
$page = end( explode("/",$_SERVER['SCRIPT_FILENAME']) );
$TablePages = array('pages-view.php','track-view.php');
$FormPages = array('job.php','page.php','employer.php','employee.php','profile.php','subscription-view.php','access-type.php');

if( in_array($page,$TablePages) ){ $PageType='table'; }
if( in_array($page,$FormPages) ){ $PageType='form'; } 

$PageType='form';
?>

<?php if($PageType=='table'){ ?>
    <script src="assets/js/jquery.dataTables.min.js"></script> 
    <script src="assets/js/jquery.dataTables.bootstrap.js"></script> 
    <script type="text/javascript">
	jQuery(function($) {
		var oTable1 = $('table.datatable').dataTable( {
		"aoColumns": [
		  { "bSortable": false },
		  null, null,null, null,
		  { "bSortable": false }
		] } );
		
		
		$('table th input:checkbox').on('click' , function(){
			var that = this;
			$(this).closest('table').find('tr > td:first-child input:checkbox')
			.each(function(){
				this.checked = that.checked;
				$(this).closest('tr').toggleClass('selected');
			});
				
		});
	
	
		$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
		function tooltip_placement(context, source) {
			var $source = $(source);
			var $parent = $source.closest('table')
			var off1 = $parent.offset();
			var w1 = $parent.width();
	
			var off2 = $source.offset();
			var w2 = $source.width();
	
			if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
			return 'left';
		}
	})
</script>
<? }?>

<?php if($PageType=='form'){ ?>
    <script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="assets/js/markdown/markdown.min.js"></script>
    <script src="assets/js/markdown/bootstrap-markdown.min.js"></script>
    <script src="assets/js/jquery.hotkeys.min.js"></script>
    <script src="assets/js/bootstrap-wysiwyg.min.js"></script>
    <script src="assets/js/bootbox.min.js"></script>
		<script src="assets/js/date-time/bootstrap-datepicker.min.js"></script>
		<script src="assets/js/date-time/bootstrap-timepicker.min.js"></script>
		<script src="assets/js/date-time/moment.min.js"></script>
		<script src="assets/js/date-time/daterangepicker.min.js"></script>
    
    <script src="assets/js/chosen.jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/chosen.css" />
    
    <!-- inline scripts related to this page -->
    
    <link rel="stylesheet" href="assets/redactor/redactor.css" />
    <script src="assets/redactor/redactor.js"></script>
    <script type="text/javascript">
    $(function() {
        $('.ContentEditor').redactor({ autoresize: false });
		$('.date-range-picker').daterangepicker().prev().on(ace.click_event, function(){
			  $(this).next().focus();
		  });
		$(".chosen-select").chosen(); 
		$('#chosen-multiple-style').on('click', function(e){
			var target = $(e.target).find('input[type=radio]');
			var which = parseInt(target.val());
			if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
			 else $('#form-field-select-4').removeClass('tag-input-style');
		});
    });
    </script>
    		<link rel="stylesheet" href="assets/css/datepicker.css" />
		<link rel="stylesheet" href="assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="assets/css/daterangepicker.css" />
<? }?>
<script type="text/javascript">
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>




<?php
function GenModelBOX($divid, $filename){
	$path = $_SESSION["site_path"];
	$html = '
		<div id="'.$divid.'" class="modal fade" tabindex="-1">
			<div class="modal-dialog" style="width:65%">
				<div class="modal-content"><h4 class="modal-body"><i class="icon-spinner icon-spin green bigger-170"></i> Loading Please wait...</h4></div>
			</div>
		</div>
		<script type="text/javascript">
			jQuery(function($) {
				$("#'.$divid.'").on("show.bs.modal", function (event) {
					
					if( $("#'.$divid.'").load() ){
						var uid = $(event.relatedTarget).data("uid");
						$("#'.$divid.' .modal-content").load("'.$path.'admin/inner-html/'.$filename.'?uid="+uid+"&r="+Math.random(), function ()
						{ 
							return true;
						});
					}
					
				})
			})
		</script>
	';
	return $html;
}

?>
