<?php
    
	$Emails = new CJS_Daily_Emails($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $message = false;

    //check that we have the ID of a thing, if not redirect back to the listing.
    if (isset($_GET['id']) && $_GET['id']!='') {
        $Email = $Emails->find($_GET['id'], true);
    }else{
        PerchUtil::redirect($API->app_path());
    }

    // if the confirmation of delete has been submitted this is a form post
      if ($Form->submitted()) {

        $postvars = array('recipient','campaignID');	    
        $data = $Form->receive($postvars);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://api.brevo.com/v3/emailCampaigns/".$data['campaignID']."/sendTest",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode([
            'emailTo' => [
                $data['recipient']
            ]
          ]),
          CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "api-key: " . (defined('CJS_BREVO_API_KEY') ? CJS_BREVO_API_KEY : ''),
            "content-type: application/json"
          ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          //echo $response;
        }
        
        $response = json_decode($response, true);

        if($response['code']=='invalid_parameter'){
            $message = $HTML->failure_message($response['message']);
        }else{
            $message = $HTML->success_message('Test email sent, check your inbox.');
        }
      }

      $details = $Email->to_array();
