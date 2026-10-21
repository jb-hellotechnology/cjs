<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');
    
  $Lcourses = new CJS_Training_Long_Courses($API);

  $lcourses = array();

  $lcourses = $Lcourses->pending();

  if($Form->submitted()){
    $postvars = array('checkDate','useCheckDate','advertStatus');
    $data = $Form->receive($postvars);
    foreach ($lcourses as $LCourse){
      if ($_POST['select_'.$LCourse['longID']]=='1'){
        $Lcourses->update_advert($data,$LCourse['longID']);
      }
    }
    $lcourses = $Lcourses->pending();
    $message = $HTML->success_message('Your long courses have been successfully updated. Return to %sLong Courses%s', '<a href="'.$API->app_path() .'/long_courses/">', '</a>');   
  }