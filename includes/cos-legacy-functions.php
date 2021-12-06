<?php

function parse_hrs($hours)
{
	$hrs_array = preg_split("/[-,]/", $hours);
	//echo "<pre>"; print_r($hrs_array); echo "</pre>";
	foreach ($hrs_array as &$value) {
		$value = strtotime($value);
		$value = date("g:i A", $value);
	}
	unset($value);


	// There must be an even number of elements in array
	if (count($hrs_array) % 2 == 0) {
		return $hrs_array;
	} elseif (preg_match('/private/', strtolower($hours))) {
		return "private";
	} else {
		return false;
	}
}

function cos_person_tag($full_tag_name){

    include_once( ABSPATH . WPINC . '/feed.php' );

    $stuffToReturn = "";

    $feed = "http://news.cos.ucf.edu/tag/".$full_tag_name."/feed";

    // Set Feed cache to two hours
    add_filter( 'wp_feed_cache_transient_lifetime' , 'cos_increase_cache' );
    $rss = fetch_feed($feed);
    remove_filter( 'wp_feed_cache_transient_lifetime' , 'cos_increase_cache' );

    if(!is_wp_error($rss)){

		$maxitems = 4;
		$items = $rss->get_items(0, $maxitems);

		if(empty($items)) return false;

		foreach ($items as $item) :

			$stuffToReturn .= "<article class='news-tag'>";
			$stuffToReturn .=
			"<h2><a href='".$item->get_permalink()."'  target='_blank'>".$item->get_title()."</a></h2>";

			//Strip out any image tag because it was already stripped out and used above
			$postDescription = substr(preg_replace("/<p><img[^>]+\>/i", "", $item->get_description()), 0, 210);
			$postDescription = preg_replace("/<p><\/p>/", "", $postDescription);
			//$postDescription = $item->get_description();
			$postDescription .= '... <a href="'.$item->get_permalink().'">Read more</a>';

			$stuffToReturn .= "
			<p>".$postDescription."</p>";

			$stuffToReturn .= "</article>";

		endforeach;

		if($maxitems > 0 ){
			$stuffToReturn .= "<p><strong><a href='http://news.cos.ucf.edu/tag/$full_tag_name' target='_blank'>Click here to read additional news stories</a></strong></p>";
		}

		return $stuffToReturn;

    }
}

function get_hrs( $person ){
    $parity = true;
    $absent_msg = 'Not Available';
    $connector = "&nbsp;to&nbsp;";
    $separator = "</li><li>";
    $emptyHours = "";
    $days = array('mon'=>'Monday', 'tue'=>'Tuesday', 'wed'=>'Wednesday', 'thu'=>'Thursday', 'fri'=>'Friday',);
    $hours = '<ul>';

    foreach( $days as $day => $dayTitle ){
      $office_hours_today = 'p_office_hours_'.$day;

      $hours .= '<li><h2>' . $dayTitle . '</h2>';
      $hours .= '<ul><li>';

      if( is_array($person[$office_hours_today]) ){
        foreach( $person[$office_hours_today] as $hour ){

          $hours .= '<strong>'.$hour.'</strong>';
          $hours .=  $parity ? $connector : $separator ;
          $parity = !$parity;
        }
        $hours = substr( $hours, 0, -9 ); // Remove the extra </li><li>
        $emptyHours = "Have Office Hours";
      } else {
        $hours .= '<em>' . $absent_msg . '</em>';
      }

      $hours .= '</li></ul></li>';
    }

    $hours .= '</ul>';

    if(empty($emptyHours))
      return "";
    else
      return $hours;
}

function cos_increase_cache(){
	// Change the feed cache recreation period to 2 hours
	return 1;
}
