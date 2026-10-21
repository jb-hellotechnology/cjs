<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Surveys = new CJS_Workdays_Surveys($API);

  $surveys = array();

  $surveys = $Surveys->all();

  if($Form->submitted()){
    //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
    $postvars = array('checkDate','advertStatus');
    $data = $Form->receive($postvars);

    //GETS PROPER DATE STRUCTURE FOR DATE FIELDS      	
    $data['checkDate'] = $Form->get_date('checkDate');
    foreach ($surveys as $Survey){
      if ($_POST['select_'.$Survey->id()]=='1'){
        $Survey->update($data);
      }
    }
    $surveys = $Surveys->all();
    $message = $HTML->success_message('Your Surveys have been successfully updated. Return to %sSurveys%s', '<a href="'.$API->app_path() .'/surveys/">', '</a>');   
  }