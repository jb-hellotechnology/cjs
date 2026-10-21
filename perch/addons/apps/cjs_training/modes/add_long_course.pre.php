<?php

	  $LCourses = new CJS_Training_Long_Courses($API);
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
    $Template->set('cjs_training/long_courses.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $longID = (int) $_GET['id'];    
        $LCourse = $LCourses->find($longID, true);
        $details = $LCourse->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Long Course';
      
        $buttonText = 'Update Long Course';
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $LCourse = false;
        $longID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Long Course';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Long Course';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars =  array('startDate', 'longTitle', 'categoryOne', 'categoryTwo', 'categoryThree',
                                'location', 'lat', 'lng', 'webinar', 'county', 'provider', 'advertTelephone', 'advertEmail', 'advertWebsite', 'advertWebsiteShort', 'advertLink', 
                                'description', 'logo', 'logoAlt', 'displayAd', 'cHnd', 'cDiploma', 'cNvq', 'cHnc', 'cMsc', 
                                'cDegree', 'cBtec', 'cFoundation', 'cPhd', 'cApprenticeship', 'tOrganisationID', 'organisationName',
                                'contactName', 'contactTelephone', 'contactEmail' ,'firstOnline', 'checkDate', 'dateUpdated', 'displayUntil', 
                                'advertType', 'advertPaid', 'advertCost', 'notes', 'freeAd', 'advertStatus');
    	  $data = $Form->receive($postvars);


        $data['firstOnline'] = switchDate($data['firstOnline']);
        $data['checkDate'] = switchDate($data['checkDate']);
        $data['dateUpdated'] = switchDate($data['dateUpdated']);
        $data['displayUntil'] = switchDate($data['displayUntil']);
        
        $data['organisationID'] = $data['tOrganisationID'];
        unset($data['tOrganisationID']);
        
//         if(!isset($data['cShort'])){$data['cShort']  = '0';}
        if(!isset($data['cHnd'])){$data['cHnd']  = '0';}
        if(!isset($data['cDiploma'])){$data['cDiploma']  = '0';}
        if(!isset($data['cNvq'])){$data['cNvq']  = '0';}
        if(!isset($data['cHnc'])){$data['cHnc']  = '0';}
        if(!isset($data['cMsc'])){$data['cMsc']  = '0';}
        if(!isset($data['cDegree'])){$data['cDegree']  = '0';}
        if(!isset($data['cBtec'])){$data['cBtec']  = '0';}
        if(!isset($data['cFoundation'])){$data['cFoundation']  = '0';}
        if(!isset($data['cPhd'])){$data['cPhd']  = '0';}
        if(!isset($data['cApprenticeship'])){$data['cApprenticeship']  = '0';}
        
        if(!isset($data['displayAd'])){$data['displayAd']  = '0';}
        if(!isset($data['advertPaid'])){$data['advertPaid']  = '0';}
        if(!isset($data['freeAd'])){$data['freeAd']  = '0';}
        
        if(!isset($data['webinar'])){$data['webinar']  = '0';}
      
        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['longDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['longDynamicFields'], true);
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
      
        # GENERATE BIT.LY URL
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		// $ch = curl_init();
		// 
		// curl_setopt($ch, CURLOPT_URL, 'https://api-ssl.bitly.com/v4/shorten');
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"long_url\": \"".$data['advertWebsite']."\",\n  \"domain\": \"c-js.info\"\n}");
		// 
		// $headers = array();
		// $headers[] = 'Authorization: Bearer 92055b23087a5945a7085303394a6b8bf34a4630';
		// $headers[] = 'Content-Type: application/json';
		// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// 
		// $result = curl_exec($ch);
		// if (curl_errno($ch)) {
		//     echo 'Error:' . curl_error($ch);
		// }
		// curl_close($ch);
		// 
		// $urlData = json_decode($result,true);
		// $data['advertWebsiteShort'] = $urlData['link'];
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $LCourses, $LCourse);
      
        $data['longDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($LCourse)) {
            $result = $LCourse->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['longID'])) unset($data['longID']);

            $new_lcourse = $LCourses->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_lcourse) {
                PerchUtil::redirect($API->app_path() .'/long_courses/add_new/?id='.$new_lcourse->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that long course could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your long course has been successfully updated. Return to %sLong Courses%s', '<a href="'.$API->app_path() .'/long_courses/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that long course could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($LCourse)) {
            $details = $LCourse->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your long course has been successfully created. Return to %sLong Courses%s', '<a href="'.$API->app_path() .'/long_courses/">', '</a>'); 
    }