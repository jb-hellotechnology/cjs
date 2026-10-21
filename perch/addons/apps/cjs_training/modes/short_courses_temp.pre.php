<?php
/*
ini_set('display_errors', 1); 
  ini_set('display_startup_errors', 1); 
  error_reporting(E_ALL);
*/
  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Scourses = new CJS_Training_Short_Courses($API);

  $scourses = array();

  $scourses = $Scourses->get_temp();

  if($Form->submitted()){
    foreach($scourses as $Course){
	    
	    $Course['shortID'] = NULL;
 
	    $Scourses->import_temp_row($Course);
	    
    }
    
    $Scourses->empty_temp();
    PerchUtil::redirect($API->app_path().'/short_courses');  
  }