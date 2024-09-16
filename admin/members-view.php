<?php include('header.php');
?>
<link href="<?=$path?>css/paging.css" rel="stylesheet">
<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Members</li>
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
            <h3 class="header smaller lighter blue">Members</h3>
            <div id="ajax-result">
              <?=$message?>
            </div>
            <div class="table-header"> Results for "All Members" <span style="float:right; margin-right:8px;"> <a href="javascript:deleteAllMembers();" class="btn btn-danger btn-sm">Delete All</a> </span> <span style="float:right; margin-right:8px;">
              <button type="button" class="btn btn-success btn-sm" onclick="openAddMember();">Add New</button>
              </span> </div>
            <div class="table-responsive" id="members_view"> </div>
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
<div class="modal fade" id="members_modal" tabindex="-1" role="dialog" aria-labelledby="members_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="members_label">Add Member</h4>
      </div>
      <div class="modal-body"> </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="members_submit">Add Member</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
	
	$('#members_submit').on('click', function (event) {
		
		var form_data = $('#members_form').serialize();
		form_data = "action=members_add_edit&" + form_data;
		//alert(form_data);
		
		var member_id = $('#member_id').val();
		var firstname = $('#firstname').val();
		var lastname = $('#lastname').val();
		
		$.ajax({
			cache: false, 
			type: "POST",
			url: "members-ajax.php",
			data: form_data,
			dataType : 'json',
			success: function(data){
				if(data.status == 'success')
				{
					if(member_id)
					{
						var resultTXT = '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>Member [ '+firstname+' '+lastname+' ] Edited Successfully ...!<br></div>';
					}
					else
					{
						var resultTXT = '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button><strong>Success! </strong>Member [ '+firstname+' '+lastname+' ] Added Successfully ...!<br></div>';
					}
					
					$("#members_modal #ajax-result").html(resultTXT);
					setTimeout(function(){ $('#members_modal').modal('hide'); }, 800);
					updateMembersGrid();
				}
			},
			error: function(){
				//alert('Error Loading AJAX Request...!');
			}
		});
	});
});


function openAddMember()
{

	$("#members_modal .modal-body").load('html-forms/edit-members.php?r='+Math.random()+'&', function ()
	{
		$('#member_id').val('');
	
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
	
	$('#members_label').html('Add Member');
	$("#members_submit").html('Add Member');
	
	$('#members_modal').modal('show');
	});
	
	
	
}

function openEditMember(member_id)
{
	$('#members_modal #member_id').val(member_id);
	
	form_data = "action=members_load&member_id=" + member_id;
	//alert(form_data);
	$("#members_modal .modal-body").load('html-forms/edit-members.php?r='+Math.random()+'&'+form_data, function ()
	{
		/*$.ajax({
		cache: false, 
		type: "POST",
		url: "members-ajax.php",
		data: form_data,
		dataType : 'json',
		async: false,
		success: function(data){
			//alert(data.status);
			
			if(data.status == 'success')
			{
				$('#username').val(data.data.username);
				$('#password').val(data.data.password);
				$('#firstname').val(data.data.firstname);
				$('#lastname').val(data.data.lastname);
				$('#email').val(data.data.email);
				//$('#avatar').val(data.data.avatar);
				$('#country').val(data.data.country);
				$('#city').val(data.data.city);
				$('#company_name').val(data.data.company_name);
				$('#profile_heading').val(data.data.profile_heading);
				$('#profile_text').val(data.data.profile_text);
			}
		},
		error: function(){
			//alert('Error Loading AJAX Request...!');
		}
	});*/
	
		$('#members_label').html('Edit Member');
		$("#members_submit").html('Edit Member');
		
		$('#members_modal').modal('show');
		
		
	});
	
	
}

function openStatusMember(member_id, status)
{
	form_data = "action=members_status&member_id="+member_id+"&status="+status;
	//alert(form_data);
	
	$.ajax({
		cache: false, 
		type: "POST",
		url: "members-ajax.php",
		data: form_data,
		dataType : 'json',
		async: false,
		success: function(data){
			//alert(data.status);
			
			if(data.status == 'success')
			{
				$("#ajax-result").html(data.data);
				
				updateMembersGrid();
			}
		},
		error: function(){
			//alert('Error Loading AJAX Request...!');
		}
	});
}


function deleteMember(id)
{

	ajaxreq('members-ajax.php', "action=members_delete&member_id="+id, 'ajax-result');

	$("#row_"+id).remove();

}

function deleteAllMembers()
{
	if(confirm('Are you sure you want to delete selected member(s)?'))
	{
		$('.memberscheckbox').each(function(index)
		{
			if($(this).prop('checked'))
			{
				var member_id = $(this).val();
				//alert(member_id);
				
				deleteMember(member_id);
			}
		});
		
		updateMembersGrid();
	}
}


function updateMembersGrid()
{

	/**
	*
	@param: page
	@param: query string
	@param: div where response is printed.
	*/

	ajaxreq('members-ajax.php', "action=members_view&pager=<?=$_REQUEST['pager']?>", 'members_view');

}
updateMembersGrid();


function openStatusTemplate(template_id, status)
{
	form_data = "action=template_status&template_id="+template_id+"&status="+status;
	$.ajax({
		cache: false, 
		type: "POST",
		url: "template-ajax.php",
		data: form_data,
		dataType : 'json',
		async: false,
		success: function(data){
			if(data.status == 'success')
			{
				$("#members_modal #ajax-result").html(data.data);
				setTimeout(function(){ $('#members_modal').modal('hide'); }, 800);
				
			}
		},
		error: function(){
			//alert('Error Loading AJAX Request...!');
		}
	});
}


</script>
<?php include('footer.php');?>
