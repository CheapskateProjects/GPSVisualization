<?php

require_once("config.php");

class CPoint
{
	public $lat = 0;
	public $lon = 0;
	public $time = "";
}

// Distance between two coordinate points in meters
function distanceMeters($c1, $c2)
{
  $earthRadiusMeters = 6371000;
  $deltaLat = deg2rad($c2->lat-$c1->lat);
  $deltaLon = deg2rad($c2->lon-$c1->lon);
  $a = sin($deltaLat/2) * sin($deltaLat/2) + sin($deltaLon/2) * sin($deltaLon/2) * cos(deg2rad($c1->lat)) * cos(deg2rad($c2->lat)); 
  $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
  return $earthRadiusMeters * $c;
}

// Returns last row from given file
function lastRowFromFile($fileName)
{
	$line = '';

	$f = fopen($fileName, 'r');
	$cursor = -1;

	fseek($f, $cursor, SEEK_END);
	$char = fgetc($f);

	while ($char === "\n" || $char === "\r")
	{
    	      fseek($f, $cursor--, SEEK_END);
    	      $char = fgetc($f);
	}

	while ($char !== false && $char !== "\n" && $char !== "\r")
	{
    	      $line = $char . $line;
    	      fseek($f, $cursor--, SEEK_END);
    	      $char = fgetc($f);
	}

	return $line;
}

// Parse coordinate object from line
function parseCoordinates($line)
{
	$parts = explode(',', $line);
	$cret = new CPoint();
	if(count($parts) < 2)
	{
		$cret->lat=51.5287714;
		$cret->lon=-0.2420434;
	}
	else
	{
		$cret->lat=(float)$parts[0];
		$cret->lon=(float)$parts[1];

		if(count($parts) >= 3)
		{
			$cret->time=trim($parts[2]);
		}

	}
	return $cret;
}

// Find latest coordinates from a file
function latestCoordinate($file)
{
	$lastLine = lastRowFromFile($file);
	return parseCoordinates($lastLine);
}

// Initialize map functionality (create dirs and current track file)
function initMaps()
{
	if(!file_exists($GLOBALS['save_dir']))
	{
		mkdir($GLOBALS['save_dir'], 0777, true);
	}
	if(!file_exists($GLOBALS['current_file']))
	{
		touch($GLOBALS['current_file']);
	}
}

?>

