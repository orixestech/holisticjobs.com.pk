<?php include('header.php');?>

<?php

// backup the db OR just a table 

function backup_tables($host,$user,$pass,$name,$tables = '*')

{

	

	$link = mysql_connect($host,$user,$pass);

	mysql_select_db($name,$link);

	

	//get all of the tables

	if($tables == '*')

	{

		$tables = array();

		$result = mysql_query('SHOW TABLES');

		while($row = mysql_fetch_row($result))

		{

			$tables[] = $row[0];

		}

	}

	else

	{

		$tables = is_array($tables) ? $tables : explode(',',$tables);

	}

	

	//cycle through

	foreach($tables as $table)

	{

		$result = mysql_query('SELECT * FROM '.$table);

		$num_fields = mysql_num_fields($result);

		

		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));

		$return.= "\n\n".$row2[1].";\n\n";

		

		for ($i = 0; $i < $num_fields; $i++) 

		{

			while($row = mysql_fetch_row($result))

			{

				$return.= 'INSERT INTO '.$table.' VALUES(';

				for($j=0; $j<$num_fields; $j++) 

				{

					$row[$j] = addslashes($row[$j]);

					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }

					if ($j<($num_fields-1)) { $return.= ','; }

				}

				$return.= ");\n";

			}

		}

		$return.="\n\n\n";

	}

	

	//save file

	$file = $name.'-db-backup-'.date('Y-m-d').'.sql';

	$handle = fopen("mysql_backup/".$file,'w+');

	fwrite($handle,$return);

	fclose($handle);

	return $file;

}

?>



<div class="main-content">

  <div class="breadcrumbs" id="breadcrumbs">

    <ul class="breadcrumb">

      <li> <i class="icon-home home-icon"></i> <a href="#">Home</a> </li>

      <li class="active">Settings</li>

    </ul>

    <!-- .breadcrumb -->

    <div class="nav-search" id="nav-search">

      <form class="form-search">

        <span class="input-icon">

        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />

        <i class="icon-search nav-search-icon"></i> </span>

      </form>

    </div>

    <!-- #nav-search -->

  </div>

  <div class="page-content">

    <div class="page-header">

      <h1> Settings <small> <i class="icon-double-angle-right"></i> Modify your Website Settings </small> </h1>

    </div>

    <!-- /.page-header -->

    <?=$FormMessge?>

    <form class="form-horizontal" role="form" method="post">

      <div class="row">

        <div class="col-xs-12">

          <!-- PAGE CONTENT BEGINS -->

          <h4 class="header green">Admin Settings</h4>

          <div class="row"> <?php 

			$backup = backup_tables($hostname_dbconn,$username_dbconn,$password_dbconn,$rating_dbname);

			$file = str_replace("mysql_backup\\","",$backup);$FileList=array();

			if ($handle = opendir('mysql_backup')) {

				while (false !== ($entry = readdir($handle))) {

					if ($entry != "." && $entry != "..") {

						$FileList[] = $entry;

					}

				}

				closedir($handle);

			}?>

            <div class="table-responsive">

              <table class="table table-striped table-bordered table-hover">

                <thead>

                  <tr>

                    <!--<th>Date Time</th>-->

					<th>File Name</th>

					<th>Size</th>

                  </tr>

                </thead>

                <tbody><?php

				foreach($FileList as $val){ ?>

					<tr>

						<!--<td><?=date("F d Y H:i:s.", strtotime( filemtime("mysql_backup/".$val) ) );?></td>-->

						<td><a href="download_database.php?file=<?=$val?>"><?=$val?></a></td>

						<td><?=@round(filesize("mysql_backup/".$val)/1024,2)?> Kb</td>

					</tr>

				<?php } ?>

                </tbody>

              </table>

            </div>

            <div class="space-4"></div>

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

<?php include('footer.php');?>

