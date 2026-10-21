<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Workdays = new CJS_Workdays_Work_Days($API);

  $workdays = array();

  $workdays = $Workdays->all();

  if($Form->submitted()){
    //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
    $postvars = array('checkDate','advertStatus');
    $data = $Form->receive($postvars);

    //GETS PROPER DATE STRUCTURE FOR DATE FIELDS      	
    $data['checkDate'] = $Form->get_date('checkDate');
    foreach ($workdays as $Workday){
      if ($_POST['select_'.$Workday->id()]=='1'){
        $Workday->update($data);
      }
    }
    $workdays = $Workdays->all();
    $message = $HTML->success_message('Your Work Days have been successfully updated. Return to %sWork Days%s', '<a href="'.$API->app_path() .'/work_days/">', '</a>');   
  }