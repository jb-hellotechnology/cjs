<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$HTML = $API->get('HTML');
$Form 		= $API->get('Form');
$Template = $API->get('Template');

$Djobs = new CJS_Daily_Jobs($API);
$djobs = array();
$djobs = $Djobs->get_pending(null);

$Volunteers = new CJS_Daily_Volunteers($API);
$volunteers = array();
$volunteers = $Volunteers->get_pending();

function switchDate($date){
	
	$parts = explode("-",$date);
	$date = "$parts[2]/$parts[1]/$parts[0]";
	
	return $date;
	
}