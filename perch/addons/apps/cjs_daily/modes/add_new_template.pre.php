<?php

	$Emails = new CJS_Daily_Emails($API);
    $Templates = new CJS_Daily_Emails_Templates($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_daily/email.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $templateID = (int) $_GET['id'];    
        $eTemplate = $Templates->find($templateID, true);
        $details = $eTemplate->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Email Template';
      
        $buttonText = 'Update Email Template';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $eTemplate = false;
        $templateID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Email Template';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Email Template';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array('templateID', 'name');	    
    	$data = $Form->receive($postvars);

        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['templateDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['templateDynamicFields'], true);
        }
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Templates, $eTemplate);
        $data['templateDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
        
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($eTemplate)) {
            $result = $eTemplate->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['templateID'])) unset($data['templateID']);
        
            $new_template = $Templates->create($data);
        
            // SHOW RELEVANT MESSAGE
            if ($new_template) {
                PerchUtil::redirect($API->app_path() .'/email/templates/add_new/?id='.$new_template->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that email template could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your email template has been successfully updated. Return to %semails%s', '<a href="'.$API->app_path() .'/email/templates/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that email template could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($eTemplate)) {
            $details = $eTemplate->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your email template has been successfully created. Return to %semail%s', '<a href="'.$API->app_path() .'/email/templates/">', '</a>'); 
    }