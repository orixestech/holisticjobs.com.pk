<?php include('header.php');

//print_r($_REQUEST);

if( isset($_GET['deleteid']) && $_GET['deleteid'] > 0 ){
  echo $_GET['deleteid'];

  query(" DELETE FROM `jobs_reminders` WHERE `UID` = '".$_GET["deleteid"]."' ");
  $num = mysql_affected_rows();
  if($num){
    $message = Alert('success', 'Reminder Successfully Deleted...!');
    ?><script type="text/javascript">setTimeout(function(){ window.location = 'reminders.php?module=<?=$_GET['module']?>&uid=<?=$_GET['uid']?>'; }, 2000);</script><?php
  }


}

$ApplicationID = $_GET['uid'];
$ReminderModule = $_GET['module'];


if( $ReminderModule=='invitation' ){

  $stmt = query("
    SELECT
        `jobs_invitations`.`InterviewDate`
        , `jobs_invitations`.`InterviewVenue`
        , `jobs_invitations`.`InterviewCity`
		, `jobs_invitations`.`EmployerUID`
		, `jobs_invitations`.`EmployeeUID`
		, `jobs_invitations`.`JobUID`
		, `jobs_invitations`.`Designation`
        , `employee`.`EmployeeName`
        , `employee`.`EmployeeEmail`
        , `jobs_invitations`.`UID`
    FROM
        `jobs_invitations`
    INNER JOIN `employee` ON (`jobs_invitations`.`EmployeeUID` = `employee`.`UID`)
    WHERE (`jobs_invitations`.`UID` = '".$ApplicationID."'); ");
  $interview = fetch( $stmt );
  $InterviewDate = $interview['InterviewDate'];
  $Employer = GetData('EmployerCompany','employer','UID', $interview['EmployerUID'] );
  if($interview['JobID']==0){
		$JOB = optionVal($interview['Designation']);	
	} else {
		$JOB = GetData('JobTitle','jobs','UID',$interview['JobID']);	
	}
		
  $ReminderSubject = "Reminder for Interview";
  $ReminderEmail = '
	<strong>Dear '.$interview['EmployeeName'].', </strong><br ><br >
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; With reference to the email for interview at <strong>'.$Employer.'</strong> for the post of <strong>'.$JOB.'</strong>, it is a reminder of your interview that is scheduled on: <br /><br />
	Date: <strong>'.date("d M, Y", strtotime($interview['InterviewDate']) ).'</strong><br />
	City: <strong>'.( ($interview['InterviewCity']==0)? 'N/A' : optionVal($interview['InterviewCity']) ).'</strong><br />
	Venue: <strong>'.$interview['InterviewVenue'].'</strong><br /><br />
	All the best for your interview.<br /><br />
  ';

}

if( $ReminderModule=='application' ){

  $stmt = query("
    SELECT
        `jobs_apply`.`InterviewDate`
        , `jobs_apply`.`InterviewVenue`
        , `jobs_apply`.`InterviewCity`
        , `employee`.`EmployeeName`
        , `employee`.`EmployeeEmail`
        , `jobs_apply`.`UID`
    FROM
        `jobs_apply`
    INNER JOIN `employee` ON (`jobs_apply`.`EmployeeID` = `employee`.`UID`)
    WHERE (`jobs_apply`.`UID` = '".$ApplicationID."'); ");
  $interview = fetch( $stmt );
  $InterviewDate = $interview['InterviewDate'];

  $ReminderSubject = "Reminder for Interview";
  $ReminderEmail = '
  <strong>Dear '.$interview['EmployeeName'].',</strong><br><br>
  <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; With reference to the interview call email, it is a reminder of your interview that is scheduled on:<br>
  <br>
  Date: '.date("d M, Y", strtotime($interview['InterviewDate'])).' <br>
  Venue: '.$interview['InterviewVenue'].' <br>
  <br>
  All the best for your interview.</p>
  <br><br>
  <p>Regards,<br>
  <strong>Team Holistic Jobs</strong></p>';

}


?>

<link href="<?=$path?>css/paging.css" rel="stylesheet">
<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li class="active">Reminders</li>
    </ul>
  </div>
  <div class="page-content"> 
    <!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12"> 
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
          <div class="col-xs-12">
            <h3 class="header smaller lighter blue">Reminders</h3>
            <div id="ajax-result"><?=$message?></div>
            <div class="col-xs-12">
              <div class="table-header"> Results for "All Reminders" <span style="float:right; margin-right:8px;"><a type="button" class="btn btn-success btn-sm" href="#AddReminderEmailContent" data-toggle="modal" data-uid="<?=$_GET['uid']?>" title="Add New" >Add New</a> </span></div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover ">
                  <?php 
                $limit = 25;
                (!$_REQUEST['pager'])?$_REQUEST['pager']=1:'';
                $count = ( $limit * ( $_REQUEST['pager'] - 1 ) ) + 1 ;
                $paging = getPaging('jobs_reminders'," Where ReminderModule = '".$_GET['module']."' and ReminderAppID = '".$_GET['uid']."' ORDER BY SystemDate DESC ",$limit,'reminders.php','?',$_REQUEST['pager']);
               // echo $paging[0];
                $rs_pages = mysql_query($paging[0]) or die($paging[0]);
                $row = mysql_num_rows( $rs_pages );
                $pagination = $paging[1];?>
                  <thead>
                    <tr>
                      <th width="59">Sr. No</th>
                      <th width="13%">Interview Date</th>
                      <th width="13%">Reminder Date</th>
                      <th>Title</th>
                      <th width="7%">Status</th>
                      <th width="6%">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
            if($row>0){
              while( $rslt = mysql_fetch_array($rs_pages) ){
			  	  if($_GET['module']=='invitation'){
					 $interview = fetch ( query(" SELECT * FROM `jobs_invitations` WHERE `UID` = '".$_GET['uid']."' ") );
				  }
				  if($_GET['module']=='application'){
					 $interview = fetch ( query(" SELECT * FROM `jobs_apply` WHERE `UID` = '".$_GET['uid']."' ") );
				  } //print_r( $interview );
				  
				  ?>
                    <tr>
                      <td><?=$count?></td>
                      <td><?=$interview['InterviewDate']?></td>
                      <td><?=$rslt['ReminderDate']?></td>
                      <td><?=$rslt['ReminderSubject']?></td>
                      <td><?=$rslt['ReminderStatus']?></td>
                      <td><?php
						$data = array(
									array('title'=>'<i class="icon-pencil bigger-130"></i>',  'href'=>'#EditReminderEmailContent', 'js'=>' role="button" data-toggle="modal" data-uid="'.$rslt['UID'].'" onClick="LoadReminder('.$rslt['UID'].')" class="green" title="Edit"'),
									array('title'=>'<i class="icon-trash bigger-130"></i>', 'href'=>'javascript:void(0)', 'js'=>' onclick="ConfirmDelete(\'reminders.php?module='.$_GET['module'].'&uid='.$_GET['uid'].'&deleteid='.$rslt['UID'].'\')" class="red ConfirmDelete"  title="Delete" ')
								);
						echo TableActions($data); 
				?></td>
                    </tr>
                    <? $count++;
                }
              } else {  ?>
                    <tr>
                      <th class="center" colspan="6">No Records Found.!</th>
                    </tr>
                    <?
                } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="center" colspan="6"><div class="pull-right">
                          <?=$pagination?>
                        </div></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- PAGE CONTENT ENDS --> 
      </div>
      <!-- /.col --> 
    </div>
    <!-- /.row --> 
  </div>
</div>
<?php include('footer.php');?>
<div id="AddReminderEmailContent" class="modal">
  <div class="modal-dialog" style="width:65%">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
          Add Reminders</div>
      </div>
      <div class="modal-body form-horizontal">
        <h3 style="margin-top:0;">Set a Reminder for an Applicant</h3>
        <div class="slim-scroll" data-height="500">
          <form role="form" id="AddReminderContentForm" name="AddReminderContentForm">
            <input type="hidden" name="ReminderJobAppID" value="<?=$ApplicationID?>" />
            <input type="hidden" name="ReminderModule" value="<?=$ReminderModule?>" />
            <div id="Ajax-Result"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Reminder Date:</label>
              <div class="col-sm-9">
                <input type="text" name="ReminderDate" id="ReminderDate" class="col-xs-7 col-sm-7 col-md-7 date-picker validate[required,custom[date],future[<?=date("Y-m-d")?>],past[<?=date("Y-m-d", strtotime($InterviewDate))?>]]" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd"  />  <!---->
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Title / Subject:</label>
              <div class="col-sm-9">
                <input type="text" name="ReminderSubject" id="ReminderSubject" class="col-xs-7 col-sm-7 col-md-7" 
						value="<?=$ReminderSubject?>"  />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Email Content:</label>
              <div class="col-sm-9">
                <textarea id="ReminderEmail" name="ReminderEmail" class="ContentEditor col-sm-12"  style="height: 250px; width:350px"><?=$ReminderEmail?></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer no-margin-top">
        <button type="button" onclick="AddReminder()" class="btn btn-success"> Submit</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" > Close </button>
      </div>
      
    </div>
  </div>
</div>
<div id="EditReminderEmailContent" class="modal">
  <div class="modal-dialog" style="width:65%">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> <span class="white">&times;</span> </button>
          Edit Reminders</div>
      </div>
      <div class="modal-body form-horizontal">
        <h3 style="margin-top:0;">Edit Reminder for an Applicant</h3>
        <div class="slim-scroll" data-height="500">
          <form role="form" id="EditReminderContentForm">
            <input type="hidden" name="UID" id="UID" value="" />
            <div id="Ajax-Result"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Reminder Date:</label>
              <div class="col-sm-9">
                <input type="text" name="ReminderDate" id="ReminderDate" class="col-xs-5 col-sm-5 date-picker validate[required,custom[date],future[<?=date("Y-m-d")?>],past[<?=date("Y-m-d", strtotime($InterviewDate))?>]]" data-date-format="yyyy-mm-dd" value="" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Title:</label>
              <div class="col-sm-9">
                <input type="text" name="ReminderSubject" id="ReminderSubject" class="col-xs-5 col-sm-5" value="" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Email Content:</label>
              <div class="col-sm-9">
                <textarea id="ReminderEmail" name="ReminderEmail" class="col-sm-12"  style="height: 250px; width:350px"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer no-margin-top">
        <button type="button" onclick="EditReminder()" class="btn btn-success"> Submit</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" > Close </button>
      </div>
    </div>
  </div>
</div>
<script>

    $('.ContentEditor').redactor({ autoresize: false });
    $("form#AddReminderContentForm").validationEngine('validate');
	$('.date-picker').datepicker({autoclose:true,dateFormat: 'yy/mm/dd'}).next().on(ace.click_event, function(){
		$(this).prev().focus();
	});
		    
    function LoadReminder(uid){
      form_data = "action=LoadReminder&UID=" + uid ;
      //alert(form_data);
      $.ajax({
        cache: false, 
        type: "POST",
        url: "<?=$path?>employer/ajaxpage.php",
        data: form_data,
        dataType : 'json',
        async: false,
        success: function(data){
          //alert( data.toSource() );
          $("#EditReminderContentForm #UID").val( data.UID );
          $("#EditReminderContentForm #ReminderDate").val( data.ReminderDate );
          $("#EditReminderContentForm #ReminderSubject").val( data.ReminderSubject );
          $("#EditReminderContentForm #ReminderEmail").val( data.ReminderEmail );
          $('#EditReminderContentForm #ReminderEmail').redactor({ autoresize: false });
        },
        error: function(){
        }
      }); 
    }
    
    function AddReminder(){
      var validate = $("form#AddReminderContentForm").validationEngine('validate');
      var valid = $("#AddReminderContentForm .formError").length;
      if (valid != 0){ return false; }
      
	  //alert(validate);
      var form_data = $('form#AddReminderContentForm').serialize();
      form_data = "action=AddReminder&Module=<?=$ReminderModule?>&ModuleID=<?=$ApplicationID?>&" + form_data;
      //alert(form_data);
      $.ajax({
        cache: false, 
        type: "POST",
        url: "<?=$path?>employer/ajaxpage.php",
        data: form_data,
        dataType : 'html',
        async: false,
        success: function(data){
          $("form#AddReminderContentForm #Ajax-Result").html(data);
          setTimeout(function(){ window.location.reload(true); }, 3000);
          return false;
        },
        error: function(){
        }
      });
    }
    
    function EditReminder(){

      var validate = $("form#EditReminderContentForm").validationEngine('validate');
      var valid = $("#EditReminderContentForm .formError").length;
      if (valid != 0){ return false; }
      
      var form_data = $('form#EditReminderContentForm').serialize();
      form_data = "action=EditReminder&Module=<?=$ReminderModule?>&ModuleID=<?=$ApplicationID?>&" + form_data;
      //alert(form_data);

      $.ajax({
        cache: false, 
        type: "POST",
        url: "<?=$path?>employer/ajaxpage.php",
        data: form_data,
        dataType : 'html',
        async: false,
        success: function(data){
          $("form#EditReminderContentForm #Ajax-Result").html(data);
          setTimeout(function(){ window.location.reload(true); }, 3000);
          return false;
        },
        error: function(){
        }
      });
      
    }
    </script> 