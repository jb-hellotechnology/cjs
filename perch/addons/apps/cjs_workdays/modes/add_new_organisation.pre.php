<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

	  $Organisations_wd = new CJS_Workdays_Organisations($API);
    $VOrganisations = new CJS_Workdays_Vol_Orgs($API);
    $Workdays = new CJS_Workdays_Work_Days($API);
    $Surveys = new CJS_Workdays_Surveys($API);
    
    $master_Organisations = new CJS_Organisations_Orgs($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_workdays/organisation.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $organisationID = (int) $_GET['id'];    
        $Organisation_wd = $Organisations_wd->find($organisationID, true);
        $details = $Organisation_wd->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Office #'.$_GET['id'];
      
        $buttonText = 'Update Office';
      
        $vorgs = $VOrganisations->vorgs_by_organisation($organisationID);
        $workdays = $Workdays->workdays_by_organisation($organisationID);
        $surveys = $Surveys->surveys_by_organisation($organisationID);
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $Organisation_wd = false;
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
        $postvars = array('organisationID', 'organisationID_2', 'name', 'master_organisationID', 'contactName', 'contactJobTitle','contactPhone','contactEmail', 'doNotContact');
    	$data = $Form->receive($postvars);
	    
	    if($data['organisationID']<>'' AND $data['organisationID_2']<>''){
		    
		    //UPDATE WORKDAYS
			$Organisations_wd->updateWorkdays($data['organisationID'],$data['organisationID_2']);
			
			//UPDATE SURVEYS
			$Organisations_wd->updateSurveys($data['organisationID'],$data['organisationID_2']);
			
			//UPDATE SURVEYS
			$Organisations_wd->updateVOrgs($data['organisationID'],$data['organisationID_2']);
			
			//DELETE MERGED ORG
			$Organisation = $Organisations_wd->find($data['organisationID'], true);
			$Organisation->delete();
			
			$message = $HTML->success_message('Organisations merged. Return to %sOrganisations%s', '<a href="'.$API->app_path() .'/offices/">', '</a>'); 
		    
		}else{
		    
	        
	
	        // READ IN DYNAMIC FIELDS FROM TEMPLATE
	        $previous_values = false;
	        if (isset($details['organisationDynamicFields'])) {
	            $previous_values = PerchUtil::json_safe_decode($details['organisationDynamicFields'], true);
	        }
	        
	        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
	        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Organisations_wd, $Organisation_wd);
	        $data['organisationDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
	      
	        // IF WE HAVE AN ORG UPDATE IT
	        if (is_object($Organisation_wd)) {
	            $result = $Organisation_wd->update($data);
	        }else{
	            // OTHERWISE CREATE IT
	            if (isset($data['organisationID'])) unset($data['organisationID']);
	
	            $new_organisation = $Organisations_wd->create($data);
	
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
	        if (is_object($Organisation_wd)) {
	            $details = $Organisation_wd->to_array();
	        }else{
	            $details = array();
	        }
        
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Office has been successfully created. Return to %sOffices%s', '<a href="'.$API->app_path() .'/offices/">', '</a>'); 
    }