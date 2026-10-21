<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Volunteer'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/volunteers/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_daily/volunteers/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_daily/volunteers/published/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_daily/volunteers/suspended/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Archive',
      'link'  => '/addons/apps/cjs_daily/volunteers/archive/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Upload CSV',
      'link'  => '/addons/apps/cjs_daily/volunteers/upload_csv/',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Export',
      'link'  => '/addons/apps/cjs_daily/volunteers/export/',
  ]);

  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

	echo $Form->form_start();

  // Export Work Days by Month
  echo $HTML->heading2('Export Volunteer Adverts - filter by \'Posted Date\'');

  echo $Form->select_field('MonthOrRecently', 'Export Volunteer Adverts', array(
            array('label'=>'Between Display From Dates', 'value'=>'From'), array('label'=>'Between Display Until Fields', 'value'=>'Until')
  ), 'active');

  echo $Form->date_field('checkDateGreater', 'From', false, true);

  echo $Form->date_field('checkDateLess', 'To', false, true);

  echo $Form->checkbox_field('longRunning', 'Long Running', '1', false);

  echo $Form->submit_field('exportButton', 'Export Adverts');

  echo $Form->form_end();

  #Main Panel End

  $HTML->main_panel_end();