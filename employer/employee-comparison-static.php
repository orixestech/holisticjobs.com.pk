<?php include('header.php');?>
    <link href="<?=$path?>css/paging.css" rel="stylesheet">
    <div class="main-content">
      <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
          <li> <i class="icon-home home-icon"></i> <a href="index.php">Home</a> </li>
          <li class="active">Employee Comparison</li>
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
                <h3 class="header smaller lighter blue">Employee Comparison</h3>
                <div id="ajax-result">
                  <?=$message?>
                </div>
                <div class="col-xs-12">
                  <div class="table-header"> Results for "All Applicants" </div>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover ">
                      <thead>
                        <tr>
                          <th width="70">Sr. No</th>
                          <th width="25%">Personal Details</th>
                          <th>Experience</th>
                          <th>Education</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th>1</th>
                          <td><strong>Name</strong> : Ms. BDO1 <br>
                            <strong>Age</strong> : 23 <br>
                            <strong>City</strong> : Islamabad <br>
                          </td>
                          <td>Total Experience: 0</td>
                          <td>Highest Education: Bachelors of Engineering in 2015 from Comsats Institute of Information Technology</td>
                        </tr>
                        <tr>
                          <th>2</th>
                          <td><strong>Name</strong> : Mrs.   Fariha Tehseen <br>
                            <strong>Age</strong> : 25 <br>
                            <strong>City</strong> : Islamabad <br>
                          </td>
                          <td>Total Experience: 0</td>
                          <td>Highest Education: Bachelors In Economics in 2012 from IIUI </td>
                        </tr>
                        <tr>
                          <th>3</th>
                          <td><strong>Name</strong> : Dr.   Friya Shaheryar <br>
                            <strong>Age</strong> : 25 <br>
                            <strong>City</strong> : Islamabad <br>
                          </td>
                          <td>Total Experience: 5 Year</td>
                          <td>Highest Education: Intermediate in 2016 from Collage </td>
                        </tr>
                      </tbody>
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
      <!-- /.page-content -->
    </div>
    <!-- /.main-content -->
    <?php include('footer.php');?>
