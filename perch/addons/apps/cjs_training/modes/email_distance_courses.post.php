<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Send Emails'),

    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Long Courses',
      'link'  => '/addons/apps/cjs_training/emails/',
  ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Short Courses',
        'link'  => '/addons/apps/cjs_training/emails/short_courses/',
    ]);

  $Smartbar->add_item([
        'active' => true,
        'title' => 'Distance Courses',
        'link'  => '/addons/apps/cjs_training/emails/distance_courses/',
    ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Providers',
        'link'  => '/addons/apps/cjs_training/emails/providers/',
    ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Paid Adverts',
        'link'  => '/addons/apps/cjs_training/emails/paid_adverts/',
    ]);

  echo $Smartbar->render();

    # Main panel
$HTML->main_panel_start();
  
  echo $Form->form_start();

  echo $HTML->heading2('Distance Course Check Reminder');

	  echo $Form->select_field('free', 'Distance Courses Check Reminder', array(
	            array('label'=>'Free Only', 'value'=>'1'), array('label'=>'Paid', 'value'=>'0')
	  ), 'active');
	
	  echo $Form->date_field('from', 'Check Date Greater Than', false, true);
	
	  echo $Form->date_field('to', 'Check Date Less Than', false, true);
	
	  echo $Form->submit_field('getListReminder', 'Get List');
	  
	  echo $Form->hidden('checkreminder','1');
  
  echo $Form->form_end();
  
  echo $Form->form_start();
    
	  echo $HTML->heading2('Distance Course Readvertise');
	
	  echo $Form->submit_field('getListReadvertise', 'Get List');
	  
	  echo $Form->hidden('readvertise','1');
  
  echo $Form->form_end();
 
  $HTML->main_panel_end();