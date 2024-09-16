<?php include('header.php');
?>
<link href="<?=$path?>css/paging.css" rel="stylesheet">
<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Help & Support</li>
    </ul>
    <!-- .breadcrumb -->
  </div>
  <div class="page-content">
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
          <div class="col-xs-12">
            <h3 class="header smaller lighter blue">Help & Support</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="table-header"> Results for "All Help & Support" <span style="float:right; margin-right:8px;"> <a href="javascript:deleteAllfaq();" class="btn btn-danger btn-sm">Delete All</a> </span> <span style="float:right; margin-right:8px;">
              <button type="button" class="btn btn-success btn-sm" onclick="openAddfaq();">Add New</button>
              </span> </div>
            <div class="table-responsive" id="faq_view"> </div>
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
<div class="modal fade" id="faq_modal" tabindex="-1" role="dialog" aria-labelledby="faq_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="faq_label">Add Help & Support</h4>
      </div>
      <div class="modal-body">
        <form role="form" id="faq_form">
          <input type="hidden" id="faq_id" name="faq_id" value="">
		  <div class="form-group">
            <label class="control-label">Category:</label>
			<select class="form-control" id="fq_cat" name="fq_cat"><?php
			$stmt = query(" SELECT * FROM `category` WHERE `category`.`type` = 'Support' ORDER BY `category`.`title` ASC  ");
			while( $rslt = fetch( $stmt ) ){
				echo '<option value="'.$rslt['id'].'">'.$rslt['title'].'</option>';
			}
			?>
			</select>
          </div>
          <div class="form-group">
            <label for="email" class="control-label">Heading :</label>
			<textarea style="width:100%;" rows="2" class="form-control" id="fq_qs" name="fq_qs"></textarea>
          </div>
          <div class="form-group">
            <label for="password" class="control-label">Description:</label>
			<textarea style="width:100%;" rows="5" class="form-control" id="fq_ans" name="fq_ans"></textarea>
          </div>
		  
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="faq_submit">Add Help & Support</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
	
	$('#faq_submit').on('click', function (event) {
		
		var form_data = $('#faq_form').serialize();
		form_data = "action=faq_add_edit&" + form_data;
		//alert(form_data);
		
		var faq_id = $('#faq_id').val();
		var firstname = $('#firstname').val();
		var lastname = $('#lastname').val();
		
		$.ajax({
			cache: false, 
			type: "POST",
			url: "support-ajax.php",
			data: form_data,
			dataType : 'json',
			success: function(data){
				//alert(data.status);
				
				if(data.status == 'success')
				{
					$('#faq_modal').modal('hide');
					
					if(faq_id)
					{
						$('#ajax-result').html('<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>Help & Support Edited Successfully ...!<br></div>');
					}
					else
					{
						$('#ajax-result').html('<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>Help & Support Added Successfully ...!<br></div>');
					}
					
					updatefaqGrid();
				}
			},
			error: function(){
				//alert('Error Loading AJAX Request...!');
			}
		});
	});
});


function openAddfaq()
{
	$('#faq_id').val('');
	
	$('#username').val('');
	$('#password').val('');
	$('#firstname').val('');
	$('#lastname').val('');
	$('#email').val('');
	//$('#avatar').val('');
	$('#country').val('');
	$('#city').val('');
	$('#company_name').val('');
	$('#profile_heading').val('');
	$('#profile_text').val('');
	
	$('#faq_label').html('Add faq');
	$("#faq_submit").html('Add faq');
	
	$('#faq_modal').modal('show');
}

function openEditfaq(faq_id)
{
	$('#faq_id').val(faq_id);
	
	form_data = "action=faq_load&faq_id=" + faq_id;
	//alert(form_data);
	
	$.ajax({
		cache: false, 
		type: "POST",
		url: "support-ajax.php",
		data: form_data,
		dataType : 'json',
		async: false,
		success: function(data){
			//alert(data.status);
			
			if(data.status == 'success')
			{
				$('#fq_qs').val(data.data.fq_qs);
				$('#fq_ans').val(data.data.fq_ans);
				$('#fq_cat').val(data.data.fq_cat);
			}
		},
		error: function(){
			//alert('Error Loading AJAX Request...!');
		}
	});
	
	$('#faq_label').html('Edit Help & Support');
	$("#faq_submit").html('Edit Help & Support');
	
	$('#faq_modal').modal('show');
}

function openStatusfaq(faq_id, status)
{
	form_data = "action=faq_status&faq_id="+faq_id+"&status="+status;
	//alert(form_data);
	
	$.ajax({
		cache: false, 
		type: "POST",
		url: "support-ajax.php",
		data: form_data,
		dataType : 'json',
		async: false,
		success: function(data){
			//alert(data.status);
			
			if(data.status == 'success')
			{
				$("#ajax-result").html(data.data);
				
				updatefaqGrid();
			}
		},
		error: function(){
			//alert('Error Loading AJAX Request...!');
		}
	});
}


function deletefaq(id)
{

	ajaxreq('support-ajax.php', "action=faq_delete&faq_id="+id, 'ajax-result');

	$("#row_"+id).remove();

}

function deleteAllfaq()
{
	if(confirm('Are you sure you want to delete selected Help & Support Content?'))
	{
		$('.faqcheckbox').each(function(index)
		{
			if($(this).prop('checked'))
			{
				var faq_id = $(this).val();
				//alert(faq_id);
				
				deletefaq(faq_id);
			}
		});
		
		updatefaqGrid();
	}
}


function updatefaqGrid()
{

	/**
	*
	@param: page
	@param: query string
	@param: div where response is printed.
	*/

	ajaxreq('support-ajax.php', "action=faq_view&pager=<?=$_REQUEST['pager']?>", 'faq_view');

}
updatefaqGrid();

</script>
<?php include('footer.php');?>
