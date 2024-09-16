<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="3" align="left" valign="top"><img src="<?=$image_path?>adtombamb_14.gif" width="972" height="29" alt="" /></td>
            </tr>
          <tr>
            <td width="35" align="left" valign="top"><img src="<?=$image_path?>adtombamb_15.gif" width="35" height="85" alt="" /></td>
            <td width="901" align="left" valign="top" background="<?=$image_path?>adtombamb_16.gif">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" align="left" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
        <td width="552" align="left" valign="top"><img src="<?=$image_path?>spacer.gif" width="533" height="1" /></td>
     </tr>
	 <tr>
       <td align="left" valign="top" style="padding-left:10px;"><img src="<?=$image_path?>enter.gif" width="490" height="21" /></td>
     </tr>
	  <tr>
      <td height="13" align="left" valign="top"></td>
     </tr>
	  <tr>
     <td width="552" height="43" align="left" valign="top" background="<?=$image_path?>searchbg.gif">
				<form name="search" method="get" action="search.php">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="10" colspan="4" align="left" valign="top"></td>
                  </tr>
                  <tr>
                    <td width="2%" align="left" valign="top">&nbsp;</td>
                    <td width="50%" align="left" valign="top">
					 <input name="keywords" id="keywords" type="text" value="Enter the keyword here" size="38" class="text12" onclick="change_val();" />
					</td>
                    <td width="30%" align="left" valign="top">
					<select name="ad_type" class="text12" id="ad_type">
					 <option selected="selected" value="0">All Categories</option>
                     <option value="1" class="red" style="font-weight:normal" <? if($_REQUEST['ad_type']==1){?> selected="selected" <? }?>>Web and Blog Ads</option>
					  <option value="2" class="greensmall" style="font-weight:normal" <? if($_REQUEST['ad_type']==2){?> selected="selected" <? }?>>Print Ads</option>
					  <option value="3" class="yellowsmall" style="font-weight:normal" <? if($_REQUEST['ad_type']==3){?> selected="selected" <? }?>>Billboard Ads</option>
					  <option value="4" class="blacksmall" style="font-weight:normal" <? if($_REQUEST['ad_type']==4){?> selected="selected" <? }?>>Outdoor Ads</option>
					   <option value="5" class="ornagesmall" style="font-weight:normal" <? if($_REQUEST['ad_type']==5){?> selected="selected" <? }?>>List Ads</option>
					    <option value="6" class="radiosmall" style="font-weight:normal" <? if($_REQUEST['ad_type']==6){?> selected="selected" <? }?>>Radio Ads</option>
                    </select>                    </td>
                    <td width="18%" align="left" valign="top">
					<input type="image" name="search" id="search" src="<?=$image_path?>search1.gif" />
					</td>
                  </tr>
                  
                </table>
				</form>
		  </td>
     </tr>
	<?php /*?>  <tr>
     <td align="left" valign="top" class="blacksmall" style="padding-left:10px;"><a href="signup_type.php">Create an account</a>  |  AD Management  |  Looking for Ad Space? </td>
     </tr><?php */?>
   </table>

	</td>
    <td width="5%">&nbsp;</td>
    <td width="47%" align="left" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td width="51%" align="left" valign="top" class="grey">Ads Category </td>
  <td width="49%" align="left" valign="top" class="blacksmall">All</td>
  </tr>
  <tr>  </tr>
  <tr>
  <td height="5" colspan="2"></td>
  </tr>
  <tr>
   <td colspan="2" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" align="left" valign="top">
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td align="left" valign="top" class="red"><a href="<?=$path?>search.php?ad_type=1"><img src="<?=$image_path?>arrow.gif" width="9" height="10" /> Web and Blog space </a> </td>
  </tr>
   <tr>
    <td align="left" valign="top" class="blacksmall"><a href="<?=$path?>search.php?ad_type=4"><span class="red"><img src="<?=$image_path?>arrow.gif" width="9" height="10" /> </span>Outdoor Ads </a> </td>
  </tr>
   <tr>
    <td align="left" valign="top" class="greensmall"><a href="<?=$path?>search.php?ad_type=2"><span class="red"><img src="<?=$image_path?>arrow.gif" width="9" height="10" /> </span> Print Space Only</a> </td>
  </tr>
</table>	</td>
    <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
   <td align="left" valign="top" class="yellowsmall"><a href="<?=$path?>search.php?ad_type=3"><img src="<?=$image_path?>arrow.gif" width="9" height="10" /> <span class="yellowsmall">Billboards Only</span></a></td>
  </tr>
   <tr>
   <td align="left" valign="top" class="ornagesmall"><a href="<?=$path?>search.php?ad_type=5"><img src="<?=$image_path?>arrow.gif" width="9" height="10" /><span class="ornagesmall"> </span>Lists Ads Only</a></td>
  </tr>
   <tr>
   <td align="left" valign="top"  class="radiosmall"><a href="<?=$path?>search.php?ad_type=6"><img src="<?=$image_path?>arrow.gif" width="9" height="10" /><span class="radiosmall">Radio Ads Only</span></a></td>
  </tr>
</table></td>
  </tr>
</table></td>
  </tr>

  <?php /*?><tr>
  <td colspan="2" align="left" valign="top">
  <table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
     <td width="29%" align="left" valign="top" class="black">Price</td>
     <td width="29%" align="left" valign="top" class="black">Traffic</td>
    <td width="42%" align="left" valign="top"><span class="black">Location</span> <span class="footer" style="font-size:10px;">(within 50 miles)</span> </td>
  </tr>
   <tr>
     <td width="29%" align="left" valign="top" class="black"><select name="select"  class="text12" style="width:92px; font-size:11px;">
                  <option>High to Low or....</option>
                </select>                </t></td>
     <td width="29%" align="left" valign="top" class="black"><select name="select2" class="text12" style="width:92px; font-size:11px;">
                  <option>High to Low or....</option>
                </select></td>
    <td width="42%" align="left" valign="top"><select name="select3" class="text12" style="width:92px; font-size:11px;">
                  <option>High to Low or....</option>
                  <option selected="selected">Zip Code</option>
                                                </select></td>
  </tr>
</table>  </td>
  </tr><?php */?>
</table>

	
	</td>
	  </tr>
	</table>

			</td>
            <td width="36" align="left" valign="top"><img src="<?=$image_path?>adtombamb_17.gif" width="36" height="85" alt="" /></td>
          </tr>
        </table>