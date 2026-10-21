<?php    

    # Main panel
    echo $HTML->main_panel_start();
    
//     include('_subnav.php');

 // DISPLAY THE HEADING (EDIT/NEW)
echo $HTML->heading1($heading1);

 $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Email',
      'link'  => '/addons/apps/cjs_daily/email?id='.$_GET['id'],
  ]);

  $Smartbar->add_item([
      'active' => false,
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

    

     // IF THERE'S A MESSAGE DISPLAY IT
    if ($message){
	    
	    echo $message; 
	    
	  }

	    echo $Form->form_start();

      // USE THIS TAG TO ENTER DIVIDERS
      echo $HTML->heading2('Email Details');
      
      // THIS IS A FIELD CREATED HERE
      echo $Form->text_field('subject', 'Subject', isset($details['subject'])?$details['subject']:false, 'm input-simple');
      echo $Form->date_field('scheduledDate', 'Send Time', isset($details['scheduledDate'])?$details['scheduledDate']:false, 'm input-simple');

    $status = array();
    if($details['status']!=='scheduled'){
        $status[] = array("label"=>'Draft', "value"=>'draft');
    }
    $status[] = array("label"=>'Scheduled', "value"=>'scheduled');
    echo $Form->select_field('status','Status', $status, isset($details['status'])?$details['status']:'active', true, true);
      
      echo $Form->hidden('campaignID', isset($details['campaignID'])?$details['campaignID']:false);
      
      echo "<br /><br /><h2>Email Content</h2>";
      // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
      echo $Form->fields_from_template($Template, $details, $Emails->static_fields);

      // THIS IS THE SUBMIT BUTTON
      echo $Form->submit_field('btnSubmit', $buttonText, $API->app_path()."/email/");

      echo $Form->form_end();	    
    
    
    
    echo $HTML->main_panel_end();