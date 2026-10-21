<?php

  $HTML 		= $API->get('HTML');
  $Form 		= $API->get('Form');
  $Template = $API->get('Template');

  $Emails = new CJS_Daily_Emails($API);

  $emails = array();

  $emails = $Emails->all();

  if($Form->submitted()){

    //GETS PROPER DATE STRUCTURE FOR DATE FIELDS      	
    foreach ($emails as $Email){
      if ($_POST['select_'.$Email->id()]=='1'){
        $Email->delete();
      }
    }
    $emails = $Emails->all();
  }