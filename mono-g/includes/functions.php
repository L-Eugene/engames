<?php

require_once dirname(__FILE__).'/db.php';

function api_init_state(){
	global $team;
	$cells = R::getAll(
		"call get_team_field(:team)",
		array(':team' => $team['id'])
	);
	$data = R::getRow(
		"call get_team_step(:team)", 
		array(':team' => $team['id'])
	);
	$comb = R::getAll(
		"call get_team_combos(:team)",
		array(':team' => $team['id'])
	);
	$result = array(
		'field'  => $cells,
		'state'  => $data,
		'combos' => $comb
	);
	echo "{$_REQUEST['callback']}(".json_encode($result).")";
}

function api_dice_throw(){
	global $team;
	R::exec(
		"call team_move(:team, :dice)",
		array(
			':team' => $team['id'],
			':dice' => get_random_dice()
		)
	);
	api_init_state();
}

function api_fight(){
	global $team;
	R::exec(
		"call team_fight(:team)",
		array(':team' => $team['id'])
	);
	api_init_state();
}

function api_escape(){
	global $team;
	R::exec(
		"call team_escape(:team)",
		array(':team' => $team['id'])
	);
	api_init_state();
}

function api_take(){
	global $team;
	R::exec(
		"call team_take(:team, :key)",
		array(
			':team'	=> $team['id'],
			':key'	=> $_REQUEST['ckey']
		)
	);
}

function api_combo(){
	global $team;
	R::exec(
		"call team_combine(:team, :key)",
		array(
			':team'	=> $team['id'],
			':key'	=> $_REQUEST['ckey']
		)
	);
}

function api_recruite(){
	global $team;
	R::exec(
		"call take_bonus(:team, :key)",
		array(
			':team'	=> $team['id'],
			':key'	=> $_REQUEST['ckey']
		)
	);
}

function get_random_dice(){
	return rand(1, 6);
}

function check_ukey($ukey){
	$data = R::getRow("SELECT id, name FROM `teams` WHERE `ukey`=:ukey LIMIT 1", array(':ukey' => $ukey));
	if (count($data) == 0) return false;
	return $data;
}
