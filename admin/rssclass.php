<?php

 class rss {
	
     var $feed;

  function rss($feed) 
  {
    $this->feed = $feed;
	
  }
  
  
  

  function parse() 
  {
    $rss = simplexml_load_file($this->feed);
    $rss_split = array();$cnt=0;
    foreach ($rss->channel->item as $item) { $cnt++;
		//print_r($item);
		$title = (string) $item->title; // Title
		$link   = (string) $item->link; // Url Link
		$description = (string) $item->description; //Description
		  
		$rss_splitt[] = '
		  <div>
			<a href="'.$link.'" target="_blank" title="" >
				'.$title.' 
			</a> '.$description.'
			<hr>
		  </div>
		';
		$rss_split[$cnt]['title'] = $title;
		$rss_split[$cnt]['link'] = $link;
    }
    return $rss_split;
  }



  function display($numrows,$head) 
  {
    $rss_split = $this->parse();
    $i = 0;
    $rss_data = '
			 <div class="vas">
           <div class="title-head">
         '.$head.'
           </div>
         <div class="feeds-links">';

    while ( $i < $numrows ) 
	{
      $rss_data .= $rss_split[$i];
      $i++;
    }
    $trim = str_replace('', '',$this->feed);
    $user = str_replace('&lang=en-us&format=rss_200','',$trim);
    
	
	$rss_data.='</div></div>';
    
    return $rss_data;
  }
}
?>