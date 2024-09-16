<!-- /#ace-settings-container -->
			</div><!-- /.main-container-inner -->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

			<!-- inline scripts related to this page -->
	<script src="<?=$admin_path?>assets/js/date-time/bootstrap-datepicker.min.js"></script>
	<script src="<?=$admin_path?>assets/js/date-time/bootstrap-timepicker.min.js"></script>
	<script src="<?=$admin_path?>assets/js/date-time/moment.min.js"></script>
	<script src="<?=$admin_path?>assets/js/jquery.autosize.min.js"></script>
	<script src="<?=$admin_path?>assets/js/jquery.inputlimiter.1.3.1.min.js"></script>
	<script src="<?=$admin_path?>assets/js/jquery.maskedinput.min.js"></script>
	<script>
	
	function LoadScripts(){
		$('textarea[class*=autosize]').autosize({append: "\n"});
		$('textarea.limited').inputlimiter({
			remText: '%n character%s remaining...',
			limitText: 'max allowed : %n.'
		});
		
		$.mask.definitions['~']='[+-]';
		$('.input-mask-date').mask('99/99/9999');
		$('.input-mask-phone').mask('(999) 999-9999');
		$('.input-mask-eyescript').mask('~9.99 ~9.99 999');
		$(".input-mask-product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("You typed the following: "+this.val());}});
		
		//console.log('Mask Input Load....');
		$('.date-picker').datepicker( { 
			showOtherMonths: true, 
			autoclose: true,
			selectOtherMonths: true, 
			dateFormat: "yy-mm-dd",
			onSelect: function() {
				ID = $(this).attr("id");
				alert( ID + "xxxxxxxxxxx" );
				$('#date').addClass('validate[required,custom[date]]');
				$.validationEngine.loadValidation('#date');
				$('#date').blur( function() {
					$.validationEngine.loadValidation('#date');
				} );
			},
		});
		/*$('.date-pickerxxxx').datepicker({
			//autoclose:true,
			
		}).next().on(ace.click_event, function(){
			$(this).prev().focus();
			alert($(this).valid());
		});
		
		$('.date-picker').click(function() {
			$(this).datepicker({autoclose:true}).next().on(ace.click_event, function(){
				$(this).prev().focus();
			});
		});
		
		$( ".date-picker" ).on( "change", function() {
		 $( this ).datepicker( { showOtherMonths: true, selectOtherMonths: true, dateFormat: "yy-mm-dd" } );
		 $(this).removeClass('invalid').addClass('success');  
		 
		  
		});*/
		
		//console.log('Slim Scroll Load....');
		// scrollables
		$('.slim-scroll').each(function () {
			var $this = $(this);
			$this.slimScroll({
				height: $this.data('height') || 100,
				railVisible:true
			});
		});
		
		//console.log('DatePicker Load....');
		
		$('.modal').on('hidden.bs.modal', function () {
			$(".modal-dialog .modal-content",this).html('<h4 class="modal-body"><i class="icon-spinner icon-spin green bigger-170"></i> Loading Please wait...</h4>');
		});
		//console.log('Modal dialog Load....');
		
		$(".modal .modal-dialog .modal-content").html('<h4 class="modal-body"><i class="icon-spinner icon-spin green bigger-170"></i> Loading Please wait...</h4>');
	
		$('.time-picker').timepicker({
			minuteStep: 1,
			showSeconds: true,
			showMeridian: false
		}).next().on(ace.click_event, function(){
			$(this).prev().focus();
		});
		//console.log('Time Picker Load....');
	}
	setTimeout(function(){
		LoadScripts();
	}, 2000);
	</script>
	</body>
</html>
