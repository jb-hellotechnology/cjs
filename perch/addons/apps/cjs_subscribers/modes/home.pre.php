<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 

$HTML 		= $API->get('HTML');
$Form 		= $API->get('Form');
$Template = $API->get('Template');

$Subscribers = new CJS_Subscribers_Subscribers($API);

$subscribers = array();

$subscribers = $Subscribers->all();