<?php include('header.php');
?>
<link href="<?=$path?>css/paging.css" rel="stylesheet">
<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Dropdowns</li>
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
            <h3 class="header smaller lighter blue">Dropdown Name</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="table-header"> Results for "All Dropdown Name" <span style="float:right; margin-right:8px;"> <a href="<?=$path?>admin/other-dropdowns.php" class="btn btn-warning btn-sm">Others</a> </span><span style="float:right; margin-right:8px;"> <a href="javascript:deleteAllDropdownName();" class="btn btn-danger btn-sm">Delete All</a> </span> <span style="float:right; margin-right:8px;">
              <button type="button" class="btn btn-success btn-sm" onclick="openAddDropdownName();">Add New</button>
              </span> </div>
            <div class="table-responsive" id="dropdown_name_view"> </div>
          </div>
          <div class="col-xs-6">
            <h3 class="header smaller lighter blue">Dropdown Options <span id="dropdown_for">for </span></h3>
            <div id="ajax-result2">
              <?=$message2?>
            </div>
            <div class="table-header"> Results for "All Dropdown Options" <span style="float:right; margin-right:8px;" id="dropdown_option_button1"> <a href="javascript:deleteAllDropdownOption();" class="btn btn-danger btn-sm">Delete All</a> </span> <span style="float:right; margin-right:8px;" id="dropdown_option_button2">
              <button type="button" class="btn btn-success btn-sm" onclick="openAddDropdownOption();">Add New</button>
              </span> </div>
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
          <div class="form-group">
            <label for="dropdown_title" class="control-label">Key:</label>
            <input type="text" class="form-control" id="dropdown_title" name="dropdown_title">
          </div>
          <div class="form-group">
            <label for="dropdown_description" class="control-label">Description:</label>
            <input type="text" class="form-control" id="dropdown_description" name="dropdown_description">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="dropdown_name_submit">Add Dropdown</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="dropdown_option_modal" tabindex="-1" role="dialog" aria-labelledby="dropdown_option_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="dropdown_option_label">Add Dropdown Option</h4>
      </div>
      <div class="modal-body">
        <form role="form" id="dropdown_option_form">
          <input type="hidden" id="dropdown_option_id" name="dropdown_option_id" value="">
          <input type="hidden" id="dropdown_option_type" name="dropdown_option_type" value="">
          <div class="form-group">
            <label for="dropdown_option_title" class="control-label">Option Key:</label>
            <input type="text" class="form-control" id="dropdown_option_title" name="dropdown_option_title">
          </div>
          <div class="form-group">
            <label for="dropdown_option_description" class="control-label">Option Value:</label>
            <input type="text" class="form-control" id="dropdown_option_description" name="dropdown_option_description">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="dropdown_option_submit">Add Dropdown Option</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
	
	$('#dropdown_name_submit').on('click', function (event) {
		
		var form_data = $('#dropdown_name_form').serialize();
		form_data = "action=dropdown_name_add_edit&" + form_data;
		//alert(form_data);
		
		var dropdown_id = $('#dropdown_id').val();
		var dropdown_title = $('#dropdown_title').val();
		
		$.ajax({
			cache: false, 
			type: "POST",
			url: "dropdown-ajax.php",
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
				//alert('Error Loading AJAX Request...!');
			}
		});
	});
	
	$('#dropdown_option_submit').on('click', function (event) {
		
		var form_data = $('#dropdown_option_form').serialize();
		form_data = "action=dropdown_option_add_edit&" + form_data;
		//alert(form_data);
		
		var dropdown_option_id = $('#dropdown_option_id').val();
		var dropdown_option_title = $('#dropdown_option_title').val();
		var dropdown_option_type = $('#dropdown_option_type').val();
		
		$.ajax({
			cache: false, 
			type: "POST",
			url: "dropdown-ajax.php",
			data: form_data,
			dataType : 'json',
			success: function(data){
				//alert(data.status);
				
				if(data.status == 'success')
				{
					$('#dropdown_option_modal').modal('hide');
					
					if(dropdown_option_id)
					{
						$('#ajax-result2').html('<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>Dropdown [ '+dropdown_option_title+' ] Edited Successfully ...!<br></div>');
					}
					else
					{
						$('#ajax-result2').html('<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>Dropdown [ '+dropdown_option_title+' ] Added Successfully ...!<br></div>');
					}
					
					updateDropdownOptionGrid(dropdown_option_type);
				}
			},
			error: function(){
				//alert('Error Loading AJAX Request...!');
			}
		});
	});
});


