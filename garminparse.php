<?php

if(count($argv) < 2)
{
	echo "\nUsage: php -f garminparse.php <Garmin XML file to parse>\n\n";
	die;
}

if(!file_exists($argv[1]))
{
	echo "\nInvalid file\n\n";
	die;
}


$content = file_get_contents($argv[1]);
$xml=simplexml_load_string($content) or die("Error: Cannot create object");

// If route file
if(isset($xml->rte))
{
	$total=count($xml->rte->rtept);
	for($i = 0; $i < $total; $i++)
	{
		$point = $xml->rte->rtept[$i];
		$lat = (float)$point["lat"];
		$lon = (float)$point["lon"];
		$time = $point->time;
		echo $lat.",".$lon.",".$time."\n";
	}
}
// If track file
else if(isset($xml->trk))
{
	$total=count($xml->trk->trkseg->trkpt);
	for($i = 0; $i < $total; $i++)
	{
		$point = $xml->trk->trkseg->trkpt[$i];
		$lat = (float)$point["lat"];
		$lon = (float)$point["lon"];
		$time = $point->time;
		echo $lat.",".$lon.",".$time."\n";
	}
}



?>
