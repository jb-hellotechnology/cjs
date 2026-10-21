<?php

    $Lcourses = new CJS_Training_Long_Courses($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        $postvars = array('tidyArchives','date');
    	$data = $Form->receive($postvars);
    	
    	$dates = explode("-",$data['date']);
    	$date = "$dates[2]-$dates[1]-$dates[0]";
 
        $Lcourses->tidyArchives($data['tidyArchives'],$date);
        
        $message = $HTML->success_message('Archives tidied.');
        
      }