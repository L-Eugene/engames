<?php
// Bulls & Cows
$answers = array(
	'n' => '536809',
	'e' => '271542'
);
$result = array();

function sort_string($str){
	$stringParts = str_split($str);
	sort($stringParts);
	return implode('', $stringParts);
}

function bc($a, $b){
	$bulls = 0; $cows = 0;
	$aa = ''; $bb = '';
	for ($i=0; $i<strlen($a); $i++){
 		if ($a[$i] == $b[$i]) {
			$bulls++;
		} else {
			$aa .= $a[$i];
			$bb .= $b[$i];
		}
	}

	$aa = sort_string($aa);
	$bb = sort_string($bb);

	while(strlen($aa)>0 && strlen($bb)>0){
		if ($aa[0] == $bb[0]){
			$cows++;
			$aa = substr($aa, 1);
			$bb = substr($bb, 1);
		} elseif ($aa[0] < $bb[0]) {
			$aa = substr($aa, 1);
		} else {
			$bb = substr($bb, 1);
		}
	}

	return array(
		'bulls' => $bulls, 
		'cows'  => $cows
	);
}

if (isset($_REQUEST['data']) && (substr($_REQUEST['data'], 0, 1)=='n' || substr($_REQUEST['data'], 0, 1)=='e') && strlen($_REQUEST['data'])==7){
	$ind = substr($_REQUEST['data'], 0, 1);
	$val = substr($_REQUEST['data'], 1);
	$result = bc($val, $answers[$ind]);
	$result['index'] = $ind;
}

echo $_REQUEST['callback'].'('.json_encode($result).')';
