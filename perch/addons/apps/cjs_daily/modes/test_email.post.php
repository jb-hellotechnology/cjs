<?php

    # Main panel
    echo $HTML->main_panel_start(); 
    echo $HTML->heading1('Send Test Email');
    
    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    
      $Smartbar->add_item([
          'active' => false,
          'title' => 'Email',
          'link'  => '/addons/apps/cjs_daily/email?id='.$_GET['id'],
      ]);
    
      $Smartbar->add_item([
          'active' => true,
          'title' => 'Send Test',
          'link'  => '/addons/apps/cjs_daily/email/test?id='.$_GET['id'],
      ]);
      
      $Smartbar->add_item([
            'active' => false,
            'title' => "Preview",
            'link'  => '/addons/apps/cjs_daily/email/preview?id='.$_GET['id'],
            'position' => 'end',
        ]);
    
    
      echo $Smartbar->render();
    
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $Form->form_start();
        echo $Form->text_field('recipient', 'Recipient', 'ranger@countryside-jobs.com', 'm input-simple');
        echo $Form->hidden('emailID', $details['emailID']);
        echo $Form->hidden('campaignID', $details['campaignID']);
		echo $Form->submit_field('btnSubmit', 'Send Test', $API->app_path().'/email/');

        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();
