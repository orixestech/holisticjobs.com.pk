<?php include('header.php');?>
<?php
if($_POST){
	$contents = $_POST;
	$nowDate = date("Y-m-d h:i:s");
	
	if($_GET["mode"]=="update"){
		echo $run = FormData('user_manual', 'update', $contents, " UID = '".$_GET["code"]."' ", $view=false );
		$FormMessge = Alert('success', 'User Manual Updated...!');
	} else {
		echo $run = FormData('user_manual', 'insert', $contents, "", $view=false );
		$FormMessge = Alert('success', 'New User Manual Created...!');
	}

}
	
if($_GET["mode"]=="update"){
	$stmt = mysql_query(" SELECT * FROM user_manual WHERE UID = '".$_GET["code"]."' ");
	$PAGE = mysql_fetch_array($stmt);
} ?>

<div class="main-content">
  <div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
      <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
      <li> <a href="#">Web Site</a> </li>
      <li class="active">
        <?=($_GET["code"])?'Modify':'Add New'?>
        Employer User Manual </li>
    </ul>
    <!-- .breadcrumb -->
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1><?=($_GET["code"])?'Modify':'Add New'?> User Manual<small> <i class="icon-double-angle-right"></i> <?=($_GET["code"])?'Modify':'Add New'?> Employer User Manual </small> </h1>
    </div>
    <!-- /.page-header -->
    <?=$FormMessge?>
    <form class="form-horizontal" role="form" method="post" name="AddUserManual" id="AddUserManual">
      <div class="row">
        <div class="col-xs-12">
          <!-- PAGE CONTENT BEGINS -->
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Category:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <select name="ManualCategory" id="ManualCategory" class="col-xs-5 col-sm-5 selectstyle validate[required]" >
                <?php echo CategoryDropDown('User-Manual-Employer',$PAGE['ManualCategory']);?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title:<i class="icon-asterisk"></i></label>
              <div class="col-sm-9">
                <input type="text" id="ManualQuestion" name="ManualQuestion" placeholder="Enter User Manual Title" class="col-xs-10 col-sm-5 validate[required]" value="<?=$PAGE['ManualQuestion']?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Description:<i class="icon-asterisk"></i></label>
              <div class="col-sm-7">
                <textarea class="ContentEditor" id="ManualAnswer" name="ManualAnswer" style="height: 260px;" ><?=$PAGE['ManualAnswer']?>
</textarea>
              </div>
            </div>
          </div>
          <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
              <button type="submit" class="btn btn-info"> <i class="icon-ok bigger-110"></i> Submit </button>
              <button type="reset" class="btn"> <i class="icon-undo bigger-110"></i> Reset </button>
            </div>
          </div>
          <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </form>
  </div>
  <!-- /.page-content -->
</div>
<!-- /.main-content -->
<script>
$(document).ready(function(){
	$("form#AddUserManual").validationEngine('attach', {
		promptPosition : "centerRight", 
		scroll: false,
		onValidationComplete: function(form, status){
			if(status){
				document.AddUserManual.submit();
			} else {
				//alert("The form status is: " +status+", it will never submit");
			}
	  }
	});

	$("#reset").click(function(){
		$(".formError").hide();
		
	});
	
	
});
</script>
<?php include('footer.php');?>
