<?php
	require_once dirname(__FILE__) . '/includes/functions.php';

	global $team;
	if (FALSE !== $team = check_ukey($_REQUEST['ukey'])){
		if (isset($_REQUEST['action']) && function_exists($method = "api_{$_REQUEST['action']}")){
			$method();
		} else {
			header("HTTP/1.0 404 Not found");
			die();
		}
	} else {
		header("HTTP/1.0 403 Forbidden");
		die();
	}
