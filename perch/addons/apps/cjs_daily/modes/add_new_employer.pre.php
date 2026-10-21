<?php

	$Employers = new CJS_Daily_Employers($API);
	$Organisations = new CJS_Organisations_Orgs($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_daily/employers.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $employerID = (int) $_GET['id'];    
        $Employer = $Employers->find($employerID, true);
        $details = $Employer->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Employer';
      
        $buttonText = 'Update Employer';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $Employer = false;
        $employerID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Employer Advert';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Employer Advert';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array('cjsOrganisationID', 'name', 'slug', 'logo', 'contactName', 'contactJobTitle', 'contactPhone', 'contactEmail',
                                'doNotContact');	    
    	  $data = $Form->receive($postvars);
    	  
    	  $data['organisationID'] = $data['cjsOrganisationID'];
    	  unset($data['cjsOrganisationID']);
      
        if(!isset($data['doNotContact'])){$data['doNotContact']  = '0';}

        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['employerDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['employerDynamicFields'], true);
        }
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Employers, $Employer);
        $data['employerDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($Employer)) {
            $result = $Employer->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['employerID'])) unset($data['employerID']);

            $new_employers = $Employers->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_employers) {
                PerchUtil::redirect($API->app_path() .'/employers/add_new/?id='.$new_employers->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that Employer Advert could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your Employer has been successfully updated. Return to %sEmployers%s', '<a href="'.$API->app_path() .'/employers/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that Employer could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($Employer)) {
            $details = $Employer->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Employer Advert has been successfully created. Return to %sEmployers%s', '<a href="'.$API->app_path() .'/employers/">', '</a>'); 
    }