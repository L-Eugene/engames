<?php

$keys = array(
	'1' => 'oly-1-asdknxjsl.png',
	'2' => 'oly-2-akhsbnlf.png',
	'3' => 'oly-3-olaksjd.png',
	'4' => 'oly-4-yujjjz.png',
	'5' => 'oly-5-nnnar.png',
	'6' => 'oly-6-asdzxc.png',
	'7' => 'oly-7-zjhsf.png',
	'8' => 'oly-8-zdfa.png',
	'2727' => 'oly-9-2727.png',
	'8120' => 'oly-10-8120.png',
	'6419' => 'oly-11-6419.png',
	'1973' => 'oly-12-1973.png',
	'5462' => 'oly-13-5462.png',
	'2299' => 'oly-14-2299.png'
);

if (array_key_exists($_REQUEST['code'], $keys)){
	header('Content-Type: image/png');
	echo file_get_contents(dirname(__FILE__).'/'.$keys[$_REQUEST['code']]);
	die();
} else {
	file_put_contents(dirname(__FILE__).'/logs/'.$_SERVER['REMOTE_ADDR'].'.html', "{$_REQUEST['code']}<br>", FILE_APPEND);
	header("HTTP/1.0 404 Not Found");
	echo file_get_contents(dirname(__FILE__).'/404.html');
	die();
}

