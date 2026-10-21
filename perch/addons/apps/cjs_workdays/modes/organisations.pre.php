<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Organisations_wd = new CJS_Workdays_Organisations($API);

  $organisations_wd = array();

  $organisations_wd = $Organisations_wd->all();

  if($Form->submitted()){
    foreach ($organisations_wd as $Organisation_wd){
      if ($_POST['select_'.$Organisation_wd->id()]=='1'){
        $Organisation_wd->delete(); 
      }      
    }
    $organisations_wd = $Organisations_wd->all();
  }