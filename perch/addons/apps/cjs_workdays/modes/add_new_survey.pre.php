<?php

	  $Surveys = new CJS_Workdays_Surveys($API);
    $Organisations_wd = new CJS_Workdays_Organisations($API);
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
    $Template->set('cjs_workdays/survey.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $surveyID = (int) $_GET['id'];    
        $Survey = $Surveys->find($surveyID, true);
        $details = $Survey->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Survey';
      
        $buttonText = 'Update Survey';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $Survey = false;
        $surveyID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Survey Advert';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Survey Advert';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array('Title', 'region', 'section', 'provider', 'advertEmail', 'advertWebsite', 'advertWebsiteShort', 'advertDisplay', 'logo', 'logoAlt', 'displayAd', 'bannerAd',
                          'bannerAdPos', 'organisationID', 'organisationName', 'contactName', 'contactEmail', 'firstOnline', 'dateUpdated', 'checkDate', 'displayUntil',
                          'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus');	   
    	  $data = $Form->receive($postvars);
    	  
    	  $data['firstOnline'] = switchDate($data['firstOnline']);
        $data['checkDate'] = switchDate($data['checkDate']);
        $data['dateUpdated'] = switchDate($data['dateUpdated']);
        $data['displayUntil'] = switchDate($data['displayUntil']);

        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['surveyDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['surveyDynamicFields'], true);
        }
        
        $url = trim($data['advertWebsite']);
        $bitly = 'http://api.bit.ly/shorten?version=2.0.1&longUrl='.urlencode($url).'&login=cjstraining&apiKey=R_641123f2e44e4482aca77288259bfb60&format=json';
        $response = file_get_contents($bitly);
        $json = @json_decode($response,true);
        $data['advertWebsiteShort'] = $json['results'][$url]['shortUrl'];      
      
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Surveys, $Survey);
        $data['surveyDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($Survey)) {
            $result = $Survey->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['surveyID'])) unset($data['surveyID']);

            $new_surveys = $Surveys->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_surveys) {
                PerchUtil::redirect($API->app_path() .'/surveys/add_new/?id='.$new_surveys->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that Survey Advert could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your Survey has been successfully updated. Return to %sSurveys%s', '<a href="'.$API->app_path() .'/surveys/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that Survey could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($Survey)) {
            $details = $Survey->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Survey Advert has been successfully created. Return to %sSurveys%s', '<a href="'.$API->app_path() .'/surveys/">', '</a>'); 
    }