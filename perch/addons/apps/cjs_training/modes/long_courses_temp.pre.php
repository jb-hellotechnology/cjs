<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $LCourses = new CJS_Training_Long_Courses($API);

  $message = false;

  $lcourses = array();

  $lcourses = $LCourses->get_temp();

  if($Form->submitted()){
    foreach($lcourses as $Course){

	    $Course['longID'] = NULL;

	    $LCourses->import_temp_row($Course);

    }

    $LCourses->empty_temp();
    PerchUtil::redirect($API->app_path().'/long_courses');
  }
