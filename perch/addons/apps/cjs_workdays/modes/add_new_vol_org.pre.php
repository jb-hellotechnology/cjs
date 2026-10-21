<?php

	$VOrganisations = new CJS_Workdays_Vol_Orgs($API);
	$Organisations = new CJS_Workdays_Organisations($API);
	$CJSOrganisations = new CJS_Organisations_Orgs($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');
    
    function switchDate($input){
      $dates = explode("-",$input);
      return "$dates[2]-$dates[1]-$dates[0]";
    }


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_workdays/voluntary_organisation.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $vorganisationID = (int) $_GET['id'];    
        $VOrganisation = $VOrganisations->find($vorganisationID, true);
        $details = $VOrganisation->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Voluntary Organisation';
      
        $buttonText = 'Update Voluntary Organisation';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $VOrganisation = false;
        $vorganisationID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Voluntary Organisation';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Voluntary Organisation';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array('vorgName', 'advertEmail', 'advertWebsite', 'advertWebsiteShort', 'advertDisplay', 'logo', 'logoAlt', 'displayAd', 'bannerAd',
                          'bannerAdPos', 'region', 'county', 'cVolPlace', 'cVolOpps', 'cWorkDays', 'cSurveys', 'cFromHome', 'URLAdverts', 'organisationID', 'organisationName', 
                          'contactName', 'contactJobTitle', 'contactTelephone', 'contactEmail', 'contactNotes', 'firstOnline', 'checkDate', 'dateUpdated', 'displayUntil',
                          'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus');	 
    	  $data = $Form->receive($postvars);

        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['vorganisationDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['vorganisationDynamicFields'], true);
        }
        
        $url = trim($data['advertWebsite']);
        $bitly = 'http://api.bit.ly/shorten?version=2.0.1&longUrl='.urlencode($url).'&login=cjstraining&apiKey=R_641123f2e44e4482aca77288259bfb60&format=json';
        $response = file_get_contents($bitly);
        $json = @json_decode($response,true);
        $data['advertWebsiteShort'] = $json['results'][$url]['shortUrl'];  
        
        $data['firstOnline'] = switchDate($data['firstOnline']);
        $data['checkDate'] = switchDate($data['checkDate']);
        $data['dateUpdated'] = switchDate($data['dateUpdated']);
        $data['displayUntil'] = switchDate($data['displayUntil']);
        
        if(!isset($data['displayAd'])){$data['displayAd']  = '0';}
        if(!isset($data['bannerAd'])){$data['bannerAd']  = '0';}
        if(!isset($data['cVolPlace'])){$data['cVolPlace']  = '0';}
        if(!isset($data['cVolOpps'])){$data['cVolOpps']  = '0';}
        if(!isset($data['cWorkDays'])){$data['cWorkDays']  = '0';}
        if(!isset($data['cSurveys'])){$data['cSurveys']  = '0';}
        if(!isset($data['cFromHome'])){$data['cFromHome']  = '0';}
        if(!isset($data['advertPaid'])){$data['advertPaid']  = '0';}
        if(!isset($data['freeAd'])){$data['freeAd']  = '0';}
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $VOrganisations, $VOrganisation);
        $data['vorganisationDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($VOrganisation)) {
            $result = $VOrganisation->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['vorganisationID'])) unset($data['vorganisationID']);

            $new_vorganisation = $VOrganisations->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_vorganisation) {
                PerchUtil::redirect($API->app_path() .'/voluntary_organisations/add_new/?id='.$new_vorganisation->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that Voluntary Organisation could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your Voluntary Organisation has been successfully updated. Return to %sVoluntary Organisations%s', '<a href="'.$API->app_path() .'/voluntary_organisations/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that Voluntary Organisation could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($VOrganisation)) {
            $details = $VOrganisation->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Voluntary Organisation has been successfully created. Return to %sVoluntary Organisations%s', '<a href="'.$API->app_path() .'/voluntary_organisations/">', '</a>'); 
    }