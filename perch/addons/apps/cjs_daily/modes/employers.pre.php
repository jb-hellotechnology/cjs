<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Employers = new CJS_Daily_Employers($API);

  $employers = array();

  $employers = $Employers->all();

  if($Form->submitted()){

    //GETS PROPER DATE STRUCTURE FOR DATE FIELDS      	
    foreach ($employers as $Employer){
      if ($_POST['select_'.$Employer->id()]=='1'){
        $Employer->delete();
      }
    }
    $employers = $Employers->all();
  }