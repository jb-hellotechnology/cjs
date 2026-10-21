<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Tidy Archives'),

    ], $CurrentUser);
    
  # Call Smartbar to create the tabs



    # Main panel
  $HTML->main_panel_start();

  if($message){
    echo $message;
  }else{

    echo $Form->form_start();

    # Select Field for choosing either Short Courses or Events to tidy up
    echo $Form->select_field('tidyArchives', 'Delete all data for which the Short Course or Event is older than 6 months', array(
                   array('label'=>'Providers (WHERE \'Display Until\' LESS THAN)', 'value'=>'Providers'),
                   array('label'=>'Short Courses (WHERE \'Display Until\' LESS THAN)', 'value'=>'ShortCourses'), 
                   array('label'=>'Events (WHERE \'Start Date\' LESS THAN)', 'value'=>'Events'), 
                   array('label'=>'Long Courses (WHERE \'Display Until\' LESS THAN)', 'value'=>'LongCourses')
                ), 'active');
    echo $Form->text_field('date', 'Date','', 'm input-simple datepicker');

    // Delete Archives Button
    echo $Form->submit_field('btnDelete', 'Tidy Archives', '');

    echo $Form->form_end();

  }
	$HTML->main_panel_end();