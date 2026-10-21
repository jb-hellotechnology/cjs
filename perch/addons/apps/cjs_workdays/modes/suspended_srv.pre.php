<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Surveys = new CJS_Workdays_Surveys($API);

  $surveys = array();

  $surveys = $Surveys->suspended();
  
  function switchDate($input){
      $dates = explode("-",$input);
      return "$dates[2]-$dates[1]-$dates[0]";
    }

  if($Form->submitted()){
    //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
    $postvars = array('useCheckDate','checkDate','advertStatus');
    $data = $Form->receive($postvars);
    foreach ($surveys as $Survey){
      if ($_POST['select_'.$Survey['surveyID']]=='1'){
        $Surveys->update_advert($data,$Survey['surveyID']);
      }
    }
    $surveys = $Surveys->suspended();
    $message = $HTML->success_message('Your Surveys have been successfully updated. Return to %sSurveys%s', '<a href="'.$API->app_path() .'/surveys/">', '</a>');   
  }