function openAddDropdownName()
{
	$('#dropdown_id').val('');
	$('#dropdown_title').val('');
	$('#dropdown_title').removeAttr('readonly');
	$('#dropdown_description').val('');
	$('#dropdown_name_label').html('Add Dropdown');
	$("#dropdown_name_submit").html('Add Dropdown');
	
	$('#dropdown_name_modal').modal('show');
}

function openEditDropdownName(dropdown_name_id, dropdown_name_title, dropdown_name_description)
{
	//alert(dropdown_name_id);
	//alert(dropdown_name_title);
	//alert(dropdown_name_description);
	
	$('#dropdown_id').val(dropdown_name_id);
	$('#dropdown_title').val(dropdown_name_title);
	$('#dropdown_title').attr('readonly','readonly');
	$('#dropdown_description').val(dropdown_name_description);
	$('#dropdown_name_label').html('Edit Dropdown');
	$("#dropdown_name_submit").html('Edit Dropdown');
	
	$('#dropdown_name_modal').modal('show');
}


function deleteDropdownName(id)
{

	ajaxreq('dropdown-ajax.php', "action=dropdown_name_delete&dropdown_id="+id, 'ajax-result');

	$("#ddn_"+id).remove();

}

function deleteAllDropdownName()
{
	if(confirm('Are you sure you want to delete selected dropdown(s)?'))
	{
		$('.dropdowncheckbox').each(function(index)
		{
			if($(this).prop('checked'))
			{
				var dropdown_id = $(this).val();
				//alert(dropdown_id);
				
				deleteDropdownName(dropdown_id);
			}
		});
		
		updateDropdownNameGrid();
	}
}


function updateDropdownNameGrid()
{

	/**
	*
	@param: page
	@param: query string
	@param: div where response is printed.
	*/

	ajaxreq('dropdown-ajax.php', "action=dropdown_name_view&pager=<?=$_REQUEST['pager']?>", 'dropdown_name_view');

}
updateDropdownNameGrid();




function openAddDropdownOption()
{
	$('#dropdown_option_id').val('');
	$('#dropdown_option_title').val('');
	$('#dropdown_option_description').val('');
	$('#dropdown_option_label').html('Add Dropdown Option');
	$("#dropdown_option_submit").html('Add Dropdown Option');
	
	$('#dropdown_option_modal').modal('show');
}

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

function deleteDropdownOption(id)
{
	ajaxreq('dropdown-ajax.php', "action=dropdown_option_delete&dropdown_option_id="+id, 'ajax-result2');
	$("#ddo_"+id).remove();
}

function deleteAllDropdownOption()
{
	if(confirm('Are you sure you want to delete selected dropdown option(s)?'))
	{
		$('.dropdowncheckbox2').each(function(index)
		{
			if($(this).prop('checked'))
			{
				var dropdown_option_id = $(this).val();
				//alert(dropdown_option_id);
				
				deleteDropdownOption(dropdown_option_id);
			}
		});
		
		var dropdown_option_type = $('#dropdown_option_type').val();
		updateDropdownOptionGrid(dropdown_option_type);
	}
}

function updateDropdownOptionGrid(dropdown_id)
{
	//alert(dropdown_id);
	
	if(dropdown_id)
	{
		//Update dropdown name in second grid.
		ajaxreq('dropdown-ajax.php', "action=get_dropdown_name&dropdown_id="+dropdown_id, 'dropdown_for');
		
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
	
	/**
	*
	@param: page
	@param: query string
	@param: div where response is printed.
	*/
	ajaxreq('dropdown-ajax.php', "action=dropdown_option_view&dropdown_id="+dropdown_id+"&pager=<?=$_REQUEST['pager2']?>", 'dropdown_option_view');
}
updateDropdownOptionGrid('');


</script>
<?php include('footer.php');?>
