<?php
$htmlview = "no";
$htmlFile = $_GET['print-type'];
$htmlheader = '
	<header>
	<table style="padding:0; align:right; vertical-align:top">
	  <tr>
		<td width="20%"><img src="images/holistic-jobs-logo.png" title="Holistic Jobs" style="height:100px"/></td>
		<td width="60%" align="left"><h2>Holistic Jobs</h2> <p class="red-text">Holistic Tower Plot # 28, Jinnah Boulevard West, Sector A, <br>
		DHA Phase-II, Islamabad,<br>
		 Land Line No. 051-4493740 <br>
		 www.holisticjobs.com.pk <br> 
		 E-mail: info@holisticjobs.com.pk </p></td>
		 <td width="20%" align="right"> <h1> INVOICE</h1></td>
	  </tr>
	</table>
	</header>
';

$html = '
<table style="padding:0; align:left" width="100%">
	<tr>
	  <td width="70%"><h4>Bill To. <span class="text">Shaheryar Malik </span></h4></td>
	  <td width="30%"><h4>Date. <span class="text"> '.date("d M, Y").' </span></h4></td>
	</tr>
	<tr>
	  <td width="70%"><h4>Address. <span class="text">Rawalpindi</span></h4></td>
	  <td width="30%"><h4>Invoice No. <span class="text">HJ-002</span></h4></td>
	</tr>
</table>
<br>
<table width="100%" class="border-table" style="text-align:center">
	<thead>
		<tr>
		  <th width="7%" style="border-left-color:#FFFFFF !important;">Sr. No</th>
		  <th style="border-left-color:#FFFFFF !important;">Description</th>
		  <th width="18%" style="border-left-color:#FFFFFF !important;">Subscription Charges</th>
		  <th width="10%" style="border-left-color:#FFFFFF !important;">Quantity</th>
		  <th width="15%">Total</th>
		</tr>
	</thead>
	<tbody>
		<tr>
		  <td style="height:600px">123</td>
		  <td>Description</td>
		  <td>Rs. 20,000</td>
		  <td>123</td>
		  <td>Rs. 20,000</td>
		</tr>
		<tr>
		  <td></td>
		  <td style="text-align:right">Total Amount</td>
		  <td></td>
		  <td></td>
		  <td></td>
		</tr>
	</tbody>
</table>

';
?>
