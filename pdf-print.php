<?php
ob_start();
include("admin/includes/conn.php");
include("admin/admin_theme_functions.php");
include("site_theme_functions.php");

ob_end_clean();
include("mpdf/mpdf.php");
include("pdf-print-processor.php");

if($html=='') exit;

$htmlfooter = '
	<footer>
	  <hr class="red">
	  <p class="tag">Holistic Jobs:: A Project of Holistic Solutions (Pvt.) Ltd. </p>
	  <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
		<tr>
		  <td width="50%" align="left"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
		  <td align="right" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg} </td>
		</tr>
	  </table>
	</footer>
';


if($htmlview == "yes"){ echo $htmlheader . '<link rel="stylesheet" href="'.$path.'pdf-print.css" />' . $html . $htmlfooter; exit; }

$mpdf = new mPDF('c', '', 0, '', 5, 5, 5, 5, 5, 5); 
// LOAD a stylesheet
$stylesheet = file_get_contents('pdf-print.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

//$mpdf->SetHTMLHeader($htmlheader); 

$outputfile = ( isset($filename) )? $filename : $_GET['print-type'];
$mpdf->WriteHTML(trim($htmlheader . $html));
$mpdf->SetHTMLFooter(trim($htmlfooter));
ob_end_flush();
$mpdf->Output($outputfile.'.pdf','I');
exit;

?>