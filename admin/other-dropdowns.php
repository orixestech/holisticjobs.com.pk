<?php include('header.php');
?>
<link href="<?=$path?>css/paging.css" rel="stylesheet">
<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Other Dropdowns</li>
    </ul>
    <!-- .breadcrumb -->
  </div>
  <div class="page-content">
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
          <div class="col-xs-6">
            <h3 class="header smaller lighter blue">Other Dropdown Name</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="table-header"> Results for "All Other Dropdown Name" </div>
            <div class="table-responsive" id="dropdown_name_view"> </div>
          </div>
          <div class="col-xs-6">
            <h3 class="header smaller lighter blue">Dropdown Options <span id="dropdown_for">for </span></h3>
            <div id="ajax-result2">
              <?=$message2?>
            </div>
            <div class="table-header"> Results for "All Dropdown Options"</div>
            <div class="table-responsive" id="dropdown_option_view"> </div>
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
<div class="modal fade" id="dropdown_name_modal" tabindex="-1" role="dialog" aria-labelledby="dropdown_name_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="dropdown_name_label">Add Dropdown</h4>
      </div>
      <div class="modal-body">
        <form role="form" id="dropdown_name_form">
          <input type="hidden" id="dropdown_id" name="dropdown_id" value="">
		  <input type="hidden" id="OptionId" name="OptionId" value="">
          <div class="form-group">
            <label for="dropdown_option_title" class="control-label">Replace With:</label>
            <select class="form-control" id="TypeOptions" name="TypeOptions">
			
			</select>
          </div>
          <div class="form-group">
            <label for="dropdown_description" class="control-label">Description:</label>
            <input type="text" class="form-control" id="dropdown_description" name="dropdown_description">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="ApproveDropdown">Approve</button>
		<button type="button" class="btn btn-danger" id="ReplaceDropdown">Replace</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
	
	$('#ReplaceDropdown').on('click', function (event) {
		
		var form_data = $('#dropdown_name_form').serialize();
		form_data = "action=dropdown_name_edit&type=Replace&" + form_data;
		
		var dropdown_id = $('#dropdown_id').val();
		var dropdown_title = $('#dropdown_title').val();
		
		$.ajax({
			cache: false, 
			type: "POST",
			url: "other-dropdowns-ajax.php",
			data: form_data,
			dataType : 'json',
			success: function(data){
				//alert(data.status);
				
				if(data.status == 'success')
				{
					$('#dropdown_name_modal').modal('hide');
					$('#ajax-result').html('<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>'+data.message+'<br></div>');
					updateDropdownNameGrid();
				}
			},
			error: function(){
				alert('Error Loading AJAX Request...!');
			}
		});
	});
	
	$('#ApproveDropdown').on('click', function (event) {
		
		var form_data = $('#dropdown_name_form').serialize();
		form_data = "action=dropdown_name_edit&type=Approve&" + form_data;
		
		var dropdown_id = $('#dropdown_id').val();
		var dropdown_title = $('#dropdown_title').val();
		
		$.ajax({
			cache: false, 
			type: "POST",
			url: "other-dropdowns-ajax.php",
			data: form_data,
			dataType : 'json',
			success: function(data){
				//alert(data.status);
				
				if(data.status == 'success')
				{
					$('#dropdown_name_modal').modal('hide');
					
					if(dropdown_id)
					{
						$('#ajax-result').html('<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>Dropdown [ '+dropdown_title+' ] Edited Successfully ...!<br></div>');
					}
					else
					{
						$('#ajax-result').html('<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>Dropdown [ '+dropdown_title+' ] Added Successfully ...!<br></div>');
					}
					
					updateDropdownNameGrid();
				}
			},
			error: function(){
				alert('Error Loading AJAX Request...!');
			}
		});
	});
	
});



function openEditDropdownName(dropdown_name_id, OptionId,  dropdown_name_description)
{
	//alert(dropdown_name_id);
	//alert(dropdown_name_description);
	
	$('#dropdown_id').val(dropdown_name_id);
	$('#OptionId').val(OptionId);
	$('#dropdown_title').attr('readonly','readonly');
	$('#dropdown_description').val(dropdown_name_description);
	$('#dropdown_name_label').html('Edit Dropdown');
	$("#dropdown_name_submit").html('Edit Dropdown');
	
	ajaxreq('other-dropdowns-ajax.php', "action=load_type_dropdown&dropdown_id="+dropdown_name_id, 'dropdown_name_modal #TypeOptions');
	
	$('#dropdown_name_modal').modal('show');
}


function updateDropdownNameGrid()
{
	ajaxreq('other-dropdowns-ajax.php', "action=dropdown_name_view&pager=<?=$_REQUEST['pager']?>", 'dropdown_name_view');
}
updateDropdownNameGrid();

function openEditDropdownOption(dropdown_option_id, dropdown_option_type, dropdown_option_title, dropdown_option_description)
{
	//alert(dropdown_option_id);
	//alert(dropdown_option_type);
	//alert(dropdown_option_title);
	//alert(dropdown_option_description);
	
	$('#dropdown_option_id').val(dropdown_option_id);
	$('#dropdown_option_type').val(dropdown_option_type);
	$('#dropdown_option_title').val(dropdown_option_title);
	$('#dropdown_option_description').val(dropdown_option_description);
	$('#dropdown_option_label').html('Edit Dropdown Option');
	$("#dropdown_option_submit").html('Edit Dropdown Option');
	
	$('#dropdown_option_modal').modal('show');
}

function updateDropdownOptionGrid(dropdown_id)
{
	if(dropdown_id)
	{
		//Update dropdown name in second grid.
		ajaxreq('other-dropdowns-ajax.php', "action=get_dropdown_name&dropdown_id="+dropdown_id, 'dropdown_for');
		
		$('#dropdown_option_type').val(dropdown_id);
		$('#dropdown_option_button1').show();
		$('#dropdown_option_button2').show();
		$('#dropdown_for').show();
	}
	else
	{
		$('#dropdown_option_type').val('');
		$('#dropdown_option_button1').hide();
		$('#dropdown_option_button2').hide();
		$('#dropdown_for').hide();
	}

	ajaxreq('other-dropdowns-ajax.php', "action=dropdown_option_view&dropdown_id="+dropdown_id+"&pager=<?=$_REQUEST['pager2']?>", 'dropdown_option_view');
}
updateDropdownOptionGrid('');

</script>
<?php include('footer.php');?>
