<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Advertiser Messages'),

    ], $CurrentUser);
    
  # Call Smartbar to create the tabs


  if ($message){
	    
	    echo $message; 
	    
   }else{

    # Main panel
  $HTML->main_panel_start();

	echo $Form->form_start();

  echo $HTML->heading2('Display?'); 
  echo $Form->checkbox_field('displayf', 'Display Focus Message', '1', isset($details['displayf'])?$details['displayf']:'1');
  echo $Form->text_field('date', 'Enter Focus Message Date', isset($details['date'])?$details['date']:false, 'm input-simple datepicker');

  echo $Form->checkbox_field('displayj', 'Display January Message', '1', isset($details['displayj'])?$details['displayj']:'1');

  echo $Form->checkbox_field('displayc', 'Display Christmas Message', '1', isset($details['displayc'])?$details['displayc']:'1');
  
  echo $Form->checkbox_field('displayv', 'Display Volunteers Message', '1', isset($details['displayv'])?$details['displayv']:'1');
  echo $Form->checkbox_field('displayw', 'Display Workdays Message', '1', isset($details['displayw'])?$details['displayw']:'1');

  // THIS GETS ALL OTHER FIELDS FROM THE TEMPLATE FILE
  echo $Form->fields_from_template($Template, $details, $AMessages->static_fields);

  // THIS IS THE SUBMIT BUTTON
  echo $Form->submit_field('btnSubmit', 'Update Advertiser Messages', $API->app_path()."/advertiser_messages/");
    
  echo $Form->form_end();

  }
 
	$HTML->main_panel_end();