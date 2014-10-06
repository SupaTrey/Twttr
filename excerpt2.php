<?php 

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('statuses/user_timeline', array('count' => '50', 'exclude_replies' => true, 'include_rts' => false));
$embedTweet = $connection->get('statuses/oembed', array('id' => $luckiestTweetId));

/* Convert the return for TwitterOauth to go with the code written for Twitter-API-PHP */
$string = array();
foreach ($content as $newCon){
	$newRay = (array)$newCon;
	array_push ($string , array($newRay['id_str'], $newRay['text']));
	}
	
/*
This function converts each letter into a single, positive integer using the ASCII ID for that character
*/
function convLets(&$item1){
    $item1 = array_sum(str_split(abs(ord(strtolower($item1)) - 96)))-1;
    }

if(count($string)<7){
/*
First, some error reporting
*/
	exit("You need to tweet some more so our robot fortune tellers can calculate your luck!<br/><em>(Retweets and Replies don't count!)</em>");
	}else{

$lottoArray = array();

/*
convert tweet into ISO and then an array
*/
	foreach($string as $items){
        $convTweet = strtolower(preg_replace("/[^a-zA-Z]/", '', iconv("UTF-8", "ISO-8859-1//TRANSLIT", $items[1])));
        $arrayedText = str_split($convTweet);

		array_walk($arrayedText, 'convLets');
		
/*
Now, let's calculate how "lucky" each tweet is
*/
   		$luckBal = array_count_values($arrayedText);
   		ksort($luckBal);
		$luckLev = array(0 => 0,-1,2,4,-3,0,-4,3,-2,1);	

		$totalLuck = 0;
		
		for ($i = 0; $i < 10; $i++) {
    		$totalLuck += $luckBal[$i]*$luckLev[$i];
			}

/*
Is this the luckiest tweet so far? Let's nab it while we have it!
*/
		if ($totalLuck>$prevLuck){
			$luckiestTweetId = $items[0];
			$luckiestTweet = $items[1];
			$prevLuck = $totalLuck;
			}

/*
Build a list of possible lotto numbers as you go!
Numbers are generated from a selection of two digits from the tweet ID
Note: the regular lotto numbers are 1 - 59, powerball is 1 - 35
Get all the selections under 60; I'll fix the powerballs later
*/
		if(substr($items[0], -2)>=60 && substr($items[0],-3,2)>=60){
			$newPick = preg_replace("/[^1-5]/",'',$items[0]);
			$lottoPick = substr($newPick,-2);
			}elseif(substr($items[0], -2)>60){
			$lottoPick = substr($items[0],-3,2);
			}elseif(substr($items[0], -2)<1){
			$lottoPick = 1;
			}else{
			$lottoPick = substr($items[0], -2);
			}

		$lottoArray[$totalLuck] = $lottoPick;
		krsort($lottoArray);
		

	}

	$lottoPicks = array_slice($lottoArray,1,5);
    $powerBall = array_slice($lottoArray,0,1);
    
    
/* Get the embedded URL for luckiest tweet */
	$embedTweet = $connection->get('statuses/oembed', array('id' => $luckiestTweetId));

    
	echo "<h1>Your Luckiest Tweet</h1><blockquote class=\"twitter-tweet\" lang=\"en\"><p>";
	print_r($embedTweet);	
	echo "</a></blockquote></p><h1>Your Lottery Numbers</h1><p class=\"text-center\">";

    foreach ($lottoPicks as $key => $value){
		echo "".$value." ";
		}
    echo "</h2><br />And your PowerBall Number is: ";
    
/*
Make sure the powerball pick isn't over 35. If it is, fix it! 
*/  
    foreach ( $powerBall as $key => $value){
		if($value>35){
		$value =  $value-24;
		}
		echo $value."<br/>";		
		}

?>	
					
