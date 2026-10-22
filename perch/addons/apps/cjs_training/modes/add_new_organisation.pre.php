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
    $bulk_delete_message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $organisationID = (int) $_GET['id'];    
        $Organisation = $Organisations->find($organisationID, true);
        $details = $Organisation->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Office';
      
        $buttonText = 'Update Office';
        
        /*
         * The tables under the office form. Distance courses are long courses with
         * categoryOne="distance-learning", and by_organisation() doesn't exclude them, so they
         * are taken out of the Long Courses list here - otherwise each one would be listed
         * under both headings with a Bulk Delete box in each. The filtering is done here
         * rather than in the class because by_organisation() also feeds the public site.
         */
        $providers = $Providers->by_organisation($organisationID);
        $events = $Events->by_organisation($organisationID);
        $scourses = $Scourses->by_organisation($organisationID);
        $lcourses = $Lcourses->by_organisation($organisationID);
        $dcourses = $Lcourses->by_organisation_distance($organisationID);

        $lcourses = array_values(array_filter((array)$lcourses, function($row){
            return !isset($row['categoryOne']) || $row['categoryOne']!='distance-learning';
        }));

        /*
         * BULK DELETE
         *
         * Each table gets its own form, named so Perch's posted() check (which matches
         * formaction and the CSRF token) can tell which one came back. One Delete Selected
         * button therefore only ever touches rows in its own table.
         *
         * Checkbox names carry the table key as well as the ID: all five tables are on one
         * page and their IDs overlap, so select_12 on its own would be ambiguous.
         *
         * Rows are removed one at a time through the factory, the same path the single Delete
         * button uses. Only IDs actually in this office's list are touched - the selection is
         * checked against the rows that were rendered, never trusted on its own.
         */
        $bulk_delete_message = false;

        $bulk_delete_groups = array(
            'providers' => array('pk'=>'providerID', 'factory'=>$Providers, 'rows'=>$providers, 'single'=>'provider',        'plural'=>'providers'),
            'scourses'  => array('pk'=>'shortID',    'factory'=>$Scourses,  'rows'=>$scourses,  'single'=>'short course',    'plural'=>'short courses'),
            'events'    => array('pk'=>'eventID',    'factory'=>$Events,    'rows'=>$events,    'single'=>'event',           'plural'=>'events'),
            'lcourses'  => array('pk'=>'longID',     'factory'=>$Lcourses,  'rows'=>$lcourses,  'single'=>'long course',     'plural'=>'long courses'),
            'dcourses'  => array('pk'=>'longID',     'factory'=>$Lcourses,  'rows'=>$dcourses,  'single'=>'distance course', 'plural'=>'distance courses'),
        );

        foreach ($bulk_delete_groups as $bulk_key => $bulk_group) {

            $BulkForm = $API->get('Form');
            $BulkForm->set_name('bulk_delete_'.$bulk_key);

            if (!$BulkForm->posted()) continue;

            $deleted = 0;

            foreach ((array)$bulk_group['rows'] as $bulk_row) {
                if (!isset($bulk_row[$bulk_group['pk']])) continue;
                $bulk_id = $bulk_row[$bulk_group['pk']];
                $bulk_field = 'select_'.$bulk_key.'_'.$bulk_id;
                if (!isset($_POST[$bulk_field]) || $_POST[$bulk_field]!='1') continue;

                $BulkItem = $bulk_group['factory']->find($bulk_id, true);
                if (is_object($BulkItem)) {
                    $BulkItem->delete();
                    $deleted++;
                }
            }

            if ($deleted) {
                $bulk_delete_message = $HTML->success_message('%s %s deleted.', $deleted, ($deleted==1 ? $bulk_group['single'] : $bulk_group['plural']));
            }else{
                $bulk_delete_message = $HTML->failure_message('Nothing was ticked, so no %s were deleted.', $bulk_group['plural']);
            }

            // redraw the tables without the rows that have just gone
            $providers = $Providers->by_organisation($organisationID);
            $events = $Events->by_organisation($organisationID);
            $scourses = $Scourses->by_organisation($organisationID);
            $lcourses = $Lcourses->by_organisation($organisationID);
            $dcourses = $Lcourses->by_organisation_distance($organisationID);

            $lcourses = array_values(array_filter((array)$lcourses, function($row){
                return !isset($row['categoryOne']) || $row['categoryOne']!='distance-learning';
            }));

            break;
        }

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