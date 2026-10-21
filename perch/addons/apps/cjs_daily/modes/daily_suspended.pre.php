<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Djobs = new CJS_Daily_Jobs($API);

  $djobs = array();

  $djobs = $Djobs->get_suspended($_GET['order']);

  if($Form->submitted()){
    foreach ($djobs as $Djob){
      if ($_POST['select_'.$Djob['daily_jobID']]=='1'){
        $Djobs->delete_job($Djob['daily_jobID']); 
      }      
    }
    $djobs = $Djobs->get_suspended();
  }