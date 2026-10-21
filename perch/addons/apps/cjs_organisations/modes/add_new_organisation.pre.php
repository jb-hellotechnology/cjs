<?php

	$Organisations = new CJS_Organisations_Orgs($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_organisations/organisation.html','cjs');

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
        $heading1 = 'Edit Organisation';
      
        $buttonText = 'Update Organisation';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $Organisation = false;
        $organisationID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Organisation';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Organisation';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
 
        $postvars = array('name', 'slug', 'nameTag', 'websiteURL', 'logoURL', 'doNotDisplay');
    	$data = $Form->receive($postvars);
    	  
    	if(!isset($data['doNotDisplay'])){
	    	$data['doNotDisplay'] = 0;
    	}

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
                PerchUtil::redirect($API->app_path() .'/organisations/add_new/?id='.$new_organisation->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that Organisation could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your Organisation has been successfully updated. Return to %sOrganisations%s', '<a href="'.$API->app_path() .'/organisations/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that Organisation could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($Organisation)) {
            $details = $Organisation->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Organisation has been successfully created. Return to %sOrganisations%s', '<a href="'.$API->app_path() .'/organisations/">', '</a>'); 
    }