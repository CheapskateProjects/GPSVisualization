<?php
	require_once("config.php");
	require_once("map_utils.php");
	initMaps();
	if($_REQUEST['key'] != $GLOBALS['track_key'])
	{
		http_response_code(403);
	}
	else
	{
		$coordinates=$_REQUEST['coordinates'].','.date("Y-m-d").'T'.date("H:i:s")."\n";
		file_put_contents($GLOBALS['current_file'], $coordinates, FILE_APPEND);
		echo "OK";
	}
?>
