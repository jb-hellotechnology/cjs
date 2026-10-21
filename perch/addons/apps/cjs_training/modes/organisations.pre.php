<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $OrganisationsT = new CJS_Training_Organisations($API);

  $organisations = $OrganisationsT->all();

  if($Form->submitted()){
    foreach ($organisations as $Organisation){
      if ($_POST['select_'.$Organisation->id()]=='1'){
        $OrganisationT = $OrganisationsT->find($Organisation->id(), true);
        $OrganisationT->delete(); 
      }      
    }
    $organisations = $OrganisationsT->all();
  }