<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Surveys = new CJS_Workdays_Surveys($API);

  $surveys = array();

  $surveys = $Surveys->get_temp();
  
  function switchDate($input){
      $dates = explode("-",$input);
      return "$dates[2]-$dates[1]-$dates[0]";
    }

  if($Form->submitted()){
     
     foreach($surveys as $survey){
	    
	    $survey['surveyID'] = NULL;
	    
	    $Surveys->import_temp_row($survey);
	    
    }
    
    $Surveys->empty_temp();
    PerchUtil::redirect($API->app_path().'/surveys');
     
  }