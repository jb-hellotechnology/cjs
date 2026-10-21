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
        'active' => false,
        'title' => 'Distance Courses',
        'link'  => '/addons/apps/cjs_training/emails/distance_courses/',
    ]);

  $Smartbar->add_item([
        'active' => false,
        'title' => 'Providers',
        'link'  => '/addons/apps/cjs_training/emails/providers/',
    ]);

  $Smartbar->add_item([
        'active' => true,
        'title' => 'Paid Adverts',
        'link'  => '/addons/apps/cjs_training/emails/paid_adverts/',
    ]);

  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();

 	echo $Form->form_start();

  echo $Form->select_field('cat', 'Advert Type', array(
    array('label'=>'Events', 'value'=>'Events'), array('label'=>'Short Courses', 'value'=>'Short Courses'), array('label'=>'Long Courses', 'value'=>'Long Courses'), array('label'=>'Providers', 'value'=>'Providers')
  ), 'active');

  echo $Form->submit_field('getList', 'Get List');

  echo $Form->form_end();
 
	$HTML->main_panel_end();