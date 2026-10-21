<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	  $Workdays = new CJS_Workdays_Work_Days($API);
    $Organisations_wd = new CJS_Organisations_Orgs($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');
    
    function switchDate($input){
      $dates = explode("-",$input);
      return "$dates[2]-$dates[1]-$dates[0]";
    }


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_workdays/work_day.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $workdayID = (int) $_GET['id'];    
        $Workday = $Workdays->find($workdayID, true);
        $details = $Workday->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Work Day';
      
        $buttonText = 'Update Work Day';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $Workday = false;
        $workdayID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Work Day Advert';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Work Day Advert';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
    
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array('Title', 'startDate', 'startText', 'displayDate', 'region', 'location', 'provider', 
                          'advertTelephone', 'advertEmail', 'advertEmailSub', 'advertWebsite', 'advertWebsiteShort', 'advertDisplay', 'logo', 'logoAlt', 'displayAd', 'bannerAd',
                          'bannerAdPos', 'organisationID', 'organisationName', 'contactName', 'contactTelephone', 'contactEmail', 
                          'firstOnline', 'checkDate', 'dateUpdated', 'advertType', 'advertPaid', 'advertCost', 'freeAd', 'advertStatus');	   
    	  $data = $Form->receive($postvars);

    	$data['startDate'] = switchDate($data['startDate']);  
    	$data['firstOnline'] = switchDate($data['firstOnline']);
        $data['checkDate'] = switchDate($data['checkDate']);
        $data['dateUpdated'] = switchDate($data['dateUpdated']);
        
        if(!isset($data['displayAd'])){$data['displayAd']  = '0';}
        if(!isset($data['bannerAd'])){$data['bannerAd']  = '0';}
        if(!isset($data['advertPaid'])){$data['advertPaid']  = '0';}

      
        if($_POST['duplicate']=='yes'){
          
          $Workday = $Workdays->find($_GET['id'], true);
          $details = $Workday->to_array();
          $details['workdayID'] = '';
          
          $new_workdays = $Workdays->create($details);
          
          $message = $HTML->success_message('Your Work Day has been successfully duplicated. Return to %sWork Days%s', '<a href="'.$API->app_path() .'/work_days/">', '</a>');
          
        }else{
      

          // READ IN DYNAMIC FIELDS FROM TEMPLATE
          $previous_values = false;
          if (isset($details['workdayDynamicFields'])) {
              $previous_values = PerchUtil::json_safe_decode($details['workdayDynamicFields'], true);
          }

          $data['advertWebsiteShort'] = '';
          $url = trim($data['advertWebsite']);
          $bitly = 'https://api.bit.ly/shorten?version=2.0.1&longUrl='.urlencode($url).'&login=cjstraining&apiKey=R_641123f2e44e4482aca77288259bfb60&format=json';
          $response = file_get_contents($bitly);
          $json = @json_decode($response,true);
          $data['advertWebsiteShort'] = $json['results'][$url]['shortUrl'];


          // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
          $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Workdays, $Workday);
          $data['workdayDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);

          // IF WE HAVE AN ORG UPDATE IT
          if (is_object($Workday)) {
              $result = $Workday->update($data);
          }else{
              // OTHERWISE CREATE IT
              if (isset($data['workdayID'])) unset($data['workdayID']);

              $new_workdays = $Workdays->create($data);

              // SHOW RELEVANT MESSAGE
              if ($new_workdays) {
                  PerchUtil::redirect($API->app_path() .'/work_days/add_new/?id='.$new_workdays->id().'&created=1');
              }else{
                  $message = $HTML->failure_message('Sorry, that Work Day Advert could not be updated.');
              }
          }

          // SHOW RELEVANT MESSAGE
          if ($result) {
              $message = $HTML->success_message('Your Work Day has been successfully updated. Return to %sWork Days%s', '<a href="'.$API->app_path() .'/work_days/">', '</a>');  
          }else{
              $message = $HTML->failure_message('Sorry, that Work Day could not be updated.');
          }

          // GET ORG DATA TO POPULATE FORM
          if (is_object($Workday)) {
              $details = $Workday->to_array();
          }else{
              $details = array();
          }
          
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Work Day Advert has been successfully created. Return to %sWork Days%s', '<a href="'.$API->app_path() .'/work_days/">', '</a>'); 
    }