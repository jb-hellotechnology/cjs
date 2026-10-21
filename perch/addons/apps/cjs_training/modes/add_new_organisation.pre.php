<?php

	$Organisations = new CJS_Training_Organisations($API);
	$master_Organisations = new CJS_Organisations_Orgs($API);
	
	$Providers = new CJS_Training_Providers($API);
	$Scourses = new CJS_Training_Short_Courses($API);
	$Events = new CJS_Training_Events($API);
	$Lcourses = new CJS_Training_Long_Courses($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_training/organisation.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $organisationID = (int) $_GET['id'];    
        $Organisation = $Organisations->find($organisationID, true);
        $details = $Organisation->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Office';
      
        $buttonText = 'Update Office';
        
        $providers = $Providers->by_organisation($organisationID);
        $events = $Events->by_organisation($organisationID);
        $scourses = $Scourses->by_organisation($organisationID);
        $lcourses = $Lcourses->by_organisation($organisationID);
        $dcourses = $Lcourses->by_organisation_distance($organisationID);
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $Organisation = false;
        $organisationID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Office';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Office';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array('master_organisationID','name','contactName','contactPhone','contactEmail','doNotContact');
        
    	$data = $Form->receive($postvars);
    	
    	if(!isset($data['doNotContact'])){$data['doNotContact']  = '0';}

        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['organisationDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['organisationDynamicFields'], true);
        }
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Organisations, $Organisation);
        $data['organisationDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($Organisation)) {
            $result = $Organisation->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['organisationID'])) unset($data['organisationID']);

            $new_organisation = $Organisations->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_organisation) {
                PerchUtil::redirect($API->app_path() .'/offices/add_new/?id='.$new_organisation->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that Office could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your Office has been successfully updated. Return to %sOffices%s', '<a href="'.$API->app_path() .'/offices/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that Office could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($Organisation)) {
            $details = $Organisation->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Office has been successfully created. Return to %sOffices%s', '<a href="'.$API->app_path() .'/offices/">', '</a>'); 
    }