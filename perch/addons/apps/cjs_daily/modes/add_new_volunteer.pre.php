<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

	$Daily_Jobs = new CJS_Daily_Jobs($API);
	$Volunteers = new CJS_Daily_Volunteers($API);
    $Employers = new CJS_Daily_Employers($API);
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
    $Template->set('cjs_daily/volunteer.html','cjs');

    // SETTING SOME VARIABLES
    $result = false;
    $message = false;

    // DO WE HAVE AN ID? IF SO, IT'S AN 'EDIT'...
    if (isset($_GET['id']) && $_GET['id']!='') {
    	  // GET THE ITEM AND MAKE IT INTO AN ARRAY
        $volunteerID = (int) $_GET['id'];    
        $Volunteer = $Volunteers->find($volunteerID, true);
        $details = $Volunteer->to_array();
        
        // SET THE HEADING
        $heading1 = 'Edit Volunteer';
      
        $buttonText = 'Update Volunteer';
	    
	    $details['closingDate'] = switchDate($details['closingDate']);
	    $details['deleteDate'] = switchDate($details['deleteDate']);
	    $details['displayUntil'] = switchDate($details['displayUntil']);
	    $details['intDate'] = switchDate($details['intDate']);
	    $details['displayFrom'] = switchDate($details['displayFrom']);
        
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $Volunteer = false;
        $volunteerID = false;
        $details = array();
        
        // SET THE HEADING
        $heading1 = 'Add Volunteer Advert';
      
        // SET THE FORM BUTTON TEXT
        $buttonText = 'Add Volunteer Advert';
      
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array('title', 'advertRef', 'memberID', 'employerDisplay', 'employerID', 'advertText','closingDate', 'closingDateT', 'intDate', 'location', 'payBenefits',
                          'tcOccasional', 'tcOnceorTwiceWeek', 'tcPartTime', 'tcFullTime', 'tcSixMonths', 'tcSixMonthYear', 'tcOneYearMore', 
                          'vidEmbedC', 'howToAp', 'typeRole', 'sAdminIT', 'sArbo', 'sComVol', 'sCountry', 'sEco', 'sEnvOE', 'sHorti', 'sPubF', 'sRoW', 'sVisMan', 'sWiWoAnC',  
                          'rNational', 'rInternational', 'rScotland', 'rScotlandNorth', 'rScotlandSouth', 'rWales', 'rNorthernIreland', 'rEngland', 'rAnglia', 'rMidlands', 'rNorthEast', 'rNorthWest', 'rSouth', 'rSouthWest', 'rFromHome', 'rCounty',
                          'accommodation', 'expenses', 'fTraining', 'imageLogo', 'imageAlt', 'imageLogo2', 'imageAlt2', 'longRun', 'deleteDate', 'displayUntil', 'freeLin', 'displayFrom', 
                          'archive', 'advertStatus');   
    	  $data = $Form->receive($postvars);
    	  
    	  $data['closingDate'] = switchDate($data['closingDate']);
        $data['deleteDate'] = switchDate($data['deleteDate']);
        $data['displayUntil'] = switchDate($data['displayUntil']);
        $data['displayFrom'] = switchDate($data['displayFrom']);
      
        if(!isset($data['tcOccasional'])){$data['tcOccasional']  = '0';}
        if(!isset($data['tcOnceorTwiceWeek'])){$data['tcOnceorTwiceWeek']  = '0';}
        if(!isset($data['tcPartTime'])){$data['tcPartTime']  = '0';}
        if(!isset($data['tcFullTime'])){$data['tcFullTime']  = '0';}
        if(!isset($data['tcSixMonths'])){$data['tcSixMonths']  = '0';}
        if(!isset($data['tcSixMonthYear'])){$data['tcSixMonthYear']  = '0';}
        if(!isset($data['tcOneYearMore'])){$data['tcOneYearMore']  = '0';}
 
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
        if(!isset($data['rFromHome'])){$data['rFromHome']  = '0';}  
      
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
      
        if(!isset($data['accommodation'])){$data['accommodation']  = '0';}
        if(!isset($data['expenses'])){$data['expenses']  = '0';}
        if(!isset($data['fTraining'])){$data['fTraining']  = '0';}

        if(!isset($data['longRun'])){$data['longRun']  = '0';}
        if(!isset($data['freeLin'])){$data['freeLin']  = '0';}
        if(!isset($data['archive'])){$data['archive']  = '0';}
      
        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['volunteersDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['volunteersDynamicFields'], true);
        }
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $Volunteers, $Volunteer);
        $data['volunteerDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($Volunteer)) {
	        if($data['advertStatus']=='overwrite'){
		        $result = $Daily_Jobs->delete_overwritten_vol_job($data['advertRef']);
		        $data['advertStatus'] = 'pub';
		        $result = $Volunteer->update($data);
	        }else{
	            $result = $Volunteer->update($data);
	        }
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['volunteerID'])) unset($data['volunteerID']);

            $new_volunteers = $Volunteers->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_volunteers) {
                PerchUtil::redirect($API->app_path() .'/volunteers/add_new/?id='.$new_volunteers->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, that Volunteer Advert could not be updated.');
            }
        }
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your Volunteer has been successfully updated. Return to %sVolunteer%s', '<a href="'.$API->app_path() .'/volunteers/">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, that Volunteer Advert could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($Volunteer)) {
            $details = $Volunteer->to_array();
        }else{
            $details = array();
        }
        
    }
    
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Volunteer Advert has been successfully created. Return to %sVolunteer%s', '<a href="'.$API->app_path() .'/volunteers/">', '</a>'); 
    }