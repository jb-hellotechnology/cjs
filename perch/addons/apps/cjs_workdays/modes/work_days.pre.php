<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Workdays = new CJS_Workdays_Work_Days($API);

  $workdays = array();

  $workdays = $Workdays->pending();

  if($Form->submitted()){
    //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
    $postvars = array('useCheckDate','checkDate','advertStatus');
	$data = $Form->receive($postvars);

    foreach ($workdays as $Workday){
      if ($_POST['select_'.$Workday['workdayID']]=='1'){
        $Workdays->update_advert($data,$Workday['workdayID']);
      }
    }
    $workdays = $Workdays->pending();
    $message = $HTML->success_message('Your Work Days have been successfully updated. Return to %sWork Days%s', '<a href="'.$API->app_path() .'/work_days/">', '</a>');   
  }