<?php

    # Main panel
    echo $HTML->main_panel_start(); 
    echo $HTML->heading1('Preview Email');
    
    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    
      $Smartbar->add_item([
          'active' => false,
          'title' => 'Email',
          'link'  => '/addons/apps/cjs_daily/email?id='.$_GET['id'],
      ]);
    
      $Smartbar->add_item([
          'active' => false,
          'title' => 'Send Test',
          'link'  => '/addons/apps/cjs_daily/email/test?id='.$_GET['id'],
      ]);
      
      $Smartbar->add_item([
            'active' => true,
            'title' => "Preview",
            'link'  => '/addons/apps/cjs_daily/email/preview?id='.$_GET['id'],
            'position' => 'end',
        ]);
    
    
    echo $Smartbar->render();
    
    
    echo $HTML->main_panel_start();
    
    $Email = $Emails->find($_GET['id'], true);
    
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.brevo.com/v3/emailCampaigns/" . $Email->campaignID(),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "api-key: " . (defined('CJS_BREVO_API_KEY') ? CJS_BREVO_API_KEY : ''),
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $campaign = json_decode($response, true);
        $htmlContent = $campaign['htmlContent'];
        $encoded = base64_encode($htmlContent);
        echo '<iframe src="data:text/html;base64,' . $encoded . '" style="width:100%;height:800px;border:none;"></iframe>';
    }
    
    echo $HTML->main_panel_end();
