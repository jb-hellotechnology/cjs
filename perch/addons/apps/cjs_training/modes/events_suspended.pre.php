<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');
    
  $Events = new CJS_Training_Events($API);

  $events = array();

  $events = $Events->suspended();

  if($Form->submitted()){
    $postvars = array('checkDate','useCheckDate','advertStatus');
    $data = $Form->receive($postvars);
    foreach ($events as $Event){
      if ($_POST['select_'.$Event['eventID']]=='1'){
        $Events->update_advert($data,$Event['eventID']);
      }
    }
    $events = $Events->suspended();
    $message = $HTML->success_message('Your events have been successfully updated. Return to %sEvents%s', '<a href="'.$API->app_path() .'/events/">', '</a>'); 
  }