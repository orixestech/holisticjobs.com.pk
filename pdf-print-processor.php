<?php
$html = '';
$file = "pdf-print-templates/".$_GET['print-type'].".php";
if( file_exists($file) ){
	include($file);
} else {
	
}


/*
$html = '
<h1>mPDF Images</h1>

<table>
<tr>
<td>GIF</td>
<td>JPG</td>
<td>JPG (CMYK)</td>
<td>PNG</td>
<td>BMP</td>
<td>WMF</td>
<td>SVG</td>
</tr>
<tr>
<td><img style="vertical-align: top" src="mpdf/examples/tiger.gif" width="80" /></td>
<td><img style="vertical-align: top" src="mpdf/examples/tiger.jpg" width="80" /></td>
<td><img style="vertical-align: top" src="mpdf/examples/tigercmyk.jpg" width="80" /></td>
<td><img style="vertical-align: top" src="mpdf/examples/tiger.png" width="80" /></td>
<td><img style="vertical-align: top" src="mpdf/examples/tiger.bmp" width="80" /></td>
<td><img style="vertical-align: top" src="mpdf/examples/tiger2.wmf" width="80" /></td>
<td><img style="vertical-align: top" src="mpdf/examples/tiger.svg" width="80" /></td>
</tr>
</tr>
<tr>
<td colspan="7" style="text-align: left" ><h4>Opacity 50%</h4></td>
</tr>
<tr>
<tr>
<td><img style="vertical-align: top; opacity: 0.5" src="mpdf/examples/tiger.gif" width="80" /></td>
<td><img style="vertical-align: top; opacity: 0.5" src="mpdf/examples/tiger.jpg" width="80" /></td>
<td><img style="vertical-align: top; opacity: 0.5" src="mpdf/examples/tigercmyk.jpg" width="80" /></td>
<td><img style="vertical-align: top; opacity: 0.5" src="mpdf/examples/tiger.png" width="80" /></td>
<td><img style="vertical-align: top; opacity: 0.5" src="mpdf/examples/tiger.bmp" width="80" /></td>
<td><img style="vertical-align: top; opacity: 0.5" src="mpdf/examples/tiger2.wmf" width="80" /></td>
<td><img style="vertical-align: top; opacity: 0.5" src="mpdf/examples/tiger.svg" width="80" /></td>
</tr>
</table>

<h4>Alpha channel</h4>
<table>
<tr>
<td>PNG</td>
<td><img style="vertical-align: top" src="mpdf/examples/alpha.png" width="85" /></td>
<td style="background-color:#FFCCFF; "><img style="vertical-align: top" src="mpdf/examples/alpha.png" width="85" /></td>
<td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="mpdf/examples/alpha.png" width="85" /></td>
<td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="mpdf/examples/alpha.png" width="85" /></td>
<td style="background-color:#CCFFFF; background: transparent url(\'bg.jpg\') repeat scroll right top;"><img style="vertical-align: top" src="mpdf/examples/alpha.png" width="85" /></td>
</tr>
</table>
<h4>Transparency</h4>
<table><tr>
<td>PNG</td>
<td style="background-color:#FFCCFF; "><img style="vertical-align: top" src="mpdf/examples/tiger24trns.png" width="85" /></td>
<td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="mpdf/examples/tiger24trns.png" width="85" /></td>
<td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="mpdf/examples/tiger24trns.png" width="85" /></td>
<td style="background-color:#CCFFFF; background: transparent url(\'bg.jpg\') repeat scroll right top;"><img style="vertical-align: top" src="mpdf/examples/tiger24trns.png" width="85" /></td>
</tr><tr>
<td>GIF</td>
<td style="background-color:#FFCCFF;"><img style="vertical-align: top" src="mpdf/examples/tiger8trns.gif" width="85" /></td>
<td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="mpdf/examples/tiger8trns.gif" width="85" /></td>
<td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="mpdf/examples/tiger8trns.gif" width="85" /></td>
<td style="background-color:#CCFFFF; background: transparent url(\'bg.jpg\') repeat scroll right top;"><img style="vertical-align: top" src="mpdf/examples/tiger8trns.gif" width="85" /></td>
</tr><tr>
<td>WMF</td>
<td style="background-color:#FFCCFF;"><img style="vertical-align: top" src="mpdf/examples/tiger2.wmf" width="85" /></td>
<td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="mpdf/examples/tiger2.wmf" width="85" /></td>
<td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="mpdf/examples/tiger2.wmf" width="85" /></td>
<td style="background-color:#CCFFFF; background: transparent url(\'bg.jpg\') repeat scroll right top;"><img style="vertical-align: top" src="mpdf/examples/tiger2.wmf" width="85" /></td>
</tr><tr>
<td>SVG</td>
<td style="background-color:#FFCCFF;"><img style="vertical-align: top" src="mpdf/examples/tiger.svg" width="85" /></td>
<td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="mpdf/examples/tiger.svg" width="85" /></td>
<td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="mpdf/examples/tiger.svg" width="85" /></td>
<td style="background-color:#CCFFFF; background: transparent url(\'bg.jpg\') repeat scroll right top;"><img style="vertical-align: top" src="mpdf/examples/tiger.svg" width="85" /></td>
</tr></table>


Images returned from tiger.php
<div>
GIF <img style="vertical-align: top" src="mpdf/examples/tiger.php?t=gif" width="85" />
JPG <img style="vertical-align: top" src="mpdf/examples/tiger.php?t=jpg" width="85" />
PNG <img style="vertical-align: top" src="mpdf/examples/tiger.php?t=png" width="85" />
WMF <img style="vertical-align: top" src="mpdf/examples/tiger.php?t=wmf" width="85" />
SVG <img style="vertical-align: top" src="mpdf/examples/tiger.php?t=svg" width="85" />
</div>

<pagebreak />


<h3>Image Alignment</h3>
<div>In-line images can be individually aligned (vertically). Most of the values for "vertical-align" are supported: top, bottom, middle, baseline, text-top, and text-bottom. The default value for vertical alignment is baseline, and the default padding 0, consistent with most browsers.
</div>
<br />

<div style="background-color:#CCFFFF;">
These images <img src="mpdf/examples/img1.png" style="vertical-align: top;" />
are <img src="mpdf/examples/img2.png" style="vertical-align: top;" />
<b>top</b> <img src="mpdf/examples/img3.png" style="vertical-align: top;" />
aligned <img src="mpdf/examples/img4.png" style="vertical-align: middle;" />
</div>
<br />

<div style="background-color:#CCFFFF;">
These images <img src="mpdf/examples/img1.png" style="vertical-align: text-top;" />
are <img src="mpdf/examples/img2.png" style="vertical-align: text-top;" />
<b>text-top</b> <img src="mpdf/examples/img3.png" style="vertical-align: text-top;" />
aligned <img src="mpdf/examples/img4.png" style="vertical-align: middle;" />
</div>
<br />

<div style="background-color:#CCFFFF;">
These images <img src="mpdf/examples/img1.png" style="vertical-align: bottom;" />
are <img src="mpdf/examples/img2.png" style="vertical-align: bottom;" />
<b>bottom</b> <img src="mpdf/examples/img3.png" style="vertical-align: bottom;" />
aligned <img src="mpdf/examples/img4.png" style="vertical-align: middle;" />
</div>
<br />

<div style="background-color:#CCFFFF;">
These images <img src="mpdf/examples/img1.png" style="vertical-align: text-bottom;" />
are <img src="mpdf/examples/img2.png" style="vertical-align: text-bottom;" />
<b>text-bottom</b> <img src="mpdf/examples/img3.png" style="vertical-align: text-bottom;" />
aligned <img src="mpdf/examples/img4.png" style="vertical-align: middle;" />
</div>
<br />

<div style="background-color:#CCFFFF;">
These images <img src="mpdf/examples/img1.png" style="vertical-align: middle;" />
are <img src="mpdf/examples/img2.png" style="vertical-align: middle;" />
<b>middle</b> <img src="mpdf/examples/img3.png" style="vertical-align: middle;" />
aligned <img src="mpdf/examples/img5.png" style="vertical-align: bottom;" />
</div>

<h4>Mixed alignment</h4>
<div style="background-color:#CCFFFF;">
baseline: <img src="mpdf/examples/sunset.jpg" width="50" style="vertical-align: baseline;" />
text-bottom: <img src="mpdf/examples/sunset.jpg" width="30" style="vertical-align: text-bottom;" />
middle: <img src="mpdf/examples/sunset.jpg" width="30" style="vertical-align: middle;" />
bottom: <img src="mpdf/examples/sunset.jpg" width="80" style="vertical-align: bottom;" />
text-top: <img src="mpdf/examples/sunset.jpg" width="50" style="vertical-align: text-top;" />
top: <img src="mpdf/examples/sunset.jpg" width="100" style="vertical-align: top;" />
</div>

';*/