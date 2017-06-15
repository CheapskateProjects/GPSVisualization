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
		unlink($GLOBALS['current_file']);
		header('Location: track.php?key='.$_REQUEST['key']);
	}
?>
