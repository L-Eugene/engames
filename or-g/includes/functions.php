<?php

require_once dirname(__FILE__).'/db.php';

function api_init_state(){
	global $team;
	
	$cells   = array();
	$visible = array();
	$hidden  = array();
	$tmp = explode("|", $_REQUEST['cells']);
	foreach ($tmp as $v){
		$t = explode(':', $v);
		$r = R::getRow("call get_team_cell(:cell, :key, :team)", array(':cell' => $t[0], ':key' => $t[1], ':team' => $team['idteam']));
		if (count($r) > 0) {
			$cells[$t[0]] = $r;
			$visible[] = $t[0];
			foreach(explode(',', $r['visible']) as $vv){
				$hidden[] = $vv;
			}
		}
	}
	$hidden = array_diff(array_unique($hidden), $visible);
	foreach ($hidden as $v){
		$cells[$v] = R::getRow("CALL get_cell_open_task(:cell)", array(':cell' => $v));
	}

	$money = R::getCell("SELECT get_team_money(:team)", array(':team' => $team['idteam']));


	$result = array(
		'status' => 'ok',
		'cells'  => $cells,
		'money'  => $money
	);

	if (R::getCell("SELECT check_finish(:team)", array(':team' => $team['idteam']))){
		$result['total_key'] = 'wearethebestofthebest';
	}

	echo "{$_REQUEST['callback']}(".json_encode($result).")";
}

function api_start_cell(){
	global $team;
	$cells  = array();

	$tmp = explode("|", $_REQUEST['cells']);
	foreach($tmp as $v){
		$t = explode(':', $v);
		if (R::getCell("SELECT check_cell(:cell, :key)", array(':cell' => $t[0], ':key' => $t[1]))){
			$cells[] = $t[0];
		}
	}

	if (!isset($_REQUEST['params']) || !isset($_REQUEST['params']['cell']) || !in_array($_REQUEST['params']['cell'], $cells)){
		$result = array('status' => 'error', 'text' => 'Wrong cell number.');
	} else if (R::getCell("SELECT get_cell_status(get_cell_id(:cell), :team)", array(':cell' => $_REQUEST['params']['cell'], ':team' => $team['idteam']))){
		$result = array('status' => 'error', 'text' => "Wrong cell state. Cell {$_REQUEST['params']['cell']}, team {$team['name']}.");
	} else if (! R::getCell("SELECT team_has_money(get_cell_id(:cell), :team)", array(':cell' => $_REQUEST['params']['cell'], ':team' => $team['idteam']))) {
		$result = array('status' => 'error', 'text' => "Not enough money.");
	} else {
		if (R::getCell("SELECT ((get_team_country(:team) IS NULL) OR (get_team_country(:team)=get_cell_country(get_cell_id(:cell))))", array(':team'=>$team['idteam'], ':cell'=>$_REQUEST['params']['cell']))){
			R::exec("call start_cell_solving(get_cell_id(:cell), :team)", array(':cell' => $_REQUEST['params']['cell'], ':team' => $team['idteam']));
			api_init_state();
			return;
		} else {
			$result = array('status' => 'error', 'text' => "Wrong country");
		}
	}

	echo "{$_REQUEST['callback']}(".json_encode($result).")";
}

function api_bonus(){
	global $team;
	
	if (isset($_REQUEST['params']) && isset($_REQUEST['params']['code']))
		R::exec("call do_bonus(:bonus, :team)", array(':bonus'=>$_REQUEST['params']['code'], ':team'=>$team['idteam']));
}

function api_finish_cell(){
	global $team;

	if (!isset($_REQUEST['params']) || !isset($_REQUEST['params']['cell'])){
		$result = array('status' => 'error', 'text' => 'Wrong cell number.');
	} else if (R::getCell("SELECT get_cell_status(get_cell_id(:cell), :team)", array(':cell' => $_REQUEST['params']['cell'], ':team' => $team['idteam'])) != 1){
		$result = array('status' => 'error', 'text' => "Wrong cell state. Cell {$_REQUEST['params']['cell']}, team {$team['name']}.");
	} else {
		$result = R::getRow("call finish_cell_solving(get_cell_id(:cell), :key, :team)", array(':cell' => $_REQUEST['params']['cell'], ':key' => $_REQUEST['params']['ukey'], ':team' => $team['idteam']));
	}

	echo "{$_REQUEST['callback']}(".json_encode($result).")";
}

function check_ukey($ukey){
	$data = R::getRow("SELECT idteam, name FROM team WHERE idteam=:ukey LIMIT 1", array(':ukey' => $ukey));
	if (count($data) == 0) return 0;
	return $data;
}
