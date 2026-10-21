<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Volunteers = new CJS_Daily_Volunteers($API);

  $volunteers = array();

  $volunteers = $Volunteers->all();