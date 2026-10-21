<?php

  $Events = new CJS_Training_Events($API);
  $ShortCourses = new CJS_Training_Short_Courses($API);
  $LongCourses = new CJS_Training_Long_Courses($API);
  $Providers = new CJS_Training_Providers($API);

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $show = 0;

  if($Form->submitted()) {
    
    $show = 1;
    
    $data['startDate'] = $Form->get_date('startDate');
    $data['endDate'] = $Form->get_date('endDate');
    
    $eventResults = $Events->recentAdditions($data['startDate'],$data['endDate']);
    $shortCourseResults = $ShortCourses->recentAdditions($data['startDate'],$data['endDate']);
    $longCourseResults = $LongCourses->recentAdditions($data['startDate'],$data['endDate']);
    $providerResults = $Providers->recentAdditions($data['startDate'],$data['endDate']);

  }