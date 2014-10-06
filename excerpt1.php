<?php 
$topHundy = array(
	1 => "http://www.google.com/",
	2 => "http://www.facebook.com/",
	3 => "http://www.youtube.com/",
	4 => "http://www.yahoo.com/",
	5 => "http://www.baidu.com/",
	6 => "http://www.wikipedia.org/",
	7 => "http://www.amazon.com/",
	8 => "http://www.twitter.com/",
	9 => "http://www.qq.com/",
	10 => "http://www.linkedin.com/",
// ...
	99 => "http://www.vimeo.com/",
	100 => "http://www.blogspot.in/",
	);

foreach ($topHundy as $key => $value) {
// get the headers for a given domain
	$siteReq = array_change_key_case (get_headers($value, 1), $case = CASE_LOWER);

// pull the string needed to build the link to Alexa	
	$alexaLink = substr($value, 7);
// build the Alexa link
	echo $key.' - <a href="http://www.alexa.com/siteinfo/'.$alexaLink.'">'.$value.'</a><br />';

// pull and format the [Cache-Control] values, then write it down!
	if (is_null($siteReq["cache-control"])) {
    	echo "[Cache-Control] is null";
		} elseif (is_array($siteReq["cache-control"])) {
			echo "[Cache-Control]<br />";
			foreach($siteReq["cache-control"] as $key => $value){
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[".$key."] =>".$value.",<br/>";
			}
		
		}else {
	echo "[Cache-Control] => ".$siteReq["cache-control"];
	}
?>
