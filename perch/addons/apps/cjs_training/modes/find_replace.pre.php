<?php

    $Lcourses = new CJS_Training_Long_Courses($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    // if the confirmation of delete has been submitted this is a form post
	if ($Form->submitted()) {
	
		$postvars = array('table', 'field', 'toequal', 'where', 'selector', 'value');
		$data = $Form->receive($postvars);
		
		$data['toequal'] = addslashes($data['toequal']);
		$data['value'] = addslashes($data['value']);
		
		if($data['selector']=='LIKE'){
			$value = "%$data[value]%";
		}else{
			$value = $data['value'];
		}
		
		$query = "UPDATE $data[table] SET $data[field] = '$data[toequal]' WHERE $data[where] $data[selector] '$value'";
		
		$Lcourses->process_find_replace($query);
	
	}