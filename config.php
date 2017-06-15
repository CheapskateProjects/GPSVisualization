<?php

// Dir where you want to save all the tracks
$GLOBALS['save_dir']='/tmp/gps_locations/';
// Suffix to use for track files
$GLOBALS['file_suffix']='.gps';
// Global reference to active current track
$GLOBALS['current_file']=$GLOBALS['save_dir'].'current'.$GLOBALS['file_suffix'];
// Your Google maps api key
$GLOBALS['maps_api_key']='YourGoogleMapsAPIKey';
// Your track key which is required to log new coordinates to current track
$GLOBALS['track_key']='ChangeMe';
// Your admin key which is required to show saving and clearing of current track
$GLOBALS['admin_key']='ChangeMe';

?>
