<?php
$file="mysql_backup/".$_GET['file']; //file location
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: ' . filesize($file));
readfile($file);
?>