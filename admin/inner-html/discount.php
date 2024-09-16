<?php
include("../includes/conn.php");
include("../admin_theme_functions.php");?>

<div class="modal-header no-padding">
  <div class="table-header"> Add Discount </div>
</div>
<div class="slim-scroll" data-height="500">
  <div class="modal-body form-horizontal">
    <div class="row">
      <div class="col-xs-12 col-sm-12" style="overflow:auto;">
        <h4> Add Discount Details </h4>
        <div id="accordion" class="accordion-style2">
          <div class="group">
            <h5 class="accordion-header">Discount </h5>
            <div class="modal-body form-horizontal">
              <form role="form" id="AddDiscountForm">
			  <input type="hidden" name="DiscountSubID" value="<?=$_GET['uid']?>" />
                <div class="form-group">
                  <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Discount percentage:<i class="icon-asterisk"></i> </label>
                  <div class="col-xs-8 col-sm-8">
                    <input type="text" id="DiscountPercent" name="DiscountPercent" placeholder="" class="col-xs-8 col-sm-8 validate[required, custom[integer], max[99]]" maxlength="2" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-3 col-sm-3 control-label no-padding-right" for="form-field-1">Duration:<i class="icon-asterisk"></i> </label>
                  <div class="col-xs-8 col-sm-8">
                    <select name="DiscountDuration" class="col-xs-4 col-sm-4 validate[required]"  id="DiscountDuration">
                      <option value="">Please Select</option>
                      <option value="90">3 Months</option>
                      <option value="180">6 Months</option>
                      <option value="365">1 Year</option>
                    </select>
                  </div>
                </div>
                <div class="space-4"></div>
				<div class="form-group col-xs-5 col-sm-5 text-right">
                <a class="btn btn-success btn-xs green" role="button" href="#" onclick="AddDiscount()"><i class="icon-plus bigger-120"></i>Add Discount</a>
				</div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12">
        <h4>All Discounts</h4>
        <div class="form-group">
          <div class="table-responsive">
            <table id="AllDiscounts" class="table table-striped table-bordered table-hover">
              <tbody>
                <tr>
                  <th>Sr. No</th>
                  <th>Duration</th>
                  <th>Discount Percentage</th>
                  <th>Actions</th>
                </tr>
                <?php
				$stmt = query(" SELECT * FROM `subscription_discount` WHERE `DiscountSubID` = '".$_GET['uid']."' ");
				$count = 0;
				while($rslt = fetch($stmt) ){ $count++; ?>
                <tr>
                  <td><?=$count?></td>
                  <td><?=$rslt['DiscountDuration']?>
                    days</td>
                  <td><?=$rslt['DiscountPercent']?>
                    % of total Amount</td>
                  <td><?php
						$data = array(
									array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'employer-subscription-view.php?type=discount&delete=true&id='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete" ')
	);
						echo TableActions($data);?></td>
                </tr>
                <?php
				}?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div id="Ajax-Result"></div>
  </div>
</div>
<div class="modal-footer no-margin-top">
  <button type="button" onclick="AddDiscount()" class="btn btn-primary"> Submit </button>
</div>
<script type="text/javascript">
		$('.slim-scroll').each(function () {
			var $this = $(this);
			$this.slimScroll({
				height: $this.data('height') || 100,
				railVisible:true
			});
		});

	$('.ContentEditor').redactor({ autoresize: false });

	function AddDiscount(){
		var validate = $("form#AddDiscountForm").validationEngine('validate');
		var valid = $("#AddDiscountForm .formError").length;
		if (valid != 0){ return false; }
		
		var form_data = $('form#AddDiscountForm').serialize();
		form_data = "action=AddDiscount&" + form_data;
		//alert(form_data);
		$.ajax({
			cache: false, 
			type: "POST",
			url: "<?=$path?>admin/ajaxpage.php",
			data: form_data,
			dataType : 'html',
			async: false,
			success: function(data){
				$("form#AddDiscountForm #Ajax-Result").html(data);
				setTimeout(function(){ location.href='<?=$path?>admin/employer-subscription-view.php'; }, 800);
				return false;
			},
			error: function(){
				ALERT("Error with your request, Please try again...!", 'error');
			}
		});
	}
	
	$("#reset").click(function(){
		$(".formError").hide();
	});
	
</script>
