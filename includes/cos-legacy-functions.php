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
