<?php

  echo $HTML->title_panel([
    'heading' => $Lang->get('Daily Jobs'),
    'button'  => [
          'text' => $Lang->get('Add New'),
          'link' => $API->app_nav().'/daily/add_new/',
          'icon' => 'core/plus',
      ],
    ], $CurrentUser);
    
  # Call Smartbar to create the tabs

  $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Pending',
      'link'  => '/addons/apps/cjs_daily/daily/',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Published',
      'link'  => '/addons/apps/cjs_daily/daily/published',
  ]);

  $Smartbar->add_item([
      'active' => false,
      'title' => 'Suspended',
      'link'  => '/addons/apps/cjs_daily/daily/suspended',
  ]);

  $Smartbar->add_item([
      'active' => true,
      'title' => 'Export',
      'link' => '/addons/apps/cjs_daily/daily/export/',
  ]);


  echo $Smartbar->render();

  #Main Panel Start

  $HTML->main_panel_start();

  #Some Content Here
echo $Form->form_start();

  // Export Recent Additions or Recent Updates
  echo $HTML->heading2('Export Daily Adverts');

  echo $Form->date_field('checkDateGreater', 'From', false, true);

  echo $Form->date_field('checkDateLess', 'To', false, true);

  echo $Form->submit_field('exportButton', 'Export Adverts');

  echo $Form->form_end();


  #Main Panel End

  $HTML->main_panel_end();