<?php

	  $Events = new CJS_Training_Events($API);
    $Organisations = new CJS_Training_Organisations($API);
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
    $Template->set('cjs_training/events.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $eventID = (int) $_GET['id'];    
        $Event = $Events->find($eventID, true);
        $details = $Event->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Event';
      
        $buttonText = 'Update Event';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $Event = false;
        $eventID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Event';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Event';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars =  array('eventTitle', 'startDate', 'numberDays', 'location', 'lat', 'lng', 'webinar', 'county', 'provider', 'advertTelephone',
                          'advertEmail', 'advertWebsite', 'advertWebsiteShort', 'ticketingURL', 'advertLink', 'logo', 'logoAlt', 'displayAd', 'cost', 
                          'tOrganisationID', 'organisationName', 'contactName', 'contactTelephone', 'contactEmail', 'firstOnline',
                          'checkDate', 'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus');	
    	  $data = $Form->receive($postvars);

        //GETS PROPER DATE STRUCTURE FOR DATE FIELDS
        $data['startDate'] = switchDate($data['startDate']);
      	$data['firstOnline'] = switchDate($data['firstOnline']);
        $data['checkDate'] = switchDate($data['checkDate']);
        $data['dateUpdated'] = switchDate($data['dateUpdated']);
        
        if(!isset($data['displayAd'])){$data['displayAd']  = '0';}
        if(!isset($data['advertPaid'])){$data['advertPaid']  = '0';}
        if(!isset($data['freeAd'])){$data['freeAd']  = '0';}
        
        $data['organisationID'] = $data['tOrganisationID'];
        unset($data['tOrganisationID']);
        
        if(!isset($data['webinar'])){$data['webinar']  = '0';}
      
        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['eventDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['eventDynamicFields'], true);
        }
        
        $apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
        $genericAccessToken = (defined('CJS_BITLY_TOKEN') ? CJS_BITLY_TOKEN : '');
        
        $bitlyPayload = array('long_url' => $data['advertWebsite']); // renamed
        $payload = json_encode($bitlyPayload);
        
        $header = array(
            'Authorization: Bearer ' . $genericAccessToken,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        );
        
        $ch = curl_init($apiv4);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        
        $result = json_decode($result,true);
        $data['advertWebsiteShort'] = $result['link'] ?? $data['advertWebsite']; // fall back to original URL if Bitly fails
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Events, $Event);
      
        $data['eventDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($Event)) {
            $result = $Event->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['eventID'])) unset($data['eventID']);

            $new_event = $Events->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_event) {
                PerchUtil::redirect($API->app_path() .'/events/add_new/?id='.$new_event->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that event could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your event has been successfully updated. Return to %sEvents%s', '<a href="'.$API->app_path() .'/events/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that event could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($Event)) {
            $details = $Event->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your event has been successfully created. Return to %sEvents%s', '<a href="'.$API->app_path() .'/events/">', '</a>'); 
    }