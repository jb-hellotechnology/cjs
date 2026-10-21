<?php
	echo $HTML->title_panel([
    'heading' => $Lang->get('Send Emails'),

    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Voluntary Organisations',
      'link'  => '/addons/apps/cjs_workdays/send_emails/',
  ]);

/*
  $Smartbar->add_item([
        'active' => false,
        'title' => 'Work Days',
        'link'  => '/addons/apps/cjs_workdays/send_emails/work_days/',
    ]);
*/

  $Smartbar->add_item([
        'active' => true,
        'title' => 'Surveys',
        'link'  => '/addons/apps/cjs_workdays/send_emails/surveys/',
    ]);

/*
  $Smartbar->add_item([
        'active' => false,
        'title' => 'All Advertisers',
        'link'  => '/addons/apps/cjs_workdays/send_emails/all_advertisers/',
    ]);
*/


  echo $Smartbar->render();

    # Main panel
  $HTML->main_panel_start();

	echo $Form->form_start();

  echo $HTML->heading2('All Advertisers');

  echo $Form->submit_field('getAllAdvertisers', 'Get List');

  echo $Form->form_end();

  echo $Form->form_start();

  echo $HTML->heading2('Check Reminder');

  echo $Form->hidden('FreeOrPaid','Free');

/*
  echo $Form->select_field('FreeOrPaid', 'Type', array(
            array('label'=>'Free', 'value'=>'Free'), array('label'=>'Paid', 'value'=>'Paid')
  ), 'active');
*/

  echo $Form->date_field('checkDateGreater', 'Check Date Greater Than', false, true);

  echo $Form->date_field('checkDateLess', 'Check Date Less Than', false, true);

  echo $Form->submit_field('getCheckReminderList', 'Get List');
    
  echo $Form->form_end();
 
	$HTML->main_panel_end();