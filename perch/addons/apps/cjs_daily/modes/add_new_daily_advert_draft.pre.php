<?php

	  $Daily_Jobs = new CJS_Daily_Jobs($API);
    $Employers = new CJS_Daily_Employers($API);
    $CJSOrganisations = new CJS_Organisations_Orgs($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_daily/daily_jobs.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    function switchDate($input){
      $dates = explode("-",$input);
      return "$dates[2]-$dates[1]-$dates[0]";
    }

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
    	  
    	  
    	$daily_jobID = (int) $_GET['jobID'];    
        $Daily_Job = $Daily_Jobs->find($daily_jobID, true);
    	  
        $details = $Daily_Jobs->get_advert_draft_by_id($_GET['id']);
        
		$details = $details[0];
		$fields = PerchUtil::json_safe_decode($details['daily_jobDynamicFields'], true);
		
		$perch['perch_fulltext'] = array();
		$perch['perch_fulltext']['_flang'] = $fields['fulltext']['_flang'];
		$perch['perch_fulltext']['raw'] = $fields['fulltext']['raw'];
		$perch['perch_fulltext']['processed'] = $fields['fulltext']['processed'];
		
		$perch['perch_text'] = array();
		$perch['perch_text']['_flang'] = $fields['text']['_flang'];
		$perch['perch_text']['raw'] = $fields['text']['raw'];
		$perch['perch_text']['processed'] = $fields['text']['processed'];
		
		$perch['perch_displayalt'] = array();
		$perch['perch_displayalt']['_flang'] = $fields['displayalt']['_flang'];
		$perch['perch_displayalt']['raw'] = $fields['displayalt']['raw'];
		$perch['perch_displayalt']['processed'] = $fields['displayalt']['processed'];
		
		$perch['perch_socialmediapromotion'] = $fields['socialmediapromotion'];
		$perch['perch_video'] = $fields['video'];
		$perch['perch_unpaid'] = $fields['unpaid'];
		$perch['perch_invoiceaddress'] = array();
		$perch['perch_invoiceaddress'] = $fields['invoiceaddress'];
		$perch['perch_reference'] = $fields['reference'];
		$perch['perch_payment'] = $fields['payment'];
		$perch['perch_name'] = $fields['name'];
		$perch['perch_organisation'] = $fields['organisation'];
		$perch['perch_telephone'] = $fields['telephone'];
		$perch['perch_email'] = $fields['email'];
		$perch['perch_message'] = $fields['message'];
		$perch['perch_howdidyoufindus'] = $fields['howdidyoufindus'];
		
		$details = array_merge($perch,$details);
		
		print_r($details);
        
        // SET THE HEADING
        $heading1 = 'Edit Daily Job';
      
        $buttonText = 'Update Daily Job';
      
        $details['postedDate'] = switchDate($details['postedDate']);
        $details['closingDate'] = switchDate($details['closingDate']);
        $details['jobOfDayDate'] = switchDate($details['jobOfDayDate']);
        $details['advertAmendedDate'] = switchDate($details['advertAmendedDate']);
        
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array('title', 'memberID', 'employerDisplay', 'employerID', 'imageLogo', 'imageAlt', 'imageLogo2', 'imageAlt2', 'imageLogo3', 'imageAlt3', 'imageLogo4', 'imageAlt4', 
        				'imageLogo5', 'imageAlt5', 'videoEmbed', 'postedDate', 'displayAdURL', 
                          'closingDate', 'jobOfDay', 'jobOfDayDate', 'cVolunteer', 'cApprentice', 'cIntern', 'cClosingDateEU', 'cDisplayDNotCDate', 'displayAd', 
                          'advertAmendedDate', 'advertURL', 'region', 'sAdminIT', 'sArbo', 'sComVol', 'sCountry', 'sEco', 'sEnvOE', 'sSustainbility', 'sPolicy', 'sFieldwork', 'sPractical', 'sForestry',
                          'rNational', 'rInternational', 'rScotland', 'rScotlandNorth', 'rScotlandSouth' 'rWales', 'rNorthernIreland', 'rEngland', 'rAnglia', 'rMidlands', 'rNorthEast', 'rNorthWest', 'rSouth', 'rSouthWest',
                          'rHome','sHorti', 'sPubF', 'sRoW', 'sEco', 'sVisMan', 'sWiWoAnC', 'location', 'lat', 'lng', 'rCounty', 'grade', 'cfullTime', 'cpartTime', 'cpermanent', 'cTempFixed', 'cseasonal', 'contractLength', 'advertStatus', 
                          'postCode', 'baseSalary', 'salaryType', 'jobReference', 'streetAddress');	
    	  $data = $Form->receive($postvars);
      
        $data['postedDate'] = switchDate($data['postedDate']);
        $data['closingDate'] = switchDate($data['closingDate']);
        $data['jobOfDayDate'] = switchDate($data['jobOfDayDate']);
        $data['advertAmendedDate'] = switchDate($data['advertAmendedDate']);
		
		if(!isset($data['jobOfDay'])){$data['jobOfDay']  = '0';}
		
        if(!isset($data['cVolunteer'])){$data['cVolunteer']  = '0';}
        if(!isset($data['cApprentice'])){$data['cApprentice']  = '0';}
        if(!isset($data['cIntern'])){$data['cIntern']  = '0';}
        if(!isset($data['cClosingDateEU'])){$data['cClosingDateEU']  = '0';}
        if(!isset($data['cDisplayDNotCDate'])){$data['cDisplayDNotCDate']  = '0';}
        if(!isset($data['displayAd'])){$data['displayAd']  = '0';}
      
        if(!isset($data['rNational'])){$data['rNational']  = '0';}
        if(!isset($data['rInternational'])){$data['rInternational']  = '0';}
        if(!isset($data['rScotland'])){$data['rScotland']  = '0';}
        if(!isset($data['rScotlandNorth'])){$data['rScotlandNorth']  = '0';}
        if(!isset($data['rScotlandSouth'])){$data['rScotlandSouth']  = '0';}
        if(!isset($data['rWales'])){$data['rWales']  = '0';}
        if(!isset($data['rNorthernIreland'])){$data['rNorthernIreland']  = '0';}
        if(!isset($data['rEngland'])){$data['rEngland']  = '0';}
        if(!isset($data['rAnglia'])){$data['rAnglia']  = '0';}
        if(!isset($data['rMidlands'])){$data['rMidlands']  = '0';}
        if(!isset($data['rNorthEast'])){$data['rNorthEast']  = '0';}
        if(!isset($data['rNorthWest'])){$data['rNorthWest']  = '0';}
        if(!isset($data['rSouth'])){$data['rSouth']  = '0';}
        if(!isset($data['rSouthWest'])){$data['rSouthWest']  = '0';} 
        if(!isset($data['rHome'])){$data['rHome']  = '0';}  
      
        if(!isset($data['sAdminIT'])){$data['sAdminIT']  = '0';}
        if(!isset($data['sArbo'])){$data['sArbo']  = '0';}
        if(!isset($data['sComVol'])){$data['sComVol']  = '0';}
        if(!isset($data['sCountry'])){$data['sCountry']  = '0';}
        if(!isset($data['sEco'])){$data['sEco']  = '0';}
        if(!isset($data['sEnvOE'])){$data['sEnvOE']  = '0';}
        if(!isset($data['sHorti'])){$data['sHorti']  = '0';}
        if(!isset($data['sPubF'])){$data['sPubF']  = '0';}
        if(!isset($data['sRoW'])){$data['sRoW']  = '0';}
        if(!isset($data['sVisMan'])){$data['sVisMan']  = '0';}
        if(!isset($data['sWiWoAnC'])){$data['sWiWoAnC']  = '0';}
        if(!isset($data['sSustainability'])){$data['sSustainability']  = '0';}
        if(!isset($data['sPolicy'])){$data['sPolicy']  = '0';}
        if(!isset($data['sFieldwork'])){$data['sFieldwork']  = '0';}
        if(!isset($data['sPractical'])){$data['sPractical']  = '0';}
        if(!isset($data['sForestry'])){$data['sForestry']  = '0';}
      
        if(!isset($data['cfullTime'])){$data['cfullTime']  = '0';}
        if(!isset($data['cpartTime'])){$data['cpartTime']  = '0';}
        if(!isset($data['cpermanent'])){$data['cpermanent']  = '0';}
        if(!isset($data['cTempFixed'])){$data['cTempFixed']  = '0';}
        if(!isset($data['cseasonal'])){$data['cseasonal']  = '0';} 
      
        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['daily_jobDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['daily_jobDynamicFields'], true);
        }
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Daily_Jobs, $Daily_Job);
        $data['daily_jobDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT

        if($data['advertStatus']=='overwrite'){
	        $data['advertStatus'] = 'pub';
        	$result = $Daily_Job->update($data);
        }
        $data['jobID'] = $_GET['jobID'];
        $resultDraft = $Daily_Jobs->update_draft($data);

        $message = $HTML->success_message('Your Daily Job has been successfully updated. Return to %sDaily Jobs%s', '<a href="'.$API->app_path() .'/daily/">', '</a>');  
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Daily Job has been successfully created. Return to %sDaily Jobs%s', '<a href="'.$API->app_path() .'/daily/">', '</a>'); 
    }