<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Scourses = new CJS_Training_Short_Courses($API);

  $scourses = array();

  $scourses = $Scourses->published();

  if($Form->submitted()){
    $postvars = array('checkDate','useCheckDate','advertStatus');
    $data = $Form->receive($postvars);
    foreach ($scourses as $SCourse){
      if ($_POST['select_'.$SCourse['shortID']]=='1'){
        $Scourses->update_advert($data,$SCourse['shortID']);
      }
    }
    $scourses = $Scourses->published();
    $message = $HTML->success_message('Your short courses have been successfully updated. Return to %sShort Courses%s', '<a href="'.$API->app_path() .'/short_courses/">', '</a>');   
  }