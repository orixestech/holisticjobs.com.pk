<?php #print_r($_REQUEST);
include("../includes/conn.php");

include("../admin_theme_functions.php");

/////////////////////////////////////
///////// Front Side Functions
include("../../site_theme_functions.php");
/////////////////////////////////////

$stmt = query("SELECT * FROM `members` 
					LEFT JOIN `member_setting` ON (`members`.`id` = `member_setting`.`member_id`)
					WHERE (`members`.`id`  =  '".$_REQUEST['member_id']."' ); ");
$member = fetch($stmt);

$stmt = query(" SELECT * FROM `members` WHERE `id` = '".$_REQUEST['member_id']."' ");
$USER = fetch($stmt);

$stmt = query(" SELECT * FROM `member_setting` WHERE `member_id` = '".$_REQUEST['member_id']."' ");
while($rslt = fetch($stmt)){
	$USERSETTING[$rslt['option_name']] = $rslt['option_value'];
}


?>

<div class="row">
  <div class="col-sm-12">
    <div id="ajax-result"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="tabbable">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active"> <a data-toggle="tab" href="#home"> Profile </a> </li>
        <li> <a data-toggle="tab" href="#social"> Social </a> </li>
        <?php
		if ($_REQUEST['member_id']) { ?>
        <li> <a data-toggle="tab" href="#templates" onclick="SlimScroll('#templates .slim-scroll')"> Templates </a> </li>
        <li> <a data-toggle="tab" href="#messages"> Messages </a> </li>
        <li> <a data-toggle="tab" href="#stats"> Stats </a> </li>
        <? }?>
      </ul>
      <form role="form" id="members_form">
        <input type="hidden" id="member_id" name="member_id" value="<?=$_REQUEST['member_id']?>">
        <div class="tab-content">
          <div id="home" class="tab-pane in active">
            <div class="row">
              <div class="col-xs-12" style="overflow:auto; height:500px;">
                <!--<div class="form-group col-xs-6">
            <label for="username" class="control-label">User name:</label>
            <input type="text" class="form-control" id="username" name="username">
          </div>-->
                <div class="form-group col-xs-6">
                  <label for="email" class="control-label">Email:</label>
                  <input type="text" class="form-control" id="email" name="email" value="<?=$USER['email']?>">
                </div>
                <div class="form-group col-xs-6">
                  <label for="password" class="control-label">Password:</label>
                  <input type="text" class="form-control" id="password" name="password" value="<?=($USER['password']!='') ? Password($USER['password'], "show") : ''?>">
                </div>
                <div class="form-group col-xs-6">
                  <label for="firstname" class="control-label">First Name:</label>
                  <input type="text" class="form-control" id="firstname" name="firstname" value="<?=$USER['firstname']?>">
                </div>
                <div class="form-group col-xs-6">
                  <label for="lastname" class="control-label">Last Name:</label>
                  <input type="text" class="form-control" id="lastname" name="lastname" value="<?=$USER['lastname']?>">
                </div>
                <!--<div class="form-group col-xs-6">
            <label for="avatar" class="control-label">Avatar:</label>
            <input type="text" class="form-control" id="avatar" name="avatar">
          </div>-->
                <div class="form-group col-xs-6">
                  <label for="country" class="control-label">Country:</label>
                  <select required name="country" class="form-control" id="country">
                    <option value="">Do not Display my Country</option>
                    <?php
				  $stmt = mysql_query(" SELECT * FROM `country` WHERE 1 order by `printable_name` ");
				  while($rslt=mysql_fetch_array($stmt)){
				  	if($rslt['iso'] == $USER['country']){ $selected = "selected"; } else { $selected = ""; }
					echo '<option value="'.$rslt['iso'].'" '.$selected.'>'.$rslt['printable_name'].'</option>';
				  } ?>
                  </select>
                </div>
                <div class="form-group col-xs-6">
                  <label for="city" class="control-label">City:</label>
                  <input type="text" class="form-control" id="city" name="city" value="<?=$USER['city']?>">
                </div>
                <div class="form-group col-xs-6">
                  <label for="company_name" class="control-label">Company Name:</label>
                  <input type="text" class="form-control" id="company_name" name="company_name" value="<?=$USER['company_name']?>">
                </div>
                <div class="form-group col-xs-12">
                  <label for="profile_heading" class="control-label">Profile Heading:</label>
                  <input type="text" class="form-control" id="profile_heading" name="profile_heading" value="<?=$USER['profile_heading']?>">
                </div>
                <div class="form-group col-xs-12">
                  <label for="profile_text" class="control-label">Profile Text:</label>
                  <textarea class="form-control" id="profile_text" name="profile_text" ><?=$USER['profile_text']?>
</textarea>
                </div>
              </div>
            </div>
          </div>
          <div id="social" class="tab-pane">
            <div class="row userInfo"  style="overflow:auto; height:500px;">
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="facebook"><i class="fa fa-facebook-square"></i> Face Book </label>
                  <input required type="text" class="form-control" id="facebook" name="facebook" placeholder="Your FaceBook Page" value="<?=$USERSETTING['facebook']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="twitter"><i class="fa fa-twitter-square"></i> Twitter </label>
                  <input required type="text" class="form-control" id="twitter" name="twitter" placeholder="Your Twitter Account" value="<?=$USERSETTING['twitter']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="google-plus"><i class="fa fa-google-plus-square"></i> Google Plus </label>
                  <input required type="text" class="form-control" id="google-plus" name="google-plus" placeholder="Your Google Plus Account" value="<?=$USERSETTING['google-plus']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="linked-in"><i class="fa fa-linkedin-square"></i> Linked In </label>
                  <input required type="text" class="form-control" id="linked-in" name="linked-in" placeholder="Your Linked In Account" value="<?=$USERSETTING['linked-in']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="youtube"><i class="fa fa-youtube-square"></i> YouTube </label>
                  <input required type="text" class="form-control" id="youtube" name="youtube" placeholder="Your YouTube Account" value="<?=$USERSETTING['youtube']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="vimeo"><i class="fa fa-vimeo-square"></i> Vimeo </label>
                  <input required type="text" class="form-control" id="vimeo" name="vimeo" placeholder="Your Vimeo Account" value="<?=$USERSETTING['vimeo']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="tuts"><i class="fa fa-google-plus-square"></i> Tuts Plus </label>
                  <input required type="text" class="form-control" id="tuts" name="tuts" placeholder="Your Tuts+ Account" value="<?=$USERSETTING['tuts']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="tumblr"><i class="fa fa-google-plus-square"></i> Tumblr </label>
                  <input required type="text" class="form-control" id="tumblr" name="tumblr" placeholder="Your Tumblr Account" value="<?=$USERSETTING['tumblr']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="sound-cloud"><i class="fa fa-google-plus-square"></i> Sound Cloud </label>
                  <input required type="text" class="form-control" id="sound-cloud" name="sound-cloud" placeholder="Your Sound Cloud Account" value="<?=$USERSETTING['sound-cloud']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="reddit"><i class="fa fa-reddit-square"></i> Reddit </label>
                  <input required type="text" class="form-control" id="reddit" name="reddit" placeholder="Your Reddit Account" value="<?=$USERSETTING['reddit']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="my-space"><i class="fa fa-google-plus-square"></i> My Space </label>
                  <input required type="text" class="form-control" id="my-space" name="my-space" placeholder="Your My Space Account" value="<?=$USERSETTING['my-space']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="last-fm"><i class="fa fa-lastfm-square"></i> Last.fm </label>
                  <input required type="text" class="form-control" id="last-fm" name="last-fm" placeholder="Your Last.fm Account" value="<?=$USERSETTING['last-fm']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="github"><i class="fa fa-github-square"></i> GitHub </label>
                  <input required type="text" class="form-control" id="github" name="github" placeholder="Your GitHub Account" value="<?=$USERSETTING['github']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="flickr"><i class="fa fa-flickr"></i> Flickr </label>
                  <input required type="text" class="form-control" id="flickr" name="flickr" placeholder="Your Flickr Account" value="<?=$USERSETTING['flickr']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="dribbble"><i class="fa fa-dribbble"></i> Dribbble </label>
                  <input required type="text" class="form-control" id="dribbble" name="dribbble" placeholder="Your Dribbble Account" value="<?=$USERSETTING['dribbble']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="digg"><i class="fa fa-digg"></i> Digg </label>
                  <input required type="text" class="form-control" id="digg" name="digg" placeholder="Your Digg Account" value="<?=$USERSETTING['digg']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="deviantart"><i class="fa fa-deviantart"></i> Deviantart </label>
                  <input required type="text" class="form-control" id="deviantart" name="deviantart" placeholder="Your Deviantart Account" value="<?=$USERSETTING['deviantart']?>">
                </div>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="form-group required">
                  <label for="behance"><i class="fa fa-behance-square"></i> Behance </label>
                  <input required type="text" class="form-control" id="behance" name="behance" placeholder="Your Behance Account" value="<?=$USERSETTING['behance']?>">
                </div>
              </div>
            </div>
          </div>
          <div id="templates" class="tab-pane">
            <div class="row">
              <div class="col-xs-12 col-sm-12" style="overflow:auto; height:500px;">
                <table class="table table-striped table-bordered table-hover ">
                  <thead>
                    <tr>
                      <th width="100"> <span title="table sorted by this column on load">Image</span> </th>
                      <th >Title</th>
                      <th width="122"> Action </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
				$sql = "SELECT `templates`.* FROM `templates` WHERE `userid` = '".$_REQUEST['member_id']."' order by `id`; ";
				$total = total( $sql );
				if($total > 0){
					$stmt = query( $sql );
					while($rslt=fetch($stmt)){
						$link = ItemLink($rslt['id']);
						$thumb = $path."images/no_available.jpg";
						if( file_exists("../../uploads/templates/".$rslt['thumbnail']) && $rslt['thumbnail']!=NULL ){
							$thumb = $path."uploads/templates/".$rslt['thumbnail'];
						} ?>
                    <tr id="feature-<?=$rslt['id']?>">
                      <td><img src="<?=$thumb?>" alt="img" class="img-responsive" style="max-width:100px;"></td>
                      <td><a href="<?=$link?>" target="_blank">
                        <?=$rslt['title']?>
                        </a></td>
                      <td><div class="btn-group" id="template_<?=$rslt['id']?>">
                          <button data-toggle="dropdown" class="btn btn-xs btn-<?=($rslt['status']=='Approved')?'success':'danger'?> dropdown-toggle"> <?php echo $rslt['status']; ?> <i class="icon-angle-down icon-on-right"></i> </button>
                          <ul class="dropdown-menu">
                            <li><a href="javascript:void(0);" onclick="openStatusTemplate(<?=$rslt['id']?>, 'Approved');">Approved</a></li>
                            <li><a href="javascript:void(0);" onclick="openStatusTemplate(<?=$rslt['id']?>, 'Blocked');">Block</a></li>
                          </ul>
                        </div></td>
                    </tr>
                    <? }
				} else { ?>
                    <tr>
                      <td colspan="6"><p>You have no items </p></td>
                    </tr>
                    <?
				}
				?>
                  </tbody>
                </table>
                <div class="clearfix"><br />
                </div>
              </div>
            </div>
          </div>
          <div id="messages" class="tab-pane">
            <div class="row">
              <div class="col-xs-12 col-sm-12" style="overflow:auto; height:500px;">
                <table class="table table-striped table-bordered table-hover ">
                  <thead>
                    <tr>
                      <th width="100"> To </th>
                      <th width="100"> From </th>
                      <th>Message</th>
                      <th width="122"> Datetime </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
				$sql = "SELECT * FROM `member_messages` WHERE `from_id` = '".$_REQUEST['member_id']."' or `to_id` = '".$_REQUEST['member_id']."' ORDER BY `member_messages`.`created` DESC ";
				$total = total( $sql );
				if($total > 0){
					$stmt = query( $sql );
					while($rslt=fetch($stmt)){
						$from_id = fetch ( query ( "SELECT CONCAT(firstname, '&nbsp;' , lastname) as name FROM `members` WHERE `id` = '".$rslt['from_id']."' " ) );
						$to_id = fetch ( query ( "SELECT CONCAT(firstname, '&nbsp;' , lastname) as name FROM `members` WHERE `id` = '".$rslt['to_id']."' " ) );?>
                    <tr id="feature-<?=$rslt['id']?>">
                      <td><?=$from_id['name']?></td>
                      <td><?=$to_id['name']?></td>
                      <td><?=$rslt['message']?></td>
                      <td><?=$rslt['created']?></td>
                    </tr>
                    <? }
				} else { ?>
                    <tr>
                      <td colspan="6"><p>You have no messages </p></td>
                    </tr>
                    <?
				}
				?>
                  </tbody>
                </table>
                <div class="clearfix"><br />
                </div>
              </div>
            </div>
          </div>
          <div id="stats" class="tab-pane">
            <div class="row">
              <div class="col-xs-12 col-sm-12" style="overflow:auto; height:500px;">
                <h3>Templates</h3>
                <?php
				$stmt=query("SELECT distinct `status`, count(`id`) as tot FROM `templates` WHERE `userid` = '".$_REQUEST['member_id']."' group by `status`");
				$total=$AwaitingApproval=$Approved=$Blocked=0;
				while($rslt=fetch($stmt)){
					if($rslt['status']=='Awaiting Approval'){ $AwaitingApproval = $rslt['tot']; }
					if($rslt['status']=='Approved'){ $Approved = $rslt['tot']; }
					if($rslt['status']=='Blocked'){ $Blocked = $rslt['tot']; }
					$total += $rslt['tot'];
				}?>
                <table class="table table-striped table-bordered table-hover ">
                  <thead>
                    <tr>
                      <th>Total <span class="badge badge-danger">
                        <?=$total?>
                        </span></th>
                      <th>Awaiting Approval <span class="badge badge-danger">
                        <?=$AwaitingApproval?>
                        </span></th>
                      <th>Approved <span class="badge badge-danger">
                        <?=$Approved?>
                        </span></th>
                      <th>Blocked <span class="badge badge-danger">
                        <?=$Blocked?>
                        </span></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="4"><div class="progress">
                          <div style="width: <?=round( ($Approved / $total) * 100 ) ?>%;" class="progress-bar progress-bar-success"></div>
                          <div style="width: <?=round( ($AwaitingApproval / $total) * 100 ) ?>%;" class="progress-bar progress-bar-warning"></div>
                          <div style="width: <?=round( ($Blocked / $total) * 100 ) ?>%;" class="progress-bar progress-bar-danger"></div>
                        </div></td>
                    </tr>
                  </tbody>
                </table>
                <h3>Messages</h3>
                <?php
			  /* SELECT count(`id`) FROM `member_messages` WHERE `from_id` = 13 ORDER BY `created` DESC
			  	 SELECT count(`id`) FROM `member_messages` WHERE `to_id` = 13 ORDER BY `created` DESC */
					
				$from_id = fetch( query( "SELECT count(`id`) FROM `member_messages` WHERE `from_id` = '".$_REQUEST['member_id']."' ORDER BY `created` DESC" ) );
				$from_id = $from_id[0];
				
				$to_id = fetch( query( "SELECT count(`id`) FROM `member_messages` WHERE `to_id` = '".$_REQUEST['member_id']."' ORDER BY `created` DESC" ) );
				$to_id = $to_id[0];
				
				$total = $from_id + $to_id; ?>
                <table class="table table-striped table-bordered table-hover ">
                  <thead>
                    <tr>
                      <th>Total <span class="badge badge-danger">
                        <?=$total?>
                        </span></th>
                      <th>Received <span class="badge badge-danger">
                        <?=$to_id?>
                        </span></th>
                      <th>Send <span class="badge badge-danger">
                        <?=$from_id?>
                        </span></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="4"><div class="progress">
                          <div style="width: <?=round( ($to_id / $total) * 100 ) ?>%;" class="progress-bar progress-bar-success"></div>
                          <div style="width: <?=round( ($from_id / $total) * 100 ) ?>%;" class="progress-bar progress-bar-warning"></div>
                        </div></td>
                    </tr>
                  </tbody>
                </table>
                <div class="clearfix"><br />
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
