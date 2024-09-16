<?php
error_reporting("E_ERROR");
require_once("dompdf_config.inc.php");
$filename = file_get_contents('templates/feedback.html');

$filename = stripslashes($filename);

$old_limit = ini_set("memory_limit", "512M");

$dompdf = new DOMPDF();
$dompdf->load_html($filename);
$dompdf->set_paper('letter', 'portrait');
$dompdf->render();

$dompdf->stream("dompdf_out.pdf");

exit(0);
?>