
<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
	  $AMessages = new CJS_Daily_Advertiser_Messages($API);

    $HTML     = $API->get('HTML');
    $Form     = $API->get('Form');
    $Text     = $API->get('Text');
    $Template = $API->get ('Template');


    // THIS IS THE MASTER FORM TEMPLATE FOR THIS ITEM - THE FIRST BIT IS THE PATH, THE SECOND IS THE 'NAMESPACE'
    $Template->set('cjs_daily/advertiser_messages.html','cjs');

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
        $advertiser_messageID = (int) $_GET['id'];    
        $AMessage = $AMessages->find($advertiser_messageID, true);
        $details = $AMessage->to_array();
        
    }elseif($AMessages->get_message_id()!=''){
        $advertiser_messageID = $AMessages->get_message_id();
        $AMessage = $AMessages->find($advertiser_messageID, true);
        $details = $AMessage->to_array();
        $details['date'] = switchDate($details['date']);
    }else{
      	// NO - WE'RE MAKING A NEW ONE
        $AMessage = false;
        $advertiser_messageID = false;
        $details = array();
       
    }
    
    // HANDLE BLOCKS FROM TEMPLATE
    $Form->handle_empty_block_generation($Template);

    // SET REQUIRED FIELDS
    $Form->set_required_fields_from_template($Template, $details);

    if($Form->submitted()) {
        //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
        $postvars = array ('displayf', 'date', 'displayj', 'displayc', 'displayv', 'displayw');
          
    	  $data = $Form->receive($postvars);
        $data['date'] = switchDate($data['date']);
        if(!isset($data['displayf'])){$data['displayf']  = '0';}
        if(!isset($data['displayj'])){$data['displayj']  = '0';}
        if(!isset($data['displayc'])){$data['displayc']  = '0';}
        if(!isset($data['displayv'])){$data['displayv']  = '0';}
        if(!isset($data['displayw'])){$data['displayw']  = '0';}
      
        // READ IN DYNAMIC FIELDS FROM TEMPLATE
        $previous_values = false;
        if (isset($details['advertiser_messageDynamicFields'])) {
            $previous_values = PerchUtil::json_safe_decode($details['advertiser_messageDynamicFields'], true);
        }
        
        // GET DYNAMIC FIELDS AND CREATE JSON ARRAY FOR DB
        $dynamic_fields = $Form->receive_from_template_fields($Template, $previous_values, $AMessages, $AMessage);
        $data['advertiser_messageDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
      
        // IF WE HAVE AN ORG UPDATE IT
        if (is_object($AMessage)) {
            $result = $AMessage->update($data);
        }else{
            // OTHERWISE CREATE IT
            if (isset($data['advertiser_messageID'])) unset($data['advertiser_messageID']);

            $new_advertiser_message = $AMessages->create($data);

            // SHOW RELEVANT MESSAGE
            if ($new_advertiser_message) {
                PerchUtil::redirect($API->app_path() .'/advertiser_messages/?id='.$new_advertiser_message->id().'&created=1');
            }else{
                $message = $HTML->failure_message('Sorry, your Advertiser Messages could not be updated.');
            }
        }      
//        $result = $AMessage->update($data);
    	
        // SHOW RELEVANT MESSAGE
        if ($result) {
            $message = $HTML->success_message('Your Advertiser Messages have been successfully updated. Return to %sAdvertiser Messages%s', '<a href="'.$API->app_path() .'/advertiser_messages/?id='.$advertiser_messageID.'">', '</a>');  
        }else{
            $message = $HTML->failure_message('Sorry, your Advertiser Messages could not be updated.');
        }
        
        // GET ORG DATA TO POPULATE FORM
        if (is_object($AMessage)) {
            $details = $AMessage->to_array();
        }else{
            $details = array();
        }        
    }

    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your Advertiser Messages have been successfully created. Return to %sAdvertiser Messages%s', '<a href="'.$API->app_path() .'/advertiser_messages/?id='.$advertiser_messageID.'">', '</a>'); 
    }