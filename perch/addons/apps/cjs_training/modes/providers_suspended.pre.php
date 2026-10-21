<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Providers = new CJS_Training_Providers($API);

  $providers = array();

  $providers = $Providers->get_suspended();

  if($Form->submitted()){
    //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
    $postvars = array('useCheckDate','checkDate','advertStatus');
	$data = $Form->receive($postvars);

    foreach ($providers as $Provider){
      if ($_POST['select_'.$Provider['providerID']]=='1'){
        $Providers->update_advert($data,$Provider['providerID']);
      }
    }
    $providers = $Providers->get_suspended();
    $message = $HTML->success_message('Your providers have been successfully updated. Return to %sProviders%s', '<a href="'.$API->app_path() .'/providers/">', '</a>'); 
  }