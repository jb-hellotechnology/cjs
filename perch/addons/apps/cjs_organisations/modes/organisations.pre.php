<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Organisations = new CJS_Organisations_Orgs($API);

  $organisations = array();

  $organisations = $Organisations->all();

  if($Form->submitted()){
    foreach ($organisations as $Organisation){
      if ($_POST['select_'.$Organisation->id()]=='1'){
        $Organisation = $Organisations->find($Organisation->id(), true);
        $Organisation->delete(); 
      }      
    }
    $organisations = $Organisations->all();
  }