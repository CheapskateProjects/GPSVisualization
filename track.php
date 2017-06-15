<html>
  <head>
    <style>
       #map {
        height: 80%;
        width: 100%;
       }
    </style>
  </head>
  <body>
    <h3>Shown track<select onchange="location.href = 'track.php?track='+this.value+'&key=<?php print($_REQUEST['key'])?>'">
<?php
	require_once("config.php");
	require_once("map_utils.php");
	initMaps();

	// Find selected track. Default: current
	$track='current';
	if(isset($_REQUEST['track']))
	{
		$track = $_REQUEST['track'];
		$track = preg_replace('/[^a-zA-Z\d\-_]/', '', $track);
	}
	$track_file = $GLOBALS['save_dir'].$track.$GLOBALS['file_suffix'];
	if(!file_exists($track_file))
	{
		$track = 'current';
		$track_file = $GLOBALS['current_file'];
	}

	// List possible tracks as options. Select selected.
	$cdir = scandir($GLOBALS['save_dir']);
   	foreach ($cdir as $key => $value)
   	{
		if(substr( $value, -strlen( $GLOBALS['file_suffix'] ) ) == $GLOBALS['file_suffix'])
		{
			$fname=substr( $value, 0, (strlen($value)-strlen($GLOBALS['file_suffix'])) );
			$sel="";
			if($fname == $track)
			{
				$sel = " selected=\"selected\"";
			}


			print("<option".$sel.">".$fname."</option>");
		}
	}
?>
</select></h3>
    <?php
       // Show admin options only if admin key is present and valid
       if($track == "current" && isset($_REQUEST['key']) && $_REQUEST['key']==$GLOBALS['admin_key'])
       {
       ?>
    <div>
      <form method="POST" action="save.php">
	<input type="hidden" name="key" value="<?php print($_REQUEST['key'])?>"/>
	<label>Save current map as</label><input name="name"/><input type="Submit" value="Save"/><input type="Submit" value="Clear map" onClick="return clearMap()"/>
      </form>
      
    </div>
    <?php
       }
    ?>
    <div id="map"></div>
    <script>
      function clearMap()
      {
	      	var r = confirm("Do you want to clear the current track?");
		if (r == true)
		{
	      		location.href = 'clear.php?key=<?php print($_REQUEST['key'])?>';
      		} 
	      	return false;
      }

      function initMap()
      {
	// Latest known value or default if selected track is empty
      	var currentloc =
      	<?php
		$latest = latestCoordinate($track_file);
	 	echo "{lat: ".$latest->lat.", lng:".$latest->lon . "};";
      	?>

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 17,
          center: currentloc,
          mapTypeId: 'hybrid'
        });

      var pathCoordinates = [
	<?php
	   $handle = fopen($track_file, "r");
	   $first = true;
	   $coordinates = array();
	   if ($handle)
	   {
	       while (($line = fgets($handle)) !== false)
	       {
		   if($first)
	   {
	   $first = false;
	   }
	   else
	   {
	   echo ",";
	   }
		   $coord = parseCoordinates($line);
		   $coordinates[] = $coord;
		   echo "{lat: ".$coord->lat.", lng: ".$coord->lon."}";
								      }
		if($first)
	   {
	   echo "{lat: ".$latest->lat.", lng: ".$latest->lon."}";
	   }						      
	       fclose($handle);
	   }
	?>
  	];

	<?php
		for ($i = 0; $i < count($coordinates); $i++)
		{
			if(($i == 0 && count($coordinates) >= 2) || ($i > 0 && $i % 30 == 0) || $i == (count($coordinates)-1))
			{
				$prefix="";
				if($i == 0){$prefix = "Start ";}
				if($i == (count($coordinates)-1)){$prefix = "End ";}
				echo "new google.maps.Marker({position:{lat: ".$coordinates[$i]->lat.", lng: ".$coordinates[$i]->lon."}, map: map,label: { text: '".$prefix.$coordinates[$i]->time."', color: 'white' } });\n";
			}
		}
	?>
	
  var path = new google.maps.Polyline({
    path: pathCoordinates,
    geodesic: true,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2
});


  path.setMap(map);

      }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php print($GLOBALS['maps_api_key']); ?>&callback=initMap"
  type="text/javascript"></script>
  </body>
</html>
