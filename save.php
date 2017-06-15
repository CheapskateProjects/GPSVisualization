<?php
	require_once("config.php");
	require_once("map_utils.php");
	initMaps();
	if($_REQUEST['key'] != $GLOBALS['admin_key'])
	{
		http_response_code(403);
	}
	else
	{
		if(isset($_REQUEST['name']) && strlen($_REQUEST['name']) > 0)
		{
		$name=$_REQUEST['name'];
		// Allow only simple ascii filenames a-zA-Z-_
		$name = preg_replace('/[^a-zA-Z\d\-_]/', '', $name);
		$filename=$GLOBALS['save_dir'].$name.$GLOBALS['file_suffix'];
		rename($GLOBALS['current_file'], $filename);
		}
		header('Location: track.php?key='.$_REQUEST['key']);
	}
?>
