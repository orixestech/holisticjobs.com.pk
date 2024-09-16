<?php
@session_start();

if(isset($_GET['logout']) && $_GET['logout']=='true'){
	@session_destroy();
	$_SESSION['EmployerLogged'] = $_SESSION['Employer'] = '';	
}

if(@$_SESSION['EmployerLogged'] != 1){
	echo '<meta http-equiv="refresh" content="0;URL=\'login.php\'" />';exit;
}


?>
<?php include("header.php"); ?>
    <style>
.banner-slogn{ clear: both;
    color: #f00;
    font-size: 20px;
    font-weight: bold;
    margin-top: 20px;
    text-align: center;}
</style>
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
        <div id="SubscriptionExpireStatus" class="pull-right">
          <?=$SubscriptionExpireStatus?>
        </div>
      </div>
      <div class="page-content">
        <div class="page-header">
          <h1> Dashboard <small> <i class="icon-double-angle-right"></i> overview &amp; stats </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            
            <div style="background-color:#EB3437; color:#FFF; padding:10px;" class="col-xs-12">
              <div class="col-xs-12"> <span style="font-weight: bold; padding: 0px 10px; margin: 0px; line-height: 83px; font-size: 70px; float: left;">Welcome</span> <span style="padding: 0px 10px; margin: 0px; line-height: 83px; border: 1px solid; background-color: rgb(255, 255, 255); color: #3E4095; font-size: 60px; float: left;">
                <?=$_SESSION['Employer']['EmployerCompany']?>
                </span></div>
              <div style="padding: 10px; font-size: 35px;" class="col-xs-12 center">We are glad you are here!</div>
            </div>
            <div class="banner-slogn col-xs-12">Please read user manual before getting started</div>
            <!-- /row --> 
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content --> 
    </div>
    <!-- /.main-content -->
    <?php include("footer.php"); ?>
